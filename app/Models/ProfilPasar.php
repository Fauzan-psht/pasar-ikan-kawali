<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPasar extends Model
{
    use HasFactory;

    protected $table = 'profil_pasar';

    protected $fillable = [
        'nama', 'deskripsi', 'sejarah', 'visi', 'misi',
        'alamat', 'telepon', 'whatsapp', 'email', 'jam_buka',
        'logo', 'banner', 'maps_url', 'maps_embed',
        'facebook', 'instagram', 'youtube',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'nama' => 'Pasar Ikan Kawali',
            'deskripsi' => 'Pasar Ikan Air Tawar Kawali',
        ]);
    }
}