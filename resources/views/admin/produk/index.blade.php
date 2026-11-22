@extends('layouts.dashboard')

@section('title', 'Produk')
@section('page_title', 'Kelola Produk Ikan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="{{ route('admin.produk.create') }}" class="btn btn-primary-custom">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
        </a>
    </div>
    <form action="{{ route('admin.produk.index') }}" method="GET" class="d-flex gap-2">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama produk..." value="{{ request('q') }}">
        <button class="btn btn-sm btn-outline-secondary">Cari</button>
    </form>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Tipe / Satuan</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produks as $p)
                <tr>
                    <td>
                        @if($p->foto)
                            <img src="{{ asset('storage/'.$p->foto) }}" alt="Foto" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div style="width: 60px; height: 60px; background: #E2E8F0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748b;">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold">{{ $p->nama }}</div>
                        <small class="text-muted">{{ Str::limit($p->deskripsi, 40) }}</small>
                    </td>
                    <td>{{ $p->kategori->nama }}</td>
                    <td>
                        @if($p->tipe_produk === 'konsumsi')
                            <span class="badge-soft info">Konsumsi</span>
                        @else
                            <span class="badge-soft success">Benih</span>
                        @endif
                        <br><small>/ {{ $p->satuan }}</small>
                    </td>
                    <td class="fw-bold text-primary">Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</td>
                    <td>
                        @if($p->stok <= $p->stok_minimum)
                            <span class="text-danger fw-bold">{{ $p->stok }} {{ $p->satuan }}</span>
                        @else
                            {{ $p->stok }} {{ $p->satuan }}
                        @endif
                    </td>
                    <td>
                        @if($p->status)
                            <span class="badge-soft success">Aktif</span>
                        @else
                            <span class="badge-soft danger">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.produk.edit', $p->id) }}" class="btn btn-sm btn-warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus/menonaktifkan produk ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">Belum ada produk yang ditambahkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $produks->links() }}
    </div>
</div>
@endsection