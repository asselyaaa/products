<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $tags = DB::table('tags')
            ->join('product_tags', 'tags.id', '=', 'product_tags.tag_id')
            ->where('category_id', $request->get('category_id'))
            ->get();

        return response()->json(compact('tags'));
    }
}
