import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.css',
                'resources/css/admin/products/form.css',
                'resources/js/admin/products/form-handler.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources'),
            '~': resolve(__dirname, 'node_modules'),
        }
    },
    optimizeDeps: {
        include: [
            '@splidejs/splide',
            'alpinejs'
        ]
    },
    build: {
        sourcemap: true,
        commonjsOptions: {
            include: [/node_modules/],
            transformMixedEsModules: true
        },
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: [
                        '@splidejs/splide',
                        'alpinejs'
                    ]
                }
            }
        }
    },
    server: {
        hmr: {
            overlay: true
        }
    }
});
