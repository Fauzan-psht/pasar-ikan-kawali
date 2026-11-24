@php
    $cartCount = collect(session('cart', []))->sum('qty');
@endphp

<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('public.home') }}">
            @if($profil->logo)
                <img class="brand-logo" src="{{ asset('storage/'.$profil->logo) }}" alt="Logo {{ $profil->nama }}">
            @else
                <span class="brand-icon"><i class="bi bi-fish"></i></span>
            @endif
            <span>{{ $profil->nama }}</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Buka menu">
            <i class="bi bi-list" style="font-size:1.6rem;"></i>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}" href="{{ route('public.home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.katalog*') ? 'active' : '' }}" href="{{ route('public.katalog') }}">Katalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.profil') ? 'active' : '' }}" href="{{ route('public.profil') }}">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.kontak') ? 'active' : '' }}" href="{{ route('public.kontak') }}">Kontak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.track') ? 'active' : '' }}" href="{{ route('public.track') }}">Lacak Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.cart*') ? 'active' : '' }}" href="{{ route('public.cart') }}">
                        <i class="bi bi-cart3"></i> Keranjang
                        @if($cartCount > 0)
                            <span class="badge bg-danger rounded-pill">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
        <button type="button" class="theme-toggle ms-auto ms-lg-3" id="publicThemeToggle" aria-label="Gunakan tema gelap" aria-pressed="false">
            <i class="bi bi-moon-stars" aria-hidden="true"></i>
        </button>
    </div>
</nav>

<nav class="mobile-bottom-nav" aria-label="Navigasi utama">
    <a class="{{ request()->routeIs('public.home') ? 'active' : '' }}" href="{{ route('public.home') }}">
        <i class="bi bi-house"></i><span>Beranda</span>
    </a>
    <a class="{{ request()->routeIs('public.katalog*') ? 'active' : '' }}" href="{{ route('public.katalog') }}">
        <i class="bi bi-grid"></i><span>Katalog</span>
    </a>
    <a class="{{ request()->routeIs('public.cart*') ? 'active' : '' }}" href="{{ route('public.cart') }}">
        <span class="mobile-cart-icon"><i class="bi bi-bag"></i>@if($cartCount > 0)<span>{{ $cartCount }}</span>@endif</span>
        <span>Keranjang</span>
    </a>
    <a class="{{ request()->routeIs('public.track') ? 'active' : '' }}" href="{{ route('public.track') }}">
        <i class="bi bi-receipt"></i><span>Lacak</span>
    </a>
</nav>
