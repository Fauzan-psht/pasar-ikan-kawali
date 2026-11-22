<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function index()
    {
        $ratings = Rating::with('pesanan')->latest()->paginate(15);
        return view('admin.ulasan.index', compact('ratings'));
    }

    public function updateStatus(Request $request, Rating $rating)
    {
        $request->validate(['status' => 'required|in:pending,approved,rejected']);
        $rating->update(['status' => $request->status]);
        return back()->with('success', 'Status ulasan berhasil diperbarui.');
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}