<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        return view('public.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $validatedProduk = $request->validate([
            'produk_id' => ['required', 'exists:produks,id'],
        ]);

        $produk = Produk::findOrFail($validatedProduk['produk_id']);
        $validatedQuantity = $request->validate(
            $this->quantityRules($produk),
            $this->quantityMessages($produk),
        );
        $quantity = (float) $validatedQuantity['qty'];

        if ($produk->stok < $quantity) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$produk->id])) {
            $newQty = (float) $cart[$produk->id]['qty'] + $quantity;
            if ($newQty > $produk->stok) {
                return back()->with('error', 'Total jumlah melebihi stok yang tersedia!');
            }
            $cart[$produk->id]['qty'] = $newQty;
        } else {
            $cart[$produk->id] = [
                'nama' => $produk->nama,
                'qty' => $quantity,
                'harga' => $produk->harga_jual,
                'satuan' => $produk->satuan,
                'foto' => $produk->foto,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('public.cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        $validatedId = $request->validate([
            'id' => ['required', 'integer', 'exists:produks,id'],
        ]);
        $produkId = $validatedId['id'];
        $cart = session()->get('cart', []);

        if (! isset($cart[$produkId])) {
            return response()->json(['message' => 'Produk tidak ditemukan di keranjang.'], 404);
        }

        $produk = Produk::findOrFail($produkId);
        $validatedQuantity = $request->validate(
            $this->quantityRules($produk, true),
            $this->quantityMessages($produk),
        );
        $cart[$produkId]['qty'] = (float) $validatedQuantity['qty'];
        session()->put('cart', $cart);

        return response()->json(['success' => true]);
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart', []);
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }

            return response()->json(['success' => true]);
        }
    }

    private function quantityRules(Produk $produk, bool $limitToStock = false): array
    {
        $rules = [
            'required',
            'numeric',
            'min:'.$produk->minimumOrderQuantity(),
        ];

        if ($produk->satuan !== 'kg') {
            $rules[] = 'integer';
        }

        if ($limitToStock) {
            $rules[] = 'max:'.$produk->stok;
        }

        return ['qty' => $rules];
    }

    private function quantityMessages(Produk $produk): array
    {
        return [
            'qty.min' => $produk->satuan === 'kg'
                ? 'Jumlah minimum pembelian adalah 0,5 kg.'
                : 'Jumlah minimum pembelian adalah 1 ekor.',
            'qty.integer' => 'Jumlah produk satuan ekor harus berupa bilangan bulat.',
            'qty.max' => 'Jumlah melebihi stok tersedia.',
        ];
    }
}
