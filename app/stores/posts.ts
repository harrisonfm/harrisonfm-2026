import { defineStore } from 'pinia'
import type { WPPost, WPPage, WPMedia, WPParams } from '~/composables/useWpApi'

export interface Gallery {
  images: WPMedia[]
  loaded: boolean
  gallerySlug?: string
  title?: string
  description?: string
  featured?: unknown
}

const defaultGallery: Gallery = {
  images: [],
  loaded: false,
}

export const usePostsStore = defineStore('posts', {
  state: () => ({
    posts: [] as WPPost[],
    maxPages: 0,
    single: null as WPPost | null,
    page: null as WPPage | null,
    gallery: defaultGallery as Gallery,
    harrigrams: [] as WPMedia[],
    pageError: false,
    loading: false,
    error: null as string | null,
  }),

  actions: {
    async fetchPosts(params: WPParams = {}) {
      this.loading = true
      this.error = null
      try {
        const api = useWpApi()
        const response = await api.getPosts(params)
        this.posts = response.posts
        this.maxPages = response.max_num_pages ?? 0
      } catch (e) {
        this.error = e instanceof Error ? e.message : String(e)
        this.pageError = true
      } finally {
        this.loading = false
      }
    },

    // Check in-memory posts list before hitting the API — avoids a round-trip
    // when the user navigates to a post they already saw in the listing.
    async fetchPost(slug: string) {
      const cached = this.posts.find(p => p.post_name === slug)
      if (cached) {
        this.single = cached
        if (cached.gallery) {
          this.gallery = { images: cached.gallery, loaded: true }
        } else {
          this.gallery = defaultGallery
        }
        return
      }

      this.loading = true
      this.error = null
      try {
        const api = useWpApi()
        const post = await api.getPost(slug)
        this.single = post
        if (post?.gallery) {
          this.gallery = { images: post.gallery, loaded: true }
        } else {
          this.gallery = defaultGallery
        }
      } catch (e) {
        this.error = e instanceof Error ? e.message : String(e)
        this.pageError = true
      } finally {
        this.loading = false
      }
    },

    async fetchPage(slug: string) {
      this.loading = true
      this.error = null
      try {
        const api = useWpApi()
        this.page = await api.getPage(slug)
      } catch (e) {
        this.error = e instanceof Error ? e.message : String(e)
        this.pageError = true
      } finally {
        this.loading = false
      }
    },

    async fetchHarrigrams(fetchAll = false) {
      this.loading = true
      this.error = null
      try {
        const api = useWpApi()
        const response = await api.getHarrigrams({ fetchAll })
        this.harrigrams = response.images
        this.gallery = { images: response.images, loaded: true }
      } catch (e) {
        this.error = e instanceof Error ? e.message : String(e)
      } finally {
        this.loading = false
      }
    },

    // Called by storiesStore after loading story media so the photo store
    // can navigate within those images. Avoids direct cross-store state mutation.
    setGallery(gallery: Gallery) {
      this.gallery = gallery
    },

    clearPageError() {
      this.pageError = false
    },
  },
})
