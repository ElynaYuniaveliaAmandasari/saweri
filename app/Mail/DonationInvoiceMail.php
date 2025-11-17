<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonationInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $donation;
    public $payment;

    /**
     * Create a new message instance.
     */
    public function __construct(Donation $donation, $payment)
    {
        $this->donation = $donation;
        $this->payment = $payment;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Invoice Donasi Anda')
                    ->markdown('emails.donation-invoice') // 👈 disesuaikan ke folder markdown default
                    ->with([
                        'donation' => $this->donation,
                        'payment' => $this->payment,
                    ]);
    }
}
