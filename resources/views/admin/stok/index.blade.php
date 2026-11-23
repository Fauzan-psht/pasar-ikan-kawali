@extends('layouts.dashboard')

@section('title', 'Stok Masuk')
@section('page_title', 'Riwayat Stok Masuk')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.stok.create') }}" class="btn btn-primary-custom"><i class="bi bi-plus-lg"></i> Catat Stok Masuk</a>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr><th>No. Penerimaan</th><th>Tanggal</th><th>Supplier</th><th>Admin</th><th>Status</th><th class="text-center">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($stokMasuks as $sm)
                <tr>
                    <td><code>{{ $sm->nomor }}</code></td>
                    <td>{{ $sm->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $sm->supplier->nama ?? '-' }}</td>
                    <td>{{ $sm->user->name ?? '-' }}</td>
                    <td><span class="badge-soft success">{{ strtoupper($sm->status) }}</span></td>
                    <td class="text-center">
                        <a href="{{ route('admin.stok.show', $sm->id) }}" class="btn btn-sm btn-primary-custom"><i class="bi bi-eye"></i> Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data stok masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $stokMasuks->links() }}</div>
</div>
@endsection