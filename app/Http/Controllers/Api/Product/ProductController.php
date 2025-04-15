<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Utility\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'price.min' => 'nullable|integer|lt:price_max',
            'price.max' => 'nullable|integer|gt:price_max',
            'category' => 'nullable|integer|in:'.Category::all()->pluck('id')->implode(','),
            'search_term' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1',
            'order_by' => 'nullable|string|in:price,created_at',
            'order' => 'nullable|string|in:asc,desc',
        ]);

        $products = Product::query()
            ->when($data['category'] ?? null, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($data['search_term'] ?? null, function ($query, $searchTerm) {
                return $query->where('name', 'like', "%$searchTerm%");
            })
            ->when($data['price']['min'] ?? null, function ($query, $minPrice) {
                return $query->where('price', '<=', $minPrice);
            })
            ->when($data['price']['max'] ?? null, function ($query, $maxPrice) {
                return $query->where('price', '>=', $maxPrice);
            })
            ->orderBy($data['order_by'] ?? 'id', $data['order'] ?? 'desc')
            ->paginate($data['per_page'] ?? 20);

        return ProductResource::collection($products);
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

        return ProductResource::make([
            'product' => $product,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category');

        return ApiResponse::handle([
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,'.$product->id,
            'category' => 'required|int|in:'.Category::all()->pluck('id')->implode(','),
            'price' => 'required|int|min:0',
            'description' => 'required|string|max:20000',
        ]);

        $product->update($data);

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
