<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CategoryForm;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
        $this->authorizeResource(Category::class, 'product');
    }

    public function index()
    {
        $categories = Cache::get('categories');
        if (!$categories) {
            $categories = Category::whereNull('parent_id')
                ->with('childrenRecursive')
                ->get();
            Cache::put('categories', $categories, 3600*6);
        }

        return response()->json(compact('categories'));
    }

    public function store(CategoryForm $request)
    {
        $category = Category::create($request->all());
        Cache::forget('categories');

        return response()->json(compact('category'));
    }

    public function show(Category $category)
    {
        return response()->json(compact('category'));
    }

    public function update(CategoryForm $request, Category $category)
    {
        $category->update($request->all());
        Cache::forget('categories');

        return response()->json(compact($category));
    }

    public function destroy(Category $category)
    {
        if (Category::where('parent_id', $category->id)->first()) {
            return response()->json([
                'errors' => 'cannot delete parent category'
            ]);
        }

        $category = $category->delete();
        Cache::forget('categories');

        return response()->json($category);
    }
}
