@extends('public.layouts.app')

@section('title', 'Profil Pasar')

@section('content')
<div class="page-header">
    <div class="container">
        <nav class="breadcrumb-custom mb-2"><a href="{{ route('public.home') }}">Beranda</a> / Profil</nav>
        <h1>Tentang Pasar Ikan Kawali</h1>
    </div>
</div>

<section class="py-5">
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card-modern p-4">
                    <h3 style="color:var(--primary-900);margin-bottom:1rem;"><i class="bi bi-book me-2" style="color:var(--primary-600);"></i> Sejarah</h3>
                    <p style="color:var(--gray-700);line-height:1.8;">{{ $profil->sejarah ?: 'Belum diisi.' }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-modern p-4">
                    <h3 style="color:var(--primary-900);margin-bottom:1rem;"><i class="bi bi-bullseye me-2" style="color:var(--primary-600);"></i> Visi</h3>
                    <p style="color:var(--gray-700);line-height:1.8;">{{ $profil->visi ?: 'Belum diisi.' }}</p>
                    <h3 style="color:var(--primary-900);margin:1.5rem 0 1rem;"><i class="bi bi-list-check me-2" style="color:var(--primary-600);"></i> Misi</h3>
                    <p style="color:var(--gray-700);line-height:1.8;">{{ $profil->misi ?: 'Belum diisi.' }}</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-md-4">
                <div class="card-modern p-4 text-center">
                    <div class="feature-icon"><i class="bi bi-geo-alt"></i></div>
                    <h5>Alamat</h5>
                    <p style="color:var(--gray-700);font-size:0.92rem;">{{ $profil->alamat ?: '-' }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-modern p-4 text-center">
                    <div class="feature-icon"><i class="bi bi-clock"></i></div>
                    <h5>Jam Buka</h5>
                    <p style="color:var(--gray-700);font-size:0.92rem;">{{ $profil->jam_buka ?: '-' }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-modern p-4 text-center">
                    <div class="feature-icon"><i class="bi bi-telephone"></i></div>
                    <h5>Kontak</h5>
                    <p style="color:var(--gray-700);font-size:0.92rem;">{{ $profil->telepon ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection