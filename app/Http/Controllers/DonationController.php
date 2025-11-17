<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\DonationInvoiceMail;
use App\Models\User;
use App\Models\Donation;
use App\Models\Payment;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceItem;

class DonationController extends Controller
{
    public function __construct()
    {
        // Pastikan XENDIT_SECRET_KEY diset di .env
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }

    /**
     * Halaman donasi berdasarkan username
     */
    public function index($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        return view('donation', compact('user'));
    }

    /**
     * Proses donasi dan redirect ke Xendit
     */
    public function store(Request $request)
    {
        $user = User::where('username', $request->username)->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'amount' => ['required', 'integer', 'min:1000'],
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        DB::beginTransaction();

        try {
            // Simpan data donasi
            $donation = Donation::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'amount' => $validated['amount'],
                'message' => $validated['message'] ?? '',
                'status' => 'pending',
            ]);

            // Siapkan item invoice
            $invoiceItem = new InvoiceItem([
                'name' => 'Donation from ' . $donation->name,
                'price' => $donation->amount,
                'quantity' => 1,
            ]);

            // Buat invoice Xendit
            $createInvoice = new CreateInvoiceRequest([
                'external_id' => 'donation-' . $donation->id,
                'payer_email' => $validated['email'],
                'description' => 'Donation for ' . $user->username,
                'amount' => $donation->amount,
                'items' => [$invoiceItem],
                'invoice_duration' => 86400, // 24 jam
                'success_redirect_url' => route('donations.success', ['id' => $donation->id]),
                'failure_redirect_url' => route('donations.failed'),
            ]);

            $api = new InvoiceApi();
            $invoice = $api->createInvoice($createInvoice);

            // Simpan data pembayaran dan simpan ke variabel $payment
            $payment = Payment::create([
                'donation_id' => $donation->id,
                'payment_id' => $invoice['id'],
                'payment_method' => 'xendit',
                'status' => 'pending',
                'payment_url' => $invoice['invoice_url'],
            ]);

            DB::commit();

            // ✅ Kirim email invoice ke pendonasi
            Mail::to($request->email)->queue(new DonationInvoiceMail($donation, $payment));

            // ✅ Redirect ke halaman pembayaran Xendit
            return redirect()->away($invoice['invoice_url']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat invoice: ' . $th->getMessage());
        }
    }

    /**
     * Callback dari Xendit (update status pembayaran)
     */
    public function callbackXendit(Request $request)
    {
        $getToken = $request->header('x-callback-token');
        $callbackToken = env('XENDIT_CALLBACK_TOKEN');

        if (!$callbackToken || $getToken !== $callbackToken) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payment = Payment::where('payment_id', $request->id)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $newStatus = $request->status === 'PAID' ? 'completed' : 'failed';
        $payment->update(['status' => $newStatus]);

        $donation = Donation::find($payment->donation_id);
        if ($donation && $request->status === 'PAID') {
            $donation->update(['status' => 'completed']);

            event(new DonationReceived($donation));
        }

        // Kirim email notifikasi update status ke pendonasi
        Mail::to($donation->email)->queue(new DonationInvoiceMail($donation, $payment));

        return response()->json(['message' => 'Payment updated successfully']);
    }

    /**
     * Halaman sukses donasi
     */
    public function success($id)
    {
        $donation = Donation::with('user')->find($id);

        if (!$donation) {
            abort(404, 'Donation not found');
        }

        return view('success', compact('donation'));
    }
}
