<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRecipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'produk_id',
        'raw_material_id',
        'quantity',
        'unit',
        'notes'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    // Helper untuk konversi unit
    public function convertToBaseUnit($value, $fromUnit, $toUnit)
    {
        $conversions = [
            'g' => 1,
            'kg' => 1000,
            'mg' => 0.001,
            'ml' => 1,
            'l' => 1000,
        ];

        if (isset($conversions[$fromUnit]) && isset($conversions[$toUnit])) {
            return $value * ($conversions[$fromUnit] / $conversions[$toUnit]);
        }

        return $value; // Return as is if conversion not defined
    }
} 