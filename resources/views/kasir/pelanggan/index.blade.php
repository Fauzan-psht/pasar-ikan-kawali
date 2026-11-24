@extends('layouts.dashboard')

@section('title', 'Pelanggan')
@section('page_title', 'Kelola Pelanggan')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-person-plus text-primary"></i> Tambah Pelanggan Baru</div>
            <form action="{{ route('kasir.pelanggan.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor Telepon / WA</label>
                    <input type="text" name="telepon" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat (Opsional)</label>
                    <textarea name="alamat" class="form-control" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Simpan Pelanggan</button>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-people text-primary"></i> Daftar Pelanggan</div>
            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr><th>Nama</th><th>Telepon</th><th>Alamat</th><th>Terdaftar</th><th class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($pelanggans as $p)
                        <tr>
                            <td class="fw-bold">{{ $p->nama }}</td>
                            <td>{{ $p->telepon ?? '-' }}</td>
                            <td>{{ Str::limit($p->alamat, 30) ?? '-' }}</td>
                            <td style="font-size: 0.85rem;">{{ $p->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $p->id }}"><i class="bi bi-pencil"></i> Edit</button>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('kasir.pelanggan.update', $p->id) }}" method="POST" class="modal-content">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Pelanggan: {{ $p->nama }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="nama" class="form-control" value="{{ $p->nama }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Telepon</label>
                                            <input type="text" name="telepon" class="form-control" value="{{ $p->telepon }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Alamat</label>
                                            <textarea name="alamat" class="form-control" rows="2">{{ $p->alamat }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary-custom">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pelanggan terdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $pelanggans->links() }}</div>
        </div>
    </div>
</div>
@endsection