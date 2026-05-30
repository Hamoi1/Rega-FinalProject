import './bootstrap';
import collapse from '@alpinejs/collapse';
import 'preline';
import { initFlowbite } from 'flowbite';
// Map Components
import maplibregl from 'maplibre-gl';
import 'maplibre-gl/dist/maplibre-gl.css';
import './components/map/index.js';

Alpine.plugin([
    collapse,
]);
window.HSStaticMethods.autoInit();
window.maplibregl = maplibregl;

window.toggleLoading = (loading) => {
    let loadingElement = document.getElementById('main-loading');
    if (loading) {
        loadingElement.classList.remove('hidden');
    } else {
        loadingElement.classList.add('hidden');
    }
}

document.addEventListener("livewire:navigated", () => {
    window.HSStaticMethods.autoInit();
    initFlowbite();
    toggleLoading(false);
});

document.addEventListener("livewire:navigate", () => {
    toggleLoading(true);
});

window.addEventListener('popstate', function (event) {
    event.preventDefault();
    toggleLoading(true);
    window.location.replace(window.location.href);
});

window.addEventListener('unload', function () { });
window.addEventListener('beforeunload', function () { });

Livewire.hook('element.init', ({ component, element }) => {
    window.HSStaticMethods.autoInit();
    initFlowbite();
    // console.clear();
});

Alpine.store('theme', {
    toggle() {
        if (localStorage.theme === 'dark') {
            localStorage.setItem('theme', 'light');
            document.documentElement.classList.remove('dark');
        } else {
            localStorage.setItem('theme', 'dark');
            document.documentElement.classList.add('dark');
        }
        this.changeMetaThemeColor();
    },
    get() {
        return localStorage.getItem('theme') !== null ? localStorage.getItem('theme') : null;
    },
    changeMetaThemeColor() {
        let metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (localStorage.theme === 'dark') {
            metaThemeColor.setAttribute('content', '#18181b'); // Dark mode color
        } else {
            metaThemeColor.setAttribute('content', '#ffffff'); // Light mode color
        }
    }
});
