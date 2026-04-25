import './bootstrap';

import Alpine from 'alpinejs';
import L from 'leaflet';

window.Alpine = Alpine;
window.L = L;
window.dispatchEvent(new Event('leaflet:ready'));

Alpine.start();
