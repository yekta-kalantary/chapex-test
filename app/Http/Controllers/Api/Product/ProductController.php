<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Utility\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'price_min' => 'nullable|integer',
            'price_max' => 'nullable|integer',
            'category' => ['nullable', 'integer',  Rule::in(Category::all()->pluck('id'))],
            'search_term' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1',
            'order_by' => 'nullable|string|in:price,created_at',
            'order' => 'nullable|string|in:asc,desc',
        ]);

        $products = Product::query()
            ->when($request->input('category'), function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->input('search_term'), function ($query, $searchTerm) {
                return $query->where('name', 'like', "%$searchTerm%");
            })
            ->when($request->input('price_min'), function ($query, $minPrice) {
                return $query->where('price', '>=', $minPrice);
            })
            ->when($request->input('price_max'), function ($query, $maxPrice) {
                return $query->where('price', '<=', $maxPrice);
            })
            ->orderBy($request->input('order_by', 'id'), $request->input('order', 'desc'))
            ->paginate($request->input('per_page', 20));

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'category' => ['required', 'integer', Rule::in(Category::all()->pluck('id'))],
            'price' => 'required|int|min:0',
            'description' => 'required|string|max:20000',
        ]);

        $product = Product::create($data);

        return new ApiResponse([
            'message' => 'Product create successfully.',
            'id' => $product->id,
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return ProductResource::make($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,'.$product->id,
            'category' => ['required', 'integer',  Rule::in(Category::all()->pluck('id'))],
            'price' => 'required|int|min:0',
            'description' => 'required|string|max:20000',
        ]);

        $product->update($data);

        return new ApiResponse([
            'message' => 'Product saved successfully.',
            'id' => $product->id,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return new ApiResponse(['message' => 'Product removed successfully.']);
    }

    public function categoryList()
    {
        return ApiResponse::handle(Category::all());
    }
}
