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
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'qty' => 'required|numeric|min:0.1',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        
        if ($produk->stok < $request->qty) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        $cart = session()->get('cart', []);

        // Jika produk sudah ada di keranjang, tambahkan qty-nya
        if(isset($cart[$produk->id])) {
            $newQty = $cart[$produk->id]['qty'] + $request->qty;
            if ($newQty > $produk->stok) {
                return back()->with('error', 'Total jumlah melebihi stok yang tersedia!');
            }
            $cart[$produk->id]['qty'] = $newQty;
        } else {
            // Jika belum ada, buat item baru
            $cart[$produk->id] = [
                "nama" => $produk->nama,
                "qty" => $request->qty,
                "harga" => $produk->harga_jual,
                "satuan" => $produk->satuan,
                "foto" => $produk->foto
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('public.cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        if($request->id && $request->qty){
            $cart = session()->get('cart');
            $cart[$request->id]["qty"] = $request->qty;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json(['success' => true]);
        }
    }
}