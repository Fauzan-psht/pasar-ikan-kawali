<?php

namespace Tests\Unit;

use App\Models\ProfilPasar;
use PHPUnit\Framework\TestCase;

class ProfilPasarMapTest extends TestCase
{
    public function test_plain_map_location_is_converted_to_an_embedded_map_url(): void
    {
        $profil = new ProfilPasar([
            'maps_embed' => 'R976+V8X, Kawalimukti, Kawali',
        ]);

        $this->assertSame(
            'https://www.google.com/maps?q=R976%2BV8X%2C%20Kawalimukti%2C%20Kawali&output=embed',
            $profil->maps_embed_url,
        );
    }

    public function test_only_google_map_iframe_sources_are_used(): void
    {
        $googleMap = new ProfilPasar([
            'maps_embed' => '<iframe src="https://www.google.com/maps/embed?pb=test&amp;key=value"></iframe>',
        ]);

        $this->assertSame('https://www.google.com/maps/embed?pb=test&key=value', $googleMap->maps_embed_url);

        $untrustedMap = new ProfilPasar([
            'maps_embed' => '<iframe src="https://example.com/maps/embed"></iframe>',
            'alamat' => 'Kawali, Ciamis',
        ]);

        $this->assertSame(
            'https://www.google.com/maps?q=Kawali%2C%20Ciamis&output=embed',
            $untrustedMap->maps_embed_url,
        );
    }
}
