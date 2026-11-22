@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-value">Rp {{ number_format($omzetHariIni, 0, ',', '.') }}</div>
            <div class="stat-label">Omzet Hari Ini</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon teal"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="stat-value">Rp {{ number_format($omzetBulanIni, 0, ',', '.') }}</div>
            <div class="stat-label">Omzet Bulan Ini</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon gold"><i class="bi bi-bag-check"></i></div>
            <div class="stat-value">{{ $pesananMasuk }}</div>
            <div class="stat-label">Pesanan Masuk</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-value">{{ $stokMenipis }}</div>
            <div class="stat-label">Produk Stok Menipis</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-value">{{ $totalProduk }}</div>
            <div class="stat-label">Produk Aktif</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-value">{{ $totalKategori }}</div>
            <div class="stat-label">Kategori Ikan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-value">{{ $totalUser }}</div>
            <div class="stat-label">User Aktif</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-receipt text-primary"></i> Transaksi Terbaru</div>
            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr><th>Nomor</th><th>Kasir</th><th>Total</th><th>Status</th><th>Waktu</th></tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTerbaru as $t)
                        <tr>
                            <td><code style="font-size:0.8rem;">{{ $t->nomor }}</code></td>
                            <td>{{ $t->user->name ?? '-' }}</td>
                            <td>Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                            <td>
                                @if($t->status === 'paid')<span class="badge-soft success">Paid</span>
                                @elseif($t->status === 'void')<span class="badge-soft danger">Void</span>
                                @else<span class="badge-soft info">{{ $t->status }}</span>@endif
                            </td>
                            <td style="font-size:0.82rem;">{{ $t->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-bag-check text-primary"></i> Pesanan Online Terbaru</div>
            @forelse($pesananTerbaru as $p)
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div>
                    <div style="font-weight:600;font-size:0.9rem;">{{ $p->guest_name ?? 'Guest' }}</div>
                    <small class="text-muted">{{ $p->nomor }}</small>
                </div>
                <div class="text-end">
                    <div style="font-weight:700;color:var(--primary-600);">Rp {{ number_format($p->total, 0, ',', '.') }}</div>
                    <span class="badge-soft info">{{ $p->status }}</span>
                </div>
            </div>
            @empty
            <p class="text-muted text-center py-4">Belum ada pesanan.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection