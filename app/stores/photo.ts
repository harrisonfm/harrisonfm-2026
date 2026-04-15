import { defineStore } from 'pinia'
import { usePostsStore } from '~/stores/posts'
import type { WPMedia } from '~/composables/useWpApi'

const defaultPhoto: WPMedia = { images: false }

export const usePhotoStore = defineStore('photo', {
  state: () => ({
    photo: defaultPhoto as WPMedia,
    likes: 0,
    liked: false,
    galleryIndex: 0,
    galleryInfo: true,
    slideshow: false,
    prevPhoto: defaultPhoto as WPMedia,
    nextPhoto: defaultPhoto as WPMedia,
  }),

  actions: {
    // Set the active photo and resolve its like state from localStorage.
    setPhoto(photo: WPMedia) {
      this.photo = photo
      this.likes = parseInt(photo.likes ?? 0)

      // localStorage is only available on the client
      if (import.meta.client) {
        this.liked = localStorage.getItem(`liked-${photo.ID}`) === 'true'
      }
    },

    // Set the gallery index and compute adjacent photos for prev/next navigation.
    // Reads the gallery from postsStore so the photo store doesn't own that list.
    setGalleryIndex(idx: number) {
      const postsStore = usePostsStore()
      const images = postsStore.gallery?.images ?? []

      this.galleryIndex = idx
      this.prevPhoto = idx > 0 ? images[idx - 1] : images[images.length - 1]
      this.nextPhoto = idx < images.length - 1 ? images[idx + 1] : images[0]
    },

    toggleSlideshow() {
      this.slideshow = !this.slideshow
    },

    toggleInfo() {
      this.galleryInfo = !this.galleryInfo
    },

    async likePhoto() {
      if (!this.photo?.ID) return

      const api = useWpApi()
      const newCount = this.likes + (this.liked ? -1 : 1)
      await api.like(this.photo.ID, newCount)

      this.likes = Math.max(0, newCount)
      this.liked = !this.liked
      this.photo = { ...this.photo, likes: this.likes }

      if (import.meta.client) {
        localStorage.setItem(`liked-${this.photo.ID}`, String(this.liked))
      }
    },
  },
})
