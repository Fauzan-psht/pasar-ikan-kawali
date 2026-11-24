<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function detailTransaksis(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function detailPesanans(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Menentukan batas minimal pembelian berdasarkan satuan.
     */
    public function minimumOrderQuantity(): float
    {
        return $this->satuan === 'kg' ? 0.5 : 1.0;
    }

    /**
     * Validasi apakah qty yang diminta pembeli/kasir diperbolehkan.
     * Diperbaiki agar aman dari error tipe data string.
     */
    public function acceptsOrderQuantity($quantity): bool
    {
        $qty = (float) $quantity;

        // Cek minimal order
        if ($qty < $this->minimumOrderQuantity()) {
            return false;
        }

        // Jika satuan kg, desimal diperbolehkan.
        // Jika satuan ekor, harus bilangan bulat.
        if ($this->satuan === 'ekor' && floor($qty) !== $qty) {
            return false;
        }

        return true;
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