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

    public function getMapsEmbedUrlAttribute(): ?string
    {
        $mapsEmbed = trim((string) $this->maps_embed);

        if ($mapsEmbed !== '' && preg_match('/<iframe\b[^>]*\bsrc\s*=\s*["\']([^"\']+)["\']/i', $mapsEmbed, $matches)) {
            $source = html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $sourceParts = parse_url($source);

            if (
                ($sourceParts['scheme'] ?? null) === 'https'
                && in_array(strtolower($sourceParts['host'] ?? ''), ['maps.google.com', 'www.google.com'], true)
                && str_starts_with($sourceParts['path'] ?? '', '/maps/embed')
            ) {
                return $source;
            }
        } elseif ($mapsEmbed !== '' && ! str_contains($mapsEmbed, '<') && ! filter_var($mapsEmbed, FILTER_VALIDATE_URL)) {
            return $this->googleMapsEmbedUrl($mapsEmbed);
        }

        $searchQuery = null;
        if ($this->maps_url) {
            $mapsUrlParts = parse_url($this->maps_url);
            if (is_array($mapsUrlParts)) {
                parse_str($mapsUrlParts['query'] ?? '', $query);
                $searchQuery = $query['query'] ?? $query['q'] ?? null;
            }
        }

        $searchQuery = trim((string) ($searchQuery ?: $this->alamat));

        return $searchQuery !== '' ? $this->googleMapsEmbedUrl($searchQuery) : null;
    }

    private function googleMapsEmbedUrl(string $query): string
    {
        return 'https://www.google.com/maps?q='.rawurlencode($query).'&output=embed';
    }
}
