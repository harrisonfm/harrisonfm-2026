import type { Config } from 'tailwindcss'

export default {
  content: [
    './app/**/*.{vue,ts}',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      screens: {
        xs: '480px',
        xxl: '1440px',
      },
      fontFamily: {
        open: ['Open Sans', 'Roboto', 'sans-serif'],
      },
      height: {
        article: '67vw',
        articleMD: 'calc((100vw) * .25)',
        hero: '500px',
        heroDesktop: '600px',
      },
      maxWidth: {
        '8xl': '90rem',
      },
      zIndex: {
        neg: '-1',
      },
      borderWidth: {
        '16': '16px',
      },
    },
  },
  plugins: [],
} satisfies Config
