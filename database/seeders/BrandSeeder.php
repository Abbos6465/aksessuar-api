<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['category_id'=>1,'name'=>"Nokia"],
            ['category_id'=>1 , 'name'=>"Realme"],
            ['category_id'=>1 , 'name'=>'Redmi'],
            ['category_id'=>2 , 'name'=>"Samsung"],
            ['category_id'=>2 , 'name'=>"Redmi"],
            ['category_id'=>2 , 'name'=>"Iphone"],
            ['category_id'=>3 , 'name'=>"Asus"],
            ['category_id'=>3 , 'name'=>"Hp"],
            ['category_id'=>3 , 'name'=>"Dell"],
        ];

        foreach($brands as $brand){
            Brand::create([
                'category_id'=>$brand['category_id'],
                'name'=>$brand['name']
            ]);
        }
    }
}
