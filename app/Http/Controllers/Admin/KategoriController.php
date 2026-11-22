<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('tipe')->orderBy('nama')->get();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:konsumsi,benih',
            'deskripsi' => 'nullable|string',
            'status' => 'boolean',
        ]);

        Kategori::create($validated);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:konsumsi,benih',
            'deskripsi' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $kategori->update($validated);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->produks()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk.');
        }
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}