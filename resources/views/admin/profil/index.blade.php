@extends('layouts.dashboard')

@section('title', 'Profil Pasar')
@section('page_title', 'Pengaturan Profil Pasar')

@section('content')
<form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="panel mb-3">
                <div class="panel-title"><i class="bi bi-info-circle text-primary"></i> Informasi Dasar</div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Pasar <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $profil->nama) }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $profil->deskripsi) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Sejarah</label>
                        <textarea name="sejarah" class="form-control" rows="4">{{ old('sejarah', $profil->sejarah) }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Visi</label>
                        <textarea name="visi" class="form-control" rows="4">{{ old('visi', $profil->visi) }}</textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Misi</label>
                    <textarea name="misi" class="form-control" rows="3">{{ old('misi', $profil->misi) }}</textarea>
                </div>
            </div>

            <div class="panel mb-3">
                <div class="panel-title"><i class="bi bi-geo-alt text-primary"></i> Kontak & Lokasi</div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $profil->alamat) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Jam Buka</label>
                        <input type="text" name="jam_buka" class="form-control" value="{{ old('jam_buka', $profil->jam_buka) }}" placeholder="Contoh: Setiap hari, 06:00 - 18:00">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $profil->telepon) }}" inputmode="numeric" maxlength="13" pattern="[0-9]{1,13}" data-digits-only autocomplete="tel">
                        @error('telepon')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $profil->whatsapp) }}" inputmode="numeric" maxlength="13" pattern="[0-9]{1,13}" data-digits-only autocomplete="tel">
                        @error('whatsapp')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $profil->email) }}">
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-title"><i class="bi bi-map text-primary"></i> Google Maps</div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">URL Google Maps (Link)</label>
                    <input type="url" name="maps_url" class="form-control" value="{{ old('maps_url', $profil->maps_url) }}" placeholder="https://maps.app.goo.gl/...">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Lokasi atau Embed Code Google Maps</label>
                    <textarea name="maps_embed" class="form-control" rows="3" placeholder="Masukkan nama/alamat lokasi, Plus Code, atau kode iframe Google Maps">{{ old('maps_embed', $profil->maps_embed) }}</textarea>
                    <small class="text-muted">Bisa isi alamat/Plus Code, atau salin kode iframe dari Google Maps.</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel mb-3">
                <div class="panel-title"><i class="bi bi-image text-primary"></i> Logo & Banner</div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Logo Pasar</label><br>
                    @if($profil->logo)
                        <img src="{{ asset('storage/'.$profil->logo) }}" class="img-thumbnail mb-2" style="max-height:100px;">
                    @endif
                    <input type="file" name="logo" class="form-control form-control-sm" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Banner Utama</label><br>
                    @if($profil->banner)
                        <img src="{{ asset('storage/'.$profil->banner) }}" class="img-thumbnail mb-2" style="max-height:120px; width:100%; object-fit:cover;">
                    @endif
                    <input type="file" name="banner" class="form-control form-control-sm" accept="image/*">
                </div>
            </div>

            <div class="panel mb-3">
                <div class="panel-title"><i class="bi bi-share text-primary"></i> Sosial Media</div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Facebook</label>
                    <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $profil->facebook) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Instagram</label>
                    <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $profil->instagram) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">YouTube</label>
                    <input type="url" name="youtube" class="form-control" value="{{ old('youtube', $profil->youtube) }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 py-2">
                <i class="bi bi-save me-2"></i> Simpan Perubahan
            </button>
        </div>
    </div>
</form>
@endsection