<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['user_id'=>2,'category_id'=>1,'brand_id'=>1,'title'=>'Nokia S22 ultra','price'=>200,'content'=>'Yangi hali ishlatilinmagan',"photo"=>null],
            ['user_id'=>2,'category_id'=>2,'brand_id'=>4,'title'=>'Iphone Tab ultra','price'=>400,'content'=>'Yangi hali ishlatilinmagan',"photo"=>null],
            ['user_id'=>2,'category_id'=>3,'brand_id'=>8,'title'=>'Hp Victus ultra','price'=>600,'content'=>'Yangi hali ishlatilinmagan',"photo"=>null]
        ];

        foreach($products as $product){
            Product::create([
                'user_id' => $product['user_id'],
                'category_id' => $product['category_id'],
                'brand_id' => $product['brand_id'],
                'title' => $product['title'],
                'price' => $product['price'],
                'content' => $product['content'],
                'photo' => $product['photo'],
            ]);
        }
    }
}
