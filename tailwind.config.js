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
            dark: '#162B47',
            light: '#2C5282',
            soft: '#EEF3F9',
        },
        accent: {
            DEFAULT: '#0D9488',
            dark: '#0F766E',
        },
        },
        fontFamily: {
            sans: ['Inter', ...defaultTheme.fontFamily.sans],
        },
        boxShadow: {
            card: '0 1px 2px rgba(16, 24, 40, 0.05)',
        },
    },
},

    plugins: [forms],
};
