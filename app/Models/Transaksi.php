<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor', 'user_id', 'pelanggan_id', 'pesanan_id', 'kanal',
        'total', 'bayar', 'kembalian', 'status',
        'void_reason', 'void_at', 'void_by',
    ];

    protected $casts = [
        'void_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function voidBy()
    {
        return $this->belongsTo(User::class, 'void_by');
    }

    public static function generateNomor(): string
    {
        return 'TRX-' . now()->format('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(2)));
    }
}