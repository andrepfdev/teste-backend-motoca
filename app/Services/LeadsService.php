<?php

namespace App\Services;

use App\Models\Lead;
use App\Http\Resources\LeadResource;

class LeadsService
{
    public function getAllLeads()
    {
        return LeadResource::collection(Lead::query()->paginate(10));
    }

    public function getLeadsByVehicle(int $vehicleId)
    {
        return LeadResource::collection(
            Lead::query()->where('vehicle_id', $vehicleId)->paginate(10)
        );
    }

    public function getLeadById(int $id)
    {
        $lead = Lead::findOrFail($id);

        return new LeadResource($lead);
    }

    public function createLead(array $data)
    {
        $lead = Lead::create($data);

        return new LeadResource($lead);
    }

    public function updateLead(int $id, array $data)
    {
        $lead = Lead::findOrFail($id);
        $lead->update($data);

        return new LeadResource($lead);
    }

    public function deleteLead(int $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return response()->json(['message' => 'Lead deleted successfully']);
    }
}
