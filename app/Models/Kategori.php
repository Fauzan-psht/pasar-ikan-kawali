<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'slug', 'tipe', 'deskripsi', 'foto', 'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama);
            }
        });
    }

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }
}