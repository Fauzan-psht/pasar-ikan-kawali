@extends('layouts.dashboard')

@section('title', 'Pesanan Online')
@section('page_title', 'Pesanan Online Masuk')

@section('content')
<div class="panel">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pembeli</th>
                    <th>Tgl Masuk</th>
                    <th>Total</th>
                    <th>Pengambilan</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $p)
                <tr>
                    <td><code>{{ $p->nomor }}</code></td>
                    <td>
                        <div class="fw-bold">{{ $p->guest_name }}</div>
                        <small class="text-muted">{{ $p->guest_phone }}</small>
                    </td>
                    <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                    <td class="fw-bold text-primary">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    <td>
                        @if($p->metode_pengambilan === 'kirim')
                            <span class="badge-soft warning"><i class="bi bi-truck"></i> Kirim</span>
                        @else
                            <span class="badge-soft info"><i class="bi bi-shop"></i> Ambil</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $badge = match($p->status) {
                                'pending_payment' => 'warning',
                                'confirmed', 'processing' => 'info',
                                'ready' => 'primary',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge-soft {{ $badge }}">{{ strtoupper(str_replace('_', ' ', $p->status)) }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.pesanan.show', $p->id) }}" class="btn btn-sm btn-primary-custom">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada pesanan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $pesanans->links() }}</div>
</div>
@endsection