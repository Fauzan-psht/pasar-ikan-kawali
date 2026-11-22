@extends('layouts.dashboard')

@section('title', 'Manajemen User')
@section('page_title', 'Manajemen User (Admin & Kasir)')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-person-plus text-primary"></i> Tambah User Baru</div>
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email (Untuk Login)</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="kasir">Kasir</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Simpan User</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-people text-primary"></i> Daftar User</div>
            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                @if($u->role === 'admin') <span class="badge-soft danger">Admin</span>
                                @else <span class="badge-soft info">Kasir</span> @endif
                            </td>
                            <td>
                                @if($u->is_active) <span class="badge-soft success">Aktif</span>
                                @else <span class="badge-soft secondary">Nonaktif</span> @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUser{{ $u->id }}"><i class="bi bi-pencil"></i></button>
                                @if($u->id !== auth()->id())
                                <form action="{{ route('admin.user.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editUser{{ $u->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.user.update', $u->id) }}" method="POST" class="modal-content">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User: {{ $u->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select name="role" class="form-select">
                                                <option value="kasir" {{ $u->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                                <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password Baru (Opsional)</label>
                                            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diganti">
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $u->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label">Akun Aktif</label>
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
            <div class="mt-3">{{ $users->links() }}</div>
        </div>
    </div>
</div>
@endsection