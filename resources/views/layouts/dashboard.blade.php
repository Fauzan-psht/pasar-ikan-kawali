@php($profil = \App\Models\ProfilPasar::current())
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ $profil->nama }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-900: #0A2540;
            --primary-800: #0D3B66;
            --primary-700: #14538F;
            --primary-600: #1E6BB8;
            --primary-500: #2E86DE;
            --accent-teal: #14B8A6;
            --accent-cyan: #06B6D4;
            --accent-gold: #F59E0B;
            --gray-50: #F8FAFC;
            --gray-100: #F1F5F9;
            --gray-200: #E2E8F0;
            --gray-700: #334155;
            --gray-900: #0F172A;
        }
        body { font-family: 'Inter', sans-serif; background: var(--gray-50); color: var(--gray-900); overflow-x: hidden; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; letter-spacing: -0.02em; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 260px;
            background: linear-gradient(180deg, var(--primary-900) 0%, var(--primary-800) 60%, var(--primary-700) 100%);
            color: #fff;
            padding: 1.5rem 0;
            overflow-y: auto;
            z-index: 1040;
            transition: transform 0.3s ease;
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
            color: #fff;
            text-decoration: none;
        }
        .sidebar-brand .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--accent-teal), var(--accent-cyan));
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-brand .brand-logo {
            width: 40px; height: 40px;
            object-fit: contain;
            background: #fff;
            border-radius: 10px;
            padding: 3px;
            flex-shrink: 0;
        }
        .sidebar-brand .brand-text { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 1rem; line-height: 1.2; }
        .sidebar-brand .brand-text small { display: block; font-size: 0.72rem; color: var(--accent-teal); font-weight: 500; }

        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu .menu-label {
            font-size: 0.7rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
            padding: 1rem 1.5rem 0.5rem;
        }
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.7rem 1.5rem;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
            border-left: 3px solid transparent;
        }
        .sidebar-menu li a i { font-size: 1.05rem; width: 20px; text-align: center; }
        .sidebar-menu li a:hover {
            background: rgba(255,255,255,0.06);
            color: #fff;
        }
        .sidebar-menu li a.active {
            background: linear-gradient(90deg, rgba(20,184,166,0.18), transparent);
            color: #fff;
            border-left-color: var(--accent-teal);
        }
        .sidebar-menu li a.active i { color: var(--accent-teal); }

        /* ===== MAIN ===== */
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--gray-200);
            padding: 0.9rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 12px rgba(10,37,64,0.04);
        }
        .topbar h5 { margin: 0; font-size: 1.1rem; color: var(--primary-900); }
        .topbar .user-info { display: flex; align-items: center; gap: 0.8rem; }
        .topbar .avatar {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--primary-600), var(--primary-500));
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }
        .topbar .user-detail { line-height: 1.2; }
        .topbar .user-detail .name { font-weight: 600; font-size: 0.9rem; color: var(--primary-900); }
        .topbar .user-detail .role { font-size: 0.75rem; color: var(--gray-700); text-transform: capitalize; }

        .content-area { padding: 1.8rem; }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 1.4rem;
            border: 1px solid var(--gray-100);
            transition: all 0.3s ease;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(10,37,64,0.08);
        }
        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1rem;
        }
        .stat-card .stat-icon.blue { background: rgba(46,134,222,0.12); color: var(--primary-600); }
        .stat-card .stat-icon.teal { background: rgba(20,184,166,0.12); color: var(--accent-teal); }
        .stat-card .stat-icon.gold { background: rgba(245,158,11,0.12); color: var(--accent-gold); }
        .stat-card .stat-icon.red  { background: rgba(239,68,68,0.12); color: #EF4444; }
        .stat-card .stat-value {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary-900);
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: 0.85rem;
            color: var(--gray-700);
            margin-top: 0.4rem;
        }

        .panel {
            background: #fff;
            border-radius: 14px;
            border: 1px solid var(--gray-100);
            padding: 1.5rem;
        }
        .panel-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary-900);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-custom { font-size: 0.9rem; }
        .table-custom thead th {
            background: var(--gray-50);
            color: var(--gray-700);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 2px solid var(--gray-200);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-600), var(--primary-500));
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            transition: all 0.25s ease;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(46,134,222,0.35);
            color: #fff;
        }

        .badge-soft { padding: 0.35rem 0.7rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
        .badge-soft.success { background: rgba(20,184,166,0.12); color: #0d9488; }
        .badge-soft.warning { background: rgba(245,158,11,0.12); color: #b45309; }
        .badge-soft.danger  { background: rgba(239,68,68,0.12); color: #b91c1c; }
        .badge-soft.info    { background: rgba(46,134,222,0.12); color: var(--primary-600); }

        /* ===== LOADING OVERLAY ===== */
        #loading-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(4px);
            z-index: 99999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .spinner-custom {
            width: 4rem; height: 4rem;
            color: var(--primary-600);
            border-width: 0.3em;
        }
        .loading-text {
            margin-top: 1.5rem;
            font-weight: 800;
            color: var(--primary-800);
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: 0.1em;
            font-size: 1.1rem;
        }

        /* Mobile */
        .sidebar-toggle { display: none; }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-toggle { display: inline-flex; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- LOADING OVERLAY --}}
<div id="loading-overlay">
    <div class="spinner-border spinner-custom" role="status"></div>
    <div class="loading-text">MEMPROSES DATA...</div>
</div>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        @if($profil->logo)
            <img class="brand-logo" src="{{ asset('storage/'.$profil->logo) }}" alt="Logo {{ $profil->nama }}">
        @else
            <span class="brand-icon"><i class="bi bi-fish"></i></span>
        @endif
        <span class="brand-text">{{ $profil->nama }}</span>
    </a>

    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>

        @if(auth()->user()->isAdmin())
            <li class="menu-label">Master Data</li>
            <li><a href="{{ route('admin.profil.index') }}" class="{{ request()->routeIs('admin.profil*') ? 'active' : '' }}"><i class="bi bi-shop"></i> Profil Pasar</a></li>
            <li><a href="{{ route('admin.kategori.index') }}" class="{{ request()->routeIs('admin.kategori*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Kategori</a></li>
            <li><a href="{{ route('admin.produk.index') }}" class="{{ request()->routeIs('admin.produk*') ? 'active' : '' }}"><i class="bi bi-box-seam"></i> Produk</a></li>
            <li><a href="{{ route('admin.supplier.index') }}" class="{{ request()->routeIs('admin.supplier*') ? 'active' : '' }}"><i class="bi bi-truck"></i> Supplier</a></li>
            <li><a href="{{ route('admin.stok.index') }}" class="{{ request()->routeIs('admin.stok*') ? 'active' : '' }}"><i class="bi bi-boxes"></i> Stok Masuk</a></li>

            <li class="menu-label">Transaksi</li>
            <li><a href="{{ route('admin.pesanan.index') }}" class="{{ request()->routeIs('admin.pesanan*') ? 'active' : '' }}"><i class="bi bi-bag-check"></i> Pesanan Online</a></li>
            <li><a href="{{ route('admin.transaksi.index') }}" class="{{ request()->routeIs('admin.transaksi*') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Semua Transaksi</a></li>

            <li class="menu-label">Laporan & Pengaturan</li>
            <li><a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan*') ? 'active' : '' }}"><i class="bi bi-graph-up"></i> Laporan</a></li>
            <li><a href="{{ route('admin.ulasan.index') }}" class="{{ request()->routeIs('admin.ulasan*') ? 'active' : '' }}"><i class="bi bi-star"></i> Ulasan</a></li>
            <li><a href="{{ route('admin.user.index') }}" class="{{ request()->routeIs('admin.user*') ? 'active' : '' }}"><i class="bi bi-people"></i> User</a></li>
            <li><a href="{{ route('admin.qrcode') }}" class="{{ request()->routeIs('admin.qrcode') ? 'active' : '' }}"><i class="bi bi-qr-code"></i> QR Code Web</a></li>

            <li class="menu-label">Sistem & Data</li>
            <li><a href="{{ route('admin.pelanggan.index') }}" class="{{ request()->routeIs('admin.pelanggan*') ? 'active' : '' }}"><i class="bi bi-person-lines-fill"></i> Pelanggan</a></li>
            <li><a href="{{ route('admin.log.index') }}" class="{{ request()->routeIs('admin.log*') ? 'active' : '' }}"><i class="bi bi-clock-history"></i> Log Aktivitas</a></li>
        @endif

        @if(auth()->user()->isKasir())
            <li class="menu-label">Kasir</li>
            <li><a href="{{ route('kasir.pos') }}" class="{{ request()->routeIs('kasir.pos*') ? 'active' : '' }}"><i class="bi bi-cart-check"></i> POS / Kasir</a></li>
            <li><a href="{{ route('kasir.transaksi') }}" class="{{ request()->routeIs('kasir.transaksi*') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Transaksi Saya</a></li>
            <li><a href="{{ route('kasir.pelanggan') }}" class="{{ request()->routeIs('kasir.pelanggan*') ? 'active' : '' }}"><i class="bi bi-person-lines-fill"></i> Pelanggan</a></li>
        @endif
    </ul>
</aside>

{{-- MAIN --}}
<div class="main-wrapper">
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list"></i>
            </button>
            <h5>@yield('page_title', 'Dashboard')</h5>
        </div>
        <div class="user-info">
            <div class="user-detail d-none d-md-block text-end">
                <div class="name">{{ auth()->user()->name }}</div>
                <div class="role">{{ auth()->user()->role }}</div>
            </div>
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <form action="{{ route('logout') }}" method="POST" class="m-0" id="logoutForm">
                @csrf
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmLogout()" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. Konfirmasi Hapus (Form dengan atribut data-confirm)
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('form[data-confirm]');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = this.getAttribute('data-confirm') || 'Apakah Anda yakin ingin menghapus data ini?';
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        form.submit();
                    }
                });
            });
        });
    });

    // 2. Tampilkan Loading saat form POST biasa disubmit
    document.addEventListener('DOMContentLoaded', function() {
        const allForms = document.querySelectorAll('form:not([data-confirm])');
        allForms.forEach(form => {
            if(form.id !== 'logoutForm' && form.method.toUpperCase() === 'POST') {
                form.addEventListener('submit', function() {
                    showLoading();
                });
            }
        });
    });

    // 3. Fungsi Loading Overlay
    function showLoading() {
        document.getElementById('loading-overlay').style.display = 'flex';
    }

    // 4. Logout dengan SweetAlert
    function confirmLogout() {
        Swal.fire({
            title: 'Logout?',
            text: "Anda akan keluar dari sistem.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                document.getElementById('logoutForm').submit();
            }
        });
    }

    // 5. Auto Close Alert setelah 3 detik
    setTimeout(() => {
        let alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 3000);
</script>

@stack('scripts')
</body>
</html>