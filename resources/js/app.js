import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

// Register Alpine plugins
Alpine.plugin(collapse);

// Make Alpine available globally for Blade x-data attributes
window.Alpine = Alpine;

// Start Alpine
Alpine.start();
