import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                // abcdFont substitute — all UI text: nav, buttons, body, labels
                sans:    ['Inter', ...defaultTheme.fontFamily.sans],
                // ivarTextFont substitute — hero/section display headlines only (≥32px)
                display: ['Fraunces', ...defaultTheme.fontFamily.serif],
                mono:    ['"Geist Mono"', 'ui-monospace', ...defaultTheme.fontFamily.mono],
            },

            colors: {
                // Deep Cosmos family — nav, filled buttons, selected states
                brand: {
                    50:  '#e6eeff',
                    100: '#c5d5ff',
                    200: '#8eaeff',
                    300: '#5585ff',
                    400: '#2260ff',
                    500: '#0050f8',   // electric blue
                    600: '#001033',   // Deep Cosmos — primary action color
                    700: '#000c27',
                    800: '#000819',
                    900: '#00040d',
                    950: '#000208',
                },
                // Chartreuse Pulse — primary CTA fill only
                accent: {
                    50:  '#f7ffc7',
                    100: '#eeff8a',
                    200: '#d0f100',   // Chartreuse Pulse
                    300: '#b8d600',
                    400: '#9dba00',
                    500: '#d0f100',   // main CTA (alias)
                    600: '#9dba00',
                    700: '#7a9200',
                    800: '#5a6b00',
                    900: '#3d4900',
                },
                // Midnight Navy family — text, structural chrome
                surface: {
                    50:  '#f8f9fc',   // Ghost Canvas — page background
                    100: '#f0f2f7',
                    200: '#dde2ed',
                    300: '#c4cad8',
                    400: '#7c8293',   // Ash Medium — tertiary text
                    500: '#6b7184',   // Slate Ink — secondary text
                    600: '#596075',   // Storm Gray
                    700: '#434a5c',
                    800: '#2c3348',
                    900: '#1b2540',   // Midnight Navy — primary text
                    950: '#0d1220',
                },
                // Dark-mode surfaces (neutral zinc)
                ink: {
                    50:  '#fafafa',
                    100: '#f4f4f5',
                    200: '#e4e4e7',
                    300: '#d4d4d8',
                    400: '#a1a1aa',
                    500: '#71717a',
                    600: '#52525b',
                    700: '#3f3f46',
                    800: '#27272a',
                    900: '#18181b',
                    950: '#09090b',
                },
            },

            letterSpacing: {
                'ui':    '-0.016em',
                'ui-md': '-0.010em',
                'ui-sm': '-0.005em',
            },

            boxShadow: {
                // Feature Card (Elevated) — --shadow-xl
                'card':            'rgba(0,39,80,0.03) 0px 56px 72px -16px, rgba(0,39,80,0.03) 0px 32px 32px -16px, rgba(0,39,80,0.04) 0px 6px 12px -3px, rgba(0,39,80,0.04) 0px 0px 0px 1px',
                'card-hover':      'rgba(0,39,80,0.06) 0px 0px 0px 1px, rgba(0,39,80,0.06) 0px 8px 16px -3px, rgba(0,39,80,0.05) 0px 40px 40px -16px',
                'card-dark':       '0 1px 3px rgba(0,0,0,0.40), 0 0 0 1px rgba(255,255,255,0.05)',
                'card-dark-hover': '0 4px 16px rgba(0,0,0,0.50), 0 0 0 1px rgba(255,255,255,0.08)',
                // Badge Pill (Floating) — --shadow-md
                'badge':           'rgba(0,39,80,0.08) 0px 6px 16px -3px, rgba(0,39,80,0.04) 0px 0px 0px 1px',
                // Announcement Banner Pill — --shadow-subtle-4
                'announce':        'rgba(255,255,255,0.88) 0px 1px 1px 0px inset, rgba(0,39,80,0.04) 0px 48px 72px -12px, rgba(0,39,80,0.03) 0px 28px 40px 0px, rgba(0,39,80,0.02) 0px 4px 12px 0px, rgba(0,39,80,0.04) 0px 0px 0px 1px',
                // Floating panels
                'float':           'rgba(0,39,80,0.08) 0px 8px 24px, rgba(0,39,80,0.04) 0px 0px 0px 1px',
                'float-dark':      '0 8px 32px rgba(0,0,0,0.50), 0 0 0 1px rgba(255,255,255,0.06)',
                // Chartreuse CTA Button — --shadow-subtle-3 (7 layers)
                'btn-cta':         'rgba(24,37,66,0.32) 0px 1px 3px 0px, rgba(24,37,66,0.12) 0px 0.5px 0.5px 0px, rgba(24,37,66,0.44) 0px 12px 24px -12px, rgba(219,247,255,0.06) 0px 8px 16px 0px inset, rgba(219,247,255,0.48) 0px 0.5px 0.5px 0px inset, rgba(219,247,255,0.04) 0px -4px 8px 0px inset, rgba(219,247,255,0.24) 0px -0.5px 0.5px 0px inset',
                // Dark Ghost Button — --shadow-md-2
                'btn-dark-ghost':  'rgba(255,255,255,0.08) 0px 0px 16px 8px inset, rgba(255,255,255,0.08) 0px 0px 8px 4px inset, rgba(255,255,255,0.08) 0px 0px 4px 2px inset, rgba(255,255,255,0.12) 0px 0px 2px 1px inset',
                // Light Ghost Button — --shadow-subtle
                'btn-ghost':       'rgba(255,255,255,0.72) 0px 1px 1px 0px inset, rgba(4,33,80,0.02) 0px 8px 16px 0px, rgba(4,33,80,0.03) 0px 4px 12px 0px, rgba(4,33,80,0.06) 0px 1px 2px 0px, rgba(4,33,80,0.04) 0px 0px 0px 1px',
                // Dark Solid Button
                'btn':             'rgba(0,39,80,0.20) 0px 1px 3px 0px, inset 0 1px 0 rgba(255,255,255,0.08)',
                'btn-hover':       'rgba(0,39,80,0.25) 0px 4px 12px, inset 0 1px 0 rgba(255,255,255,0.12)',
                'btn-active':      'inset 0 2px 4px rgba(0,0,0,0.20)',
                // Input states
                'input':           'inset 0 1px 2px rgba(0,0,0,0.06), 0 0 0 1px rgba(27,37,64,0.14)',
                'input-focus':     'inset 0 1px 2px rgba(0,0,0,0.04), 0 0 0 2.5px rgba(0,80,248,0.32)',
                'input-error':     'inset 0 1px 2px rgba(0,0,0,0.04), 0 0 0 2.5px rgba(239,68,68,0.35)',
                'input-dark':      'inset 0 1px 2px rgba(0,0,0,0.30), 0 0 0 1px rgba(255,255,255,0.06)',
                'input-focus-dark':'inset 0 1px 2px rgba(0,0,0,0.20), 0 0 0 2.5px rgba(80,180,255,0.40)',
                // Legacy compat
                'soft':            '0 2px 15px -3px rgba(0,0,0,0.07), 0 10px 20px -2px rgba(0,0,0,0.04)',
                'soft-lg':         '0 4px 25px -3px rgba(0,0,0,0.10), 0 10px 30px -2px rgba(0,0,0,0.05)',
                'soft-xl':         '0 20px 60px -12px rgba(0,0,0,0.15), 0 10px 40px -10px rgba(0,0,0,0.08)',
                'elevated':        'rgba(0,39,80,0.04) 0px 0px 0px 1px, rgba(0,39,80,0.04) 0px 4px 16px -4px',
                'glow':            '0 0 20px rgba(0,80,248,0.30)',
                'glow-lg':         '0 0 40px rgba(0,80,248,0.40)',
            },

            backgroundImage: {
                'hero':             'linear-gradient(180deg, #001033 0%, #0050f8 55%, #5fbdf7 100%)',
                'radial-glow':      'radial-gradient(50% 50% at 50% 50%, rgba(0,128,248,0.32) 0%, rgba(95,189,247,0.32) 20%, rgba(211,239,252,0.32) 60%, rgba(248,249,252,0) 100%)',
                'radial-glow-dark': 'radial-gradient(ellipse 80% 50% at 50% -20%, rgba(0,80,248,0.10), transparent)',
                'grid-pattern':     "url('data:image/svg+xml,%3Csvg width=\"40\" height=\"40\" viewBox=\"0 0 40 40\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M0 0h40v40H0V0zm20 20h20v20H20V20zM0 20h20v20H0V20z\" fill=\"currentColor\" fill-opacity=\"0.04\" fill-rule=\"evenodd\"/%3E%3C/svg%3E')",
            },

            transitionTimingFunction: {
                'spring':      'cubic-bezier(0.16,1,0.3,1)',
                'spring-back': 'cubic-bezier(0.34,1.56,0.64,1)',
                'bounce-in':   'cubic-bezier(0.68,-0.55,0.265,1.55)',
            },

            animation: {
                'fade-in-up': 'fadeInUp 0.5s ease-out forwards',
                'fade-in':    'fadeIn 0.4s ease-out forwards',
                'scale-in':   'scaleIn 0.3s cubic-bezier(0.16,1,0.3,1) forwards',
                'slide-up':   'slideUp 0.3s cubic-bezier(0.16,1,0.3,1) forwards',
                'shimmer':    'shimmer 2s linear infinite',
                'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
                'spin-slow':  'spin 3s linear infinite',
            },

            keyframes: {
                fadeInUp: {
                    '0%':   { opacity: '0', transform: 'translateY(12px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    '0%':   { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                scaleIn: {
                    '0%':   { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                slideUp: {
                    '0%':   { opacity: '0', transform: 'translateY(8px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                shimmer: {
                    '0%':   { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                pulseSoft: {
                    '0%, 100%': { opacity: '1' },
                    '50%':      { opacity: '0.6' },
                },
            },

            borderRadius: {
                '4xl': '1.5rem',
                '5xl': '2rem',
            },
        },
    },

    plugins: [forms],
};
