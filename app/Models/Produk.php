<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id', 'nama', 'slug', 'deskripsi', 'foto',
        'tipe_produk', 'satuan', 'harga_jual', 'harga_modal',
        'stok', 'stok_minimum', 'status',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'harga_modal' => 'decimal:2',
        'stok' => 'decimal:2',
        'stok_minimum' => 'decimal:2',
        'status' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($produk) {
            if (empty($produk->slug)) {
                $produk->slug = Str::slug($produk->nama) . '-' . Str::random(4);
            }
        });
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    public function scopeKonsumsi($query)
    {
        return $query->where('tipe_produk', 'konsumsi');
    }

    public function scopeBenih($query)
    {
        return $query->where('tipe_produk', 'benih');
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->harga_jual, 0, ',', '.');
    }
}