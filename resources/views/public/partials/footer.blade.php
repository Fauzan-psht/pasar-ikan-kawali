@php $profil = \App\Models\ProfilPasar::current(); @endphp
<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="d-flex align-items-center gap-2">
                    <span class="brand-icon" style="width:34px;height:34px;font-size:1rem;background:linear-gradient(135deg,var(--accent-teal),var(--accent-cyan));border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#fff;">
                        <i class="bi bi-fish"></i>
                    </span>
                    {{ $profil->nama }}
                </h5>
                <p style="font-size:0.92rem;line-height:1.7;">
                    {{ Str::limit($profil->deskripsi, 160) }}
                </p>
                <div class="d-flex gap-3 mt-3">
                    @if($profil->facebook)<a href="{{ $profil->facebook }}" target="_blank" style="font-size:1.2rem;"><i class="bi bi-facebook"></i></a>@endif
                    @if($profil->instagram)<a href="{{ $profil->instagram }}" target="_blank" style="font-size:1.2rem;"><i class="bi bi-instagram"></i></a>@endif
                    @if($profil->youtube)<a href="{{ $profil->youtube }}" target="_blank" style="font-size:1.2rem;"><i class="bi bi-youtube"></i></a>@endif
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <h5>Menu</h5>
                <a href="{{ route('public.home') }}">Beranda</a>
                <a href="{{ route('public.profil') }}">Profil</a>
                <a href="{{ route('public.katalog') }}">Katalog</a>
                <a href="{{ route('public.kontak') }}">Kontak</a>
            </div>
            <div class="col-lg-3 col-md-4">
                <h5>Kontak</h5>
                @if($profil->alamat)<p style="font-size:0.9rem;"><i class="bi bi-geo-alt me-2"></i>{{ $profil->alamat }}</p>@endif
                @if($profil->telepon)<p style="font-size:0.9rem;"><i class="bi bi-telephone me-2"></i>{{ $profil->telepon }}</p>@endif
                @if($profil->email)<p style="font-size:0.9rem;"><i class="bi bi-envelope me-2"></i>{{ $profil->email }}</p>@endif
                @if($profil->jam_buka)<p style="font-size:0.9rem;"><i class="bi bi-clock me-2"></i>{{ $profil->jam_buka }}</p>@endif
            </div>
            <div class="col-lg-3 col-md-4">
                <h5>Lokasi</h5>
                <p style="font-size:0.9rem;">Scan QR Code atau kunjungi pasar kami.</p>
                @if($profil->maps_url)
                <a href="{{ $profil->maps_url }}" target="_blank" class="btn btn-sm" style="background:linear-gradient(135deg,var(--accent-teal),var(--accent-cyan));color:#fff;border:none;padding:0.5rem 1rem;border-radius:8px;margin-top:0.5rem;">
                    <i class="bi bi-geo-alt-fill me-1"></i> Buka Google Maps
                </a>
                @endif
            </div>
        </div>
        <div class="footer-bottom text-center">
            &copy; {{ date('Y') }} {{ $profil->nama }}. All rights reserved. | Dibuat dengan <i class="bi bi-heart-fill" style="color:var(--accent-teal);"></i> untuk masyarakat Kawali.
        </div>
    </div>
</footer>