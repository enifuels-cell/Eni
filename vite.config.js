import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        VitePWA({
            registerType: 'autoUpdate',
            strategies: 'generateSW',
            injectRegister: false,
            workbox: {
                globPatterns: ['**/*.{js,css,html,ico,png,svg}'],
            },
            includeAssets: ['icons/icon-192x192.png', 'icons/icon-512x512.png', 'icons/icon-512x512-maskable.png', 'eni.png', 'offline.html'],
            manifest: undefined // will keep using your public/manifest.webmanifest if present
        }),
    ],
});
