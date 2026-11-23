@extends('layouts.dashboard')

@section('title', 'Laporan')
@section('page_title', 'Laporan Penjualan')

@section('content')
<div class="panel mb-3">
    <form action="{{ route('admin.laporan.index') }}" method="GET" class="row align-items-end g-2">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Dari Tanggal</label>
            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
        </div>
        <div class="col-md-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom w-100">Filter Laporan</button>
                <a href="{{ route('admin.laporan.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success w-100">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                </a>
            </div>
        </div>
    </form>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon teal"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-value">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
            <div class="stat-label">Total Omzet (Paid)</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-receipt"></i></div>
            <div class="stat-value">{{ number_format($totalTransaksi, 0, ',', '.') }} Kali</div>
            <div class="stat-label">Jumlah Transaksi</div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-title"><i class="bi bi-trophy text-warning"></i> 10 Produk Terlaris (Berdasarkan Qty)</div>
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr><th>Peringkat</th><th>Nama Produk</th><th>Total Terjual</th><th>Total Pendapatan</th></tr>
            </thead>
            <tbody>
                @forelse($produkTerlaris as $index => $item)
                <tr>
                    <td><strong>#{{ $index + 1 }}</strong></td>
                    <td>{{ $item->nama_snapshot }}</td>
                    <td class="fw-bold">{{ $item->total_qty }}</td>
                    <td class="text-primary fw-bold">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada penjualan pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection