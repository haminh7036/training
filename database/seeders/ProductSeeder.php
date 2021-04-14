<?php

namespace Database\Seeders;

use App\Models\ProductModel;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 30; $i++) { 
            $count = ProductModel::where("product_id", "like", "S%")
                ->count();
            ProductModel::create([
                'product_id' => 'S'.str_pad(($count + 1), 9, '0', STR_PAD_LEFT),
                'product_name' => 'Sách thiếu nhi '.($count + 1),
                'product_image' => '',
                'product_price' => 100000,
                'description' => '',
                'is_sales' => rand(-1, 1)
            ]);
        }
    }
}
