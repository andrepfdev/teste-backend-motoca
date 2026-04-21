<?php

namespace App\Services;

use App\Models\Lead;
use App\Http\Resources\LeadResource;

class LeadsService
{
    public function getAllLeads()
    {
        return LeadResource::collection(Lead::query()->simplePaginate(10));
    }

    public function getLeadById($id)
    {
        $lead = Lead::findOrFail($id);
        return new LeadResource($lead);
    }

    public function createLead($data)
    {
        $lead = Lead::create($data);
        return new LeadResource($lead);
    }

    public function updateLead($id, $data)
    {
        $lead = Lead::findOrFail($id);
        $lead->update($data);
        return new LeadResource($lead);
    }

    public function deleteLead($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();
        return response()->json(['message' => 'Lead deleted successfully']);
    }
}
