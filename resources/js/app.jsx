import './bootstrap';
import { createRoot } from 'react-dom/client';
import WeatherApp from './components/WeatherApp';

const container = document.getElementById('app');
const root = createRoot(container);
root.render(<WeatherApp />);