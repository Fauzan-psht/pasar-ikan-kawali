<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokMasuk;
use App\Models\DetailStokMasuk;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\StokMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokMasukController extends Controller
{
    public function index()
    {
        $stokMasuks = StokMasuk::with('supplier', 'user')->latest()->paginate(10);
        return view('admin.stok.index', compact('stokMasuks'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', true)->get();
        $produks = Produk::where('status', true)->get();
        return view('admin.stok.create', compact('suppliers', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.qty' => 'required|numeric|min:0.1',
            'items.*.harga_modal' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Buat Header Stok Masuk
            $stokMasuk = StokMasuk::create([
                'nomor' => 'SM-' . now()->format('YmdHis'),
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'tanggal' => $request->tanggal,
                'catatan' => $request->catatan,
                'status' => 'posted', // Langsung posted (masuk stok)
            ]);

            // 2. Simpan Detail & Update Stok Produk
            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['harga_modal'];
                
                DetailStokMasuk::create([
                    'stok_masuk_id' => $stokMasuk->id,
                    'produk_id' => $item['produk_id'],
                    'qty' => $item['qty'],
                    'harga_modal' => $item['harga_modal'],
                    'subtotal' => $subtotal,
                ]);

                // Tambah stok produk
                $produk = Produk::lockForUpdate()->find($item['produk_id']);
                $produk->increment('stok', $item['qty']);
                // Opsional: update harga modal terakhir
                $produk->update(['harga_modal' => $item['harga_modal']]);

                // Catat Movement
                StokMovement::create([
                    'produk_id' => $produk->id,
                    'tipe' => 'masuk',
                    'qty' => $item['qty'],
                    'source_type' => StokMasuk::class,
                    'source_id' => $stokMasuk->id,
                    'user_id' => auth()->id(),
                    'alasan' => 'Penerimaan Supplier: ' . $stokMasuk->nomor
                ]);
            }

            DB::commit();
            return redirect()->route('admin.stok.index')->with('success', 'Stok masuk berhasil dicatat dan stok produk telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat stok masuk: ' . $e->getMessage())->withInput();
        }
    }

    public function show(StokMasuk $stok)
    {
        $stok->load('supplier', 'user', 'details.produk');
        return view('admin.stok.show', compact('stok'));
    }
}