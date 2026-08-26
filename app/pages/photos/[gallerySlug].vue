<script setup lang="ts">
import { callWithNuxt } from '#app'

const route = useRoute()
const gallerySlug = route.params.gallerySlug as string

const postsStore = usePostsStore()
const storiesStore = useStoriesStore()

// Composables like useWpApi() (called inside these store actions) can lose
// their Nuxt SSR context after multiple sequential awaits in one handler,
// even when the first awaited call works fine — callWithNuxt re-anchors the
// app instance for each hop. See https://nuxt.com/docs/4.x/guide/concepts/auto-imports#vue-and-nuxt-composables
const nuxtApp = useNuxtApp()

// The gallery can come from two places:
//   1. A genre embedded in the 'photos' WP page (fast — already in page data)
//   2. Story media fetched separately via /storymedia endpoint
await useAsyncData(`photos-gallery-${gallerySlug}`, async () => {
  await callWithNuxt(nuxtApp, () => postsStore.fetchPage('photos'))

  // Check if this gallerySlug matches a genre in the page
  const page = postsStore.page
  const genre = page?.genres?.find((g: any) => g.slug === gallerySlug)
  if (genre) {
    postsStore.setGallery({
      images: genre.gallery,
      loaded: true,
      gallerySlug,
      title: genre.title,
      description: genre.description,
      featured: genre.featured,
    })
    return true
  }

  // Otherwise fetch story media
  await callWithNuxt(nuxtApp, () => storiesStore.fetchStoryImages(gallerySlug))
  return true
})

const gallery = computed(() => postsStore.gallery)

if (!gallery.value.loaded && !gallery.value.images.length) {
  throw createError({ statusCode: 404, message: 'Gallery not found' })
}

useSeoMeta({
  title: computed(() => `${gallery.value.title ?? gallerySlug} — Photos`),
  description: computed(() => gallery.value.description ?? ''),
  ogImage: computed(() => gallery.value.featured?.images?.large?.src ?? gallery.value.images?.[0]?.images?.large?.src),
})
</script>

<template>
  <div class="post-container">
    <h1 class="page-title mb-2 lg:mb-4 mx-auto max-w-4xl">{{ gallery.title }}</h1>
    <LayoutLoader v-if="!gallery.loaded" />
    <p v-if="gallery.description && gallery.loaded" class="dark:text-gray-100 mb-4">{{ gallery.description }}</p>
    <Transition name="fade">
      <PostGallery
        v-if="gallery.loaded"
        :gallery="gallery"
        :basePath="`/photos/${gallerySlug}`"
      />
    </Transition>
    <!-- Photo modal opens as a nested child route, overlaying this grid
         which stays mounted underneath -->
    <NuxtPage />
  </div>
</template>
