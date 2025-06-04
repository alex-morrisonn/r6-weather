<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class WeatherController extends Controller
{
    private WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index()
    {
        return view('weather.index', [
            'cities' => $this->weatherService->getValidCities()
        ]);
    }

    public function forecast(Request $request): JsonResponse
    {
        $request->validate([
            'city' => 'required|string'
        ]);

        $city = $request->input('city');

        if (!$this->weatherService->isValidCity($city)) {
            return response()->json([
                'error' => 'Invalid city. Please select from: ' . implode(', ', $this->weatherService->getValidCities())
            ], 400);
        }

        try {
            $forecast = $this->weatherService->getFiveDayForecast($city);
            return response()->json($forecast);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch weather data: ' . $e->getMessage()
            ], 500);
        }
    }
}