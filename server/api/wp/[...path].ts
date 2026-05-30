export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig()
  const path = getRouterParam(event, 'path')
  const query = getQuery(event)
  const method = getMethod(event)
  const body = ['POST', 'PUT', 'PATCH'].includes(method) ? await readBody(event) : undefined

  return await $fetch(`${config.wpCmsUrl}/wp-json/${path}`, {
    method: method as any,
    params: query,
    body,
  })
})
