<?php

namespace App\Models;

use App\Enums\VehicleType;
use Database\Factories\VehicleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /** @use HasFactory<VehicleFactory> */
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
        'type' => VehicleType::class,
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
