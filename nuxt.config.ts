// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  devServer: { port: 3000 },
  runtimeConfig: {
    // CMS root URL — used server-side for direct API calls and by the /api/wp/* proxy
    wpCmsUrl: process.env.WP_CMS_URL ?? 'http://hfm-2025.lndo.site',
    public: {
      // Client-side calls go through the local proxy — no CORS needed
      wpBase: process.env.WP_BASE_URL ?? '/api/wp/hfm/v1/',
    },
  },
  routeRules: {
    // Homepage refreshes frequently (new posts)
    '/': { swr: 60 },
    // Paginated archive pages
    '/page/**': { swr: 60 },
    // Individual posts and photo modals — content rarely changes
    '/:year/:slug/**': { swr: 3600 },
    // Photos, stories, categories, tags — moderate churn
    '/photos/**': { swr: 600 },
    '/stories/**': { swr: 600 },
    '/category/**': { swr: 300 },
    '/tag/**': { swr: 300 },
    '/harrigrams/**': { swr: 300 },
    // Static-ish pages
    '/search/**': { swr: 60 },
    '/:slug': { swr: 3600 },
  },
  modules: [
    '@pinia/nuxt',
    '@nuxtjs/tailwindcss',
    '@nuxtjs/seo',
  ],
  ogImage: { enabled: false },
  css: ['~/assets/css/main.css'],
})
