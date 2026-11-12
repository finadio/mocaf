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
                sans: ['var(--font-sans)', ...defaultTheme.fontFamily.sans],
                serif: ['var(--font-serif)', ...defaultTheme.fontFamily.serif],
                poppins: ['var(--font-poppins)', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                forest: {
                    DEFAULT: '#173F2B',
                    50: '#F0F6F2',
                    100: '#DCEADF',
                    200: '#B6D3BD',
                    300: '#8FB99B',
                    400: '#5F9671',
                    500: '#173F2B',
                    600: '#133524',
                    700: '#0E291C',
                    800: '#091C13',
                    900: '#040F0A',
                },
                deep: '#0D2F20',
                olive: {
                    DEFAULT: '#71864B',
                    light: '#8FA663',
                    dark: '#586A38',
                },
                moodles: {
                    DEFAULT: '#E99A18',
                    hover: '#D4860D',
                    light: '#F8B74E',
                    dark: '#B87208',
                },
                cream: {
                    DEFAULT: '#F6EBDD',
                    50: '#FCF8F3',
                    100: '#F6EBDD',
                    200: '#EBDBCA',
                    300: '#DEC7B1',
                },
                offwhite: '#FFFDF8',
                darkbrown: {
                    DEFAULT: '#40372D',
                    light: '#615447',
                    dark: '#2A241D',
                },
                softgreen: {
                    DEFAULT: '#E8F0E4',
                    light: '#F2F7F0',
                    dark: '#D0E0CB',
                },
            },
            boxShadow: {
                'warm': '0 10px 30px -10px rgba(64, 55, 45, 0.08)',
                'card': '0 4px 20px -2px rgba(23, 63, 43, 0.06)',
                'hover': '0 20px 35px -5px rgba(23, 63, 43, 0.12)',
            },
        },
    },

    plugins: [forms],
};
