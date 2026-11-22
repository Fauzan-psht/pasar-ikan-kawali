<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor', 'pelanggan_id', 'guest_name', 'guest_phone', 'alamat',
        'metode_pengambilan', 'subtotal', 'ongkir', 'total',
        'status', 'catatan',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function details()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public static function generateNomor(): string
    {
        return 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(2)));
    }
}