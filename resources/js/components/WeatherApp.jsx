import React, { useState } from 'react';
import CitySelector from './CitySelector';
import WeatherDisplay from './WeatherDisplay';

const WeatherApp = () => {
    const [selectedCity, setSelectedCity] = useState('');
    const [weatherData, setWeatherData] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const handleCityChange = async (city) => {
        if (!city) {
            setSelectedCity('');
            setWeatherData(null);
            setError(null);
            return;
        }

        setSelectedCity(city);
        setLoading(true);
        setError(null);

        try {
            const response = await window.axios.get('/api/forecast', {
                params: { city }
            });
            setWeatherData(response.data);
        } catch (err) {
            setError(err.response?.data?.error || 'Failed to fetch weather data');
            setWeatherData(null);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen bg-gray-100 py-8">
            <div className="max-w-4xl mx-auto px-4">
                <h1 className="text-3xl font-bold text-center mb-8 text-gray-800">
                    5-Day Weather Forecast
                </h1>
                
                <CitySelector 
                    cities={window.cities}
                    selectedCity={selectedCity}
                    onCityChange={handleCityChange}
                />

                {error && (
                    <div className="mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {error}
                    </div>
                )}

                {loading && (
                    <div className="mt-6 text-center">
                        <div className="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <p className="mt-2 text-gray-600">Loading weather data...</p>
                    </div>
                )}

                {weatherData && !loading && (
                    <WeatherDisplay data={weatherData} />
                )}
            </div>
        </div>
    );
};

export default WeatherApp;