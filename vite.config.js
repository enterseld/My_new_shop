import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        watch: {
            usePolling: true,
            interval: 300,
            ignored: [
                '**/node_modules/**',
                '**/vendor/**',
                '**/storage/**',
                '**/bootstrap/cache/**',
                '**/.git/**',
                '**/public/build/**',
            ]
        },
        hmr: {
            host: 'localhost',
            port: 5173,

            overlay: false,
        },
    },
    // build optimizations
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['vue'],
                    utils: ['lodash', 'axios'], // Add common libraries
                }
            }
        }
    },
    // Optimize dependencies
    optimizeDeps: {
        include: ['vue', 'axios', 'lodash'], // Pre-bundle common deps
    }
});