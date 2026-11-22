@extends('layouts.dashboard')

@section('title', 'Transaksi Saya')
@section('page_title', 'Riwayat Transaksi Saya')

@section('content')
<div class="panel">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>Nomor Transaksi</th>
                    <th>Waktu</th>
                    <th>Total Belanja</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                <tr>
                    <td><code style="font-size: 0.85rem;">{{ $t->nomor }}</code></td>
                    <td>{{ $t->created_at->format('d M Y, H:i') }}</td>
                    <td class="fw-bold text-primary">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                    <td>
                        @if($t->status === 'paid')<span class="badge-soft success">Sukses</span>
                        @elseif($t->status === 'void')<span class="badge-soft danger">Dibatalkan</span>
                        @else<span class="badge-soft warning">{{ $t->status }}</span>@endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('kasir.pos.struk', $t->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Cetak Ulang Struk">
                            <i class="bi bi-printer"></i> Struk
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $transaksis->links() }}</div>
</div>
@endsection