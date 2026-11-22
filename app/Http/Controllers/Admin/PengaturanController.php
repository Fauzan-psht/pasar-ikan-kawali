<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PengaturanController extends Controller
{
    public function qrCode()
    {
        // Menggunakan API publik untuk generate QR Code instan
        $url = url('/'); // Mengambil URL aktif (misal: http://localhost:8000)
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($url);
        
        return view('admin.qrcode', compact('url', 'qrUrl'));
    }
}