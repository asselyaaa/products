<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //create users
        foreach (\App\User::$roles as $role) {
            DB::table('users')->insert([
                'name' => $role,
                'email' => $role.'@gmail.com',
                'password' => bcrypt('secret'),
                'role' => $role
            ]);
        }

        //create categories
        $categoriesAmount = 9;
        for ($i=0; $i<$categoriesAmount; $i++) {
            $parentId = null;
            if ($i%3 != 0) {
                $parentId = $i;
            }

            \App\Category::create([
                'name' => 'Category'.$i,
                'parent_id' => $parentId
            ]);
        }

        //create colors
        $colors = ['white', 'red', 'blue', 'green', 'black'];
        foreach ($colors as $color) {
            \App\Color::create([
                'title' => $color
            ]);
        }

        //create products
        $faker = Faker\Factory::create();
        for ($i=0; $i<20; $i++) {
            \App\Product::create([
                'title' => $faker->word,
                'description' => $faker->text(150),
                'amount' => rand(0, 9),
                'price' => rand(100, 1000),
                'color_id' => rand(1, 5)
            ]);

            $categoryId = rand(1, 9);
            $category = \App\Category::find($categoryId);
            \Illuminate\Support\Facades\DB::table('product_categories')->insert([
                'product_id' => $i+1,
                'category_id' => $categoryId
            ]);

            $parents = $category->parents();
            foreach ($parents as $parent) {
                \Illuminate\Support\Facades\DB::table('product_categories')->insert([
                    'product_id' => $i+1,
                    'category_id' => $parent->id
                ]);
            }
        }

        //create tags
        $categories = \App\Category::all();
        foreach ($categories as $category) {
            $tag = \App\Tag::create([
                'title' => $faker->word,
                'category_id' => $category->id
            ]);

            $productCategory = \Illuminate\Support\Facades\DB::table('product_categories')
                ->select('product_id')
                ->where('category_id', $category->id)
                ->first();

            if ($productCategory) {
                \Illuminate\Support\Facades\DB::table('product_tags')->insert([
                    'tag_id' => $tag->id,
                    'product_id' => $productCategory->product_id
                ]);
            }
        }
    }
}
