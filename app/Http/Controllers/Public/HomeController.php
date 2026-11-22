<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\ProfilPasar;

class HomeController extends Controller
{
    public function index()
    {
        $profil = ProfilPasar::current();
        $produkUnggulan = Produk::aktif()->latest()->take(6)->get();
        $totalProduk = Produk::aktif()->count();
        $totalKategori = Kategori::aktif()->count();

        return view('public.home', compact('profil', 'produkUnggulan', 'totalProduk', 'totalKategori'));
    }

    public function profil()
    {
        $profil = ProfilPasar::current();
        return view('public.profil', compact('profil'));
    }
}