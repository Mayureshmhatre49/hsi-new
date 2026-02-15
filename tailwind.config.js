import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
                sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                display: ['Outfit', 'Inter', 'system-ui'],
            },
            colors: {
                primary: {
                    DEFAULT: '#137fec',
                    hover: '#0062cc',
                    light: '#e8f2fe',
                },
                background: {
                    light: '#f8fafc',
                    dark: '#0f172a',
                }
            },
            boxShadow: {
                'card': '0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 10px -2px rgba(0, 0, 0, 0.03)',
            },
        },
    },

    plugins: [forms, typography],
};
