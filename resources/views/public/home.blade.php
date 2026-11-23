@extends('public.layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- HERO --}}
<section class="hero {{ $profil->banner ? 'has-banner' : '' }}" @if($profil->banner) style="--hero-banner: url('{{ asset('storage/'.$profil->banner) }}')" @endif>
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-badge">
                    <span class="dot"></span>
                    {{ $profil->nama }} — Pasar Ikan Air Tawar Terpercaya
                </div>
                <h1>
                    Ikan Air Tawar <span class="highlight">Segar & Berkualitas</span> untuk Keluarga Anda
                </h1>
                <p class="lead">
                    {{ $profil->deskripsi }}
                </p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="{{ route('public.katalog') }}" class="btn btn-hero-primary">
                        <i class="bi bi-shop me-2"></i> Lihat Katalog
                    </a>
                    <a href="{{ route('public.kontak') }}" class="btn btn-hero-outline">
                        <i class="bi bi-geo-alt me-2"></i> Lokasi Kami
                    </a>
                </div>
                <div class="d-flex gap-4 mt-5">
                    <div><strong style="font-size:1.6rem;">{{ $totalProduk }}</strong><br><small style="color:rgba(255,255,255,0.7);">Produk Tersedia</small></div>
                    <div><strong style="font-size:1.6rem;">{{ $totalKategori }}</strong><br><small style="color:rgba(255,255,255,0.7);">Jenis Ikan</small></div>
                    <div><strong style="font-size:1.6rem;">100%</strong><br><small style="color:rgba(255,255,255,0.7);">Ikan Air Tawar</small></div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div style="background:rgba(255,255,255,0.08);border-radius:24px;padding:2rem;backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,0.15);">
                    <div class="text-center mb-4" style="font-size:5rem;">
                        <i class="bi bi-fish" style="color:var(--accent-teal);"></i>
                    </div>
                    <h4 class="text-white text-center" style="font-size:1.1rem;">Pesan Online, Bayar Mudah</h4>
                    <p class="text-center" style="font-size:0.9rem;color:rgba(255,255,255,0.75);">
                        Pilih ikan, checkout tanpa akun, bayar tunai atau QRIS/transfer via payment gateway.
                    </p>
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <span class="badge" style="background:rgba(255,255,255,0.15);font-size:0.8rem;padding:0.5rem 0.9rem;"><i class="bi bi-cash me-1"></i> Tunai</span>
                        <span class="badge" style="background:rgba(255,255,255,0.15);font-size:0.8rem;padding:0.5rem 0.9rem;"><i class="bi bi-qr-code me-1"></i> QRIS</span>
                        <span class="badge" style="background:rgba(255,255,255,0.15);font-size:0.8rem;padding:0.5rem 0.9rem;"><i class="bi bi-bank me-1"></i> Transfer</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- LAYANAN --}}
<section class="py-5">
    <div class="container py-5">
        <div class="section-title">
            <span class="eyebrow">Layanan Kami</span>
            <h2>Kenapa Belanja di Pasar Ikan Kawali?</h2>
            <p>Kami hadir dengan komitmen kualitas, kesegaran, dan kemudahan untuk Anda.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="feature-icon"><i class="bi bi-droplet-half"></i></div>
                    <h4 style="font-size:1.15rem;">Selalu Segar</h4>
                    <p style="color:var(--gray-700);font-size:0.95rem;">Ikan air tawar langsung dari kolam, dipasok setiap hari.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="feature-icon"><i class="bi bi-tags"></i></div>
                    <h4 style="font-size:1.15rem;">Harga Bersaing</h4>
                    <p style="color:var(--gray-700);font-size:0.95rem;">Harga transparan per kg untuk konsumsi, per ekor untuk benih.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="feature-icon"><i class="bi bi-truck"></i></div>
                    <h4 style="font-size:1.15rem;">Ambil atau Kirim</h4>
                    <p style="color:var(--gray-700);font-size:0.95rem;">Ambil di pasar atau minta dikirim ke alamat Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- KATALOG HIGHLIGHT --}}
<section class="py-5" style="background:var(--gray-100);">
    <div class="container py-5">
        <div class="section-title">
            <span class="eyebrow">Katalog Ikan</span>
            <h2>Pilihan Ikan Air Tawar</h2>
            <p>Ikan konsumsi per kg dan benih per ekor, siap dipesan online.</p>
        </div>
        <div class="row g-4 public-product-grid">
            @forelse($produkUnggulan as $p)
            <div class="col-md-6 col-lg-4">
                <div class="card-modern">
                    <div class="card-img">
                        <span class="badge-tipe {{ $p->tipe_produk === 'benih' ? 'benih' : '' }}">
                            {{ $p->tipe_produk === 'benih' ? 'Benih' : 'Konsumsi' }}
                        </span>
                        @if($p->foto)
                            <img src="{{ asset('storage/'.$p->foto) }}" alt="{{ $p->nama }}">
                        @else
                            <i class="bi bi-fish"></i>
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $p->nama }}</h5>
                        <div class="price">
                            {{ $p->harga_format }} <small>/ {{ $p->satuan }}</small>
                        </div>
                        <a href="{{ route('public.produk.detail', $p->slug) }}" class="btn btn-detail">
                            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted">Belum ada produk.</div>
            @endforelse
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('public.katalog') }}" class="btn btn-hero-primary">
                Lihat Semua Produk <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

{{-- LOKASI / MAPS --}}
<section class="py-5">
    <div class="container py-5">
        <div class="section-title">
            <span class="eyebrow">Lokasi Kami</span>
            <h2>Kunjungi {{ $profil->nama }}</h2>
            <p>{{ $profil->alamat }}</p>
        </div>
        <div class="maps-wrapper">
            @include('public.partials.map')
        </div>
        @if($profil->maps_url)
        <div class="text-center mt-4">
            <a href="{{ $profil->maps_url }}" target="_blank" class="btn btn-hero-primary">
                <i class="bi bi-geo-alt-fill me-2"></i> Buka di Google Maps
            </a>
        </div>
        @endif
    </div>
</section>

@endsection