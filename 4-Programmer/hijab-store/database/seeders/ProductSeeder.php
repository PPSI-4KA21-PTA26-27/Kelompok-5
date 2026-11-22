<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat Produk Pertama (Pashmina)
        $product1 = Product::create([
            'name' => 'Pashmina Ceruty Premium',
            'slug' => Str::slug('Pashmina Ceruty Premium'),
            'description' => 'Pashmina dengan bahan ceruty babydoll premium yang flowy, mudah dibentuk, dan tidak mudah kusut.',
            'price' => 89000,
            'weight' => 150, // 150 gram
            'is_active' => true,
        ]);

        // Membuat Varian Warna untuk Produk 1
        ProductVariant::create(['product_id' => $product1->id, 'color' => 'Sage Green', 'sku' => 'PASH-SAGE', 'stock' => 50]);
        ProductVariant::create(['product_id' => $product1->id, 'color' => 'Toffee', 'sku' => 'PASH-TOF', 'stock' => 30]);
        ProductVariant::create(['product_id' => $product1->id, 'color' => 'Black', 'sku' => 'PASH-BLK', 'stock' => 100]);

        // 2. Membuat Produk Kedua (Voal)
        $product2 = Product::create([
            'name' => 'Voal Sahara Square',
            'slug' => Str::slug('Voal Sahara Square'),
            'description' => 'Hijab segi empat dari bahan voal sahara yang tegak paripurna di dahi, menyerap keringat, dan ironless.',
            'price' => 65000,
            'weight' => 110, // 110 gram
            'is_active' => true,
        ]);

        // Membuat Varian Warna untuk Produk 2
        ProductVariant::create(['product_id' => $product2->id, 'color' => 'Desert Sand', 'sku' => 'VOAL-SND', 'stock' => 40]);
        ProductVariant::create(['product_id' => $product2->id, 'color' => 'Broken White', 'sku' => 'VOAL-BW', 'stock' => 20]);
    }
}