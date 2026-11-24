@extends('public.layouts.app')

@section('title', 'Pesanan Berhasil')

@section('content')
<section class="py-5 mt-5">
    <div class="container py-5 text-center">
        <div class="mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 6rem;"></i>
        </div>
        <h1 class="fw-bold mb-3">Pesanan Berhasil Dibuat!</h1>
        <p class="text-muted mb-4">Terima kasih, <strong>{{ $pesanan->guest_name }}</strong>. Pesanan Anda telah kami terima dan akan segera diproses.</p>
        
        <div class="d-inline-block p-4 rounded border bg-white text-start mb-4" style="min-width: 300px;">
            <div class="mb-2"><span class="text-muted">No. Pesanan:</span> <br><strong class="fs-5">{{ $pesanan->nomor }}</strong></div>
            <div class="mb-2"><span class="text-muted">Total Belanja:</span> <br><strong class="fs-5 text-primary">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong></div>
            <div><span class="text-muted">Status Pembayaran:</span> <br>
                @if($pesanan->status === 'confirmed')
                    <span class="badge bg-success">Lunas / Bayar di Tempat</span>
                @else
                    <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                @endif
            </div>
        </div>
        
        <div class="alert alert-info mx-auto" style="max-width: 500px;">
            <i class="bi bi-info-circle me-2"></i> Silakan simpan nomor pesanan ini. Anda dapat mengecek status pesanan kapan saja melalui menu <strong>Lacak Pesanan</strong> di website.
        </div>

        <div class="mt-4 mb-5">
            <a href="{{ route('public.home') }}" class="btn btn-hero-primary">Kembali ke Beranda</a>
            @if(session('snap_token'))
                <button class="btn btn-warning ms-2" onclick="payNow()">Bayar Sekarang</button>
            @endif
        </div>

        {{-- FORM ULASAN --}}
        @if(session('success'))
            <div class="alert alert-success mt-4 mx-auto" style="max-width: 600px;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-4 mx-auto" style="max-width: 600px;">{{ session('error') }}</div>
        @endif

        <div class="mt-5 mx-auto" style="max-width: 600px; text-align: left;">
            <div class="panel p-4 bg-white rounded shadow-sm border">
                <h5 class="fw-bold mb-3 text-center">Beri Ulasan Pengalaman Anda</h5>
                <form action="{{ route('public.rating.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pesanan_id" value="{{ $pesanan->id }}">
                    <input type="hidden" name="nama" value="{{ $pesanan->guest_name }}">
                    
                    <div class="mb-3 text-center">
                        <label class="form-label fw-semibold">Rating (1-5 Bintang)</label>
                        <select name="rating" class="form-select text-center text-warning fw-bold fs-5" required>
                            <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                            <option value="4">⭐⭐⭐⭐ (Puas)</option>
                            <option value="3">⭐⭐⭐ (Cukup)</option>
                            <option value="2">⭐⭐ (Kurang)</option>
                            <option value="1">⭐ (Sangat Kurang)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Komentar (Opsional)</label>
                        <textarea name="komentar" class="form-control" rows="3" placeholder="Ikan segar, pelayanan cepat..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-hero-primary w-100">Kirim Ulasan</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
{{-- Script Midtrans Snap --}}
@if(session('snap_token'))
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script type="text/javascript">
    // Fungsi untuk memanggil popup Midtrans
    function payNow() {
        snap.pay('{{ session('snap_token') }}', {
            onSuccess: function(result){
                alert("Pembayaran berhasil!");
                window.location.reload();
            },
            onPending: function(result){
                alert("Menunggu pembayaran Anda.");
                window.location.reload();
            },
            onError: function(result){
                alert("Pembayaran gagal!");
                window.location.reload();
            },
            onClose: function(){
                alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
            }
        });
    }

    // Otomatis munculkan popup saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        payNow();
    });
</script>
@endif
@endpush