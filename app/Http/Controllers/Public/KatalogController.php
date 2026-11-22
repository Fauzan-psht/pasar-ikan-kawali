<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\ProfilPasar;

class KatalogController extends Controller
{
    public function index()
    {
        $profil = ProfilPasar::current();
        $query = Produk::aktif()->with('kategori');

        if ($tipe = request('tipe')) {
            if (in_array($tipe, ['konsumsi', 'benih'])) {
                $query->where('tipe_produk', $tipe);
            }
        }

        $produks = $query->latest()->paginate(9)->withQueryString();

        return view('public.katalog', compact('profil', 'produks'));
    }

    public function detail(string $slug)
    {
        $profil = ProfilPasar::current();
        $produk = Produk::where('slug', $slug)->aktif()->with('kategori')->firstOrFail();
        return view('public.produk-detail', compact('profil', 'produk'));
    }
}