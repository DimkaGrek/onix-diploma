<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class, 'order');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') return Order::paginate(15);
        else return Order::where('user_id', '=', Auth::user()->id)->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $cart_products = Cart::where('user_id', '=', $user_id)->get();
        if ($cart_products->isEmpty()) return response()->json(['message' => 'your cart is empty'], 404);
        $order = new Order;
        $order->user_id = $user_id;
        $order->status = 'new';
        $order->comment = $request->comment;
        $order->address = $request->address;
        $order->save();
        foreach ($cart_products as $cart) {
            $product = Product::find($cart->product_id);
            $order->products()->attach($cart->product_id, ['price' => $product->price, 'quantity' => $cart->quantity]);
            $product->in_stock = $product->in_stock - $cart->quantity; // уменьшаем stock продукта
            $product->save();
        }
        Cart::where('user_id', '=', Auth::user()->id)->delete(); // удаляем корзину пользователя
        return $order;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $order->load('products');
        //return $order->products;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $order->comment = $request->comment;
        $order->address = $request->address;
        if ($request->has('status') and Auth::user()->role == 'admin') $order->status = $request->status;
        $order->save();
        return $order;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $products = $order->products;
        foreach ($products as $product) {
            $product->in_stock = $product->in_stock + $product->pivot->quantity;
            $product->save();
        }
        $order->products()->detach();
        $order->delete();
        return response()->json(['message' => 'order deleted']);
    }

    public function userOrders(User $user) {
        $this->authorize('userOrders', Order::class);
        return $user->load('orders');
    }
}
