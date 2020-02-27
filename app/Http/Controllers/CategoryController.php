<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }

    public function index()
    {
        $categories = Cache::get('categories');
        if (!$categories) {
            $categories = Category::whereNull('parent_id')
                ->with('childrenCategories')
                ->get();
            Cache::put('categories', $categories, 3600*6);
        }

        return response()->json(compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $category = Category::create($request->all());
        Cache::forget('categories');

        return response()->json(compact('category'));
    }

    public function show(Category $category)
    {
        return response()->json(compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

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
