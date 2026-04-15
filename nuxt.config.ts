// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  devServer: { port: 3000 },
  runtimeConfig: {
    public: {
      wpBase: process.env.WP_BASE_URL ?? 'https://hfm-2025.lndo.site/wp-json/hfm/v1/',
    },
  },
  modules: [
    '@pinia/nuxt',
    '@nuxtjs/tailwindcss',
    '@nuxt/image',
    '@nuxtjs/seo',
  ],
  css: ['~/assets/css/main.css'],
})
