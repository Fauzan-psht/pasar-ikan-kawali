<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Payment;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\StokMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('public.katalog')->with('error', 'Keranjang Anda masih kosong.');
        }

        if ($error = $this->cartQuantityError($cart)) {
            return redirect()->route('public.cart')->with('error', $error);
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
            'metode_bayar' => 'required|in:tunai,transfer,qris', // Tambahan qris
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('public.katalog')->with('error', 'Keranjang kosong.');
        }

        if ($error = $this->cartQuantityError($cart)) {
            return redirect()->route('public.cart')->with('error', $error);
        }

        try {
            DB::beginTransaction();

            $total = 0;
            $itemsToInsert = [];

            foreach ($cart as $id => $details) {
                $produk = Produk::lockForUpdate()->find($id);
                $quantity = is_numeric($details['qty'] ?? null) ? (float) $details['qty'] : 0.0;

                if (! $produk) {
                    throw new \Exception('Salah satu produk di keranjang sudah tidak tersedia.');
                }

                if (! $produk->acceptsOrderQuantity($quantity)) {
                    throw new \Exception($this->orderQuantityErrorMessage($produk));
                }

                if ($produk->stok < $quantity) {
                    throw new \Exception("Stok untuk {$details['nama']} tidak mencukupi.");
                }

                $subtotal = $produk->harga_jual * $quantity;
                $total += $subtotal;

                $itemsToInsert[] = [
                    'produk_id' => $produk->id,
                    'nama_snapshot' => $produk->nama,
                    'qty' => $quantity,
                    'satuan_snapshot' => $produk->satuan,
                    'harga_snapshot' => $produk->harga_jual,
                    'subtotal' => $subtotal,
                ];

                $produk->decrement('stok', $quantity);
            }

            $pelanggan = Pelanggan::firstOrCreate(
                ['telepon' => $request->whatsapp],
                ['nama' => $request->nama, 'alamat' => $request->alamat]
            );

            // Jika QRIS atau Transfer, status pending
            $statusPesanan = in_array($request->metode_bayar, ['transfer', 'qris']) ? 'pending_payment' : 'confirmed';

            // Buat Pesanan
            $pesanan = Pesanan::create([
                'nomor' => Pesanan::generateNomor(),
                'pelanggan_id' => $pelanggan->id,
                'guest_name' => $request->nama,
                'guest_phone' => $request->whatsapp,
                'alamat' => $request->metode_pengambilan === 'kirim' ? $request->alamat : null,
                'metode_pengambilan' => $request->metode_pengambilan,
                'subtotal' => $total,
                'ongkir' => 0,
                'total' => $total,
                'status' => $statusPesanan,
                'catatan' => $request->catatan,
            ]);

            foreach ($itemsToInsert as $item) {
                $item['pesanan_id'] = $pesanan->id;
                DetailPesanan::create($item);

                StokMovement::create([
                    'produk_id' => $item['produk_id'],
                    'tipe' => 'keluar',
                    'qty' => $item['qty'],
                    'source_type' => Pesanan::class,
                    'source_id' => $pesanan->id,
                    'user_id' => null,
                    'alasan' => 'Pesanan Online',
                ]);
            }

            // JIKA MEMILIH QRIS ATAU TRANSFER (GATEWAY)
            if (in_array($request->metode_bayar, ['transfer', 'qris'])) {
                // Konfigurasi Midtrans
                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production');
                Config::$isSanitized = true;
                Config::$is3ds = true;

                // Jika QRIS, kita set enabled_payments khusus qris
                $enabledPayments = $request->metode_bayar === 'qris' ? ['other_qris'] : [];

                $params = [
                    'transaction_details' => [
                        'order_id' => $pesanan->nomor,
                        'gross_amount' => (int) $total,
                    ],
                    'customer_details' => [
                        'first_name' => $request->nama,
                        'phone' => $request->whatsapp,
                    ],
                ];

                if (!empty($enabledPayments)) {
                    $params['enabled_payments'] = $enabledPayments;
                }

                $snapToken = Snap::getSnapToken($params);

                // Simpan Payment record
                Payment::create([
                    'pesanan_id' => $pesanan->id,
                    'provider' => 'midtrans',
                    'provider_ref' => $snapToken,
                    'metode' => $request->metode_bayar, // simpan qris / transfer
                    'nominal' => $total,
                    'status' => 'pending',
                ]);

                DB::commit();
                session()->forget('cart');

                // Kirim token ke halaman sukses untuk popup Snap
                return redirect()->route('public.checkout.success', $pesanan->nomor)->with('snap_token', $snapToken);
            }

            // Jika Tunai
            DB::commit();
            session()->forget('cart');

            return redirect()->route('public.checkout.success', $pesanan->nomor);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memproses pesanan: '.$e->getMessage());
        }
    }

    public function success($nomor)
    {
        $pesanan = Pesanan::where('nomor', $nomor)->firstOrFail();

        return view('public.checkout_success', compact('pesanan'));
    }

    public function track(Request $request)
    {
        $pesanan = null;
        if ($request->has('nomor')) {
            $request->validate(['nomor' => 'required|string']);
            $pesanan = Pesanan::where('nomor', $request->nomor)->with('details')->first();
            if (! $pesanan) {
                return back()->with('error', 'Nomor pesanan tidak ditemukan.')->withInput();
            }
        }

        return view('public.track', compact('pesanan'));
    }

    private function cartQuantityError(array $cart): ?string
    {
        foreach ($cart as $id => $details) {
            $produk = Produk::find($id);

            if (! $produk) {
                return 'Salah satu produk di keranjang sudah tidak tersedia. Hapus produk tersebut sebelum melanjutkan.';
            }

            $quantity = is_array($details) && is_numeric($details['qty'] ?? null)
                ? (float) $details['qty']
                : 0.0;

            if (! $produk->acceptsOrderQuantity($quantity)) {
                return $this->orderQuantityErrorMessage($produk);
            }
        }

        return null;
    }

    private function orderQuantityErrorMessage(Produk $produk): string
    {
        return $produk->satuan === 'kg'
            ? "Jumlah minimum pembelian {$produk->nama} adalah 0,5 kg."
            : "Jumlah {$produk->nama} harus berupa bilangan bulat minimal 1 ekor.";
    }
}