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
                sans:    ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                mono:    ['"Geist Mono"', 'ui-monospace', ...defaultTheme.fontFamily.mono],
            },

            colors: {
                // ── Brand: violet (not generic indigo) ─────────────────────
                brand: {
                    50:  '#f5f3ff',
                    100: '#ede9fe',
                    200: '#ddd6fe',
                    300: '#c4b5fd',
                    400: '#a78bfa',
                    500: '#8b5cf6',
                    600: '#7c3aed',
                    700: '#6d28d9',
                    800: '#5b21b6',
                    900: '#4c1d95',
                    950: '#2e1065',
                },
                // ── Accent: amber — used for time/calendar/CTA on dark ──────
                accent: {
                    50:  '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                },
                // ── Surface: slate (light mode chrome) ──────────────────────
                surface: {
                    50:  '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                    950: '#020617',
                },
                // ── Ink: zinc (warmer dark-mode surfaces, no blue cast) ──────
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

            boxShadow: {
                // ── Card elevation tiers ────────────────────────────────────
                'card':            '0 1px 3px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.04)',
                'card-hover':      '0 4px 12px rgba(0,0,0,0.10), 0 0 0 1px rgba(0,0,0,0.06)',
                'card-dark':       '0 1px 3px rgba(0,0,0,0.40), 0 0 0 1px rgba(255,255,255,0.05)',
                'card-dark-hover': '0 4px 16px rgba(0,0,0,0.50), 0 0 0 1px rgba(255,255,255,0.08)',
                // ── Floating (dropdowns, modals) ────────────────────────────
                'float':      '0 8px 24px rgba(0,0,0,0.12), 0 0 0 1px rgba(0,0,0,0.04)',
                'float-dark': '0 8px 32px rgba(0,0,0,0.50), 0 0 0 1px rgba(255,255,255,0.06)',
                // ── Button states ───────────────────────────────────────────
                'btn':        '0 1px 2px rgba(0,0,0,0.18), inset 0 1px 0 rgba(255,255,255,0.12)',
                'btn-hover':  '0 2px 8px rgba(124,58,237,0.38), inset 0 1px 0 rgba(255,255,255,0.16)',
                'btn-active': 'inset 0 2px 4px rgba(0,0,0,0.20)',
                // ── Input states ────────────────────────────────────────────
                'input':            'inset 0 1px 2px rgba(0,0,0,0.06), 0 0 0 1px rgba(0,0,0,0.08)',
                'input-focus':      'inset 0 1px 2px rgba(0,0,0,0.04), 0 0 0 2.5px rgba(124,58,237,0.32)',
                'input-error':      'inset 0 1px 2px rgba(0,0,0,0.04), 0 0 0 2.5px rgba(239,68,68,0.35)',
                'input-dark':       'inset 0 1px 2px rgba(0,0,0,0.30), 0 0 0 1px rgba(255,255,255,0.06)',
                'input-focus-dark': 'inset 0 1px 2px rgba(0,0,0,0.20), 0 0 0 2.5px rgba(139,92,246,0.40)',
                // ── Glow (branded accents) ──────────────────────────────────
                'glow':    '0 0 20px rgba(124,58,237,0.40)',
                'glow-lg': '0 0 40px rgba(124,58,237,0.50)',
                // ── Legacy compat ───────────────────────────────────────────
                'soft':      '0 2px 15px -3px rgba(0,0,0,0.07), 0 10px 20px -2px rgba(0,0,0,0.04)',
                'soft-lg':   '0 4px 25px -3px rgba(0,0,0,0.10), 0 10px 30px -2px rgba(0,0,0,0.05)',
                'soft-xl':   '0 20px 60px -12px rgba(0,0,0,0.15), 0 10px 40px -10px rgba(0,0,0,0.08)',
                'elevated':  '0 0 0 1px rgba(0,0,0,0.04), 0 4px 16px -4px rgba(0,0,0,0.12)',
            },

            transitionTimingFunction: {
                'spring':      'cubic-bezier(0.16,1,0.3,1)',
                'spring-back': 'cubic-bezier(0.34,1.56,0.64,1)',
                'bounce-in':   'cubic-bezier(0.68,-0.55,0.265,1.55)',
            },

            backgroundImage: {
                'radial-glow':      'radial-gradient(ellipse 80% 50% at 50% -20%, rgba(124,58,237,0.12), transparent)',
                'radial-glow-dark': 'radial-gradient(ellipse 80% 50% at 50% -20%, rgba(124,58,237,0.07), transparent)',
                'grid-pattern':     "url('data:image/svg+xml,%3Csvg width=\"40\" height=\"40\" viewBox=\"0 0 40 40\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M0 0h40v40H0V0zm20 20h20v20H20V20zM0 20h20v20H0V20z\" fill=\"currentColor\" fill-opacity=\"0.04\" fill-rule=\"evenodd\"/%3E%3C/svg%3E')",
            },

            animation: {
                'fade-in-up':  'fadeInUp 0.5s ease-out forwards',
                'fade-in':     'fadeIn 0.4s ease-out forwards',
                'scale-in':    'scaleIn 0.3s cubic-bezier(0.16,1,0.3,1) forwards',
                'slide-up':    'slideUp 0.3s cubic-bezier(0.16,1,0.3,1) forwards',
                'shimmer':     'shimmer 2s linear infinite',
                'pulse-soft':  'pulseSoft 2s ease-in-out infinite',
                'spin-slow':   'spin 3s linear infinite',
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
            },
        },
    },

    plugins: [forms],
};
