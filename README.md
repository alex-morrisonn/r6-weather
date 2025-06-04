# R6 Weather App

A Laravel + React application that displays 5-day weather forecasts for three Queensland cities (Brisbane, Gold Coast, Sunshine Coast) in both a browser UI and a console command.

---

## Features

- **Web UI (React + Vite)**  
  - Dropdown to select one of three cities  
  - 5-day forecast cards showing Avg, Max, Min temperatures and conditions  
  - Error message if the API call fails

- **Console Command**  
  - `php artisan forecast [City1] [City2] [...]`  
  - Interactive mode when run without arguments  
  - Validates city names and prints a table of 5-day Avg/Max/Min temperatures  
  - Handles invalid city names gracefully

---

## Prerequisites

- PHP ≥ 8.0  
- Composer  
- Node.js ≥ 16.x & npm  
- Git (to clone the repository)

---

## Installation

1. **Clone the repo**  
   ```bash
   git clone https://github.com/alex-morrisonn/r6-weather.git
   cd r6-weather
   ```

2. **Install PHP dependencies**  
   ```bash
   composer install
   ```

3. **Install Node dependencies**  
   ```bash
   npm install
   ```

4. **Copy environment file**  
   ```bash
   copy .env.example .env
   ```
   > **Note for Windows (PowerShell):**  
   > ```powershell
   > Copy-Item .env.example .env
   > ```

5. **Open `.env` and set your WeatherBit API key**  
   - In the newly created `.env`, find the line:  
     ```dotenv
     WEATHER_API_KEY=
     ```  
   - Replace it with your own key from https://www.weatherbit.io/account/create, for example:  
     ```dotenv
     WEATHER_API_KEY=your_real_weatherbit_key_here
     ```  
   - Save the file.

6. **Generate application key**  
   ```bash
   php artisan key:generate
   ```

7. **Create (or empty) SQLite database**  
   ```powershell
   type nul > database\database.sqlite
   ```
   > Or create the file manually in File Explorer under `database/database.sqlite`.

8. **Run migrations**  
   ```bash
   php artisan migrate
   ```

---

## Build Frontend Assets

- **Production**  
  ```bash
  npm run build
  ```

---

## Running the App

### 1. Web UI

1. Start Laravel server:  
   ```bash
   php artisan serve
   ```
2. Make sure the frontend assets are built (run `npm run dev` if you need hot‑reload).  
3. Open your browser at:  
   ```
   http://127.0.0.1:8000
   ```
4. Use the dropdown to select Brisbane, Gold Coast, or Sunshine Coast. The 5-day forecast cards will appear.

### 2. Console Command

- **Interactive mode:**  
  ```bash
  php artisan forecast
  ```
  - Prompts for city names one at a time.  
  - Press Enter (with no input) to finish.

- **Single or Multiple cities (no prompt):**  
  ```bash
  php artisan forecast Brisbane
  php artisan forecast "Gold Coast" "Sunshine Coast"
  ```
- **Invalid city names** will display an error, e.g., “Invalid city: Perth.”

---

## Available Cities

- Brisbane  
- Gold Coast  
- Sunshine Coast

Any other input is considered invalid.

---

## Basic Structure

- **app/Services/WeatherService.php**  
  - Fetches and caches 5-day forecast from a third‑party API.

- **app/Console/Commands/Forecast.php**  
  - Implements `php artisan forecast`, validates cities, and prints a table.

- **routes/web.php**  
  - Serves the React-based Blade view.

- **routes/api.php**  
  - API route: `GET /api/forecast?city={CityName}`

- **resources/js/components/**  
  - `CitySelector.jsx`, `WeatherApp.jsx`, `WeatherDisplay.jsx` (React components)

- **resources/views/weather/index.blade.php**  
  - Blade template with `<div id="root"></div>` and Vite directives.

---

## Design Notes

- **React + Vite** for a fast, reactive frontend.  
- **WeatherService** abstracts all Weather API calls and caching.  
- **Console command** shares the same service to avoid duplication.  
- **Simple caching** (10 minutes) to reduce API requests.

---

## Troubleshooting

- **API Key Errors**:  
  - Make sure you copied `.env.example` to `.env` and filled in `WEATHER_API_KEY` with your own key.  
  - Verify the `WEATHER_API_URL` if you switch providers.

- **Vite Build Errors**:  
  - Ensure `vite.config.js` includes `@vitejs/plugin-react`.  
  - Confirm React components are correctly imported (using `.jsx` extensions).

- **Cache Issues**:  
  ```bash
  php artisan cache:clear
  ```

- **Database Errors**:  
  - This app uses SQLite by default. If you see DB errors, confirm `database/database.sqlite` exists.

---

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
