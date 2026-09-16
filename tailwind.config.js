import plugin from 'tailwindcss/plugin';

export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        outfit: ['Outfit', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('tailwindcss-animate'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),

    // Plugin tambahan kalau kamu mau pakai @utilities sendiri
    plugin(function({ addUtilities }) {
      addUtilities({
        '.menu-item': {
          '@apply relative flex items-center gap-3 px-3 py-2 font-medium rounded-lg text-sm': {},
        },
        '.menu-item-active': {
          '@apply bg-brand-50 text-brand-500 dark:bg-brand-500/[0.12] dark:text-brand-400': {},
        },
        '.menu-item-inactive': {
          '@apply text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-300 dark:hover:bg-white/5 dark:hover:text-gray-300': {},
        },
      });
    })
  ],
}
