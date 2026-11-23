@php $profil = \App\Models\ProfilPasar::current(); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $profil->nama) — {{ $profil->nama }}</title>
    <meta name="description" content="@yield('meta_description', $profil->deskripsi)">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
        body { font-family: 'Inter', sans-serif; color: var(--gray-900); background: var(--gray-50); overflow-x: hidden; }
        h1, h2, h3, h4, h5, h6, .navbar-brand { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; letter-spacing: -0.02em; }

        .navbar-custom { background: rgba(10, 37, 64, 0.95); backdrop-filter: blur(12px); padding: 0.9rem 0; transition: all 0.3s ease; box-shadow: 0 2px 24px rgba(10, 37, 64, 0.15); }
        .navbar-custom.scrolled { padding: 0.5rem 0; background: rgba(10, 37, 64, 0.98); }
        .navbar-custom .navbar-brand { color: #fff; font-size: 1.35rem; display: flex; align-items: center; gap: 0.6rem; }
        .navbar-custom .navbar-brand .brand-icon { width: 38px; height: 38px; background: linear-gradient(135deg, var(--accent-teal), var(--accent-cyan)); border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #fff; }
        .navbar-custom .navbar-brand .brand-logo { width: 38px; height: 38px; flex: 0 0 38px; object-fit: contain; border-radius: 10px; background: #fff; padding: 2px; }
        .navbar-custom .nav-link { color: rgba(255,255,255,0.85) !important; font-weight: 500; padding: 0.5rem 1rem !important; font-size: 0.95rem; transition: color 0.2s ease; position: relative; }
        .navbar-custom .nav-link:hover, .navbar-custom .nav-link.active { color: #fff !important; }
        .navbar-custom .nav-link.active::after { content: ''; position: absolute; bottom: 0; left: 1rem; right: 1rem; height: 2px; background: linear-gradient(90deg, var(--accent-teal), var(--accent-cyan)); border-radius: 2px; }
        .btn-nav-login { background: linear-gradient(135deg, var(--accent-teal), var(--accent-cyan)); border: none; color: #fff; font-weight: 600; padding: 0.55rem 1.4rem; border-radius: 8px; transition: all 0.3s ease; }
        .btn-nav-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(20, 184, 166, 0.4); color: #fff; }

        .hero { background: linear-gradient(135deg, var(--primary-900) 0%, var(--primary-700) 45%, var(--primary-500) 100%); color: #fff; padding: 9rem 0 6rem; position: relative; overflow: hidden; }
        .hero.has-banner { background-image: linear-gradient(135deg, rgba(10,37,64,0.9), rgba(20,83,143,0.78) 55%, rgba(46,134,222,0.68)), var(--hero-banner); background-size: cover; background-position: center; }
        .hero::before { content: ''; position: absolute; inset: 0; background-image: radial-gradient(circle at 20% 30%, rgba(20,184,166,0.18) 0%, transparent 40%), radial-gradient(circle at 80% 70%, rgba(6,182,212,0.15) 0%, transparent 45%); pointer-events: none; }
        .hero::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 80px; background: var(--gray-50); clip-path: polygon(0 100%, 100% 100%, 100% 0, 0 80%); }
        .hero-content { position: relative; z-index: 2; }
        .hero h1 { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 800; line-height: 1.1; margin-bottom: 1.2rem; }
        .hero h1 .highlight { background: linear-gradient(135deg, var(--accent-teal), var(--accent-cyan)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero p.lead { font-size: 1.15rem; color: rgba(255,255,255,0.85); max-width: 560px; margin-bottom: 2rem; }
        .hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); padding: 0.4rem 1rem; border-radius: 100px; font-size: 0.85rem; font-weight: 500; margin-bottom: 1.5rem; backdrop-filter: blur(8px); }
        .hero-badge .dot { width: 8px; height: 8px; background: var(--accent-teal); border-radius: 50%; box-shadow: 0 0 12px var(--accent-teal); }
        .btn-hero-primary { background: linear-gradient(135deg, var(--accent-teal), var(--accent-cyan)); border: none; color: #fff; font-weight: 600; padding: 0.85rem 2rem; border-radius: 10px; transition: all 0.3s ease; }
        .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(20,184,166,0.45); color: #fff; }
        .btn-hero-outline { background: transparent; border: 2px solid rgba(255,255,255,0.4); color: #fff; font-weight: 600; padding: 0.85rem 2rem; border-radius: 10px; transition: all 0.3s ease; }
        .btn-hero-outline:hover { background: rgba(255,255,255,0.1); border-color: #fff; color: #fff; }

        .section-title { text-align: center; margin-bottom: 3rem; }
        .section-title .eyebrow { display: inline-block; color: var(--primary-600); font-weight: 700; font-size: 0.85rem; letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 0.7rem; }
        .section-title h2 { font-size: clamp(1.7rem, 3.5vw, 2.5rem); color: var(--primary-900); margin-bottom: 0.8rem; }
        .section-title p { color: var(--gray-700); max-width: 620px; margin: 0 auto; }

        .card-modern { background: #fff; border-radius: 16px; border: 1px solid var(--gray-100); overflow: hidden; transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); height: 100%; }
        .card-modern:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(10,37,64,0.12); border-color: rgba(46,134,222,0.2); }
        .card-modern .card-img { height: 200px; background: linear-gradient(135deg, var(--primary-700), var(--primary-500)); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 3rem; position: relative; overflow: hidden; }
        .card-modern .card-img img { width: 100%; height: 100%; object-fit: cover; }
        .card-modern .badge-tipe { position: absolute; top: 12px; left: 12px; background: rgba(255,255,255,0.95); color: var(--primary-800); font-weight: 600; font-size: 0.72rem; padding: 0.35rem 0.75rem; border-radius: 100px; letter-spacing: 0.03em; text-transform: uppercase; }
        .card-modern .badge-tipe.benih { background: linear-gradient(135deg, var(--accent-teal), var(--accent-cyan)); color: #fff; }
        .card-modern .card-body { padding: 1.4rem; }
        .card-modern .card-title { font-size: 1.1rem; color: var(--primary-900); margin-bottom: 0.4rem; }
        .card-modern .price { font-size: 1.25rem; font-weight: 800; color: var(--primary-600); font-family: 'Plus Jakarta Sans', sans-serif; }
        .card-modern .price small { font-size: 0.8rem; color: var(--gray-700); font-weight: 500; }
        .card-modern .btn-detail { width: 100%; margin-top: 1rem; background: var(--gray-100); border: none; color: var(--primary-800); font-weight: 600; padding: 0.65rem; border-radius: 8px; transition: all 0.25s ease; }
        .card-modern .btn-detail:hover { background: linear-gradient(135deg, var(--primary-600), var(--primary-500)); color: #fff; }

        .feature-icon { width: 60px; height: 60px; background: linear-gradient(135deg, rgba(46,134,222,0.1), rgba(20,184,166,0.1)); color: var(--primary-600); border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 1rem; }

        .footer { background: linear-gradient(135deg, var(--primary-900), var(--primary-800)); color: rgba(255,255,255,0.75); padding: 4rem 0 0; }
        .footer h5 { color: #fff; font-size: 1rem; margin-bottom: 1.2rem; }
        .footer a { color: rgba(255,255,255,0.7); text-decoration: none; transition: color 0.2s ease; display: block; padding: 0.3rem 0; }
        .footer a:hover { color: var(--accent-teal); }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); margin-top: 3rem; padding: 1.5rem 0; font-size: 0.88rem; }

        .wa-float { position: fixed; bottom: 24px; right: 24px; width: 56px; height: 56px; background: #25D366; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; box-shadow: 0 8px 24px rgba(37,211,102,0.4); z-index: 999; transition: transform 0.3s ease; }
        .wa-float:hover { transform: scale(1.1) rotate(5deg); color: #fff; }

        .page-header { background: linear-gradient(135deg, var(--primary-900), var(--primary-700)); color: #fff; padding: 7rem 0 3rem; position: relative; }
        .page-header::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 50px; background: var(--gray-50); clip-path: polygon(0 100%, 100% 100%, 100% 20%, 0 80%); }
        .page-header h1 { font-size: clamp(1.8rem, 4vw, 2.8rem); position: relative; z-index: 1; }
        .page-header .breadcrumb-custom { color: rgba(255,255,255,0.7); font-size: 0.9rem; position: relative; z-index: 1; }
        .page-header .breadcrumb-custom a { color: var(--accent-cyan); text-decoration: none; }

        .maps-wrapper { border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(10,37,64,0.1); border: 1px solid var(--gray-100); }
        .maps-wrapper iframe { display: block; width: 100%; min-height: 350px; border: 0; }
        .stars { color: var(--accent-gold); }

        .mobile-bottom-nav { display: none; }
        .btn-nav-login { display: inline-flex; align-items: center; justify-content: center; text-decoration: none; }

        @media (max-width: 991.98px) {
            .navbar-custom .navbar-collapse {
                margin-top: 0.75rem;
                padding: 0.5rem 1rem 1rem;
                border-radius: 0.75rem;
                background: var(--primary-900);
            }
            .navbar-custom .navbar-nav { align-items: stretch !important; }
            .navbar-custom .nav-link { padding: 0.75rem 0.25rem !important; }
            .navbar-custom .nav-link.active::after { left: 0.25rem; right: auto; width: 2rem; }
            .btn-nav-login { margin-top: 0.5rem; min-height: 44px; }
        }

        @media (max-width: 767.98px) {
            body { padding-bottom: calc(4.5rem + env(safe-area-inset-bottom)); }
            .navbar-custom { padding: 0.6rem 0; }
            .navbar-custom .navbar-brand { gap: 0.45rem; font-size: 1.05rem; }
            .navbar-custom .navbar-brand .brand-icon { width: 34px; height: 34px; font-size: 1.05rem; }
            .navbar-custom .navbar-brand .brand-logo { width: 34px; height: 34px; flex-basis: 34px; }
            .hero { padding: 6.5rem 0 3.5rem; }
            .hero::after { height: 42px; }
            .hero h1 { font-size: clamp(2rem, 9vw, 2.8rem); }
            .hero p.lead { font-size: 1rem; }
            .hero .btn-hero-primary, .hero .btn-hero-outline { flex: 1 1 100%; text-align: center; }
            .hero .d-flex.gap-4 { gap: 1rem !important; justify-content: space-between; }
            .hero .d-flex.gap-4 strong { font-size: 1.25rem !important; }
            .section-title { margin-bottom: 2rem; }
            .section-title h2 { font-size: 1.65rem; }
            .page-header { padding: 6rem 0 2.25rem; }
            .page-header::after { height: 30px; }
            .page-header h1 { font-size: 1.8rem; overflow-wrap: anywhere; }
            .card-modern { border-radius: 12px; }
            .card-modern .card-img { height: 150px; }
            .card-modern .card-body { padding: 1rem; }
            .public-product-grid { --bs-gutter-x: 0.75rem; --bs-gutter-y: 0.75rem; }
            .public-product-grid .card-modern .card-img { height: 132px; }
            .public-product-grid .card-modern .card-title { font-size: 0.98rem; }
            .public-product-grid .card-modern .price { font-size: 1rem; }
            .public-product-grid .card-modern .btn-detail { min-height: 44px; font-size: 0.88rem; }
            .public-pagination { max-width: 100%; overflow-x: auto; }
            .maps-wrapper iframe { min-height: 280px; }
            .footer { padding-top: 2.5rem; }
            .footer-bottom { margin-top: 1.5rem; padding-bottom: 1rem; }
            .wa-float { right: 16px; bottom: calc(5rem + env(safe-area-inset-bottom)); width: 50px; height: 50px; }
            .mobile-bottom-nav {
                position: fixed;
                z-index: 1035;
                right: 0;
                bottom: 0;
                left: 0;
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                padding: 0.45rem 0.25rem calc(0.45rem + env(safe-area-inset-bottom));
                background: rgba(255, 255, 255, 0.97);
                border-top: 1px solid var(--gray-100);
                box-shadow: 0 -8px 24px rgba(10, 37, 64, 0.08);
                backdrop-filter: blur(12px);
            }
            .mobile-bottom-nav a {
                display: flex;
                min-height: 48px;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 0.15rem;
                color: #64748b;
                text-decoration: none;
                font-size: 0.68rem;
                font-weight: 600;
            }
            .mobile-bottom-nav a > i, .mobile-cart-icon { font-size: 1.15rem; line-height: 1; }
            .mobile-bottom-nav a.active { color: var(--primary-700); }
            .mobile-cart-icon { position: relative; }
            .mobile-cart-icon span {
                position: absolute;
                top: -0.5rem;
                right: -0.7rem;
                min-width: 1rem;
                padding: 0.1rem 0.25rem;
                border-radius: 999px;
                background: #ef4444;
                color: #fff;
                font-size: 0.6rem;
                text-align: center;
            }
        }

        @media (max-width: 359.98px) {
            .public-product-grid > [class*="col-"] { width: 100%; }
        }
    </style>
    @stack('styles')
</head>
<body>

@include('public.partials.navbar')

@yield('content')

@include('public.partials.footer')

@if($profil->whatsapp)
<a href="https://wa.me/{{ preg_replace('/^0/', '62', $profil->whatsapp) }}" target="_blank" rel="noopener noreferrer" class="wa-float" title="Hubungi via WhatsApp" aria-label="Hubungi via WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.addEventListener('scroll', () => {
        const nav = document.querySelector('.navbar-custom');
        if (nav) nav.classList.toggle('scrolled', window.scrollY > 50);
    });
</script>
@stack('scripts')
</body>
</html>