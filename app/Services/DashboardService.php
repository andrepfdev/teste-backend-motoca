<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Vehicle;
use App\Http\Resources\VehicleResource;

class DashboardService
{
    /**
     * @return array{total_vehicles: int, total_leads: int, most_requested_vehicle: VehicleResource|null}
     */
    public function getSummary(): array
    {
        $mostRequested = Vehicle::withCount('leads')
            ->orderByDesc('leads_count')
            ->first();

        return [
            'total_vehicles' => Vehicle::count(),
            'total_leads' => Lead::count(),
            'most_requested_vehicle' => $mostRequested ? new VehicleResource($mostRequested) : null,
        ];
    }
}
