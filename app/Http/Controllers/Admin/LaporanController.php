<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default: Bulan ini
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // 1. Total Omzet (hanya status paid)
        $totalOmzet = Transaksi::where('status', 'paid')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('total');

        // 2. Total Transaksi
        $totalTransaksi = Transaksi::where('status', 'paid')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        // 3. Produk Terlaris
        $produkTerlaris = DetailTransaksi::select('produk_id', 'nama_snapshot', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_pendapatan'))
            ->whereHas('transaksi', function($q) use ($startDate, $endDate) {
                $q->where('status', 'paid')
                  ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            })
            ->groupBy('produk_id', 'nama_snapshot')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        return view('admin.laporan.index', compact(
            'startDate', 'endDate', 'totalOmzet', 'totalTransaksi', 'produkTerlaris'
        ));
    }
}