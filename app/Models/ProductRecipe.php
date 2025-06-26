<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\UnitConverter;

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

    protected $casts = [
        'quantity' => 'decimal:3'
    ];

    // Available units for recipes
    const AVAILABLE_UNITS = ['g', 'kg', 'ml', 'l', 'pcs'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    /**
     * Get quantity in material's unit
     */
    public function getQuantityInMaterialUnit()
    {
        try {
            return UnitConverter::convert(
                $this->quantity,
                $this->unit,
                $this->rawMaterial->unit
            );
        } catch (\InvalidArgumentException $e) {
            throw new \Exception("Cannot convert recipe unit {$this->unit} to material unit {$this->rawMaterial->unit}");
        }
    }

    /**
     * Check if material has sufficient stock for this recipe
     */
    public function hasSufficientStock($multiplier = 1)
    {
        return $this->rawMaterial->hasSufficientStock(
            $this->quantity * $multiplier,
            $this->unit
        );
    }

    /**
     * Get required amount in material's unit
     */
    public function getRequiredAmountInMaterialUnit($multiplier = 1)
    {
        try {
            return UnitConverter::convert(
                $this->quantity * $multiplier,
                $this->unit,
                $this->rawMaterial->unit
            );
        } catch (\InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * Consume materials for production
     */
    public function consumeForProduction($multiplier = 1, $notes = null)
    {
        $requiredAmount = $this->quantity * $multiplier;
        $productName = $this->produk->nama_produk ?? 'Unknown Product';
        $defaultNotes = "Production of {$productName} - Recipe item: {$this->rawMaterial->name}";

        return $this->rawMaterial->reduceStock(
            $requiredAmount,
            $this->unit,
            $notes ?? $defaultNotes
        );
    }

    /**
     * Get formatted quantity with unit
     */
    public function getFormattedQuantity()
    {
        return UnitConverter::formatValue($this->quantity, $this->unit);
    }

    /**
     * Validate if recipe unit is compatible with material unit
     */
    public function validateUnitCompatibility()
    {
        return UnitConverter::areUnitsCompatible($this->unit, $this->rawMaterial->unit);
    }

    /**
     * Get available units for this recipe based on material
     */
    public function getAvailableUnits()
    {
        $materialCategory = $this->rawMaterial->getUnitCategory();
        return UnitConverter::getUnitsByCategory($materialCategory);
    }

    /**
     * Boot method to add model events
     */
    protected static function boot()
    {
        parent::boot();

        // Validate unit compatibility before saving
        static::saving(function ($recipe) {
            if ($recipe->rawMaterial && !$recipe->validateUnitCompatibility()) {
                throw new \Exception(
                    "Recipe unit '{$recipe->unit}' is not compatible with material unit '{$recipe->rawMaterial->unit}'"
                );
            }
        });
    }
}
