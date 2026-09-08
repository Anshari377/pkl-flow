import defaultTheme from 'tailwindcss/defaultTheme';

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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                official: ['Cinzel', 'serif'],
                serif: ['Lora', 'serif'],
            },
            colors: {
                forest: {
                    50: '#EAF5EF',
                    100: '#D5EBE0',
                    400: '#3DB86F',
                    500: '#1D9653',
                    600: '#187A45',
                    700: '#146238',
                    800: '#0F4A30',
                    900: '#0F3A28',
                    950: '#0B2E1F',
                },
                gold: {
                    500: '#C9A344',
                    400: '#DBB85E',
                },
                glass: {
                    white: 'rgba(255,255,255,0.55)',
                    border: 'rgba(255,255,255,0.6)',
                },
                ink: {
                    100: '#E9EDEF',
                    300: '#9AAAB3',
                    400: '#7E8D97',
                    500: '#5B6B76',
                    700: '#33424C',
                    900: '#101A22',
                },
                surface: {
                    DEFAULT: '#F6F8F9',
                    card: '#FFFFFF',
                },
                status: {
                    success: '#1FA855',
                    warning: '#C9A344',
                    danger: '#E4574C',
                    info: '#187A45',
                },
            },
            borderRadius: {
                xl: '0.875rem',
            },
            boxShadow: {
                card: '0 1px 2px rgba(16, 26, 34, 0.06), 0 1px 12px rgba(16, 26, 34, 0.04)',
            },
        },
    },
    plugins: [],
};
