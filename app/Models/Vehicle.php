<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;

    protected $fillable = [
        'type',
        'brand',
        'model',
        'year',
        'price',
        'color',
        'mileage',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Relacionamento com Lead - um veículo pode ter muitos leads
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

}
