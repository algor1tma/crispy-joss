<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\UnitConverter;

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
        'stock' => 'decimal:3',
        'price' => 'decimal:2',
        'minimum_stock' => 'decimal:3',
    ];

    // Available units
    const AVAILABLE_UNITS = ['g', 'kg', 'ml', 'l', 'pcs'];

    public function logs()
    {
        return $this->hasMany(RawMaterialLog::class);
    }

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class);
    }

    public function isLowStock()
    {
        return $this->stock <= $this->minimum_stock;
    }

    /**
     * Get stock in different unit
     */
    public function getStockInUnit($targetUnit)
    {
        try {
            return UnitConverter::convert($this->stock, $this->unit, $targetUnit);
        } catch (\InvalidArgumentException $e) {
            return null; // Units not compatible
        }
    }

    /**
     * Check if stock is sufficient for required amount in any unit
     */
    public function hasSufficientStock($requiredAmount, $requiredUnit)
    {
        try {
            $requiredInCurrentUnit = UnitConverter::convert($requiredAmount, $requiredUnit, $this->unit);
            return $this->stock >= $requiredInCurrentUnit;
        } catch (\InvalidArgumentException $e) {
            return false; // Units not compatible
        }
    }

    /**
     * Reduce stock by amount in specified unit
     */
    public function reduceStock($amount, $unit, $notes = null)
    {
        try {
            $amountInCurrentUnit = UnitConverter::convert($amount, $unit, $this->unit);

            if ($this->stock < $amountInCurrentUnit) {
                throw new \Exception("Insufficient stock. Available: {$this->stock} {$this->unit}, Required: {$amountInCurrentUnit} {$this->unit}");
            }

            $this->stock -= $amountInCurrentUnit;
            $this->save();

            // Log the reduction
            RawMaterialLog::create([
                'raw_material_id' => $this->id,
                'user_id' => auth()->id(),
                'type' => 'production',
                'quantity' => -$amountInCurrentUnit,
                'notes' => $notes ?? "Stock reduced for production: {$amount} {$unit}"
            ]);

            return true;
        } catch (\InvalidArgumentException $e) {
            throw new \Exception("Cannot convert units: " . $e->getMessage());
        }
    }

    /**
     * Add stock by amount in specified unit
     */
    public function addStock($amount, $unit, $price = null, $notes = null)
    {
        try {
            $amountInCurrentUnit = UnitConverter::convert($amount, $unit, $this->unit);

            $this->stock += $amountInCurrentUnit;
            $this->save();

            // Log the addition
            RawMaterialLog::create([
                'raw_material_id' => $this->id,
                'user_id' => auth()->id(),
                'type' => 'in',
                'quantity' => $amountInCurrentUnit,
                'price' => $price,
                'subtotal' => $price ? $price * $amount : 0,
                'notes' => $notes ?? "Stock added: {$amount} {$unit}"
            ]);

            return true;
        } catch (\InvalidArgumentException $e) {
            throw new \Exception("Cannot convert units: " . $e->getMessage());
        }
    }

    /**
     * Get formatted stock with unit
     */
    public function getFormattedStock()
    {
        return UnitConverter::formatValue($this->stock, $this->unit);
    }

    /**
     * Get unit category
     */
    public function getUnitCategory()
    {
        return UnitConverter::getUnitCategory($this->unit);
    }

    /**
     * Get compatible units for this material
     */
    public function getCompatibleUnits()
    {
        $category = $this->getUnitCategory();
        return UnitConverter::getUnitsByCategory($category);
    }
}
