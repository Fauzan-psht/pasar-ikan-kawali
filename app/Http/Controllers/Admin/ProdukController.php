<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk; // Tambahan
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
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

            $request->validate([
                'columns' => ['required', 'array', 'size:10'],
                'columns.*.name' => ['required', 'string', Rule::in($columnNames)],
                'order' => ['sometimes', 'array'],
                'order.*.column' => ['integer', Rule::in([1, 2, 3, 4, 5, 6, 8, 9])],
                'order.*.dir' => ['required', Rule::in(['asc', 'desc'])],
                'start' => ['sometimes', 'integer', 'min:0'],
                'length' => ['sometimes', 'integer', 'between:1,100'],
                'search.value' => ['sometimes', 'string', 'max:100'],
                'search.regex' => ['sometimes', Rule::in(['false', '0', false, 0])],
            ]);

            if (array_column($request->input('columns'), 'name') !== $columnNames) {
                throw ValidationException::withMessages([
                    'columns' => 'Kolom tabel tidak valid.',
                ]);
            }

            $dataTableQuery = Produk::query()
                ->leftJoin('kategoris', 'kategoris.id', '=', 'produks.kategori_id')
                ->select('produks.*', 'kategoris.nama as kategori_nama');

            return DataTables::eloquent($dataTableQuery)
                ->addColumn('actions', fn (Produk $produk): string => view('admin.produk.partials.actions', compact('produk'))->render())
                ->escapeColumns([])
                ->rawColumns(['actions'])
                ->toJson();
        }

        $query = Produk::with('kategori');

        if ($request->filled('q')) {
            $query->where('nama', 'like', '%'.$request->q.'%');
        }

        $produks = $query->latest()->paginate(10)->withQueryString();

        return view('admin.produk.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = Kategori::where('status', true)->orderBy('tipe')->orderBy('nama')->get();

        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(Request $request, ImageService $imageService)
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

        $validated['slug'] = Str::slug($request->nama).'-'.Str::random(4);
        $validated['harga_modal'] = $validated['harga_modal'] ?? 0;
        $validated['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('foto')) {
            $validated['foto'] = $imageService->store($request->file('foto'), 'produk', 'foto');
        }

        $produk = Produk::create($validated);

        // Catat Log
        ActivityLogger::log('Tambah Produk Baru', $produk, null, $produk->toArray());

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Produk $produk)
    {
        $kategoris = Kategori::where('status', true)->orderBy('tipe')->orderBy('nama')->get();

        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk, ImageService $imageService)
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

        $oldFoto = $produk->foto;

        if ($request->hasFile('foto')) {
            $validated['foto'] = $imageService->store($request->file('foto'), 'produk', 'foto');
        }

        $before = $produk->getOriginal();
        $produk->update($validated);

        if ($request->hasFile('foto') && $oldFoto) {
            Storage::disk('public')->delete($oldFoto);
        }

        // Catat Log
        ActivityLogger::log('Update Produk', $produk, $before, $produk->getChanges());

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->detailTransaksis()->count() > 0 || $produk->detailPesanans()->count() > 0) {
            $produk->update(['status' => false]);
            // Catat Log
            ActivityLogger::log('Nonaktifkan Produk (Ada Histori)', $produk, $produk->toArray(), ['status' => false]);

            return back()->with('success', 'Produk memiliki riwayat transaksi, sehingga dinonaktifkan (tidak dihapus permanen).');
        }

        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }

        // Catat Log Sebelum Hapus
        ActivityLogger::log('Hapus Produk', $produk, $produk->toArray(), null);

        $produk->delete();

        return back()->with('success', 'Produk berhasil dihapus!');
    }
}
