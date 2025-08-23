import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            spacing: {
                "app-menu": "280px",
                "app-menu-sm": "220px",
            },
            screens: {
                'xs': '375px', // iPhone SE
                'sm': '480px', // Small mobile
                'md': '768px', // Tablet
                'lg': '1024px', // Desktop
                'xl': '1280px',
                '2xl': '1536px',
            },
            fontSize: {
                'xs': ['0.75rem', { lineHeight: '1rem' }],
                'sm': ['0.875rem', { lineHeight: '1.25rem' }],
                'base': ['1rem', { lineHeight: '1.5rem' }],
                'lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'xl': ['1.25rem', { lineHeight: '1.75rem' }],
                '2xl': ['1.5rem', { lineHeight: '2rem' }],
                '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
            },
            borderRadius: {
                'btn': '0.75rem',
                'form': '0.5rem',
            },
            minWidth: {
                'touch': '44px',
            },
            minHeight: {
                'touch': '44px',
            },
            gridTemplateColumns: {
                'mobile-1': '1fr',
                'mobile-2': '1fr 1fr',
                'mobile-3': '1fr 1fr 1fr',
                'dashboard': 'repeat(auto-fit, minmax(220px, 1fr))',
            },
        },
    },

    plugins: [
        forms,
        function({ addUtilities }) {
            addUtilities({
                '.btn-touch': {
                    minWidth: '44px',
                    minHeight: '44px',
                    borderRadius: '0.75rem',
                    padding: '0.5rem 1rem',
                    fontWeight: '600',
                },
                '.form-touch': {
                    minHeight: '44px',
                    borderRadius: '0.5rem',
                    padding: '0.5rem',
                },
                '.space-mobile': {
                    padding: '1rem',
                },
                '.nav-mobile': {
                    minHeight: '56px',
                    fontSize: '1.125rem',
                    borderRadius: '0.75rem',
                },
                '.grid-mobile': {
                    display: 'grid',
                    gridTemplateColumns: '1fr',
                    gap: '1rem',
                },
                '@screen sm': {
                    '.grid-mobile': {
                        gridTemplateColumns: '1fr 1fr',
                    },
                },
                '@screen md': {
                    '.grid-mobile': {
                        gridTemplateColumns: '1fr 1fr 1fr',
                    },
                },
            });
        },
    ],
};
