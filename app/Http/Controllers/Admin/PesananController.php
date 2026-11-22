<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::latest()->paginate(15);
        return view('admin.pesanan.index', compact('pesanans'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load('details.produk', 'pelanggan');
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status' => 'required|in:pending_payment,confirmed,processing,ready,completed,cancelled'
        ]);

        $pesanan->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi: ' . strtoupper($request->status));
    }
}