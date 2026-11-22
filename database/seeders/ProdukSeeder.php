<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $konsumsi = [
            ['kategori' => 'Ikan Lele', 'nama' => 'Lele Segar', 'harga' => 22000, 'modal' => 16000, 'stok' => 50],
            ['kategori' => 'Ikan Nila', 'nama' => 'Nila Segar', 'harga' => 32000, 'modal' => 25000, 'stok' => 40],
            ['kategori' => 'Ikan Mas', 'nama' => 'Mas Segar', 'harga' => 35000, 'modal' => 27000, 'stok' => 35],
            ['kategori' => 'Ikan Gurame', 'nama' => 'Gurame Segar', 'harga' => 45000, 'modal' => 36000, 'stok' => 25],
            ['kategori' => 'Ikan Patin', 'nama' => 'Patin Segar', 'harga' => 28000, 'modal' => 21000, 'stok' => 30],
            ['kategori' => 'Ikan Mujair', 'nama' => 'Mujair Segar', 'harga' => 30000, 'modal' => 23000, 'stok' => 30],
        ];

        foreach ($konsumsi as $item) {
            $kategori = Kategori::where('nama', $item['kategori'])->first();
            if (! $kategori) continue;
            Produk::updateOrCreate(
                ['slug' => Str::slug($item['nama'])],
                [
                    'kategori_id' => $kategori->id,
                    'nama' => $item['nama'],
                    'deskripsi' => 'Ikan air tawar segar berkualitas, cocok untuk konsumsi harian.',
                    'tipe_produk' => 'konsumsi',
                    'satuan' => 'kg',
                    'harga_jual' => $item['harga'],
                    'harga_modal' => $item['modal'],
                    'stok' => $item['stok'],
                    'stok_minimum' => 10,
                    'status' => true,
                ]
            );
        }

        $benih = [
            ['kategori' => 'Benih Lele', 'nama' => 'Benih Lele Ukuran 5-7 cm', 'harga' => 300, 'modal' => 200, 'stok' => 5000],
            ['kategori' => 'Benih Nila', 'nama' => 'Benih Nila Ukuran 5-7 cm', 'harga' => 400, 'modal' => 250, 'stok' => 4000],
            ['kategori' => 'Benih Mas', 'nama' => 'Benih Mas Ukuran 5-7 cm', 'harga' => 450, 'modal' => 300, 'stok' => 3000],
            ['kategori' => 'Benih Gurame', 'nama' => 'Benih Gurame Ukuran 5-7 cm', 'harga' => 600, 'modal' => 400, 'stok' => 2000],
        ];

        foreach ($benih as $item) {
            $kategori = Kategori::where('nama', $item['kategori'])->first();
            if (! $kategori) continue;
            Produk::updateOrCreate(
                ['slug' => Str::slug($item['nama'])],
                [
                    'kategori_id' => $kategori->id,
                    'nama' => $item['nama'],
                    'deskripsi' => 'Benih ikan air tawar sehat dan siap tebar untuk budidaya.',
                    'tipe_produk' => 'benih',
                    'satuan' => 'ekor',
                    'harga_jual' => $item['harga'],
                    'harga_modal' => $item['modal'],
                    'stok' => $item['stok'],
                    'stok_minimum' => 100,
                    'status' => true,
                ]
            );
        }
    }
}