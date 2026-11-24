<div class="row g-3">
    <div class="col-lg-8">
        <div class="panel mb-3">
            <div class="panel-title"><i class="bi bi-box-seam text-primary"></i> Informasi Produk</div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $produk->nama ?? '') }}" required placeholder="Contoh: Lele Segar / Benih Nila 5-7cm">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ (old('kategori_id', $produk->kategori_id ?? '') == $k->id) ? 'selected' : '' }}>
                                [{{ ucfirst($k->tipe) }}] {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan keunggulan produk...">{{ old('deskripsi', $produk->deskripsi ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-title"><i class="bi bi-tags text-primary"></i> Harga & Stok</div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Tipe Produk <span class="text-danger">*</span></label>
                    <select name="tipe_produk" class="form-select" required>
                        <option value="konsumsi" {{ (old('tipe_produk', $produk->tipe_produk ?? '') == 'konsumsi') ? 'selected' : '' }}>Ikan Konsumsi</option>
                        <option value="benih" {{ (old('tipe_produk', $produk->tipe_produk ?? '') == 'benih') ? 'selected' : '' }}>Benih Ikan</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                    <select name="satuan" class="form-select" required>
                        <option value="kg" {{ (old('satuan', $produk->satuan ?? '') == 'kg') ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="ekor" {{ (old('satuan', $produk->satuan ?? '') == 'ekor') ? 'selected' : '' }}>Ekor</option>
                    </select>
                    <small class="text-muted">Bisa pilih kg atau ekor untuk benih.</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Harga Jual (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="harga_jual" class="form-control" value="{{ old('harga_jual', $produk->harga_jual ?? '') }}" required min="0">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Harga Modal (Rp)</label>
                    <input type="number" name="harga_modal" class="form-control" value="{{ old('harga_modal', $produk->harga_modal ?? 0) }}" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Stok Saat Ini <span class="text-danger">*</span></label>
                    <input type="number" step="0.1" name="stok" class="form-control" value="{{ old('stok', $produk->stok ?? 0) }}" required min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Batas Stok Minimum <span class="text-danger">*</span></label>
                    <input type="number" step="0.1" name="stok_minimum" class="form-control" value="{{ old('stok_minimum', $produk->stok_minimum ?? 0) }}" required min="0">
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel mb-3">
            <div class="panel-title"><i class="bi bi-image text-primary"></i> Foto Produk</div>
            @if(isset($produk) && $produk->foto)
                <img src="{{ asset('storage/'.$produk->foto) }}" class="img-thumbnail mb-2 w-100" style="object-fit:cover; max-height:200px;">
            @endif
            <input type="file" name="foto" class="form-control" accept="image/*">
            <small class="text-muted d-block mt-2">Format: JPG, PNG, WEBP. Maks: 2MB.</small>
        </div>

        <div class="panel mb-3">
            <div class="panel-title"><i class="bi bi-gear text-primary"></i> Pengaturan</div>
            <div class="form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" {{ (old('status', $produk->status ?? 1) == 1) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="statusSwitch">Tampilkan di Katalog (Aktif)</label>
            </div>
        </div>
    </div>
</div>