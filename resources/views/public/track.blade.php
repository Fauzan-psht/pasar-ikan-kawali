@extends('public.layouts.app')

@section('title', 'Lacak Pesanan')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Lacak Pesanan</h1>
    </div>
</div>

<section class="py-5">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card-modern p-4 mb-4">
                    <h5 class="fw-bold mb-3 text-center">Masukkan Nomor Pesanan Anda</h5>
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('public.track') }}" method="GET">
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="nomor" class="form-control" placeholder="Contoh: ORD-2023..." value="{{ request('nomor') }}" required>
                            <button class="btn btn-hero-primary" type="submit"><i class="bi bi-search"></i> Cek</button>
                        </div>
                    </form>
                </div>

                @if($pesanan)
                <div class="card-modern p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                        <div>
                            <h5 class="fw-bold mb-1">{{ $pesanan->nomor }}</h5>
                            <small class="text-muted">{{ $pesanan->created_at->format('d M Y, H:i') }}</small>
                        </div>
                        <div>
                            @php
                                $badge = match($pesanan->status) {
                                    'pending_payment' => 'warning',
                                    'confirmed', 'processing' => 'info',
                                    'ready' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }} fs-6">{{ strtoupper(str_replace('_', ' ', $pesanan->status)) }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Nama Pembeli:</strong> {{ $pesanan->guest_name }}<br>
                        <strong>Metode:</strong> {{ $pesanan->metode_pengambilan === 'kirim' ? 'Kirim ke Alamat' : 'Ambil di Pasar' }}<br>
                        @if($pesanan->alamat)
                        <strong>Alamat:</strong> {{ $pesanan->alamat }}
                        @endif
                    </div>

                    <h6 class="fw-bold mt-4 mb-2">Rincian Item:</h6>
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($pesanan->details as $d)
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                            <div>
                                {{ $d->nama_snapshot }} <br>
                                <small class="text-muted">{{ $d->qty }} {{ $d->satuan_snapshot }} x Rp {{ number_format($d->harga_snapshot, 0, ',', '.') }}</small>
                            </div>
                            <span class="fw-bold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                        </li>
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-between fs-5 fw-bold border-top pt-3">
                        <span>Total:</span>
                        <span class="text-primary">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
