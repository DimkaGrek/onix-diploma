<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('category_ids') and !empty($request->category_ids))
        {
            $category_ids = explode(',', $request->category_ids);
            $query = Product::query();
            $query->whereHas('categories', function ($q) use ($category_ids) {
               $q->whereIn('categories.id', $category_ids);
            });
            if ($request->has('sort_by')) $query->orderBy($request->sort_by);
            return $query->paginate(15);
        }
        else return Product::paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('store', Product::class);
        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->in_stock = $request->in_stock;
        $product->price = $request->price;
        $product->rating = 0;
        $product->save();
        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $c = new Collection;
        $images = $product->productimages;
        return $c->merge($product)->merge($images);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', Product::class);
        $product->name = $request->name;
        $product->save();
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', Product::class);
        $product->delete();
        return response()->json(['message' => 'product deleted']);
    }

    public function questions(Product $product){
        $product->load('questions', 'questions.answers');
        return $product;
    }
}
