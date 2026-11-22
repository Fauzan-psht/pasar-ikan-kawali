<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Ikan Lele', 'tipe' => 'konsumsi'],
            ['nama' => 'Ikan Nila', 'tipe' => 'konsumsi'],
            ['nama' => 'Ikan Mas', 'tipe' => 'konsumsi'],
            ['nama' => 'Ikan Gurame', 'tipe' => 'konsumsi'],
            ['nama' => 'Ikan Patin', 'tipe' => 'konsumsi'],
            ['nama' => 'Ikan Mujair', 'tipe' => 'konsumsi'],
            ['nama' => 'Benih Lele', 'tipe' => 'benih'],
            ['nama' => 'Benih Nila', 'tipe' => 'benih'],
            ['nama' => 'Benih Mas', 'tipe' => 'benih'],
            ['nama' => 'Benih Gurame', 'tipe' => 'benih'],
        ];

        foreach ($data as $item) {
            Kategori::updateOrCreate(
                ['slug' => Str::slug($item['nama'])],
                [
                    'nama' => $item['nama'],
                    'tipe' => $item['tipe'],
                    'status' => true,
                ]
            );
        }
    }
}