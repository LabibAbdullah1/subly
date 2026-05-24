// tailwind.config.js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './resources/**/*.php',
    './app/**/*.php',
    './routes/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        neutral: {
          50: 'var(--color-neutral-50)',
          100: 'var(--color-neutral-100)',
          200: 'var(--color-neutral-200)',
          250: 'var(--color-neutral-250)',
          300: 'var(--color-neutral-300)',
          350: 'var(--color-neutral-350)',
          400: 'var(--color-neutral-400)',
          450: 'var(--color-neutral-450)',
          500: 'var(--color-neutral-500)',
          600: 'var(--color-neutral-600)',
          700: 'var(--color-neutral-700)',
          800: 'var(--color-neutral-800)',
          850: 'var(--color-neutral-850)',
          900: 'var(--color-neutral-900)',
          950: 'var(--color-neutral-950)',
        },
        primary: {
          400: 'var(--color-primary-400)',
          500: 'var(--color-primary-500)',
          600: 'var(--color-primary-600)',
        },
      },
    },
  },
  plugins: [],
};
