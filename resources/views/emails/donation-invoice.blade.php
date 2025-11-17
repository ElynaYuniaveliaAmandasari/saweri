<x-mail::message>
# Halo, {{ $donation->name }}

Terima kasih atas donasi Anda! Donasi untuk {{ $donation->user->username }} telah berhasil.

<x-mail::panel>
**Nomor Transaksi:** {{ $payment['payment_id'] ?? 'Tidak tersedia' }}  
**Jumlah Donasi:** Rp{{ number_format($donation->amount, 0, ',', '.') }}  
**Status:** {{ ucfirst($payment['status'] ?? 'Pending') }}
</x-mail::panel>

<x-mail::button :url="url('/')">
Kembali ke Beranda
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
