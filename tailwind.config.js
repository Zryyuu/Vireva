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
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
                heading: ['Space Grotesk', 'sans-serif'],
            },
            colors: {
                primary: {
                    DEFAULT: '#006a66', // iStudio Teal
                    hover: '#004b49', // Darker Teal
                    light: '#e6f0ef', // Very Light Teal
                },
                secondary: '#555555', // iStudio Gray
                dark: '#1e2525', // iStudio Dark
                light: '#f8f9fa', // Light Background
            },
        },
    },

    plugins: [forms],
};
