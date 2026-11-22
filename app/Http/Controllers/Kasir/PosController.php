<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\StokMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        // Ambil semua produk yang aktif dan stoknya > 0
        $produks = Produk::aktif()->where('stok', '>', 0)->with('kategori')->get();
        return view('kasir.pos.index', compact('produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:produks,id',
            'items.*.qty' => 'required|numeric|min:0.1',
            'bayar' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $total = 0;
            $itemsToInsert = [];

            // 1. Validasi stok & hitung total
            foreach ($request->items as $item) {
                $produk = Produk::lockForUpdate()->find($item['id']);
                
                if ($produk->stok < $item['qty']) {
                    throw new \Exception("Stok {$produk->nama} tidak mencukupi. Sisa: {$produk->stok}");
                }

                $subtotal = $produk->harga_jual * $item['qty'];
                $total += $subtotal;

                $itemsToInsert[] = [
                    'produk_id' => $produk->id,
                    'nama_snapshot' => $produk->nama,
                    'qty' => $item['qty'],
                    'satuan_snapshot' => $produk->satuan,
                    'harga_snapshot' => $produk->harga_jual,
                    'subtotal' => $subtotal,
                ];

                // Kurangi stok
                $produk->decrement('stok', $item['qty']);
            }

            // Cek uang bayar
            if ($request->bayar < $total) {
                throw new \Exception("Uang bayar kurang dari total tagihan.");
            }

            // 2. Buat Header Transaksi
            $transaksi = Transaksi::create([
                'nomor' => Transaksi::generateNomor(),
                'user_id' => auth()->id(),
                'kanal' => 'pos',
                'total' => $total,
                'bayar' => $request->bayar,
                'kembalian' => $request->bayar - $total,
                'status' => 'paid',
            ]);

            // 3. Simpan Detail Transaksi & Stok Movement
            foreach ($itemsToInsert as $item) {
                $item['transaksi_id'] = $transaksi->id;
                DetailTransaksi::create($item);

                StokMovement::create([
                    'produk_id' => $item['produk_id'],
                    'tipe' => 'keluar',
                    'qty' => $item['qty'],
                    'source_type' => Transaksi::class,
                    'source_id' => $transaksi->id,
                    'user_id' => auth()->id(),
                    'alasan' => 'Penjualan POS'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'redirect' => route('kasir.pos.struk', $transaksi->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function struk(Transaksi $transaksi)
    {
        // Pastikan kasir hanya bisa lihat struk miliknya
        if ($transaksi->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $transaksi->load('details', 'user');
        return view('kasir.pos.struk', compact('transaksi'));
    }
}