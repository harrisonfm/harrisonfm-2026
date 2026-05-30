export default defineEventHandler(async () => {
  const config = useRuntimeConfig()
  const data = await $fetch<{ name: string; description: string; site_icon_url: string }>(`${config.wpCmsUrl}/wp-json/`)
  return { name: data.name, description: data.description, iconUrl: data.site_icon_url }
})
