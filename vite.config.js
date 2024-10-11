import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import tailwindcss from 'tailwindcss';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(), // Vue plugin
    ],
    css: {
        postcss: {
            plugins: [
                tailwindcss(), // TailwindCSS via PostCSS
            ],
        },
    },
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js', // Alias for Vue
        },
    },
});
