<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Scoping\Scopes\CategoryScope;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['variations', 'variations.stock'])->withScopes([
            'category' => new CategoryScope()
        ])->paginate(10);

        return ProductIndexResource::collection($products);
    }

    public function show(Product $product)
    {
        $product->load(['variations.type', 'variations.stock', 'variations.product']);

        return new ProductResource($product);
    }
}
