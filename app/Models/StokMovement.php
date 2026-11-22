<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'produk_id', 'tipe', 'qty', 'source_type', 'source_id', 'user_id', 'alasan',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}