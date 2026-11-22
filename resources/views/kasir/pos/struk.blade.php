<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #{{ $transaksi->nomor }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; color: #000; margin: 0; padding: 20px; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 4px 0; vertical-align: top; }
        .container { max-width: 300px; margin: 0 auto; }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="text-center">
            <h3 style="margin:0;">PASAR IKAN KAWALI</h3>
            <p style="margin:5px 0;">Jl. Raya Kawali, Ciamis</p>
            <p style="margin:5px 0;">Telp: 081234567890</p>
        </div>
        
        <div class="line"></div>
        
        <table>
            <tr><td>No</td><td>: {{ $transaksi->nomor }}</td></tr>
            <tr><td>Tgl</td><td>: {{ $transaksi->created_at->format('d/m/Y H:i') }}</td></tr>
            <tr><td>Kasir</td><td>: {{ $transaksi->user->name }}</td></tr>
        </table>
        
        <div class="line"></div>
        
        <table>
            @foreach($transaksi->details as $d)
            <tr>
                <td colspan="2">{{ $d->nama_snapshot }}</td>
            </tr>
            <tr>
                <td>{{ $d->qty }} {{ $d->satuan_snapshot }} x {{ number_format($d->harga_snapshot, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>
        
        <div class="line"></div>
        
        <table>
            <tr>
                <td class="fw-bold">TOTAL</td>
                <td class="text-end fw-bold">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>BAYAR</td>
                <td class="text-end">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>KEMBALI</td>
                <td class="text-end">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
            </tr>
        </table>
        
        <div class="line"></div>
        
        <div class="text-center" style="margin-top: 20px;">
            <p>Terima Kasih<br>Selamat Berbelanja Kembali</p>
        </div>
    </div>

    <div class="text-center mt-4" style="margin-top: 30px;">
        <a href="{{ route('kasir.pos') }}" style="padding: 10px 20px; background: #1E6BB8; color: #fff; text-decoration: none; border-radius: 5px; font-family: sans-serif;">Kembali ke POS</a>
    </div>
</body>
</html>