<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $fillable = [
        'tax_percentage',
        'service_charge_percentage',
    ];

    protected function casts(): array
    {
        return [
            'tax_percentage' => 'integer',
            'service_charge_percentage' => 'integer',
        ];
    }

    /**
     * Always return the single settings row, creating it if missing.
     */
    public static function instance(): self
    {
        return self::firstOrCreate([], [
            'tax_percentage' => 0,
            'service_charge_percentage' => 0,
        ]);
    }
}
