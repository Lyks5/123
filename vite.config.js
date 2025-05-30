import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/product-page.js',
                'resources/js/components/notification.js',
                'resources/js/wishlist.js',
                'resources/js/shop.js'
            ],
            refresh: true
        }),
        react(),
    ],
    resolve: {
        alias: {
            '@': '/resources/js'
        }
    }
});
