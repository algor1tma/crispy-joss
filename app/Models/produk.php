<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produks';

    protected $fillable = [
        'nama_produk',
        'harga_produk',
        'stok_produk',
        'foto',
        'deskripsi_produk',
    ];

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class, 'produk_id');
    }

    public function hasCompleteRecipe()
    {
        return $this->recipes()->exists();
    }
}
