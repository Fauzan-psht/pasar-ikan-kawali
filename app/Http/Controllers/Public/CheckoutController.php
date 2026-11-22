<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\StokMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('public.katalog')->with('error', 'Keranjang Anda masih kosong.');
        }
        return view('public.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'metode_pengambilan' => 'required|in:ambil,kirim',
            'alamat' => 'required_if:metode_pengambilan,kirim|nullable|string',
            'metode_bayar' => 'required|in:tunai,transfer',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('public.katalog')->with('error', 'Keranjang kosong.');
        }

        try {
            DB::beginTransaction();

            $total = 0;
            $itemsToInsert = [];

            // 1. Validasi stok terakhir kali
            foreach ($cart as $id => $details) {
                $produk = Produk::lockForUpdate()->find($id);
                if (!$produk || $produk->stok < $details['qty']) {
                    throw new \Exception("Stok untuk {$details['nama']} tidak mencukupi.");
                }

                $subtotal = $produk->harga_jual * $details['qty'];
                $total += $subtotal;

                $itemsToInsert[] = [
                    'produk_id' => $produk->id,
                    'nama_snapshot' => $produk->nama,
                    'qty' => $details['qty'],
                    'satuan_snapshot' => $produk->satuan,
                    'harga_snapshot' => $produk->harga_jual,
                    'subtotal' => $subtotal,
                ];

                // Kurangi stok
                $produk->decrement('stok', $details['qty']);
            }

            // 2. Simpan data pelanggan (opsional, agar masuk ke database pelanggan)
            $pelanggan = Pelanggan::firstOrCreate(
                ['telepon' => $request->whatsapp],
                ['nama' => $request->nama, 'alamat' => $request->alamat]
            );

            // 3. Buat Header Pesanan
            $pesanan = Pesanan::create([
                'nomor' => Pesanan::generateNomor(),
                'pelanggan_id' => $pelanggan->id,
                'guest_name' => $request->nama,
                'guest_phone' => $request->whatsapp,
                'alamat' => $request->metode_pengambilan === 'kirim' ? $request->alamat : null,
                'metode_pengambilan' => $request->metode_pengambilan,
                'subtotal' => $total,
                'ongkir' => 0, // Sesuaikan jika ada ongkir
                'total' => $total,
                'status' => $request->metode_bayar === 'tunai' ? 'confirmed' : 'pending_payment',
                'catatan' => $request->catatan,
            ]);

            // 4. Simpan Detail Pesanan & Stok Movement
            foreach ($itemsToInsert as $item) {
                $item['pesanan_id'] = $pesanan->id;
                DetailPesanan::create($item);

                StokMovement::create([
                    'produk_id' => $item['produk_id'],
                    'tipe' => 'keluar',
                    'qty' => $item['qty'],
                    'source_type' => Pesanan::class,
                    'source_id' => $pesanan->id,
                    'user_id' => null, // Pembeli publik
                    'alasan' => 'Pesanan Online'
                ]);
            }

            DB::commit();

            // Kosongkan keranjang
            session()->forget('cart');

            // Redirect ke halaman sukses (bisa dibuat halaman tracking pesanan)
            return redirect()->route('public.checkout.success', $pesanan->nomor);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    public function success($nomor)
    {
        $pesanan = Pesanan::where('nomor', $nomor)->firstOrFail();
        return view('public.checkout_success', compact('pesanan'));
    }

    // --- TAMBAHAN FUNGSI UNTUK LACAK PESANAN ---
    public function track(Request $request)
    {
        $pesanan = null;
        if ($request->has('nomor')) {
            $request->validate(['nomor' => 'required|string']);
            $pesanan = Pesanan::where('nomor', $request->nomor)
                               ->with('details')
                               ->first();
            
            if (!$pesanan) {
                return back()->with('error', 'Nomor pesanan tidak ditemukan.')->withInput();
            }
        }
        return view('public.track', compact('pesanan'));
    }
}