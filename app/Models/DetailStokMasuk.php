<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailStokMasuk extends Model
{
    use HasFactory;

    protected $fillable = ['stok_masuk_id', 'produk_id', 'qty', 'harga_modal', 'subtotal'];

    public function stokMasuk()
    {
        return $this->belongsTo(StokMasuk::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}