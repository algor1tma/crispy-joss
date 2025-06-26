<?php

namespace Database\Seeders;

use App\Models\ProductRecipe;
use App\Models\Produk;
use App\Models\RawMaterial;
use Illuminate\Database\Seeder;

class ProductRecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products and raw materials
        $products = Produk::all()->keyBy('nama_produk');
        $materials = RawMaterial::all()->keyBy('name');

        // Define recipes for each product
        $recipes = [
            'Ayam Geprek Original' => [
                // Base ayam crispy
                ['material' => 'Ayam Fillet', 'quantity' => 150, 'unit' => 'g'],
                ['material' => 'Tepung Terigu', 'quantity' => 30, 'unit' => 'g'],
                ['material' => 'Tepung Maizena', 'quantity' => 10, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Merica Bubuk', 'quantity' => 1, 'unit' => 'g'],
                ['material' => 'Penyedap Rasa', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 5, 'unit' => 'g'],
                ['material' => 'Minyak Goreng', 'quantity' => 100, 'unit' => 'ml'],
                // Sambal merah
                ['material' => 'Cabai Merah Keriting', 'quantity' => 25, 'unit' => 'g'],
                ['material' => 'Cabai Rawit Merah', 'quantity' => 5, 'unit' => 'g'],
                ['material' => 'Bawang Merah', 'quantity' => 10, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 3, 'unit' => 'g'],
                ['material' => 'Tomat', 'quantity' => 15, 'unit' => 'g'],
                ['material' => 'Gula Pasir', 'quantity' => 3, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 1, 'unit' => 'g'],
                // Nasi dan lalapan
                ['material' => 'Beras', 'quantity' => 80, 'unit' => 'g'],
                ['material' => 'Timun', 'quantity' => 20, 'unit' => 'g'],
                ['material' => 'Daun Kemangi', 'quantity' => 5, 'unit' => 'g'],
            ],

            'Ayam Geprek Sambal Ijo' => [
                // Base ayam crispy (sama seperti original)
                ['material' => 'Ayam Fillet', 'quantity' => 150, 'unit' => 'g'],
                ['material' => 'Tepung Terigu', 'quantity' => 30, 'unit' => 'g'],
                ['material' => 'Tepung Maizena', 'quantity' => 10, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Merica Bubuk', 'quantity' => 1, 'unit' => 'g'],
                ['material' => 'Penyedap Rasa', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 5, 'unit' => 'g'],
                ['material' => 'Minyak Goreng', 'quantity' => 100, 'unit' => 'ml'],
                // Sambal hijau
                ['material' => 'Cabai Hijau Besar', 'quantity' => 30, 'unit' => 'g'],
                ['material' => 'Cabai Rawit Hijau', 'quantity' => 8, 'unit' => 'g'],
                ['material' => 'Bawang Merah', 'quantity' => 10, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 3, 'unit' => 'g'],
                ['material' => 'Tomat', 'quantity' => 10, 'unit' => 'g'],
                ['material' => 'Gula Pasir', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 1, 'unit' => 'g'],
                // Nasi dan lalapan
                ['material' => 'Beras', 'quantity' => 80, 'unit' => 'g'],
                ['material' => 'Timun', 'quantity' => 20, 'unit' => 'g'],
                ['material' => 'Daun Kemangi', 'quantity' => 5, 'unit' => 'g'],
            ],

            'Ayam Geprek Sambal Terasi' => [
                // Base ayam crispy
                ['material' => 'Ayam Fillet', 'quantity' => 150, 'unit' => 'g'],
                ['material' => 'Tepung Terigu', 'quantity' => 30, 'unit' => 'g'],
                ['material' => 'Tepung Maizena', 'quantity' => 10, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Merica Bubuk', 'quantity' => 1, 'unit' => 'g'],
                ['material' => 'Penyedap Rasa', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 5, 'unit' => 'g'],
                ['material' => 'Minyak Goreng', 'quantity' => 100, 'unit' => 'ml'],
                // Sambal terasi
                ['material' => 'Cabai Merah Keriting', 'quantity' => 20, 'unit' => 'g'],
                ['material' => 'Cabai Rawit Merah', 'quantity' => 8, 'unit' => 'g'],
                ['material' => 'Bawang Merah', 'quantity' => 12, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 4, 'unit' => 'g'],
                ['material' => 'Terasi', 'quantity' => 3, 'unit' => 'g'],
                ['material' => 'Gula Pasir', 'quantity' => 3, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 1, 'unit' => 'g'],
                // Nasi dan lalapan
                ['material' => 'Beras', 'quantity' => 80, 'unit' => 'g'],
                ['material' => 'Timun', 'quantity' => 20, 'unit' => 'g'],
                ['material' => 'Daun Kemangi', 'quantity' => 5, 'unit' => 'g'],
            ],

            'Ayam Geprek Sambal Kecap' => [
                // Base ayam crispy
                ['material' => 'Ayam Fillet', 'quantity' => 150, 'unit' => 'g'],
                ['material' => 'Tepung Terigu', 'quantity' => 30, 'unit' => 'g'],
                ['material' => 'Tepung Maizena', 'quantity' => 10, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Merica Bubuk', 'quantity' => 1, 'unit' => 'g'],
                ['material' => 'Penyedap Rasa', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 5, 'unit' => 'g'],
                ['material' => 'Minyak Goreng', 'quantity' => 100, 'unit' => 'ml'],
                // Sambal kecap
                ['material' => 'Cabai Merah Keriting', 'quantity' => 15, 'unit' => 'g'],
                ['material' => 'Cabai Rawit Merah', 'quantity' => 3, 'unit' => 'g'],
                ['material' => 'Bawang Merah', 'quantity' => 8, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 3, 'unit' => 'g'],
                ['material' => 'Kecap Manis', 'quantity' => 15, 'unit' => 'ml'],
                ['material' => 'Gula Pasir', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 1, 'unit' => 'g'],
                // Nasi dan lalapan
                ['material' => 'Beras', 'quantity' => 80, 'unit' => 'g'],
                ['material' => 'Timun', 'quantity' => 20, 'unit' => 'g'],
                ['material' => 'Daun Kemangi', 'quantity' => 5, 'unit' => 'g'],
            ],

            'Ayam Geprek Level 5' => [
                // Base ayam crispy
                ['material' => 'Ayam Fillet', 'quantity' => 150, 'unit' => 'g'],
                ['material' => 'Tepung Terigu', 'quantity' => 30, 'unit' => 'g'],
                ['material' => 'Tepung Maizena', 'quantity' => 10, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Merica Bubuk', 'quantity' => 1, 'unit' => 'g'],
                ['material' => 'Penyedap Rasa', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 5, 'unit' => 'g'],
                ['material' => 'Minyak Goreng', 'quantity' => 100, 'unit' => 'ml'],
                // Sambal super pedas
                ['material' => 'Cabai Merah Keriting', 'quantity' => 35, 'unit' => 'g'],
                ['material' => 'Cabai Rawit Merah', 'quantity' => 15, 'unit' => 'g'],
                ['material' => 'Cabai Rawit Hijau', 'quantity' => 10, 'unit' => 'g'],
                ['material' => 'Bawang Merah', 'quantity' => 12, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 5, 'unit' => 'g'],
                ['material' => 'Tomat', 'quantity' => 10, 'unit' => 'g'],
                ['material' => 'Gula Pasir', 'quantity' => 3, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 2, 'unit' => 'g'],
                // Nasi dan lalapan
                ['material' => 'Beras', 'quantity' => 80, 'unit' => 'g'],
                ['material' => 'Timun', 'quantity' => 25, 'unit' => 'g'],
                ['material' => 'Daun Kemangi', 'quantity' => 8, 'unit' => 'g'],
            ],

            'Paket Geprek + Telur' => [
                // Base ayam crispy
                ['material' => 'Ayam Fillet', 'quantity' => 150, 'unit' => 'g'],
                ['material' => 'Tepung Terigu', 'quantity' => 30, 'unit' => 'g'],
                ['material' => 'Tepung Maizena', 'quantity' => 10, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Merica Bubuk', 'quantity' => 1, 'unit' => 'g'],
                ['material' => 'Penyedap Rasa', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 5, 'unit' => 'g'],
                ['material' => 'Minyak Goreng', 'quantity' => 120, 'unit' => 'ml'],
                // Sambal
                ['material' => 'Cabai Merah Keriting', 'quantity' => 25, 'unit' => 'g'],
                ['material' => 'Cabai Rawit Merah', 'quantity' => 5, 'unit' => 'g'],
                ['material' => 'Bawang Merah', 'quantity' => 10, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 3, 'unit' => 'g'],
                ['material' => 'Tomat', 'quantity' => 15, 'unit' => 'g'],
                ['material' => 'Gula Pasir', 'quantity' => 3, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 1, 'unit' => 'g'],
                // Telur
                ['material' => 'Telur Ayam', 'quantity' => 1, 'unit' => 'pcs'],
                // Nasi dan lalapan
                ['material' => 'Beras', 'quantity' => 80, 'unit' => 'g'],
                ['material' => 'Timun', 'quantity' => 20, 'unit' => 'g'],
                ['material' => 'Daun Kemangi', 'quantity' => 5, 'unit' => 'g'],
            ],

            'Paket Geprek Jumbo' => [
                // Base ayam crispy (porsi jumbo)
                ['material' => 'Ayam Fillet', 'quantity' => 250, 'unit' => 'g'],
                ['material' => 'Tepung Terigu', 'quantity' => 50, 'unit' => 'g'],
                ['material' => 'Tepung Maizena', 'quantity' => 15, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 3, 'unit' => 'g'],
                ['material' => 'Merica Bubuk', 'quantity' => 2, 'unit' => 'g'],
                ['material' => 'Penyedap Rasa', 'quantity' => 3, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 8, 'unit' => 'g'],
                ['material' => 'Minyak Goreng', 'quantity' => 150, 'unit' => 'ml'],
                // Sambal
                ['material' => 'Cabai Merah Keriting', 'quantity' => 35, 'unit' => 'g'],
                ['material' => 'Cabai Rawit Merah', 'quantity' => 8, 'unit' => 'g'],
                ['material' => 'Bawang Merah', 'quantity' => 15, 'unit' => 'g'],
                ['material' => 'Bawang Putih', 'quantity' => 5, 'unit' => 'g'],
                ['material' => 'Tomat', 'quantity' => 20, 'unit' => 'g'],
                ['material' => 'Gula Pasir', 'quantity' => 5, 'unit' => 'g'],
                ['material' => 'Garam', 'quantity' => 2, 'unit' => 'g'],
                // Telur
                ['material' => 'Telur Ayam', 'quantity' => 1, 'unit' => 'pcs'],
                // Nasi dan lalapan (porsi jumbo)
                ['material' => 'Beras', 'quantity' => 120, 'unit' => 'g'],
                ['material' => 'Timun', 'quantity' => 30, 'unit' => 'g'],
                ['material' => 'Daun Kemangi', 'quantity' => 8, 'unit' => 'g'],
            ],

            'Es Teh Manis' => [
                ['material' => 'Teh Celup', 'quantity' => 1, 'unit' => 'pcs'],
                ['material' => 'Gula Pasir (Minuman)', 'quantity' => 15, 'unit' => 'g'],
                ['material' => 'Es Batu', 'quantity' => 3, 'unit' => 'pcs'],
            ],

            'Es Teh Tawar' => [
                ['material' => 'Teh Celup', 'quantity' => 1, 'unit' => 'pcs'],
                ['material' => 'Es Batu', 'quantity' => 3, 'unit' => 'pcs'],
            ],

            'Teh Anget' => [
                ['material' => 'Teh Celup', 'quantity' => 1, 'unit' => 'pcs'],
                ['material' => 'Gula Pasir (Minuman)', 'quantity' => 10, 'unit' => 'g'],
            ],

            'Nasi Putih' => [
                ['material' => 'Beras', 'quantity' => 80, 'unit' => 'g'],
            ],

            'Telur Ceplok' => [
                ['material' => 'Telur Ayam', 'quantity' => 1, 'unit' => 'pcs'],
                ['material' => 'Minyak Goreng', 'quantity' => 10, 'unit' => 'ml'],
                ['material' => 'Garam', 'quantity' => 0.5, 'unit' => 'g'],
            ],

            'Lalapan' => [
                ['material' => 'Timun', 'quantity' => 30, 'unit' => 'g'],
                ['material' => 'Tomat', 'quantity' => 20, 'unit' => 'g'],
                ['material' => 'Daun Kemangi', 'quantity' => 10, 'unit' => 'g'],
            ],
        ];

        // Create recipes
        foreach ($recipes as $productName => $productRecipes) {
            if (!isset($products[$productName])) {
                continue; // Skip if product doesn't exist
            }

            $product = $products[$productName];

            foreach ($productRecipes as $recipe) {
                if (!isset($materials[$recipe['material']])) {
                    continue; // Skip if material doesn't exist
                }

                $material = $materials[$recipe['material']];

                ProductRecipe::create([
                    'produk_id' => $product->id,
                    'raw_material_id' => $material->id,
                    'quantity' => $recipe['quantity'],
                    'unit' => $recipe['unit'],
                ]);
            }
        }
    }
}
