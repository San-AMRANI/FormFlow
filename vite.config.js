import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/welcome.js',
                'resources/js/dashboard.js',
                'resources/js/newForm.js',
                'resources/css/fireForms.css',
                'resources/js/formSubmit.js'
            ],
            refresh: true,
        }),
    ],
});
