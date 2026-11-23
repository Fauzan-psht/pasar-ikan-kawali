@extends('public.layouts.app')

@section('title', $produk->nama)

@section('content')
<div class="page-header">
    <div class="container">
        <nav class="breadcrumb-custom mb-2">
            <a href="{{ route('public.home') }}">Beranda</a> /
            <a href="{{ route('public.katalog') }}">Katalog</a> /
            {{ $produk->nama }}
        </nav>
        <h1>{{ $produk->nama }}</h1>
    </div>
</div>

<section class="py-5">
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-modern" style="overflow:hidden;">
                    <div class="card-img product-detail-image" style="height:340px;font-size:5rem;">
                        @if($produk->foto)
                            <img src="{{ asset('storage/'.$produk->foto) }}" alt="{{ $produk->nama }}">
                        @else
                            <i class="bi bi-fish"></i>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card-modern p-4">
                    <span class="badge mb-3" style="background:linear-gradient(135deg,var(--accent-teal),var(--accent-cyan));font-size:0.8rem;padding:0.5rem 1rem;">
                        {{ $produk->tipe_produk === 'benih' ? 'Benih Ikan' : 'Ikan Konsumsi' }}
                    </span>
                    <h2 style="color:var(--primary-900);">{{ $produk->nama }}</h2>
                    <div class="price mb-3" style="font-size:2rem;">
                        {{ $produk->harga_format }} <small>/ {{ $produk->satuan }}</small>
                    </div>
                    <p style="color:var(--gray-700);line-height:1.8;">{{ $produk->deskripsi ?: 'Ikan air tawar berkualitas dari Pasar Ikan Kawali.' }}</p>
                    <hr>
                    <div class="row">
                        <div class="col-6"><strong>Stok tersedia:</strong><br>{{ $produk->stok }} {{ $produk->satuan }}</div>
                        <div class="col-6"><strong>Kategori:</strong><br>{{ $produk->kategori->nama }}</div>
                    </div>
                    <hr>

                    @if($produk->stok > 0)
                    <form action="{{ route('public.cart.add') }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                        <div class="d-flex flex-column flex-sm-row gap-2 align-items-stretch align-items-sm-end mb-3">
                                <div class="product-quantity">
                                <label class="form-label fw-semibold small">Jumlah ({{ $produk->satuan }})</label>
                                <input type="number" name="qty" class="form-control" value="1" min="{{ $produk->satuan === 'kg' ? '0.1' : '1' }}" step="{{ $produk->satuan === 'kg' ? '0.1' : '1' }}" max="{{ $produk->stok }}" required>
                            </div>
                                <button type="submit" class="btn btn-hero-primary flex-grow-1 product-add-button">
                                <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </form>
                    @else
                        <div class="alert alert-warning">Stok habis.</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Ulasan --}}
        <div class="mt-5">
            <h3 style="color:var(--primary-900);margin-bottom:1.5rem;"><i class="bi bi-star me-2" style="color:var(--accent-gold);"></i> Ulasan Pembeli</h3>
            @forelse($produk->ratings()->approved()->latest()->take(5)->get() as $r)
            <div class="card-modern p-3 mb-2">
                <strong>{{ $r->nama }}</strong>
                <span class="stars ms-2">
                    @for($i=1; $i<=5; $i++)
                        <i class="bi bi-star{{ $i <= $r->rating ? '-fill' : '' }}"></i>
                    @endfor
                </span>
                <p style="margin:0.5rem 0 0;color:var(--gray-700);font-size:0.92rem;">{{ $r->komentar }}</p>
            </div>
            @empty
            <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .product-detail-image { height: min(72vw, 300px) !important; }
        .product-quantity { width: 100%; }
        .product-add-button { min-height: 48px; }
    }
</style>
@endpush