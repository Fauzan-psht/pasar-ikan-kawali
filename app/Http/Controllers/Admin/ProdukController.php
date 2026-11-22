<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')->latest()->paginate(10);
        return view('admin.produk.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = Kategori::where('status', true)->orderBy('tipe')->orderBy('nama')->get();
        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tipe_produk' => 'required|in:konsumsi,benih',
            'satuan' => 'required|in:kg,ekor',
            'harga_jual' => 'required|numeric|min:0',
            'harga_modal' => 'nullable|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
            'status' => 'boolean',
        ]);

        // Generate slug unik
        $validated['slug'] = Str::slug($request->nama) . '-' . Str::random(4);
        $validated['harga_modal'] = $validated['harga_modal'] ?? 0;
        $validated['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        }

        Produk::create($validated);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Produk $produk)
    {
        $kategoris = Kategori::where('status', true)->orderBy('tipe')->orderBy('nama')->get();
        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tipe_produk' => 'required|in:konsumsi,benih',
            'satuan' => 'required|in:kg,ekor',
            'harga_jual' => 'required|numeric|min:0',
            'harga_modal' => 'nullable|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
            'status' => 'boolean',
        ]);

        $validated['harga_modal'] = $validated['harga_modal'] ?? 0;
        $validated['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $produk->update($validated);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Produk $produk)
    {
        // Sesuai modul: Produk yang sudah terjual jangan di-hard-delete, tapi diarsip/nonaktifkan.
        // Untuk amannya, kita cek apakah stok pernah berubah/terjual. 
        // Jika belum pernah masuk transaksi, boleh hapus permanen.
        if ($produk->detailTransaksis()->count() > 0 || $produk->detailPesanans()->count() > 0) {
            $produk->update(['status' => false]);
            return back()->with('success', 'Produk memiliki riwayat transaksi, sehingga dinonaktifkan (tidak dihapus permanen).');
        }

        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();
        return back()->with('success', 'Produk berhasil dihapus!');
    }
}