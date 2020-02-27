<?php

namespace App\Http\Controllers;

use App\Http\ProductService;
use App\Http\Requests\GetProductForm;
use App\Http\Requests\ProductForm;
use App\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
        $this->authorizeResource(Product::class, 'product');
    }

    public function index(GetProductForm $request)
    {
        $products = ProductService::products($request);

        return response()->json(compact('products'));
    }

    public function store(ProductForm $request)
    {
        $product = Product::create($request->all());
        (new ProductService($product))->addCategories($request->get('categories'));

        return response()->json(compact('product'));
    }

    public function show(Product $product)
    {
        return response()->json(compact('product'));
    }

    public function update(ProductForm $request, Product $product)
    {
        $product->update($request->all());
        (new ProductService($product))->deleteAndAddCategories($request->get('categories'));

        return response()->json(compact('product'));
    }

    public function destroy(Product $product)
    {
        $success = $product->delete();

        return response()->json(compact('success'));
    }
}
