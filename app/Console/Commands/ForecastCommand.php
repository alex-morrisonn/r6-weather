<?php

namespace App\Console\Commands;

use App\Services\WeatherService;
use Illuminate\Console\Command;
use Exception;

class ForecastCommand extends Command
{
    protected $signature = 'forecast {cities?*}';
    protected $description = 'Show 5-day weather forecast for specified cities';

    private WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    public function handle(): int
    {
        $cities = $this->argument('cities');
        
        if (empty($cities)) {
            $cities = $this->promptForCities();
        }

        if (empty($cities)) {
            $this->error('No cities provided.');
            return 1;
        }

        $this->displayForecasts($cities);
        return 0;
    }

    private function promptForCities(): array
    {
        $this->info('Available cities: ' . implode(', ', $this->weatherService->getValidCities()));
        
        $cities = [];
        while (true) {
            $city = $this->ask('Enter a city name (or press Enter to finish)');
            
            if (empty($city)) {
                break;
            }
            
            if (!$this->weatherService->isValidCity($city)) {
                $this->error("Invalid city: {$city}. Please choose from: " . implode(', ', $this->weatherService->getValidCities()));
                continue;
            }
            
            $cities[] = $city;
        }
        
        return $cities;
    }

    private function displayForecasts(array $cities): void
    {
        $tableData = [];
        $headers = ['City', 'Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];

        foreach ($cities as $city) {
            try {
                if (!$this->weatherService->isValidCity($city)) {
                    $this->error("Invalid city: {$city}");
                    continue;
                }

                $data = $this->weatherService->getFiveDayForecast($city);
                $row = [$data['city']];
                
                foreach ($data['forecast'] as $day) {
                    $row[] = sprintf('Avg: %d, Max: %d, Low: %d', $day['avg'], $day['max'], $day['min']);
                }
                
                $tableData[] = $row;
                
            } catch (Exception $e) {
                $this->error("Error fetching forecast for {$city}: " . $e->getMessage());
            }
        }

        if (!empty($tableData)) {
            $this->table($headers, $tableData);
        }
    }
}