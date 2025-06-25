<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'minimum_stock',
        'unit',
        'description'
    ];

    protected $casts = [
        'stock' => 'decimal:2',
        'price' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
    ];

    public function logs()
    {
        return $this->hasMany(RawMaterialLog::class);
    }

    public function isLowStock()
    {
        return $this->stock <= $this->minimum_stock;
    }
} 