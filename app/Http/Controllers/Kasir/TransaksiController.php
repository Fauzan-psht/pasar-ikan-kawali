<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function index()
    {
        // Kasir hanya melihat transaksinya sendiri
        $transaksis = Transaksi::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);
            
        return view('kasir.transaksi.index', compact('transaksis'));
    }
}