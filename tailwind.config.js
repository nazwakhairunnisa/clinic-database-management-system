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
                // font default sans
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                // font custom Abril Fatface
                abril: ['"Abril Fatface"', 'serif'],
            },
        },
    },

    plugins: [forms],
};
