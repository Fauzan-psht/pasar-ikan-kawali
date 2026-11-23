@extends('layouts.dashboard')

@section('title', 'Catat Stok Masuk')
@section('page_title', 'Form Penerimaan Barang')

@section('content')
<form action="{{ route('admin.stok.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-title"><i class="bi bi-box-seam text-primary"></i> Daftar Barang Masuk</div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="itemsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th width="120">Qty</th>
                                <th width="150">Harga Modal</th>
                                <th width="150">Subtotal</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="items[0][produk_id]" class="form-select" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($produks as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->satuan }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="items[0][qty]" class="form-control" required min="0.1" step="0.1"></td>
                                <td><input type="number" name="items[0][harga_modal]" class="form-control" required min="0"></td>
                                <td class="subtotal-text align-middle">Rp 0</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addRow()"><i class="bi bi-plus"></i> Tambah Baris</button>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-title"><i class="bi bi-info-circle text-primary"></i> Info Penerimaan</div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal Terima <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Catatan (Opsional)</label>
                    <textarea name="catatan" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100 py-2">Simpan & Posting Stok</button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let rowCount = 1;
    function addRow() {
        let table = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
        let newRow = table.insertRow();
        
        newRow.innerHTML = `
            <td>
                <select name="items[${rowCount}][produk_id]" class="form-select" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($produks as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->satuan }})</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${rowCount}][qty]" class="form-control" required min="0.1" step="0.1"></td>
            <td><input type="number" name="items[${rowCount}][harga_modal]" class="form-control" required min="0"></td>
            <td class="subtotal-text align-middle">Rp 0</td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i></button></td>
        `;
        rowCount++;
    }
</script>
@endpush
