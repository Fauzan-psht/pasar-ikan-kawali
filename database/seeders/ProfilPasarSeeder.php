<?php

namespace Database\Seeders;

use App\Models\ProfilPasar;
use Illuminate\Database\Seeder;

class ProfilPasarSeeder extends Seeder
{
    public function run(): void
    {
        ProfilPasar::updateOrCreate(
            ['id' => 1],
            [
                'nama' => 'Pasar Ikan Kawali',
                'deskripsi' => 'Pasar ikan air tawar terpercaya di Kawali, menyediakan ikan konsumsi segar dan benih ikan berkualitas untuk kebutuhan rumah tangga maupun budidaya.',
                'sejarah' => 'Pasar Ikan Kawali berdiri sejak tahun 2010 sebagai pusat penjualan ikan air tawar di wilayah Kawali dan sekitarnya.',
                'visi' => 'Menjadi pasar ikan air tawar terdepan yang mengedepankan kualitas, kesegaran, dan pelayanan terbaik.',
                'misi' => 'Menyediakan ikan air tawar segar berkualitas, mendukung petani ikan lokal, dan memberikan harga yang bersaing.',
                'alamat' => 'Jl. Raya Kawali No. 1, Kawali, Ciamis, Jawa Barat',
                'telepon' => '0265-123456',
                'whatsapp' => '081234567890',
                'email' => 'info@pasarkanikawali.id',
                'jam_buka' => 'Setiap hari, 06:00 - 18:00 WIB',
                'maps_url' => 'https://maps.google.com/?q=Pasar+Ikan+Kawali',
            ]
        );
    }
}