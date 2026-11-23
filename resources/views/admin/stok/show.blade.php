@extends('layouts.dashboard')

@section('title', 'Detail Stok Masuk')
@section('page_title', 'Detail Penerimaan #' . $stok->nomor)

@section('content')
<div class="panel mb-3">
    <div class="row">
        <div class="col-md-4"><strong>No. Penerimaan:</strong><br>{{ $stok->nomor }}</div>
        <div class="col-md-4"><strong>Tanggal:</strong><br>{{ $stok->tanggal->format('d M Y') }}</div>
        <div class="col-md-4"><strong>Supplier:</strong><br>{{ $stok->supplier->nama ?? '-' }}</div>
        <div class="col-md-4 mt-3"><strong>Admin:</strong><br>{{ $stok->user->name ?? '-' }}</div>
        <div class="col-md-8 mt-3"><strong>Catatan:</strong><br>{{ $stok->catatan ?? '-' }}</div>
    </div>
</div>

<div class="panel">
    <div class="panel-title"><i class="bi bi-list-ul text-primary"></i> Rincian Barang</div>
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr><th>Produk</th><th>Qty</th><th>Harga Modal</th><th class="text-end">Subtotal</th></tr>
            </thead>
            <tbody>
                @foreach($stok->details as $d)
                <tr>
                    <td>{{ $d->produk->nama ?? 'Produk Dihapus' }}</td>
                    <td>{{ $d->qty }} {{ $d->produk->satuan ?? '' }}</td>
                    <td>Rp {{ number_format($d->harga_modal, 0, ',', '.') }}</td>
                    <td class="text-end fw-bold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <a href="{{ route('admin.stok.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection