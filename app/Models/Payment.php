<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id', 'transaksi_id', 'provider', 'provider_ref', 'metode',
        'nominal', 'status', 'paid_at', 'verified_by', 'raw_response',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'raw_response' => 'array',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function events()
    {
        return $this->hasMany(PaymentEvent::class);
    }
}