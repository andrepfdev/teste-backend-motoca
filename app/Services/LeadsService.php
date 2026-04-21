<?php

namespace App\Services;

use App\Models\Lead;

class LeadsService
{
    public function getAllLeads()
    {
        return Lead::query()->with('vehicle')->simplePaginate(10);
    }

    public function getLeadById($id)
    {
        return Lead::query()->with('vehicle')->findOrFail($id);
    }

    public function createLead($data)
    {
        return Lead::create($data);
    }

    public function updateLead($id, $data)
    {
        $lead = Lead::findOrFail($id);
        $lead->update($data);
        return $lead;
    }

    public function deleteLead($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();
        return response()->json(['message' => 'Lead deleted successfully']);
    }
}
