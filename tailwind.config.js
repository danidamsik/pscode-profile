import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                tokyo: {
                    bg: '#1a1b26',
                    surface: '#24283b',
                    panel: '#1f2335',
                    border: '#414868',
                    text: '#c0caf5',
                    muted: '#a9b1d6',
                    comment: '#565f89',
                    blue: '#7aa2f7',
                    cyan: '#7dcfff',
                    green: '#9ece6a',
                    purple: '#bb9af7',
                    pink: '#f7768e',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
