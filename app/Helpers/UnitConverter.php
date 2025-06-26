<?php

namespace App\Helpers;

class UnitConverter
{
    // Base units untuk konversi
    const BASE_UNITS = [
        'weight' => 'g',  // gram sebagai base unit untuk berat
        'volume' => 'ml', // mililiter sebagai base unit untuk volume
        'quantity' => 'pcs' // pieces sebagai base unit untuk jumlah
    ];

    // Mapping unit ke kategori
    const UNIT_CATEGORIES = [
        'g' => 'weight',
        'kg' => 'weight',
        'ml' => 'volume',
        'l' => 'volume',
        'pcs' => 'quantity'
    ];

    // Konversi rate ke base unit
    const CONVERSION_RATES = [
        'g' => 1,       // 1 gram = 1 gram (base)
        'kg' => 1000,   // 1 kg = 1000 gram
        'ml' => 1,      // 1 ml = 1 ml (base)
        'l' => 1000,    // 1 liter = 1000 ml
        'pcs' => 1      // 1 pcs = 1 pcs (base)
    ];

    const UNIT_LABELS = [
        'g' => 'Gram',
        'kg' => 'Kilogram',
        'ml' => 'Mililiter',
        'l' => 'Liter',
        'pcs' => 'Pieces'
    ];

    /**
     * Convert value from one unit to another
     */
    public static function convert($value, $fromUnit, $toUnit)
    {
        // Check if units are in the same category
        if (!self::areUnitsCompatible($fromUnit, $toUnit)) {
            throw new \InvalidArgumentException("Cannot convert between {$fromUnit} and {$toUnit} - different categories");
        }

        // If same unit, return as is
        if ($fromUnit === $toUnit) {
            return $value;
        }

        // Convert to base unit first, then to target unit
        $baseValue = $value * self::CONVERSION_RATES[$fromUnit];
        $convertedValue = $baseValue / self::CONVERSION_RATES[$toUnit];

        return round($convertedValue, 3); // Round to 3 decimal places
    }

    /**
     * Convert value to base unit
     */
    public static function toBaseUnit($value, $unit)
    {
        return $value * self::CONVERSION_RATES[$unit];
    }

    /**
     * Convert value from base unit
     */
    public static function fromBaseUnit($value, $unit)
    {
        return $value / self::CONVERSION_RATES[$unit];
    }

    /**
     * Check if two units are compatible (same category)
     */
    public static function areUnitsCompatible($unit1, $unit2)
    {
        if (!isset(self::UNIT_CATEGORIES[$unit1]) || !isset(self::UNIT_CATEGORIES[$unit2])) {
            return false;
        }

        return self::UNIT_CATEGORIES[$unit1] === self::UNIT_CATEGORIES[$unit2];
    }

    /**
     * Get unit category
     */
    public static function getUnitCategory($unit)
    {
        return self::UNIT_CATEGORIES[$unit] ?? null;
    }

    /**
     * Get base unit for a category
     */
    public static function getBaseUnit($category)
    {
        return self::BASE_UNITS[$category] ?? null;
    }

    /**
     * Get all available units
     */
    public static function getAllUnits()
    {
        return array_keys(self::CONVERSION_RATES);
    }

    /**
     * Get units by category
     */
    public static function getUnitsByCategory($category)
    {
        $units = [];
        foreach (self::UNIT_CATEGORIES as $unit => $cat) {
            if ($cat === $category) {
                $units[] = $unit;
            }
        }
        return $units;
    }

    /**
     * Get unit label
     */
    public static function getUnitLabel($unit)
    {
        return self::UNIT_LABELS[$unit] ?? $unit;
    }

    /**
     * Format value with unit
     */
    public static function formatValue($value, $unit)
    {
        return number_format($value, 3) . ' ' . self::getUnitLabel($unit);
    }

    /**
     * Get recommended unit for display (convert small values to appropriate unit)
     */
    public static function getRecommendedDisplayUnit($value, $currentUnit)
    {
        $category = self::getUnitCategory($currentUnit);

        if ($category === 'weight') {
            // If value is >= 1000g, recommend kg
            if ($value >= 1000 && $currentUnit === 'g') {
                return 'kg';
            }
            // If value is < 1 kg, recommend g
            if ($value < 1 && $currentUnit === 'kg') {
                return 'g';
            }
        } elseif ($category === 'volume') {
            // If value is >= 1000ml, recommend l
            if ($value >= 1000 && $currentUnit === 'ml') {
                return 'l';
            }
            // If value is < 1 l, recommend ml
            if ($value < 1 && $currentUnit === 'l') {
                return 'ml';
            }
        }

        return $currentUnit;
    }
}
