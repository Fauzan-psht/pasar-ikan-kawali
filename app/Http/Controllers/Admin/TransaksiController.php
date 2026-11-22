<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\StokMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with('user', 'pelanggan')->latest()->paginate(20);
        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function void(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'void_reason' => 'required|string|max:255'
        ]);

        if ($transaksi->status === 'void') {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        try {
            DB::beginTransaction();

            // 1. Ubah status transaksi
            $transaksi->update([
                'status' => 'void',
                'void_reason' => $request->void_reason,
                'void_at' => now(),
                'void_by' => auth()->id(),
            ]);

            // 2. Kembalikan Stok Produk & Catat Movement
            foreach ($transaksi->details as $detail) {
                if ($detail->produk_id) {
                    $produk = Produk::lockForUpdate()->find($detail->produk_id);
                    if ($produk) {
                        // Kembalikan stok
                        $produk->increment('stok', $detail->qty);

                        // Catat di stok movements
                        StokMovement::create([
                            'produk_id' => $produk->id,
                            'tipe' => 'void',
                            'qty' => $detail->qty,
                            'source_type' => Transaksi::class,
                            'source_id' => $transaksi->id,
                            'user_id' => auth()->id(),
                            'alasan' => 'Void Transaksi: ' . $request->void_reason
                        ]);
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Transaksi berhasil di-void dan stok telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal melakukan void: ' . $e->getMessage());
        }
    }
}