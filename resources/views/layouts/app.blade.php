<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pasar Ikan Kawali') — Pasar Ikan Air Tawar</title>
    <meta name="description" content="@yield('meta_description', 'Pasar Ikan Kawali — pusat ikan air tawar segar dan benih berkualitas di Kawali, Ciamis.')">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
            --gray-700: #334155;
            --gray-900: #0F172A;
        }

        * { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--gray-900);
            background: var(--gray-50);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* ====== NAVBAR ====== */
        .navbar-custom {
            background: rgba(10, 37, 64, 0.95);
            backdrop-filter: blur(12px);
            padding: 0.9rem 0;
            transition: all 0.3s ease;
            box-shadow: 0 2px 24px rgba(10, 37, 64, 0.15);
        }
        .navbar-custom.scrolled {
            padding: 0.5rem 0;
            background: rgba(10, 37, 64, 0.98);
        }
        .navbar-custom .navbar-brand {
            color: #fff;
            font-size: 1.35rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .navbar-custom .navbar-brand .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--accent-teal), var(--accent-cyan));
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #fff;
        }
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            font-size: 0.95rem;
            transition: color 0.2s ease;
            position: relative;
        }
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: #fff !important;
        }
        .navbar-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0; left: 1rem; right: 1rem;
            height: 2px;
            background: linear-gradient(90deg, var(--accent-teal), var(--accent-cyan));
            border-radius: 2px;
        }
        .btn-nav-login {
            background: linear-gradient(135deg, var(--accent-teal), var(--accent-cyan));
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.55rem 1.4rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-nav-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(20, 184, 166, 0.4);
            color: #fff;
        }

        /* ====== HERO ====== */
        .hero {
            background: linear-gradient(135deg, var(--primary-900) 0%, var(--primary-700) 45%, var(--primary-500) 100%);
            color: #fff;
            padding: 9rem 0 6rem;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 20% 30%, rgba(20,184,166,0.18) 0%, transparent 40%),
                              radial-gradient(circle at 80% 70%, rgba(6,182,212,0.15) 0%, transparent 45%);
            pointer-events: none;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 80px;
            background: var(--gray-50);
            clip-path: polygon(0 100%, 100% 100%, 100% 0, 0 80%);
        }
        .hero-content { position: relative; z-index: 2; }
        .hero h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.2rem;
        }
        .hero h1 .highlight {
            background: linear-gradient(135deg, var(--accent-teal), var(--accent-cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero p.lead {
            font-size: 1.15rem;
            color: rgba(255,255,255,0.85);
            max-width: 560px;
            margin-bottom: 2rem;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 0.4rem 1rem;
            border-radius: 100px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(8px);
        }
        .hero-badge .dot {
            width: 8px; height: 8px;
            background: var(--accent-teal);
            border-radius: 50%;
            box-shadow: 0 0 12px var(--accent-teal);
        }
        .btn-hero-primary {
            background: linear-gradient(135deg, var(--accent-teal), var(--accent-cyan));
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.85rem 2rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(20,184,166,0.45);
            color: #fff;
        }
        .btn-hero-outline {
            background: transparent;
            border: 2px solid rgba(255,255,255,0.4);
            color: #fff;
            font-weight: 600;
            padding: 0.85rem 2rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .btn-hero-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: #fff;
            color: #fff;
        }

        /* ====== SECTION TITLE ====== */
        .section-title { text-align: center; margin-bottom: 3rem; }
        .section-title .eyebrow {
            display: inline-block;
            color: var(--primary-600);
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 0.7rem;
        }
        .section-title h2 {
            font-size: clamp(1.7rem, 3.5vw, 2.5rem);
            color: var(--primary-900);
            margin-bottom: 0.8rem;
        }
        .section-title p {
            color: var(--gray-700);
            max-width: 620px;
            margin: 0 auto;
        }

        /* ====== CARDS ====== */
        .card-modern {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--gray-100);
            overflow: hidden;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }
        .card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(10,37,64,0.12);
            border-color: rgba(46,134,222,0.2);
        }
        .card-modern .card-img {
            height: 200px;
            background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 3rem;
            position: relative;
            overflow: hidden;
        }
        .card-modern .card-img img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .card-modern .badge-tipe {
            position: absolute;
            top: 12px; left: 12px;
            background: rgba(255,255,255,0.95);
            color: var(--primary-800);
            font-weight: 600;
            font-size: 0.72rem;
            padding: 0.35rem 0.75rem;
            border-radius: 100px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .card-modern .badge-tipe.benih {
            background: linear-gradient(135deg, var(--accent-teal), var(--accent-cyan));
            color: #fff;
        }
        .card-modern .card-body { padding: 1.4rem; }
        .card-modern .card-title {
            font-size: 1.1rem;
            color: var(--primary-900);
            margin-bottom: 0.4rem;
        }
        .card-modern .price {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-600);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .card-modern .price small {
            font-size: 0.8rem;
            color: var(--gray-700);
            font-weight: 500;
        }
        .card-modern .btn-detail {
            width: 100%;
            margin-top: 1rem;
            background: var(--gray-100);
            border: none;
            color: var(--primary-800);
            font-weight: 600;
            padding: 0.65rem;
            border-radius: 8px;
            transition: all 0.25s ease;
        }
        .card-modern .btn-detail:hover {
            background: linear-gradient(135deg, var(--primary-600), var(--primary-500));
            color: #fff;
        }

        /* ====== FEATURE ICONS ====== */
        .feature-icon {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, rgba(46,134,222,0.1), rgba(20,184,166,0.1));
            color: var(--primary-600);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 1rem;
        }

        /* ====== FOOTER ====== */
        .footer {
            background: linear-gradient(135deg, var(--primary-900), var(--primary-800));
            color: rgba(255,255,255,0.75);
            padding: 4rem 0 0;
        }
        .footer h5 {
            color: #fff;
            font-size: 1rem;
            margin-bottom: 1.2rem;
        }
        .footer a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.2s ease;
            display: block;
            padding: 0.3rem 0;
        }
        .footer a:hover { color: var(--accent-teal); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 3rem;
            padding: 1.5rem 0;
            font-size: 0.88rem;
        }

        /* ====== WHATSAPP FLOAT ====== */
        .wa-float {
            position: fixed;
            bottom: 24px; right: 24px;
            width: 56px; height: 56px;
            background: #25D366;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            box-shadow: 0 8px 24px rgba(37,211,102,0.4);
            z-index: 999;
            transition: transform 0.3s ease;
        }
        .wa-float:hover {
            transform: scale(1.1) rotate(5deg);
            color: #fff;
        }

        /* ====== PAGE HEADER (non-hero) ====== */
        .page-header {
            background: linear-gradient(135deg, var(--primary-900), var(--primary-700));
            color: #fff;
            padding: 7rem 0 3rem;
            position: relative;
        }
        .page-header::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 50px;
            background: var(--gray-50);
            clip-path: polygon(0 100%, 100% 100%, 100% 20%, 0 80%);
        }
        .page-header h1 {
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            position: relative;
            z-index: 1;
        }
        .page-header .breadcrumb-custom {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }
        .page-header .breadcrumb-custom a { color: var(--accent-cyan); text-decoration: none; }

        /* ====== MAPS ====== */
        .maps-wrapper {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(10,37,64,0.1);
            border: 1px solid var(--gray-100);
        }
        .maps-wrapper iframe { display: block; width: 100%; border: 0; }

        /* ====== RATING STARS ====== */
        .stars { color: var(--accent-gold); }
    </style>
    @stack('styles')
</head>
<body>

{{-- NAVBAR --}}
@include('public.partials.navbar')

{{-- CONTENT --}}
@yield('content')

{{-- FOOTER --}}
@include('public.partials.footer')

{{-- WHATSAPP FLOAT --}}
@php $profil = \App\Models\ProfilPasar::current(); @endphp
@if($profil->whatsapp)
<a href="https://wa.me/{{ preg_replace('/^0/', '62', $profil->whatsapp) }}" target="_blank" class="wa-float" title="Hubungi via WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        const nav = document.querySelector('.navbar-custom');
        if (nav) nav.classList.toggle('scrolled', window.scrollY > 50);
    });
</script>
@stack('scripts')
</body>
</html>