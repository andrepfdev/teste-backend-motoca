<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Http\Resources\VehicleResource;

class VehiclesService
{
    public function getAllVehicles()
    {
        return VehicleResource::collection(Vehicle::query()->paginate(10));
    }

    public function getVehicleById($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return new VehicleResource($vehicle);
    }

    public function createVehicle($data)
    {
        $vehicle = Vehicle::create($data);
        return new VehicleResource($vehicle);
    }

    public function updateVehicle($id, $data)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($data);
        return new VehicleResource($vehicle);
    }

    public function deleteVehicle($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();
        return response()->json(['message' => 'Vehicle deleted successfully']);
    }

}
