<?php

namespace Tests\Feature\Admin;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProdukControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_product_list_for_server_side_search(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.produk.index'));

        $response->assertOk()->assertSee('productsTable');
    }

    public function test_admin_can_delete_product_without_order_or_transaction_history(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);
        $kategori = Kategori::create([
            'nama' => 'Ikan Konsumsi',
            'tipe' => 'konsumsi',
        ]);
        $produk = Produk::create([
            'kategori_id' => $kategori->id,
            'nama' => 'Lele Segar',
            'slug' => 'lele-segar',
            'harga_jual' => 22000,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.produk.index'))
            ->delete(route('admin.produk.destroy', $produk));

        $response->assertRedirect(route('admin.produk.index'));
        $this->assertDatabaseMissing('produks', ['id' => $produk->id]);
    }

    public function test_admin_can_search_products_through_the_server_side_table(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);
        $kategori = Kategori::create([
            'nama' => 'Ikan Konsumsi',
            'tipe' => 'konsumsi',
        ]);
        Produk::create([
            'kategori_id' => $kategori->id,
            'nama' => 'Lele Segar',
            'slug' => 'lele-segar',
            'harga_jual' => 22000,
        ]);

        $columnNames = [
            'produks.foto',
            'produks.nama',
            'kategoris.nama',
            'produks.tipe_produk',
            'produks.harga_jual',
            'produks.stok',
            'produks.status',
            'actions',
            'produks.created_at',
            'produks.id',
        ];
        $columns = array_map(fn (string $name): array => ['name' => $name], $columnNames);
        $response = $this->actingAs($admin)
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('admin.produk.index', [
                'columns' => $columns,
                'draw' => 1,
                'length' => 10,
                'order' => [['column' => 1, 'dir' => 'asc']],
                'search' => ['value' => 'Lele'],
                'start' => 0,
            ]));

        $response->assertOk()
            ->assertJsonPath('recordsFiltered', 1)
            ->assertJsonPath('data.0.nama', 'Lele Segar')
            ->assertJsonPath('data.0.kategori_nama', 'Ikan Konsumsi');
    }

    public function test_admin_product_upload_is_compressed_before_storage(): void
    {
        Storage::fake('public');
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);
        $kategori = Kategori::create([
            'nama' => 'Ikan Konsumsi',
            'tipe' => 'konsumsi',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.produk.store'), [
            'kategori_id' => $kategori->id,
            'nama' => 'Lele Segar',
            'deskripsi' => 'Ikan lele',
            'foto' => UploadedFile::fake()->image('lele.jpg', width: 2400, height: 1200),
            'tipe_produk' => 'konsumsi',
            'satuan' => 'kg',
            'harga_jual' => 22000,
            'harga_modal' => 16000,
            'stok' => 10,
            'stok_minimum' => 2,
            'status' => 1,
        ]);

        $response->assertRedirect(route('admin.produk.index'));
        $produk = Produk::firstOrFail();
        Storage::disk('public')->assertExists($produk->foto);

        $imageInfo = getimagesizefromstring(Storage::disk('public')->get($produk->foto));
        $this->assertSame(1920, $imageInfo[0]);
        $this->assertSame(960, $imageInfo[1]);
    }
}
