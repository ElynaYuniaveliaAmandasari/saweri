<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'payment_id',
        'payment_method',
        'status',
        'payment_url',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
