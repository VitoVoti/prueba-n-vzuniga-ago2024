import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Titillium Web', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                'prueba-n-primary': '#730B94',
                'prueba-n-menu-icons': '#6451A9',
                'prueba-n-menu-text' :'#403E45',
            },
        },
    },

    plugins: [
        forms,
    ],
};
