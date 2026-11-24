<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\StokMovement;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columnNames = [
                'transaksis.nomor',
                'transaksis.created_at',
                'transaksis.kanal',
                'users.name',
                'pelanggans.nama',
                'transaksis.total',
                'transaksis.status',
                'actions',
                'transaksis.id',
            ];

            $request->validate([
                'columns' => ['required', 'array', 'size:9'],
                'columns.*.name' => ['required', 'string', Rule::in($columnNames)],
                'order' => ['sometimes', 'array'],
                'order.*.column' => ['integer', Rule::in([0, 1, 2, 3, 4, 5, 6, 8])],
                'order.*.dir' => ['required', Rule::in(['asc', 'desc'])],
                'start' => ['sometimes', 'integer', 'min:0'],
                'length' => ['sometimes', 'integer', 'between:1,100'],
                'search.value' => ['sometimes', 'string', 'max:100'],
                'search.regex' => ['sometimes', Rule::in(['false', '0', false, 0])],
            ]);

            if (array_column($request->input('columns'), 'name') !== $columnNames) {
                throw ValidationException::withMessages([
                    'columns' => 'Kolom tabel tidak valid.',
                ]);
            }

            $dataTableQuery = Transaksi::query()
                ->leftJoin('users', 'users.id', '=', 'transaksis.user_id')
                ->leftJoin('pelanggans', 'pelanggans.id', '=', 'transaksis.pelanggan_id')
                ->select('transaksis.*', 'users.name as user_name', 'pelanggans.nama as pelanggan_name');

            return DataTables::eloquent($dataTableQuery)
                ->addColumn('actions', function (Transaksi $transaksi): string {
                    if ($transaksi->status !== 'paid') {
                        return '';
                    }

                    return '<button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#voidModal" data-action="'.
                        e(route('admin.transaksi.void', $transaksi)).
                        '" data-nomor="'.e($transaksi->nomor).'" title="Batalkan Transaksi"><i class="bi bi-x-circle"></i> Void</button>';
                })
                ->escapeColumns([])
                ->rawColumns(['actions'])
                ->toJson();
        }

        $transaksis = Transaksi::with('user', 'pelanggan')->latest()->paginate(20);

        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function void(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'void_reason' => 'required|string|max:255',
        ]);

        if ($transaksi->status === 'void') {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        try {
            DB::beginTransaction();

            // 1. Ubah status transaksi
            $transaksi->update([
                'status' => 'void',
                'void_reason' => $request->void_reason,
                'void_at' => now(),
                'void_by' => auth()->id(),
            ]);

            // 2. Kembalikan Stok Produk & Catat Movement
            foreach ($transaksi->details as $detail) {
                if ($detail->produk_id) {
                    $produk = Produk::lockForUpdate()->find($detail->produk_id);
                    if ($produk) {
                        // Kembalikan stok
                        $produk->increment('stok', $detail->qty);

                        // Catat di stok movements
                        StokMovement::create([
                            'produk_id' => $produk->id,
                            'tipe' => 'void',
                            'qty' => $detail->qty,
                            'source_type' => Transaksi::class,
                            'source_id' => $transaksi->id,
                            'user_id' => auth()->id(),
                            'alasan' => 'Void Transaksi: '.$request->void_reason,
                        ]);
                    }
                }
            }

            DB::commit();

            return back()->with('success', 'Transaksi berhasil di-void dan stok telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal melakukan void: '.$e->getMessage());
        }
    }
}
