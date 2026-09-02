export default defineEventHandler(async () => {
  const config = useRuntimeConfig()
  try {
    const data = await $fetch<{ name: string; description: string; site_icon_url: string }>(`${config.wpCmsUrl}/wp-json/`, { timeout: 8000 })
    return { name: data.name, description: data.description, iconUrl: data.site_icon_url }
  } catch {
    // app.vue awaits this on every SSR render — fall back instead of failing the whole page when the WP backend is slow/down
    return { name: null, description: null, iconUrl: null }
  }
})
