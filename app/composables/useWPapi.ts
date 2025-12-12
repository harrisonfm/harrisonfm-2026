export function useWpApi() {
  const config = useRuntimeConfig()

  const api = $fetch.create({
    baseURL: config.public.wpBase
  })

  return {
    getHome: () => api('/home'),
    getPage: (slug) => api('/page', { params: { slug } }),
    getPost: (slug) => api('/post', { params: { slug } }),
    getPosts: (params) => api('/posts', { params }),
    getMenu: (menu) => api(`/menus/${menu}`),
    getStories: () => api('/stories'),
    getStory: (params) => api('/story', { params }),
    getStoryMedia: (params) => api('/storymedia', { params }),
    like: (photo, likes) =>
      api(`/media/${photo}/like`, { method: 'PUT', body: { likes } })
  }
}
