<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\LeadsService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;

class LeadController extends Controller
{
    public function __construct(protected LeadsService $leadsService) {}

    /**
     * Lista todos os leads, com paginação de 10 itens por página.
     */
    public function index()
    {
        return $this->leadsService->getAllLeads();
    }

    /**
     * Salva um novo lead no banco de dados. Os dados do lead são validados usando o StoreLeadRequest.
     */
    public function store(StoreLeadRequest $request): JsonResponse
    {
        $data = $request->validated();
        return $this->leadsService->createLead($data)->response()->setStatusCode(201);
    }
    
    /**
     * Exibe os detalhes de um lead específico, identificado pelo seu ID, se falhar retorna 404.
     */
    public function show(Lead $lead)
    {
        return $this->leadsService->getLeadById($lead->id);
    }

    /**
     * Atualiza os dados de um lead existente, identificado pelo seu ID.
     */
    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $data = $request->validated();
        return $this->leadsService->updateLead($lead->id, $data);
    }

    /**
     * Remove um lead do banco de dados, identificado pelo seu ID.
     */
    public function destroy(Lead $lead): JsonResponse
    {
        return $this->leadsService->deleteLead($lead->id);
    }
}
