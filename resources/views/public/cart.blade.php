@extends('public.layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="page-header">
    <div class="container">
        <nav class="breadcrumb-custom mb-2"><a href="{{ route('public.home') }}">Beranda</a> / Keranjang</nav>
        <h1>Keranjang Belanja</h1>
    </div>
</div>

<section class="py-5">
    <div class="container py-4">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(count($cart) > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="panel p-4 bg-white rounded shadow-sm border">
                    <table class="table align-middle cart-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th width="120">Jumlah</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($cart as $id => $details)
                                @php $total += $details['harga'] * $details['qty'] @endphp
                                <tr>
                                    <td data-label="Produk">
                                        <div class="d-flex align-items-center gap-3">
                                            @if($details['foto'])
                                                <img src="{{ asset('storage/'.$details['foto']) }}" width="60" height="60" class="rounded object-fit-cover" alt="{{ $details['nama'] }}">
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $details['nama'] }}</div>
                                                <small class="text-muted">{{ $details['satuan'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Harga">Rp {{ number_format($details['harga'], 0, ',', '.') }}</td>
                                    <td data-label="Jumlah">
                                        <input type="number" class="form-control form-control-sm update-cart" data-id="{{ $id }}" value="{{ $details['qty'] }}" min="0.1" step="0.1" aria-label="Jumlah {{ $details['nama'] }}">
                                    </td>
                                    <td data-label="Subtotal" class="fw-bold">Rp {{ number_format($details['harga'] * $details['qty'], 0, ',', '.') }}</td>
                                    <td data-label="Hapus">
                                        <button class="btn btn-sm btn-danger remove-from-cart" data-id="{{ $id }}" aria-label="Hapus {{ $details['nama'] }}"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="panel p-4 bg-white rounded shadow-sm border">
                    <h5 class="fw-bold mb-3">Ringkasan Belanja</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Harga</span>
                        <span class="fw-bold fs-5 text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('public.checkout') }}" class="btn btn-hero-primary w-100 py-2">
                        Lanjut ke Checkout <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <a href="{{ route('public.katalog') }}" class="btn btn-outline-secondary w-100 py-2 mt-2">Lanjut Belanja</a>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h3 class="mt-3">Keranjang Anda Kosong</h3>
            <p class="text-muted mb-4">Yuk, cari ikan segar untuk kebutuhan Anda!</p>
            <a href="{{ route('public.katalog') }}" class="btn btn-hero-primary">Lihat Katalog</a>
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .cart-table thead { display: none; }
        .cart-table, .cart-table tbody, .cart-table tr, .cart-table td { display: block; width: 100%; }
        .cart-table tr {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.25rem 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .cart-table td { padding: 0.35rem 0; border: 0; text-align: left !important; }
        .cart-table td::before {
            content: attr(data-label);
            display: block;
            margin-bottom: 0.2rem;
            color: #64748b;
            font-size: 0.72rem;
            font-weight: 600;
        }
        .cart-table td:first-child { grid-column: 1 / -1; }
        .cart-table td:first-child::before { display: none; }
        .cart-table td:last-child { display: flex; justify-content: flex-end; align-items: center; }
        .cart-table .update-cart { max-width: 6rem; min-height: 42px; }
        .cart-table .remove-from-cart { min-width: 42px; min-height: 42px; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.update-cart').forEach(input => {
        input.addEventListener('change', function() {
            let id = this.getAttribute('data-id');
            let qty = this.value;
            fetch('{{ route("public.cart.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id, qty: qty })
            }).then(() => window.location.reload());
        });
    });

    document.querySelectorAll('.remove-from-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            if(confirm('Hapus produk ini dari keranjang?')) {
                fetch('{{ route("public.cart.remove") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: id })
                }).then(() => window.location.reload());
            }
        });
    });
</script>
@endpush