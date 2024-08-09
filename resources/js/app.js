import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'

window.Alpine = Alpine.plugin(collapse);

Alpine.start();
