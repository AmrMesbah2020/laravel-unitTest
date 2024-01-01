<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return response([ 'products' => $products, 'message' => 'Products Listed Successfully'], 200);
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'qty' => $request->qty
        ]);

        return response([ 'product' => $product, 'message' => 'Products Created Successfully'], 200);
    }

    public function show(Product $product)
    {
        return response([ 'product' => $product, 'message' => 'Products Showed Successfully'], 200);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'qty' => $request->qty
        ]);

        return response([ 'product' => $product->fresh(), 'message' => 'Products Updated Successfully'], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response([ 'message' => 'Products Deleted Successfully'], 200);
    }
}
