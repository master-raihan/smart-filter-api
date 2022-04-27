<?php

namespace App\Http\Controllers;

use App\Contracts\Services\AuthContract;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthContract $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->authService->login($request);
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function me(): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->authService->me();
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function refresh(): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->authService->refresh();
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->authService->logout();
        return response()->json($serviceResponse, $serviceResponse['status']);
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $serviceResponse = $this->authService->register($request);
        return response()->json($serviceResponse, $serviceResponse['status']);
    }
}
