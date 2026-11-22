@extends('layouts.dashboard')

@section('title', 'Detail Pesanan')
@section('page_title', 'Detail Pesanan #' . $pesanan->nomor)

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="panel mb-3">
            <div class="panel-title"><i class="bi bi-basket text-primary"></i> Item Pesanan</div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr><th>Produk</th><th>Harga</th><th>Qty</th><th class="text-end">Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan->details as $d)
                        <tr>
                            <td>{{ $d->nama_snapshot }}</td>
                            <td>Rp {{ number_format($d->harga_snapshot, 0, ',', '.') }}</td>
                            <td>{{ $d->qty }} {{ $d->satuan_snapshot }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">TOTAL:</th>
                            <th class="text-end text-primary fs-5">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="panel">
            <div class="panel-title"><i class="bi bi-person text-primary"></i> Informasi Pembeli</div>
            <div class="row">
                <div class="col-md-6 mb-2"><strong>Nama:</strong><br>{{ $pesanan->guest_name }}</div>
                <div class="col-md-6 mb-2"><strong>WhatsApp:</strong><br><a href="https://wa.me/{{ preg_replace('/^0/', '62', $pesanan->guest_phone) }}" target="_blank" class="text-success"><i class="bi bi-whatsapp"></i> {{ $pesanan->guest_phone }}</a></div>
                <div class="col-md-6 mb-2"><strong>Metode:</strong><br>{{ $pesanan->metode_pengambilan === 'kirim' ? 'Kirim ke Alamat' : 'Ambil di Pasar' }}</div>
                <div class="col-md-6 mb-2"><strong>Catatan:</strong><br>{{ $pesanan->catatan ?: '-' }}</div>
                @if($pesanan->alamat)
                <div class="col-12 mt-2"><strong>Alamat Pengiriman:</strong><br>{{ $pesanan->alamat }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-title"><i class="bi bi-gear text-primary"></i> Ubah Status</div>
            <form action="{{ route('admin.pesanan.status', $pesanan->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status Pesanan</label>
                    <select name="status" class="form-select">
                        <option value="pending_payment" {{ $pesanan->status == 'pending_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="confirmed" {{ $pesanan->status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="processing" {{ $pesanan->status == 'processing' ? 'selected' : '' }}>Sedang Disiapkan</option>
                        <option value="ready" {{ $pesanan->status == 'ready' ? 'selected' : '' }}>Siap Diambil / Dikirim</option>
                        <option value="completed" {{ $pesanan->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ $pesanan->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Simpan Status</button>
            </form>
            <hr>
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-outline-secondary w-100">Kembali ke Daftar</a>
        </div>
    </div>
</div>
@endsection