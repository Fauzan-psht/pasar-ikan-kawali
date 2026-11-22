@extends('layouts.dashboard')

@section('title', 'Semua Transaksi')
@section('page_title', 'Semua Transaksi (POS & Online)')

@section('content')
<div class="panel">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Waktu</th>
                    <th>Kasir / Kanal</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                <tr>
                    <td><code style="font-size:0.8rem;">{{ $t->nomor }}</code></td>
                    <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($t->kanal === 'pos')
                            <span class="badge-soft info">POS</span> {{ $t->user->name ?? '-' }}
                        @else
                            <span class="badge-soft success">Online</span>
                        @endif
                    </td>
                    <td>{{ $t->pelanggan->nama ?? 'Guest' }}</td>
                    <td class="fw-bold text-primary">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                    <td>
                        @if($t->status === 'paid')<span class="badge-soft success">Paid</span>
                        @elseif($t->status === 'void')<span class="badge-soft danger" title="{{ $t->void_reason }}">Void</span>
                        @else<span class="badge-soft warning">{{ $t->status }}</span>@endif
                    </td>
                    <td class="text-center">
                        @if($t->status === 'paid')
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#voidModal{{ $t->id }}" title="Batalkan Transaksi">
                            <i class="bi bi-x-circle"></i> Void
                        </button>

                        <!-- Modal Void -->
                        <div class="modal fade" id="voidModal{{ $t->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.transaksi.void', $t->id) }}" method="POST" class="modal-content text-start">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title text-danger">Batalkan Transaksi (Void)</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin membatalkan transaksi <strong>{{ $t->nomor }}</strong>? Stok produk akan dikembalikan secara otomatis.</p>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Alasan Pembatalan <span class="text-danger">*</span></label>
                                            <input type="text" name="void_reason" class="form-control" required placeholder="Contoh: Salah input qty">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $transaksis->links() }}</div>
</div>
@endsection