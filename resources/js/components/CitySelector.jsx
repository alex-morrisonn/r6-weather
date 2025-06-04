import React from 'react';

const CitySelector = ({ cities, selectedCity, onCityChange }) => {
    return (
        <div className="max-w-md mx-auto">
            <label htmlFor="city-select" className="block text-sm font-medium text-gray-700 mb-2">
                Select a City
            </label>
            <select
                id="city-select"
                value={selectedCity}
                onChange={(e) => onCityChange(e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
                <option value="">Choose a city...</option>
                {cities.map((city) => (
                    <option key={city} value={city}>
                        {city}
                    </option>
                ))}
            </select>
        </div>
    );
};

export default CitySelector;