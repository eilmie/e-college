import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
    extend: {
        colors: {
            brand: {
                DEFAULT: '#1E3A5F',
                light: '#2C5282',
            },
            accent: '#0D9488',
        },
        fontFamily: {
            // keep your existing fontFamily block here if present
        },
    },
},

    plugins: [forms],
};
