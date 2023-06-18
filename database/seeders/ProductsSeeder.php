<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents('jsons/Products.json');

        $json_data = json_decode($json,true);

        foreach ($json_data as $reg) {
            Product::create([
                "name" => $reg["name"],
                "code" => $reg["code"],
                "value" => $reg["value"],
            ]);
        }
    }
}
