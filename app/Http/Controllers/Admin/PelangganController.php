<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;

class PelangganController extends Controller
{
    public function index()
    {
        // Menampilkan pelanggan beserta jumlah transaksi dan pesanan mereka
        $pelanggans = Pelanggan::withCount(['transaksis', 'pesanans'])
            ->latest()
            ->paginate(15);

        return view('admin.pelanggan.index', compact('pelanggans'));
    }
}
