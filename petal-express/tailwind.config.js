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
                page: '#fcfaf9',
                accent: {
                    DEFAULT: '#4f6fa5',
                    hover: '#3f5b89',
                    soft: '#eaf2ff',
                },
                brand: '#4f6fa5',
            },
            fontFamily: {
                sans: [
                    '-apple-system',
                    'BlinkMacSystemFont',
                    '"Segoe UI"',
                    'Roboto',
                    'Oxygen',
                    'Ubuntu',
                    'Cantarell',
                    '"Fira Sans"',
                    '"Droid Sans"',
                    '"Helvetica Neue"',
                    ...defaultTheme.fontFamily.sans,
                ],
                playfair: ['"Playfair Display"', 'serif'],
                dancing: ['"Dancing Script"', 'cursive'],
            },
            maxWidth: {
                content: '1400px',
            },
        },
    },

    plugins: [forms],
};
