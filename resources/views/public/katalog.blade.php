@extends('public.layouts.app')

@section('title', 'Katalog Ikan')

@section('content')
<div class="page-header">
    <div class="container">
        <nav class="breadcrumb-custom mb-2"><a href="{{ route('public.home') }}">Beranda</a> / Katalog</nav>
        <h1>Katalog Ikan Air Tawar</h1>
    </div>
</div>

<section class="py-5">
    <div class="container py-4">
        {{-- Filter --}}
        <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
            <a href="{{ route('public.katalog') }}" class="btn {{ request('tipe') ? 'btn-outline-secondary' : 'btn-hero-primary' }} btn-sm px-3">
                Semua
            </a>
            <a href="{{ route('public.katalog', ['tipe'=>'konsumsi']) }}" class="btn {{ request('tipe')==='konsumsi' ? 'btn-hero-primary' : 'btn-outline-secondary' }} btn-sm px-3">
                Ikan Konsumsi (per kg)
            </a>
            <a href="{{ route('public.katalog', ['tipe'=>'benih']) }}" class="btn {{ request('tipe')==='benih' ? 'btn-hero-primary' : 'btn-outline-secondary' }} btn-sm px-3">
                Benih Ikan (per ekor)
            </a>
        </div>

        <div class="row g-4">
            @forelse($produks as $p)
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
                        <p style="color:var(--gray-700);font-size:0.88rem;margin-top:0.5rem;">Stok: {{ $p->stok }} {{ $p->satuan }}</p>
                        <a href="{{ route('public.produk.detail', $p->slug) }}" class="btn btn-detail">
                            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-5">Belum ada produk.</div>
            @endforelse
        </div>

        <div class="mt-4">{{ $produks->links() }}</div>
    </div>
</section>
@endsection
