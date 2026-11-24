@extends('layouts.dashboard')

@section('title', 'Ulasan')
@section('page_title', 'Moderasi Ulasan Pembeli')

@section('content')
<div class="panel">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Pembeli</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ratings as $r)
                <tr>
                    <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
                    <td class="fw-bold">{{ $r->nama }}</td>
                    <td class="text-warning fw-bold">
                        {{ $r->rating }} <i class="bi bi-star-fill"></i>
                    </td>
                    <td>
                        <span class="text-muted" style="font-size: 0.9rem;">
                            {{ $r->komentar ?? '- Tidak ada komentar -' }}
                        </span>
                    </td>
                    <td>
                        @if($r->status === 'approved') 
                            <span class="badge-soft success">Disetujui</span>
                        @elseif($r->status === 'rejected') 
                            <span class="badge-soft danger">Ditolak</span>
                        @else 
                            <span class="badge-soft warning">Pending</span> 
                        @endif
                    </td>
                    <td class="text-center">
                        <!-- Form Ubah Status -->
                        <form action="{{ route('admin.ulasan.status', $r->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <select name="status" class="form-select form-select-sm d-inline-block w-auto border-secondary" onchange="this.form.submit()">
                                <option value="pending" {{ $r->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $r->status == 'approved' ? 'selected' : '' }}>Setujui</option>
                                <option value="rejected" {{ $r->status == 'rejected' ? 'selected' : '' }}>Tolak</option>
                            </select>
                        </form>

                        <!-- Form Hapus -->
                        <form action="{{ route('admin.ulasan.destroy', $r->id) }}" method="POST" class="d-inline" data-confirm="Hapus ulasan dari {{ $r->nama }} ini?">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger ms-1" title="Hapus Permanen">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-chat-square-text fs-1 d-block mb-2"></i>
                        Belum ada ulasan dari pembeli.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $ratings->links() }}</div>
</div>
@endsection