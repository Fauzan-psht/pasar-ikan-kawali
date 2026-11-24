<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        if ($user->isKasir()) {
            return $this->kasirDashboard();
        }

        auth()->logout();

        return redirect()->route('login')->withErrors(['email' => 'Role akun tidak valid.']);
    }

    private function adminDashboard()
    {
        $hariIni = now()->startOfDay();
        $bulanIni = now()->startOfMonth();

        $omzetHariIni = Transaksi::where('status', 'paid')
            ->where('created_at', '>=', $hariIni)->sum('total');

        $omzetBulanIni = Transaksi::where('status', 'paid')
            ->where('created_at', '>=', $bulanIni)->sum('total');

        $totalProduk = Produk::aktif()->count();
        $totalKategori = Kategori::aktif()->count();
        $pesananMasuk = Pesanan::whereIn('status', ['pending_payment', 'confirmed', 'processing'])->count();
        $stokMenipis = Produk::whereColumn('stok', '<=', 'stok_minimum')->where('status', 1)->count();
        $totalUser = User::where('is_active', 1)->count();

        $transaksiTerbaru = Transaksi::with(['user', 'pelanggan'])->latest()->take(10)->get();
        $pesananTerbaru = Pesanan::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'omzetHariIni', 'omzetBulanIni', 'totalProduk', 'totalKategori',
            'pesananMasuk', 'stokMenipis', 'totalUser',
            'transaksiTerbaru', 'pesananTerbaru'
        ));
    }

    private function kasirDashboard()
    {
        $user = auth()->user();
        $hariIni = now()->startOfDay();

        $transaksiHariIni = Transaksi::where('user_id', $user->id)
            ->where('created_at', '>=', $hariIni)->where('status', 'paid');

        $jumlahTransaksi = $transaksiHariIni->count();
        $totalHariIni = (clone $transaksiHariIni)->sum('total');

        $transaksiTerbaru = Transaksi::where('user_id', $user->id)
            ->with('pelanggan')->latest()->take(10)->get();

        $produkStokRendah = Produk::whereColumn('stok', '<=', 'stok_minimum')
            ->where('status', 1)->take(5)->get();

        return view('kasir.dashboard', compact(
            'jumlahTransaksi', 'totalHariIni', 'transaksiTerbaru', 'produkStokRendah'
        ));
    }
}
