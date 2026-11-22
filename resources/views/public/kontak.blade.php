@extends('public.layouts.app')

@section('title', 'Kontak & Lokasi')

@section('content')
<div class="page-header">
    <div class="container">
        <nav class="breadcrumb-custom mb-2"><a href="{{ route('public.home') }}">Beranda</a> / Kontak</nav>
        <h1>Kontak & Lokasi</h1>
    </div>
</div>

<section class="py-5">
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-modern p-4">
                    <h4 style="color:var(--primary-900);margin-bottom:1rem;">Informasi Kontak</h4>
                    @if($profil->alamat)
                    <p><i class="bi bi-geo-alt-fill me-2" style="color:var(--primary-600);"></i>{{ $profil->alamat }}</p>
                    @endif
                    @if($profil->telepon)
                    <p><i class="bi bi-telephone-fill me-2" style="color:var(--primary-600);"></i>{{ $profil->telepon }}</p>
                    @endif
                    @if($profil->whatsapp)
                    <p><i class="bi bi-whatsapp me-2" style="color:var(--accent-teal);"></i>{{ $profil->whatsapp }}</p>
                    @endif
                    @if($profil->email)
                    <p><i class="bi bi-envelope-fill me-2" style="color:var(--primary-600);"></i>{{ $profil->email }}</p>
                    @endif
                    @if($profil->jam_buka)
                    <p><i class="bi bi-clock-fill me-2" style="color:var(--primary-600);"></i>{{ $profil->jam_buka }}</p>
                    @endif
                    @if($profil->maps_url)
                    <a href="{{ $profil->maps_url }}" target="_blank" class="btn btn-hero-primary w-100 mt-3">
                        <i class="bi bi-geo-alt-fill me-2"></i> Buka Google Maps
                    </a>
                    @endif
                </div>
            </div>
            <div class="col-lg-7">
                <div class="maps-wrapper">
                    {!! $profil->maps_embed ?: '<div style="padding:4rem;text-align:center;color:#64748b;">Peta belum diatur. Silakan atur di panel admin.</div>' !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection