<?php
/**
 * Created by PhpStorm.
 * User: assel
 * Date: 27.02.2020
 * Time: 16:38
 */

namespace App\Http;


use App\Http\Requests\GetProductForm;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public static function products(GetProductForm $request)
    {
        $priceFrom = $request->get('price_from') ?? 0;
        $priceTo = $request->get('price_to');
        $colors = $request->get('color');
        $tags = $request->get('tags');

        $products = DB::table('products')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->where('product_categories.category_id', $request->get('category_id'))
            ->where('price', '>=', $priceFrom);

        if ($priceTo && $priceTo > $priceFrom) {
            $products->where('price', '<=', $priceTo);
        }

        if ($colors) {
            $colorIds = explode(',', $colors);
            $products->whereIn('color_id', $colorIds);
        }

        if ($tags) {
            $tags = explode(',', $tags);
            $products->join('product_tags', 'products.id', '=', 'product_tags.product_id')
                ->join('tags', 'tags.id', '=', 'product_tags.tag_id')
                ->whereIn('product_tags.tag_id', $tags)
                ->where('tags.category_id', $request->get('category_id'));
        }

        return $products->paginate($request->get('size') ?? 5);
    }

    public function deleteAndAddCategories(array $categories)
    {
        if (!empty($categories)) {
            $this->deleteCategories();
            $this->addCategories($categories);
        }
    }

    public function deleteCategories()
    {
        DB::table('product_categories')->where('product_id', $this->product->id)->delete();
    }

    public function addCategories(array $categories)
    {
        foreach ($categories as $categoryId) {
            DB::table('product_categories')->insert([
                'product_id' => $this->product->id,
                'category_id' => $categoryId
            ]);
        }
    }

}