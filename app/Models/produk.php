<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    /**
     * Check if all materials are available for production
     */
    public function canProduce($quantity = 1)
    {
        if (!$this->hasCompleteRecipe()) {
            return false;
        }

        foreach ($this->recipes as $recipe) {
            if (!$recipe->hasSufficientStock($quantity)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get missing materials for production
     */
    public function getMissingMaterials($quantity = 1)
    {
        $missing = [];

        foreach ($this->recipes as $recipe) {
            if (!$recipe->hasSufficientStock($quantity)) {
                $required = $recipe->getRequiredAmountInMaterialUnit($quantity);
                $available = $recipe->rawMaterial->stock;
                $shortage = $required - $available;

                $missing[] = [
                    'material' => $recipe->rawMaterial,
                    'required' => $required,
                    'available' => $available,
                    'shortage' => $shortage,
                    'unit' => $recipe->rawMaterial->unit
                ];
            }
        }

        return $missing;
    }

    /**
     * Produce the product (consume materials and increase stock)
     */
    public function produce($quantity = 1, $notes = null)
    {
        if (!$this->canProduce($quantity)) {
            $missing = $this->getMissingMaterials($quantity);
            $missingList = collect($missing)->map(function($item) {
                return $item['material']->name . ' (shortage: ' . $item['shortage'] . ' ' . $item['unit'] . ')';
            })->join(', ');

            throw new \Exception("Cannot produce {$quantity} units. Missing materials: " . $missingList);
        }

        DB::beginTransaction();
        try {
            // Consume all materials
            foreach ($this->recipes as $recipe) {
                $recipe->consumeForProduction($quantity, $notes);
            }

            // Increase product stock
            $this->stok_produk += $quantity;
            $this->save();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Get production cost based on current material prices
     */
    public function getProductionCost($quantity = 1)
    {
        $cost = 0;

        foreach ($this->recipes as $recipe) {
            $requiredAmount = $recipe->getRequiredAmountInMaterialUnit($quantity);
            $materialCost = $requiredAmount * $recipe->rawMaterial->price;
            $cost += $materialCost;
        }

        return $cost;
    }

    /**
     * Get maximum producible quantity based on available materials
     */
    public function getMaxProducibleQuantity()
    {
        if (!$this->hasCompleteRecipe()) {
            return 0;
        }

        $maxQuantity = PHP_INT_MAX;

        foreach ($this->recipes as $recipe) {
            $availableInRecipeUnit = $recipe->rawMaterial->getStockInUnit($recipe->unit);
            if ($availableInRecipeUnit === null) {
                return 0; // Unit conversion not possible
            }

            $possibleQuantity = floor($availableInRecipeUnit / $recipe->quantity);
            $maxQuantity = min($maxQuantity, $possibleQuantity);
        }

        return $maxQuantity === PHP_INT_MAX ? 0 : $maxQuantity;
    }
}
