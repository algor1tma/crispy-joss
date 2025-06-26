<?php

namespace Database\Seeders;

use App\Models\RawMaterial;
use Illuminate\Database\Seeder;

class RawMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rawMaterials = [
            // Protein
            [
                'name' => 'Ayam Fillet',
                'stock' => 5000,
                'unit' => 'g',
                'price' => 35,
                'minimum_stock' => 500,
                'description' => 'Daging ayam fillet segar untuk ayam geprek'
            ],

            // Tepung dan Bumbu Kering
            [
                'name' => 'Tepung Terigu',
                'stock' => 2000,
                'unit' => 'g',
                'price' => 10,
                'minimum_stock' => 200,
                'description' => 'Tepung terigu untuk coating ayam'
            ],
            [
                'name' => 'Tepung Maizena',
                'stock' => 1000,
                'unit' => 'g',
                'price' => 12,
                'minimum_stock' => 100,
                'description' => 'Tepung maizena untuk tekstur crispy'
            ],
            [
                'name' => 'Garam',
                'stock' => 500,
                'unit' => 'g',
                'price' => 5,
                'minimum_stock' => 50,
                'description' => 'Garam dapur untuk bumbu'
            ],
            [
                'name' => 'Merica Bubuk',
                'stock' => 200,
                'unit' => 'g',
                'price' => 25,
                'minimum_stock' => 20,
                'description' => 'Merica bubuk untuk bumbu'
            ],
            [
                'name' => 'Penyedap Rasa',
                'stock' => 300,
                'unit' => 'g',
                'price' => 15,
                'minimum_stock' => 30,
                'description' => 'Penyedap rasa ayam'
            ],

            // Bumbu Basah
            [
                'name' => 'Bawang Putih',
                'stock' => 500,
                'unit' => 'g',
                'price' => 30,
                'minimum_stock' => 50,
                'description' => 'Bawang putih segar'
            ],
            [
                'name' => 'Jahe',
                'stock' => 300,
                'unit' => 'g',
                'price' => 20,
                'minimum_stock' => 30,
                'description' => 'Jahe segar untuk bumbu'
            ],

            // Cabai dan Sambal
            [
                'name' => 'Cabai Merah Keriting',
                'stock' => 1000,
                'unit' => 'g',
                'price' => 25,
                'minimum_stock' => 100,
                'description' => 'Cabai merah untuk sambal'
            ],
            [
                'name' => 'Cabai Rawit Merah',
                'stock' => 500,
                'unit' => 'g',
                'price' => 40,
                'minimum_stock' => 50,
                'description' => 'Cabai rawit untuk sambal pedas'
            ],
            [
                'name' => 'Cabai Hijau Besar',
                'stock' => 800,
                'unit' => 'g',
                'price' => 20,
                'minimum_stock' => 80,
                'description' => 'Cabai hijau untuk sambal ijo'
            ],
            [
                'name' => 'Cabai Rawit Hijau',
                'stock' => 400,
                'unit' => 'g',
                'price' => 35,
                'minimum_stock' => 40,
                'description' => 'Cabai rawit hijau untuk sambal ijo pedas'
            ],

            // Sayuran
            [
                'name' => 'Tomat',
                'stock' => 1000,
                'unit' => 'g',
                'price' => 15,
                'minimum_stock' => 100,
                'description' => 'Tomat segar untuk sambal'
            ],
            [
                'name' => 'Bawang Merah',
                'stock' => 800,
                'unit' => 'g',
                'price' => 25,
                'minimum_stock' => 80,
                'description' => 'Bawang merah untuk bumbu sambal'
            ],
            [
                'name' => 'Daun Kemangi',
                'stock' => 200,
                'unit' => 'g',
                'price' => 10,
                'minimum_stock' => 20,
                'description' => 'Daun kemangi segar untuk pelengkap'
            ],
            [
                'name' => 'Timun',
                'stock' => 1000,
                'unit' => 'g',
                'price' => 8,
                'minimum_stock' => 100,
                'description' => 'Timun untuk lalapan'
            ],

            // Bumbu Pelengkap
            [
                'name' => 'Gula Pasir',
                'stock' => 1000,
                'unit' => 'g',
                'price' => 12,
                'minimum_stock' => 100,
                'description' => 'Gula pasir untuk sambal'
            ],
            [
                'name' => 'Kecap Manis',
                'stock' => 2000,
                'unit' => 'ml',
                'price' => 8,
                'minimum_stock' => 200,
                'description' => 'Kecap manis untuk sambal kecap'
            ],
            [
                'name' => 'Terasi',
                'stock' => 100,
                'unit' => 'g',
                'price' => 50,
                'minimum_stock' => 10,
                'description' => 'Terasi untuk sambal terasi'
            ],
            [
                'name' => 'Asam Jawa',
                'stock' => 200,
                'unit' => 'g',
                'price' => 15,
                'minimum_stock' => 20,
                'description' => 'Asam jawa untuk sambal asam'
            ],

            // Minyak dan Lemak
            [
                'name' => 'Minyak Goreng',
                'stock' => 5000,
                'unit' => 'ml',
                'price' => 15,
                'minimum_stock' => 500,
                'description' => 'Minyak goreng untuk menggoreng'
            ],

            // Nasi dan Karbohidrat
            [
                'name' => 'Beras',
                'stock' => 5000,
                'unit' => 'g',
                'price' => 12,
                'minimum_stock' => 500,
                'description' => 'Beras untuk nasi putih'
            ],

            // Telur
            [
                'name' => 'Telur Ayam',
                'stock' => 50,
                'unit' => 'pcs',
                'price' => 2500,
                'minimum_stock' => 5,
                'description' => 'Telur ayam untuk telur ceplok/dadar'
            ],

            // Minuman
            [
                'name' => 'Teh Celup',
                'stock' => 100,
                'unit' => 'pcs',
                'price' => 500,
                'minimum_stock' => 10,
                'description' => 'Teh celup untuk es teh'
            ],
            [
                'name' => 'Gula Pasir (Minuman)',
                'stock' => 2000,
                'unit' => 'g',
                'price' => 12,
                'minimum_stock' => 200,
                'description' => 'Gula untuk minuman'
            ],
            [
                'name' => 'Es Batu',
                'stock' => 100,
                'unit' => 'pcs',
                'price' => 100,
                'minimum_stock' => 10,
                'description' => 'Es batu untuk minuman dingin'
            ]
        ];

        foreach ($rawMaterials as $material) {
            RawMaterial::create($material);
        }
    }
}
