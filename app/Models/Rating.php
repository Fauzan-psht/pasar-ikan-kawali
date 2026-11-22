<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'produk_id', 'pesanan_id', 'nama', 'telepon',
        'rating', 'komentar', 'status',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}