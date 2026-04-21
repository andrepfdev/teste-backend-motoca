<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Services\VehiclesService;

class VehicleController extends Controller
{
    public function __construct(protected Vehicle $vehiclesService) {}

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

        return $this->vehiclesService->createVehicle($data)->response()->setStatusCode(201);
    }

    /**
     * Exibe os detalhes de um veículo específico, identificado pelo seu ID, se falhar retorna 404.
     */
    public function show(Vehicle $vehicle)
    {
        return $this->vehiclesService->getVehicleById($vehicle->id);
    }

    /**
     * Atualiza os dados de um veículo existente, identificado pelo seu ID.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $data = $request->validated(); // Usa Form Request 

        return $this->vehiclesService->updateVehicle($vehicle->id, $data);
    }

    /**
     * Remove um veículo do banco de dados, identificado pelo seu ID.
     */
    public function destroy(Vehicle $vehicle)
    {
        return $this->vehiclesService->deleteVehicle($vehicle->id);
    }
}
