// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  devServer: { port: 3000 },
  runtimeConfig: {
    // Server-only: use HTTP to avoid SSL cert issues with Lando's self-signed CA
    wpBaseServer: process.env.WP_BASE_URL_SERVER ?? 'http://hfm-2025.lndo.site/wp-json/hfm/v1/',
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
