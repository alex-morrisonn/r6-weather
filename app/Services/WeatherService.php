<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class WeatherService
{
    private $apiKey;
    private $apiUrl;
    
    public function __construct()
    {
        $this->apiKey = config('services.weather.api_key');
        $this->apiUrl = config('services.weather.api_url');
    }

    public function getFiveDayForecast(string $city): array
    {
        $cacheKey = "weather_forecast_{$city}";
        
        return Cache::remember($cacheKey, 600, function () use ($city) {
            try {
                $response = Http::timeout(10)->get("{$this->apiUrl}/forecast/daily", [
                    'city' => $city,
                    'country' => 'AU',
                    'key' => $this->apiKey,
                    'days' => 5,
                    'units' => 'M' // Metric units
                ]);

                if (!$response->successful()) {
                    throw new Exception("Weather API request failed: " . $response->status());
                }

                $data = $response->json();
                
                if (!isset($data['data'])) {
                    throw new Exception("Invalid response format from weather API");
                }

                return $this->formatForecastData($data);
            } catch (Exception $e) {
                throw new Exception("Failed to fetch weather data: " . $e->getMessage());
            }
        });
    }

    private function formatForecastData(array $data): array
    {
        $forecast = [];
        
        foreach ($data['data'] as $index => $dayData) {
            $forecast[] = [
                'date' => $dayData['datetime'],
                'day' => 'Day ' . ($index + 1),
                'avg' => round(($dayData['max_temp'] + $dayData['min_temp']) / 2),
                'max' => round($dayData['max_temp']),
                'min' => round($dayData['min_temp']),
                'description' => $dayData['weather']['description'] ?? 'Unknown'
            ];
        }

        return [
            'city' => $data['city_name'] ?? 'Unknown',
            'forecast' => $forecast
        ];
    }

    public function getValidCities(): array
    {
        return ['Brisbane', 'Gold Coast', 'Sunshine Coast'];
    }

    public function isValidCity(string $city): bool
    {
        return in_array($city, $this->getValidCities());
    }
}