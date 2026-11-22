@extends('layouts.dashboard')

@section('title', 'QR Code Website')
@section('page_title', 'QR Code Akses Website')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="panel text-center p-5">
            <h4 class="fw-bold mb-3">Scan untuk Buka Website</h4>
            <p class="text-muted mb-4">Cetak QR Code ini dan tempelkan di banner, poster, atau meja kasir pasar.</p>
            
            <div class="bg-white p-3 d-inline-block rounded shadow-sm border mb-4">
                <img src="{{ $qrUrl }}" alt="QR Code" class="img-fluid">
            </div>

            <div class="alert alert-info">
                <strong>URL Tujuan:</strong><br>
                <a href="{{ $url }}" target="_blank">{{ $url }}</a>
            </div>

            <p class="text-muted small">Pastikan domain menggunakan HTTPS saat web ini di-online-kan (production) agar tidak diblokir oleh browser pembeli.</p>
        </div>
    </div>
</div>
@endsection