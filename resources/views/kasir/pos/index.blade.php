@extends('layouts.dashboard')

@section('title', 'POS Kasir')
@section('page_title', 'Point of Sale (POS)')

@push('styles')
<style>
    .pos-container { display: flex; gap: 1rem; height: calc(100vh - 120px); }
    .pos-products { flex: 1; overflow-y: auto; }
    .pos-cart { width: 400px; background: #fff; border-radius: 14px; border: 1px solid var(--gray-200); display: flex; flex-direction: column; }
    
    .product-card { cursor: pointer; transition: 0.2s; }
    .product-card:hover { transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-color: var(--primary-500) !important; }
    
    .cart-items { flex: 1; overflow-y: auto; padding: 1rem; }
    .cart-item { display: flex; justify-content: space-between; align-items: center; padding: 0.7rem 0; border-bottom: 1px dashed var(--gray-200); }
    
    .cart-summary { padding: 1rem; background: var(--gray-50); border-top: 1px solid var(--gray-200); border-radius: 0 0 14px 14px; }
    .total-row { display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 800; color: var(--primary-900); margin-bottom: 1rem; }
    
    .qty-input { width: 60px; text-align: center; }
</style>
@endpush

@section('content')
<div class="pos-container">
    <!-- KIRI: Daftar Produk -->
    <div class="pos-products">
        <div class="mb-3">
            <input type="text" id="searchProduct" class="form-control form-control-lg" placeholder="Cari nama produk...">
        </div>
        <div class="row g-2" id="productList">
            @foreach($produks as $p)
            <div class="col-md-4 col-xl-3 product-item" data-name="{{ strtolower($p->nama) }}">
                <div class="card h-100 product-card border" onclick="addToCart({{ $p->id }}, '{{ addslashes($p->nama) }}', {{ $p->harga_jual }}, '{{ $p->satuan }}', {{ $p->stok }})">
                    @if($p->foto)
                        <img src="{{ asset('storage/'.$p->foto) }}" class="card-img-top" style="height: 100px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100px;"><i class="bi bi-fish fs-1 text-muted"></i></div>
                    @endif
                    <div class="card-body p-2">
                        <div class="fw-bold text-truncate" style="font-size: 0.9rem;">{{ $p->nama }}</div>
                        <div class="text-primary fw-bold" style="font-size: 0.85rem;">Rp {{ number_format($p->harga_jual, 0, ',', '.') }} <span class="text-muted fw-normal">/{{ $p->satuan }}</span></div>
                        <div class="text-muted" style="font-size: 0.75rem;">Stok: {{ $p->stok }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- KANAN: Keranjang -->
    <div class="pos-cart">
        <div class="p-3 border-bottom bg-white" style="border-radius: 14px 14px 0 0;">
            <h5 class="m-0 fw-bold mb-3"><i class="bi bi-cart3 me-2"></i> Keranjang</h5>
            
            <!-- Dropdown Pelanggan -->
            <select id="selectPelanggan" class="form-select form-select-sm js-select2">
                <option value="">-- Pelanggan Umum (Guest) --</option>
                @foreach($pelanggans as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->telepon ?? 'Tanpa No HP' }})</option>
                @endforeach
            </select>
        </div>
        
        <div class="cart-items" id="cartItems">
            <div class="text-center text-muted mt-5" id="emptyCart">
                <i class="bi bi-basket fs-1 d-block mb-2"></i>
                Keranjang masih kosong
            </div>
            <!-- Item akan dirender oleh JS -->
        </div>

        <div class="cart-summary">
            <div class="total-row">
                <span>Total</span>
                <span id="totalText">Rp 0</span>
            </div>
            
            <div class="mb-3">
                <label class="fw-semibold small">Uang Bayar (Rp)</label>
                <input type="number" id="inputBayar" class="form-control form-control-lg fw-bold text-end" placeholder="0">
            </div>
            <div class="d-flex justify-content-between mb-3 text-muted small fw-bold">
                <span>Kembalian:</span>
                <span id="kembalianText">Rp 0</span>
            </div>

            <button class="btn btn-primary-custom w-100 py-3 fs-5" id="btnCheckout" onclick="processCheckout()" disabled>
                <i class="bi bi-check-circle me-2"></i> Proses Transaksi
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let cart = [];
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}';

    // Fungsi Tambah ke Keranjang
    function addToCart(id, nama, harga, satuan, maxStok) {
        const existing = cart.find(item => item.id === id);
        
        if (existing) {
            if (existing.qty >= maxStok) {
                alert('Stok tidak mencukupi!');
                return;
            }
            existing.qty += 1;
        } else {
            cart.push({ id, nama, harga, satuan, qty: 1, maxStok });
        }
        renderCart();
    }

    // Hapus dari Keranjang
    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        renderCart();
    }

    // Update Qty
    function updateQty(id, qty) {
        const item = cart.find(item => item.id === id);
        if (!item) return;
        
        qty = parseFloat(qty);
        if (isNaN(qty) || qty <= 0) {
            item.qty = 1;
        } else if (qty > item.maxStok) {
            alert('Melebihi stok tersedia!');
            item.qty = item.maxStok;
        } else {
            item.qty = qty;
        }
        renderCart();
    }

    // Render Tampilan Keranjang
    function renderCart() {
        const container = document.getElementById('cartItems');
        const btnCheckout = document.getElementById('btnCheckout');
        
        container.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            container.innerHTML = `<div class="text-center text-muted mt-5" id="emptyCart"><i class="bi bi-basket fs-1 d-block mb-2"></i>Keranjang masih kosong</div>`;
            btnCheckout.disabled = true;
        } else {
            cart.forEach(item => {
                total += item.harga * item.qty;
                const html = `
                    <div class="cart-item">
                        <div style="flex:1;">
                            <div class="fw-bold" style="font-size:0.9rem;">${item.nama}</div>
                            <div class="text-primary small">Rp ${formatRupiah(item.harga)} / ${item.satuan}</div>
                        </div>
                        <div class="d-flex align-items-center gap-2 mx-2">
                            <input type="number" class="form-control form-control-sm qty-input" value="${item.qty}" min="0.1" step="0.1" onchange="updateQty(${item.id}, this.value)">
                        </div>
                        <div class="fw-bold text-end" style="width: 80px;">Rp ${formatRupiah(item.harga * item.qty)}</div>
                        <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeFromCart(${item.id})"><i class="bi bi-x"></i></button>
                    </div>
                `;
                container.innerHTML += html;
            });
            btnCheckout.disabled = false;
        }

        document.getElementById('totalText').innerText = 'Rp ' + formatRupiah(total);
        hitungKembalian();
    }

    // Hitung Kembalian
    function hitungKembalian() {
        const total = cart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
        const bayar = parseFloat(document.getElementById('inputBayar').value) || 0;
        const kembali = bayar - total;
        
        document.getElementById('kembalianText').innerText = 'Rp ' + formatRupiah(kembali >= 0 ? kembali : 0);
        if (kembali < 0) {
            document.getElementById('kembalianText').classList.add('text-danger');
        } else {
            document.getElementById('kembalianText').classList.remove('text-danger');
        }
    }

    document.getElementById('inputBayar').addEventListener('input', hitungKembalian);

    // Format Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // Proses Checkout ke Backend
    function processCheckout() {
        const total = cart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
        const bayar = parseFloat(document.getElementById('inputBayar').value) || 0;

        if (bayar < total) {
            alert('Uang bayar kurang!');
            return;
        }

        const btn = document.getElementById('btnCheckout');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';

        fetch('{{ route("kasir.pos.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                items: cart,
                bayar: bayar,
                pelanggan_id: document.getElementById('selectPelanggan').value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Proses Transaksi';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan sistem.');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Proses Transaksi';
        });
    }

    // Fitur Pencarian
    document.getElementById('searchProduct').addEventListener('input', function(e) {
        const keyword = e.target.value.toLowerCase();
        document.querySelectorAll('.product-item').forEach(item => {
            const name = item.getAttribute('data-name');
            item.style.display = name.includes(keyword) ? 'block' : 'none';
        });
    });
</script>
@endpush