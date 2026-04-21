<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Services\VehiclesService;
use App\Http\Requests\StoreVehicleRequest;

class VehicleController extends Controller
{

    protected $vehiclesService;

    public function __construct(VehiclesService $vehiclesService)
    {
        $this->vehiclesService = $vehiclesService;
    }

    /**
     * Retorna uma lista paginada de veículos, com 10 itens por página.
     */
    public function index()
    {
        return $this->vehiclesService->getAllVehicles();
    }

    /**
     * Salva um novo veículo no banco de dados. Os dados do veículo são validados usando o StoreVehicleRequest. 
     */
    public function store(StoreVehicleRequest $request)
    {
        $data = $request->validated(); // Usando Form Request Validation para validar os dados

        return $this->vehiclesService->createVehicle($data)->response()->json(['message' => 'Vehicle created successfully'], 201);
    }

    /**
     * Exibe os detalhes de um veículo específico, identificado pelo seu ID, se falhar retorna 404.
     */
    public function show(Vehicle $vehicle)
    {
        return $this->vehiclesService->getVehicleById($vehicle->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $data = $request->validated();

        return $this->vehiclesService->updateVehicle($vehicle->id, $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        return $this->vehiclesService->deleteVehicle($vehicle->id);
    }
}
