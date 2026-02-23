<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\ProductFactory; // ✅ Mayúsculas correctas (Database\Factories)

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        ProductFactory::new()->count(5)->create();

        ProductFactory::new()->create([
            'name'  => 'Producto de Prueba VIP',
            'price' => 999.99,
        ]);
    }
}
