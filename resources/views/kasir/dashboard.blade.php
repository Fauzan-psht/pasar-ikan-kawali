@extends('layouts.dashboard')

@section('title', 'Dashboard Kasir')
@section('page_title', 'Dashboard Kasir')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-receipt-cutoff"></i></div>
            <div class="stat-value">{{ $jumlahTransaksi }}</div>
            <div class="stat-label">Transaksi Hari Ini</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon teal"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-value">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</div>
            <div class="stat-label">Total Penjualan Hari Ini</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-clock-history text-primary"></i> Transaksi Terakhir Saya</div>
            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead><tr><th>Nomor</th><th>Total</th><th>Waktu</th></tr></thead>
                    <tbody>
                        @forelse($transaksiTerbaru as $t)
                        <tr>
                            <td><code style="font-size:0.8rem;">{{ $t->nomor }}</code></td>
                            <td>Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                            <td style="font-size:0.82rem;">{{ $t->created_at->format('d/m H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">Belum ada transaksi hari ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-exclamation-triangle text-warning"></i> Stok Menipis</div>
            @forelse($produkStokRendah as $p)
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span style="font-size:0.9rem;">{{ $p->nama }}</span>
                <span class="badge-soft warning">{{ $p->stok }} {{ $p->satuan }}</span>
            </div>
            @empty
            <p class="text-muted text-center py-4">Semua stok aman.</p>
            @endforelse
        </div>

        <div class="panel mt-3">
            <div class="panel-title"><i class="bi bi-lightning-charge text-warning"></i> Shortcut</div>
            <a href="{{ route('kasir.pos') }}" class="btn btn-primary-custom w-100">
                <i class="bi bi-cart-check me-2"></i> Buka POS / Kasir
            </a>
        </div>
    </div>
</div>

@endsection