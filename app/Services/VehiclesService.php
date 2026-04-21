<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Http\Resources\VehicleResource;

class VehiclesService
{
    /**
     * @param array{type?: string, min_price?: numeric, max_price?: numeric} $filters
     */
    public function getAllVehicles(array $filters = [])
    {
        $query = Vehicle::query();

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (! empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        return VehicleResource::collection($query->paginate(10));
    }

    public function getVehicleById(int $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        return new VehicleResource($vehicle);
    }

    public function createVehicle(array $data)
    {
        $vehicle = Vehicle::create($data);

        return new VehicleResource($vehicle);
    }

    public function updateVehicle(int $id, array $data)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($data);

        return new VehicleResource($vehicle);
    }

    public function deleteVehicle(int $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return response()->json(['message' => 'Vehicle deleted successfully']);
    }
}
