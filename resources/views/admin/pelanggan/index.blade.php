@extends('layouts.dashboard')

@section('title', 'Pelanggan')
@section('page_title', 'Data Semua Pelanggan')

@section('content')
<div class="panel">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th>Total Belanja (POS)</th>
                    <th>Total Order (Online)</th>
                    <th>Terdaftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggans as $p)
                <tr>
                    <td class="fw-bold">{{ $p->nama }}</td>
                    <td>{{ $p->telepon ?? '-' }}<br><small class="text-muted">{{ $p->email ?? '' }}</small></td>
                    <td>{{ Str::limit($p->alamat, 40) ?? '-' }}</td>
                    <td><span class="badge-soft info">{{ $p->transaksis_count }} Kali</span></td>
                    <td><span class="badge-soft success">{{ $p->pesanans_count }} Kali</span></td>
                    <td>{{ $p->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pelanggan terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $pelanggans->links() }}</div>
</div>
@endsection