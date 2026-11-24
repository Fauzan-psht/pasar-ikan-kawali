@extends('layouts.dashboard')

@section('title', 'Supplier')
@section('page_title', 'Kelola Supplier')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-truck text-primary"></i> Tambah Supplier</div>
            <form action="{{ route('admin.supplier.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Supplier</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Telepon</label>
                    <input type="text" name="telepon" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Simpan Supplier</button>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-list-ul text-primary"></i> Daftar Supplier</div>
            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr><th>Nama</th><th>Telepon</th><th>Alamat</th><th>Status</th><th class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $s)
                        <tr>
                            <td class="fw-bold">{{ $s->nama }}</td>
                            <td>{{ $s->telepon ?? '-' }}</td>
                            <td>{{ Str::limit($s->alamat, 30) ?? '-' }}</td>
                            <td>
                                @if($s->status) <span class="badge-soft success">Aktif</span>
                                @else <span class="badge-soft danger">Nonaktif</span> @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $s->id }}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('admin.supplier.destroy', $s->id) }}" method="POST" class="d-inline" data-confirm="Hapus supplier?" data-confirm-button="Ya, Hapus!">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal{{ $s->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.supplier.update', $s->id) }}" method="POST" class="modal-content">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Supplier</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="nama" class="form-control" value="{{ $s->nama }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Telepon</label>
                                            <input type="text" name="telepon" class="form-control" value="{{ $s->telepon }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Alamat</label>
                                            <textarea name="alamat" class="form-control" rows="2">{{ $s->alamat }}</textarea>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" value="1" {{ $s->status ? 'checked' : '' }}>
                                            <label class="form-check-label">Aktif</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary-custom">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada supplier.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $suppliers->links() }}</div>
        </div>
    </div>
</div>
@endsection