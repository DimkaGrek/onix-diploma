<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\Response;

class CartController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cart = new Cart;
        $cart->user_id = Auth::user()->id;
        $cart->product_id = $request->product_id;
        $product = Product::find($request->product_id);
        if ($request->quantity > $product->in_stock) $cart->quantity = $product->quantity;
        else $cart->quantity = $request->quantity;
        $cart->save();
        return $cart;
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return Cart::where('user_id', '=', Auth::user()->id)
            ->join('products', 'product_id','=','products.id')
            ->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        if (Auth::user()->id != $cart->user_id) return response()->json(['error' => "It's not authorize"], 401);
        else {
            $cart->delete();
            return response()->json(['message' => 'cart element deleted']);
        }
    }

    public function deleteAll() {
        Cart::where('user_id', '=', Auth::user()->id)->delete();
        return response()->json(['message' => 'cart deleted']);
    }
}
