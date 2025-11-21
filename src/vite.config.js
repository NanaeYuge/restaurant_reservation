import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    server: {
        host: true,
    port: Number(process.env.VITE_PORT) || 5173,
    strictPort: true,
    hmr: {
        host: process.env.VITE_HMR_HOST || 'localhost',
        port: Number(process.env.VITE_PORT) || 5173,
        clientPort: Number(process.env.VITE_PORT) || 5173,
        protocol: 'ws',
    },
    watch: { usePolling: true },
    },
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.css',
                'resources/css/pages/auth/login.css',
                'resources/css/pages/auth/staff-login.css',
                'resources/css/pages/owner/dashboard.css',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources',
        },
    },
})
