@extends('layouts.dashboard')

@section('title', 'Semua Transaksi')
@section('page_title', 'Semua Transaksi (POS & Online)')

@section('content')
<div class="panel">
    <div class="table-responsive">
        <table class="table table-custom align-middle w-100" id="transactionsTable">
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Waktu</th>
                    <th>Kanal / Kasir</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="voidModal" tabindex="-1" aria-labelledby="voidModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <form id="voidForm" method="POST" class="modal-content text-start">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="voidModalTitle">Batalkan Transaksi (Void)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin membatalkan transaksi <strong id="voidTransactionNumber"></strong>? Stok produk akan dikembalikan secara otomatis.</p>
                <div class="mb-3">
                    <label for="voidReason" class="form-label fw-semibold">Alasan Pembatalan <span class="text-danger">*</span></label>
                    <input type="text" name="void_reason" id="voidReason" class="form-control" required maxlength="255" placeholder="Contoh: Salah input qty">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
            </div>
        </form>
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

    new window.DataTable('#transactionsTable', {
        processing: true,
        serverSide: true,
        deferRender: true,
        ajax: @js(route('admin.transaksi.index')),
        searchDelay: 300,
        pageLength: 20,
        lengthMenu: [10, 20, 50, 100],
        order: [[1, 'desc'], [8, 'desc']],
        columns: [
            {
                data: 'nomor',
                name: 'transaksis.nomor',
                render: (nomor, type) => type === 'display' ? escapeHtml(nomor) : nomor,
            },
            {
                data: 'created_at',
                name: 'transaksis.created_at',
                render: (tanggal, type) => {
                    if (type !== 'display') {
                        return tanggal;
                    }

                    return new Intl.DateTimeFormat('id-ID', {
                        dateStyle: 'short',
                        timeStyle: 'short',
                    }).format(new Date(tanggal));
                },
            },
            {
                data: 'kanal',
                name: 'transaksis.kanal',
                render: (kanal, type, row) => {
                    if (type !== 'display') {
                        return kanal;
                    }

                    const isPos = kanal === 'pos';
                    const label = isPos ? 'POS' : 'Online';
                    const kasir = isPos && row.user_name ? ` ${escapeHtml(row.user_name)}` : '';
                    const badge = isPos ? 'info' : 'success';

                    return `<span class="badge-soft ${badge}">${label}</span>${kasir}`;
                },
            },
            { data: 'user_name', name: 'users.name', visible: false },
            {
                data: 'pelanggan_name',
                name: 'pelanggans.nama',
                render: (nama, type) => type === 'display' ? escapeHtml(nama || 'Guest') : nama,
            },
            {
                data: 'total',
                name: 'transaksis.total',
                render: (total, type) => type === 'display'
                    ? `Rp ${new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(Number(total))}`
                    : total,
            },
            {
                data: 'status',
                name: 'transaksis.status',
                render: (status, type) => {
                    if (type !== 'display') {
                        return status;
                    }

                    const badges = {
                        paid: 'success',
                        void: 'danger',
                        pending: 'warning',
                        refunded: 'warning',
                    };

                    return `<span class="badge-soft ${badges[status] || 'info'}">${escapeHtml(status)}</span>`;
                },
            },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
            { data: 'id', name: 'transaksis.id', visible: false, searchable: false },
        ],
        language: {
            processing: 'Memuat data...',
            search: 'Cari:',
            searchPlaceholder: 'Cari transaksi...',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ transaksi',
            infoEmpty: 'Belum ada transaksi',
            zeroRecords: 'Transaksi tidak ditemukan',
            emptyTable: 'Belum ada transaksi.',
            paginate: { previous: 'Sebelumnya', next: 'Berikutnya' },
        },
    });

    document.getElementById('voidModal').addEventListener('show.bs.modal', event => {
        const trigger = event.relatedTarget;
        const form = document.getElementById('voidForm');

        form.action = trigger.dataset.action;
        document.getElementById('voidTransactionNumber').textContent = trigger.dataset.nomor;
        document.getElementById('voidReason').value = '';
    });
});
</script>
@endpush
