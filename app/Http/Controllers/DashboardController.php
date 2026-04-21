<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->dashboardService->getSummary()]);
    }
}
