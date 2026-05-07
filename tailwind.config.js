import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'tennis-green': '#2e5a27', // Verde Wimbledon
                'tennis-cyan': '#a5f3fc', // Cyan cielo claro
                'tennis-white': '#ffffff',
                'tennis-yellow': '#ccff00', // Color pelota de tenis
            },
        },
    },

    plugins: [forms, typography],
};
