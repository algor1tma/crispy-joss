<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Ayam Geprek Variants
            [
                'nama_produk' => 'Ayam Geprek Original',
                'harga_produk' => 18000,
                'stok_produk' => 20,
                'foto' => 'ayam-geprek-original.jpg',
                'deskripsi_produk' => 'Ayam crispy yang digeprek dengan sambal merah pedas, disajikan dengan nasi putih dan lalapan'
            ],
            [
                'nama_produk' => 'Ayam Geprek Sambal Ijo',
                'harga_produk' => 19000,
                'stok_produk' => 15,
                'foto' => 'ayam-geprek-sambal-ijo.jpg',
                'deskripsi_produk' => 'Ayam crispy dengan sambal hijau segar dan pedas, disajikan dengan nasi putih dan lalapan'
            ],
            [
                'nama_produk' => 'Ayam Geprek Sambal Terasi',
                'harga_produk' => 20000,
                'stok_produk' => 12,
                'foto' => 'ayam-geprek-terasi.jpg',
                'deskripsi_produk' => 'Ayam crispy dengan sambal terasi yang gurih dan pedas, disajikan dengan nasi putih dan lalapan'
            ],
            [
                'nama_produk' => 'Ayam Geprek Sambal Kecap',
                'harga_produk' => 19500,
                'stok_produk' => 18,
                'foto' => 'ayam-geprek-kecap.jpg',
                'deskripsi_produk' => 'Ayam crispy dengan sambal kecap manis pedas, disajikan dengan nasi putih dan lalapan'
            ],
            [
                'nama_produk' => 'Ayam Geprek Level 5',
                'harga_produk' => 21000,
                'stok_produk' => 10,
                'foto' => 'ayam-geprek-level5.jpg',
                'deskripsi_produk' => 'Ayam crispy dengan sambal super pedas level 5, untuk yang suka tantangan'
            ],
            [
                'nama_produk' => 'Ayam Geprek Keju',
                'harga_produk' => 25000,
                'stok_produk' => 8,
                'foto' => 'ayam-geprek-keju.jpg',
                'deskripsi_produk' => 'Ayam crispy dengan sambal dan keju mozarella yang meleleh'
            ],

            // Paket Komplit
            [
                'nama_produk' => 'Paket Geprek + Telur',
                'harga_produk' => 22000,
                'stok_produk' => 15,
                'foto' => 'paket-geprek-telur.jpg',
                'deskripsi_produk' => 'Ayam geprek original dengan telur ceplok, nasi putih dan lalapan'
            ],
            [
                'nama_produk' => 'Paket Geprek Jumbo',
                'harga_produk' => 28000,
                'stok_produk' => 12,
                'foto' => 'paket-geprek-jumbo.jpg',
                'deskripsi_produk' => 'Ayam geprek dengan porsi jumbo, telur ceplok, nasi putih dan lalapan lengkap'
            ],

            // Minuman
            [
                'nama_produk' => 'Es Teh Manis',
                'harga_produk' => 5000,
                'stok_produk' => 50,
                'foto' => 'es-teh-manis.jpg',
                'deskripsi_produk' => 'Es teh manis segar untuk menemani makan'
            ],
            [
                'nama_produk' => 'Es Teh Tawar',
                'harga_produk' => 4000,
                'stok_produk' => 50,
                'foto' => 'es-teh-tawar.jpg',
                'deskripsi_produk' => 'Es teh tawar segar tanpa gula'
            ],
            [
                'nama_produk' => 'Teh Anget',
                'harga_produk' => 4000,
                'stok_produk' => 30,
                'foto' => 'teh-anget.jpg',
                'deskripsi_produk' => 'Teh hangat manis untuk cuaca dingin'
            ],

            // Tambahan
            [
                'nama_produk' => 'Nasi Putih',
                'harga_produk' => 5000,
                'stok_produk' => 100,
                'foto' => 'nasi-putih.jpg',
                'deskripsi_produk' => 'Nasi putih hangat'
            ],
            [
                'nama_produk' => 'Telur Ceplok',
                'harga_produk' => 4000,
                'stok_produk' => 25,
                'foto' => 'telur-ceplok.jpg',
                'deskripsi_produk' => 'Telur ceplok dengan kuning setengah matang'
            ],
            [
                'nama_produk' => 'Lalapan',
                'harga_produk' => 3000,
                'stok_produk' => 30,
                'foto' => 'lalapan.jpg',
                'deskripsi_produk' => 'Lalapan segar timun, tomat, dan kemangi'
            ]
        ];

        foreach ($products as $product) {
            Produk::create($product);
        }
    }
}
