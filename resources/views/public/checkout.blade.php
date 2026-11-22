@extends('public.layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="page-header">
    <div class="container">
        <nav class="breadcrumb-custom mb-2"><a href="{{ route('public.cart') }}">Keranjang</a> / Checkout</nav>
        <h1>Detail Pengiriman</h1>
    </div>
</div>

<section class="py-5">
    <div class="container py-4">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('public.checkout.process') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="panel p-4 bg-white rounded shadow-sm border">
                        <h5 class="fw-bold mb-4">Informasi Pembeli</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="whatsapp" class="form-control" required value="{{ old('whatsapp') }}" placeholder="08xxxxxx">
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3">Metode Pengambilan</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metode_pengambilan" id="ambil" value="ambil" checked onchange="toggleAlamat()">
                            <label class="form-check-label" for="ambil">Ambil di Pasar (Gratis)</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="metode_pengambilan" id="kirim" value="kirim" onchange="toggleAlamat()">
                            <label class="form-check-label" for="kirim">Kirim ke Alamat (Ongkir menyusul)</label>
                        </div>

                        <div class="mb-3 d-none" id="alamatContainer">
                            <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control" rows="3">{{ old('alamat') }}</textarea>
                        </div>

                        <div class="mb-3 mt-4">
                            <label class="form-label fw-semibold">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Minta dibersihkan sisiknya">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <div class="panel p-4 bg-white rounded shadow-sm border">
                        <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                        
                        @php $total = 0; @endphp
                        @foreach($cart as $item)
                            @php $total += $item['harga'] * $item['qty'] @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $item['nama'] }} ({{ $item['qty'] }} {{ $item['satuan'] }})</span>
                                <span class="fw-bold">Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <h6 class="fw-bold mb-3">Metode Pembayaran</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metode_bayar" id="tunai" value="tunai" checked>
                            <label class="form-check-label" for="tunai">Bayar Tunai (di Pasar / Saat Terima)</label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="radio" name="metode_bayar" id="transfer" value="transfer">
                            <label class="form-check-label" for="transfer">Transfer Bank (Manual)</label>
                        </div>

                        <button type="submit" class="btn btn-hero-primary w-100 py-3 fs-5">
                            <i class="bi bi-check-circle me-2"></i> Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function toggleAlamat() {
        if(document.getElementById('kirim').checked) {
            document.getElementById('alamatContainer').classList.remove('d-none');
        } else {
            document.getElementById('alamatContainer').classList.add('d-none');
        }
    }
</script>
@endpush