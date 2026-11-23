@if($profil->maps_embed_url)
    <iframe
        src="{{ $profil->maps_embed_url }}"
        title="Lokasi {{ $profil->nama }}"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        allowfullscreen>
    </iframe>
@else
    <div style="padding:3rem;text-align:center;color:#64748b;">Peta belum diatur. Silakan atur di panel admin.</div>
@endif
