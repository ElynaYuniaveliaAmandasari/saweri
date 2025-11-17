<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'amount',
        'message',
        'status',
    ];

    /**
     * Relasi ke tabel users
     * Satu donasi dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel payments
     * Satu donasi memiliki satu pembayaran
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
