import React from 'react';

const WeatherDisplay = ({ data }) => {
    if (!data || !data.forecast) {
        return null;
    }

    return (
        <div className="mt-8">
            <h2 className="text-2xl font-semibold text-center mb-6 text-gray-800">
                {data.city} - 5 Day Forecast
            </h2>
            
            <div className="grid grid-cols-1 md:grid-cols-5 gap-4">
                {data.forecast.map((day, index) => (
                    <div key={index} className="bg-white rounded-lg shadow-md p-4">
                        <h3 className="font-semibold text-lg text-gray-800 mb-2">
                            {day.day}
                        </h3>
                        <p className="text-sm text-gray-600 mb-3">
                            {new Date(day.date).toLocaleDateString()}
                        </p>
                        <div className="space-y-1 text-sm">
                            <p><span className="font-medium">Avg:</span> {day.avg}°C</p>
                            <p><span className="font-medium">Max:</span> {day.max}°C</p>
                            <p><span className="font-medium">Min:</span> {day.min}°C</p>
                        </div>
                        <p className="text-xs text-gray-500 mt-2 capitalize">
                            {day.description}
                        </p>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default WeatherDisplay;