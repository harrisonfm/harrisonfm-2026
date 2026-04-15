import { defineStore } from 'pinia'
import { usePostsStore } from '~/stores/posts'
import type { WPMedia } from '~/composables/useWpApi'

interface StoryTerm {
  title: string | false
  term_name?: string
  featured?: Record<string, any>
  description?: string
}

interface Story extends StoryTerm {
  term_id: number
  name: string
  slug: string
  featured?: Record<string, any>
  [key: string]: unknown
}

interface CurrentStory {
  posts: unknown[]
  term: StoryTerm
  loaded: boolean
}

const defaultStory: CurrentStory = {
  term: { title: false, featured: { images: false } },
  posts: [],
  loaded: false,
}

export const useStoriesStore = defineStore('stories', {
  state: () => ({
    stories: [] as Story[],
    storyHero: { images: false } as Record<string, any>,
    storyDescription: '' as string,
    currentStory: defaultStory as CurrentStory,
    loading: false,
    error: null as string | null,
  }),

  actions: {
    async fetchStories() {
      this.loading = true
      this.error = null
      try {
        const api = useWpApi()
        const data = await api.getStories()
        this.stories = data.stories
        this.storyHero = data.hero
        this.storyDescription = data.description
      } catch (e) {
        this.error = e instanceof Error ? e.message : String(e)
      } finally {
        this.loading = false
      }
    },

    // If the story term is already in memory from fetchStories, skip re-fetching
    // it from the API by passing queryForTerm: false.
    async fetchStory(slug: string) {
      const cached = this.stories.find(s => s.term_name === slug)

      this.loading = true
      this.error = null
      try {
        const api = useWpApi()
        const response = await api.getStory({
          slug,
          queryForTerm: !cached,
        })

        if (!response.posts) throw new Error('Story not found')

        this.currentStory = {
          posts: response.posts,
          term: cached ?? response.term,
          loaded: true,
        }
      } catch (e) {
        this.error = e instanceof Error ? e.message : String(e)
      } finally {
        this.loading = false
      }
    },

    // Fetch story media and push it into postsStore.gallery so the photo
    // viewer can navigate those images. This replaces the 2020 theme's direct
    // cross-module state mutation (store.state.post.gallery = ...).
    async fetchStoryImages(slug: string) {
      this.loading = true
      this.error = null
      try {
        const api = useWpApi()
        const response = await api.getStoryMedia({ slug })

        if (!response.media) throw new Error('No media found for story')

        usePostsStore().setGallery({
          images: response.media,
          loaded: true,
          gallerySlug: slug,
          title: (response.term as StoryTerm)?.title || undefined,
          description: (response.term as StoryTerm)?.description,
          featured: (response.term as StoryTerm)?.featured,
        })
      } catch (e) {
        this.error = e instanceof Error ? e.message : String(e)
      } finally {
        this.loading = false
      }
    },
  },
})
