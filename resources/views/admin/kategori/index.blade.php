@extends('layouts.dashboard')

@section('title', 'Kategori')
@section('page_title', 'Kelola Kategori Ikan')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-plus-circle text-primary"></i> Tambah Kategori</div>
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" required placeholder="Contoh: Ikan Lele">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tipe</label>
                    <select name="tipe" class="form-select" required>
                        <option value="konsumsi">Ikan Konsumsi (per kg)</option>
                        <option value="benih">Benih Ikan (per ekor)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi (opsional)</label>
                    <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Simpan</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-list-ul text-primary"></i> Daftar Kategori</div>
            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr><th>Nama</th><th>Tipe</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($kategoris as $k)
                        <tr>
                            <td>{{ $k->nama }}</td>
                            <td>
                                @if($k->tipe === 'konsumsi')
                                    <span class="badge-soft info">Konsumsi</span>
                                @else
                                    <span class="badge-soft success">Benih</span>
                                @endif
                            </td>
                            <td>
                                @if($k->status)
                                    <span class="badge-soft success">Aktif</span>
                                @else
                                    <span class="badge-soft danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <!-- Perhatikan type="button" -->
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $k->id }}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST" class="d-inline" data-confirm="Hapus kategori ini?">
                                    @csrf @method('DELETE')
                                    <!-- Perhatikan type="submit" -->
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal{{ $k->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.kategori.update', $k->id) }}" method="POST" class="modal-content">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Kategori</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="nama" class="form-control" value="{{ $k->nama }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tipe</label>
                                            <select name="tipe" class="form-select">
                                                <option value="konsumsi" {{ $k->tipe == 'konsumsi' ? 'selected' : '' }}>Konsumsi</option>
                                                <option value="benih" {{ $k->tipe == 'benih' ? 'selected' : '' }}>Benih</option>
                                            </select>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" value="1" {{ $k->status ? 'checked' : '' }}>
                                            <label class="form-check-label">Aktif</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary-custom">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection