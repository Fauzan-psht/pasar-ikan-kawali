<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    public function handle(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

        // Verifikasi Signature
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pesanan = Pesanan::where('nomor', $request->order_id)->first();
        if (! $pesanan) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $payment = Payment::where('pesanan_id', $pesanan->id)->latest()->first();

        if (! $payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $transactionStatus = $request->transaction_status;

        DB::beginTransaction();
        try {
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $payment->update(['status' => 'paid', 'paid_at' => now()]);
                $pesanan->update(['status' => 'confirmed']);
            } elseif ($transactionStatus == 'pending') {
                // Biarkan pending
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $payment->update(['status' => 'failed']);
                $pesanan->update(['status' => 'cancelled']);

                // Kembalikan stok karena gagal bayar
                foreach ($pesanan->details as $detail) {
                    if ($detail->produk_id) {
                        Produk::where('id', $detail->produk_id)->increment('stok', $detail->qty);
                    }
                }
            }
            DB::commit();

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Error'], 500);
        }
    }
}
