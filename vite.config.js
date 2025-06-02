import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/admin/dashboard.css',
                'resources/js/admin/dashboard.js',
                'resources/css/admin/products/form.css',
                'resources/js/admin/products/edit-form.js',
                'resources/js/admin/products/eco-features.js'
            ],
            refresh: true,
        }),
    ],
});
