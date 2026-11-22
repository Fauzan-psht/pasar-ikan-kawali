<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('public.home') }}">
            <span class="brand-icon"><i class="bi bi-fish"></i></span>
            <span>Pasar Ikan <span style="color: var(--accent-teal);">Kawali</span></span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" style="color:#fff;">
            <i class="bi bi-list" style="font-size:1.6rem;"></i>
        </button>
                        <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.track') ? 'active' : '' }}" href="{{ route('public.track') }}">Lacak Pesanan</a>
                </li>
        <div class="collapse navbar-collapse" id="navMain">
                            <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.cart') ? 'active' : '' }}" href="{{ route('public.cart') }}">
                        <i class="bi bi-cart3"></i> Keranjang 
                        @if(session('cart'))
                            <span class="badge bg-danger rounded-pill">{{ count(session('cart')) }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}" href="{{ route('public.home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.profil') ? 'active' : '' }}" href="{{ route('public.profil') }}">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.katalog*') ? 'active' : '' }}" href="{{ route('public.katalog') }}">Katalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.kontak') ? 'active' : '' }}" href="{{ route('public.kontak') }}">Kontak</a>
                </li>
                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                    <a href="{{ route('login') }}" class="btn-nav-login">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>