<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_benih_product_sold_by_kilogram_can_be_added_at_half_a_kilogram(): void
    {
        $produk = $this->createProduct('benih', 'kg');

        $response = $this->from(route('public.produk.detail', $produk->slug))
            ->post(route('public.cart.add'), [
                'produk_id' => $produk->id,
                'qty' => '0.5',
            ]);

        $response->assertRedirect(route('public.cart'))
            ->assertSessionHas("cart.{$produk->id}.qty", 0.5)
            ->assertSessionHas("cart.{$produk->id}.satuan", 'kg');
    }

    public function test_kilogram_product_detail_uses_half_a_kilogram_as_the_minimum(): void
    {
        $produk = $this->createProduct('konsumsi', 'kg');

        $response = $this->get(route('public.produk.detail', $produk->slug));

        $response->assertOk()
            ->assertSee('value="0.5" min="0.5" step="0.1"', false);
    }

    public function test_kilogram_product_cannot_be_added_below_half_a_kilogram(): void
    {
        $produk = $this->createProduct('konsumsi', 'kg');

        $response = $this->from(route('public.produk.detail', $produk->slug))
            ->post(route('public.cart.add'), [
                'produk_id' => $produk->id,
                'qty' => '0.4',
            ]);

        $response->assertRedirect(route('public.produk.detail', $produk->slug))
            ->assertSessionHasErrors([
                'qty' => 'Jumlah minimum pembelian adalah 0,5 kg.',
            ]);
        $this->assertSame([], session('cart', []));
    }

    public function test_per_piece_product_cannot_be_added_as_a_fraction(): void
    {
        $produk = $this->createProduct('benih', 'ekor');

        $response = $this->from(route('public.produk.detail', $produk->slug))
            ->post(route('public.cart.add'), [
                'produk_id' => $produk->id,
                'qty' => '0.5',
            ]);

        $response->assertRedirect(route('public.produk.detail', $produk->slug))
            ->assertSessionHasErrors([
                'qty' => 'Jumlah produk satuan ekor harus berupa bilangan bulat.',
            ]);
    }

    public function test_cart_update_rejects_less_than_half_a_kilogram(): void
    {
        $produk = $this->createProduct('benih', 'kg');
        $this->withSession([
            'cart' => [
                $produk->id => [
                    'nama' => $produk->nama,
                    'qty' => 1.0,
                    'harga' => $produk->harga_jual,
                    'satuan' => $produk->satuan,
                    'foto' => null,
                ],
            ],
        ]);

        $response = $this->postJson(route('public.cart.update'), [
            'id' => $produk->id,
            'qty' => '0.4',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'qty' => 'Jumlah minimum pembelian adalah 0,5 kg.',
            ]);
        $this->assertSame(1.0, (float) session("cart.{$produk->id}.qty"));
    }

    public function test_checkout_rejects_a_legacy_cart_quantity_below_the_minimum(): void
    {
        $produk = $this->createProduct('benih', 'kg');

        $response = $this->from(route('public.checkout'))
            ->withSession([
                'cart' => [
                    $produk->id => [
                        'nama' => $produk->nama,
                        'qty' => 0.4,
                        'harga' => $produk->harga_jual,
                        'satuan' => $produk->satuan,
                        'foto' => null,
                    ],
                ],
            ])->post(route('public.checkout.process'), [
                'nama' => 'Pembeli',
                'whatsapp' => '08123456789',
                'metode_pengambilan' => 'ambil',
                'metode_bayar' => 'tunai',
            ]);

        $response->assertRedirect(route('public.cart'))
            ->assertSessionHas('error', 'Jumlah minimum pembelian Produk Test adalah 0,5 kg.');
        $this->assertSame('10.00', $produk->fresh()->stok);
        $this->assertDatabaseCount('pesanans', 0);
    }

    public function test_checkout_rejects_a_legacy_cart_with_fractional_piece_quantity(): void
    {
        $produk = $this->createProduct('benih', 'ekor');

        $response = $this->from(route('public.checkout'))
            ->withSession([
                'cart' => [
                    $produk->id => [
                        'nama' => $produk->nama,
                        'qty' => 1.5,
                        'harga' => $produk->harga_jual,
                        'satuan' => $produk->satuan,
                        'foto' => null,
                    ],
                ],
            ])->post(route('public.checkout.process'), [
                'nama' => 'Pembeli',
                'whatsapp' => '08123456789',
                'metode_pengambilan' => 'ambil',
                'metode_bayar' => 'tunai',
            ]);

        $response->assertRedirect(route('public.cart'))
            ->assertSessionHas('error', 'Jumlah Produk Test harus berupa bilangan bulat minimal 1 ekor.');
        $this->assertSame('10.00', $produk->fresh()->stok);
        $this->assertDatabaseCount('pesanans', 0);
    }

    public function test_cart_page_does_not_show_the_checkout_arrow(): void
    {
        $produk = $this->createProduct('konsumsi', 'kg');
        $response = $this->withSession([
            'cart' => [
                $produk->id => [
                    'nama' => $produk->nama,
                    'qty' => 1.0,
                    'harga' => $produk->harga_jual,
                    'satuan' => $produk->satuan,
                    'foto' => null,
                ],
            ],
        ])->get(route('public.cart'));

        $response->assertOk()
            ->assertDontSee('bi-arrow-right', false);
    }

    private function createProduct(string $type, string $unit): Produk
    {
        $kategori = Kategori::create([
            'nama' => 'Kategori '.ucfirst($type),
            'tipe' => $type,
        ]);

        return Produk::create([
            'kategori_id' => $kategori->id,
            'nama' => 'Produk Test',
            'slug' => 'produk-test-'.$unit,
            'tipe_produk' => $type,
            'satuan' => $unit,
            'harga_jual' => 1000,
            'stok' => 10,
            'status' => true,
        ]);
    }
}
