@extends('layouts.dashboard')

@section('title', 'Produk')
@section('page_title', 'Kelola Produk Ikan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.produk.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
    </a>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table table-custom align-middle w-100" id="productsTable">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Tipe / Satuan</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    })[character]);
    const storageUrl = @js(asset('storage'));

    new window.DataTable('#productsTable', {
        processing: true,
        serverSide: true,
        deferRender: true,
        ajax: @js(route('admin.produk.index')),
        searchDelay: 300,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [[8, 'desc'], [9, 'desc']],
        columns: [
            {
                data: 'foto',
                name: 'produks.foto',
                orderable: false,
                searchable: false,
                render: (foto, type) => {
                    if (type !== 'display') {
                        return foto;
                    }

                    if (!foto) {
                        return '<div class="product-image-placeholder"><i class="bi bi-image"></i></div>';
                    }

                    return `<img src="${escapeHtml(storageUrl + '/' + foto)}" alt="Foto produk" class="product-table-image">`;
                },
            },
            {
                data: 'nama',
                name: 'produks.nama',
                render: (nama, type, row) => type === 'display'
                    ? `<div class="fw-bold">${escapeHtml(nama)}</div><small class="text-muted">${escapeHtml(row.deskripsi || '')}</small>`
                    : nama,
            },
            {
                data: 'kategori_nama',
                name: 'kategoris.nama',
                render: (kategori, type) => type === 'display' ? escapeHtml(kategori || '-') : kategori,
            },
            {
                data: 'tipe_produk',
                name: 'produks.tipe_produk',
                render: (tipe, type, row) => {
                    if (type !== 'display') {
                        return tipe;
                    }

                    const konsumsi = tipe === 'konsumsi';
                    const label = konsumsi ? 'Konsumsi' : 'Benih';
                    const badge = konsumsi ? 'info' : 'success';

                    return `<span class="badge-soft ${badge}">${label}</span><br><small>/ ${escapeHtml(row.satuan)}</small>`;
                },
            },
            {
                data: 'harga_jual',
                name: 'produks.harga_jual',
                render: (harga, type) => type === 'display'
                    ? `Rp ${new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(Number(harga))}`
                    : harga,
            },
            {
                data: 'stok',
                name: 'produks.stok',
                render: (stok, type, row) => {
                    if (type !== 'display') {
                        return stok;
                    }

                    const formattedStock = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(Number(stok));
                    const satuan = escapeHtml(row.satuan);

                    return Number(stok) <= Number(row.stok_minimum)
                        ? `<span class="text-danger fw-bold">${formattedStock} ${satuan}</span>`
                        : `${formattedStock} ${satuan}`;
                },
            },
            {
                data: 'status',
                name: 'produks.status',
                render: (status, type) => {
                    if (type !== 'display') {
                        return status;
                    }

                    return status
                        ? '<span class="badge-soft success">Aktif</span>'
                        : '<span class="badge-soft danger">Nonaktif</span>';
                },
            },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
            { data: 'created_at', name: 'produks.created_at', visible: false },
            { data: 'id', name: 'produks.id', visible: false },
        ],
        language: {
            processing: 'Memuat data...',
            search: 'Cari:',
            searchPlaceholder: 'Cari produk...',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ produk',
            infoEmpty: 'Belum ada produk',
            zeroRecords: 'Produk tidak ditemukan',
            emptyTable: 'Belum ada produk yang ditambahkan.',
            paginate: { previous: 'Sebelumnya', next: 'Berikutnya' },
        },
    });
});
</script>
@endpush
