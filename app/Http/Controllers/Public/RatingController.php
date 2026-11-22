<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'nama' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        // Cek apakah pesanan ini sudah pernah dirating
        $exists = Rating::where('pesanan_id', $request->pesanan_id)->exists();
        if ($exists) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        Rating::create([
            'pesanan_id' => $request->pesanan_id,
            'nama' => $request->nama,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'status' => 'pending', // Harus di-approve admin dulu
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda akan tampil setelah disetujui admin.');
    }
}