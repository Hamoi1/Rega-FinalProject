import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true, // Enable hot module replacement (HMR) for Laravel
            }),
            tailwindcss(),
        ],
        build: {
            rollupOptions: {
                output: {
                    manualChunks(id) {
                        if (id.includes('node_modules')) {
                            // Split vendor chunks into individual files for better caching
                            const packageName = id.toString().split('node_modules/')[1].split('/')[0];
                            return `${packageName}`;
                        }
                    },
                    // Enable better chunk naming for debugging
                    chunkFileNames: 'assets/js/[name]-[hash].js',
                    entryFileNames: 'assets/js/[name]-[hash].js',
                    assetFileNames: 'assets/[ext]/[name]-[hash].[ext]',
                },
            },
            // Set a higher chunk size warning limit (default is 500 KB)
            chunkSizeWarningLimit: 1000, // Adjust this based on your project needs
            // Minification and optimization for production
            terserOptions: {
                compress: {
                    drop_console: true, // Remove console logs in production
                    drop_debugger: true, // Remove debugger statements
                },
                format: {
                    comments: false, // Remove comments from the output
                },
            },
            // Enable sourcemaps for debugging in development
            sourcemap: mode !== 'production',
            // Enable caching for faster builds
            cache: true,
        },
        // Optimize dependencies for faster development builds
        optimizeDeps: {
            include: ['lodash', 'axios'], // Add frequently used dependencies here
        },
        // Development server configuration
        server: {
            hmr: {
                host: env.VITE_DOMAIN_NAME,
            },
            watch: {
                usePolling: true, // Enable polling for file changes (useful in some environments)
            },
        },
    };
});