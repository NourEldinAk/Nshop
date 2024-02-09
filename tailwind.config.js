import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        "./node_modules/flowbite/**/*.js",

    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors:{
                primary:{
                    100: '#C6F3E3',
                    200: '#9DE8D5',
                    300: '#74DDC7',
                    400: '#4BD2B9',
                    500: '#32C7AB',
                    600: '#26A591',
                    700: '#1F7C6E',
                    800: '#15524A',
                    900: '#0B2927',
                }
            }
        },
    },

    plugins: [forms,
        require('flowbite/plugin'),
        
    ],
    
};
