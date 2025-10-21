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
                sans: ['Instrument Sans', ...defaultTheme.fontFamily.sans],
            },
            animation: {
                'blob': 'blob 7s infinite',
                'fade-in': 'fadeIn 0.6s ease-out',
                'fade-in-down': 'fadeInDown 0.6s ease-out',
                'fade-in-up': 'fadeInUp 0.6s ease-out',
            },
            keyframes: {
                blob: {
                    '0%, 100%': { transform: 'translate(0, 0) scale(1)' },
                    '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                    '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                },
                fadeIn: {
                    'from': { opacity: '0' },
                    'to': { opacity: '1' },
                },
                fadeInDown: {
                    'from': { opacity: '0', transform: 'translateY(-20px)' },
                    'to': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInUp: {
                    'from': { opacity: '0', transform: 'translateY(20px)' },
                    'to': { opacity: '1', transform: 'translateY(0)' },
                },
            },
        },
    },

    plugins: [
        forms,
        function({ addUtilities }) {
            const newUtilities = {
                '.animation-delay-200': {
                    'animation-delay': '0.2s',
                },
                '.animation-delay-400': {
                    'animation-delay': '0.4s',
                },
                '.animation-delay-600': {
                    'animation-delay': '0.6s',
                },
                '.animation-delay-800': {
                    'animation-delay': '0.8s',
                },
                '.animation-delay-900': {
                    'animation-delay': '0.9s',
                },
                '.animation-delay-1000': {
                    'animation-delay': '1s',
                },
                '.animation-delay-1100': {
                    'animation-delay': '1.1s',
                },
                '.animation-delay-1200': {
                    'animation-delay': '1.2s',
                },
                '.animation-delay-1400': {
                    'animation-delay': '1.4s',
                },
                '.animation-delay-2000': {
                    'animation-delay': '2s',
                },
                '.animation-delay-4000': {
                    'animation-delay': '4s',
                },
                '.animation-fill-both': {
                    'animation-fill-mode': 'both',
                },
            }
            addUtilities(newUtilities)
        },
    ],
};