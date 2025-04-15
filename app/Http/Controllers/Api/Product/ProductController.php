<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Utility\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'category' => 'required|int|in:'.Category::all()->pluck('id')->implode(','),
            'price' => 'required|int|min:0',
            'description' => 'required|string|max:20000',
        ]);

        $product = Product::create($data);
        $product->load('category');

        return ApiResponse::handle([
            'product' => $product,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'category' => 'required|int|in:'.Category::all()->pluck('id')->implode(','),
            'price' => 'required|int|min:0',
            'description' => 'required|string|max:20000',
        ]);

        $product->update($data);
        $product->load('category');

        return ApiResponse::handle([
            'product' => $product,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function categoryList(Request $request)
    {
        return ApiResponse::handle([
            'categories' => Category::all(),
        ]);
    }
}
