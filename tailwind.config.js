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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#1E3A8A', // Deep Blue
                    dark: '#172554', // Darker Blue for hover
                },
                secondary: '#FFFFFF', // White
                accent: {
                    DEFAULT: '#F59E0B', // Amber
                    hover: '#D97706', // Darker Amber
                },
                dark: '#000000', // Black
            },
        },
    },

    plugins: [forms],
};
