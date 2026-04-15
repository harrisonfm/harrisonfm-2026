<script setup lang="ts">
const route = useRoute()
const gallerySlug = route.params.gallerySlug as string

const postsStore = usePostsStore()
const storiesStore = useStoriesStore()

// The gallery can come from two places:
//   1. A genre embedded in the 'photos' WP page (fast — already in page data)
//   2. Story media fetched separately via /storymedia endpoint
await useAsyncData(`photos-gallery-${gallerySlug}`, async () => {
  await postsStore.fetchPage('photos')

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
    return
  }

  // Otherwise fetch story media
  await storiesStore.fetchStoryImages(gallerySlug)
})

const gallery = computed(() => postsStore.gallery)

if (!gallery.value.loaded && !gallery.value.images.length) {
  throw createError({ statusCode: 404, message: 'Gallery not found' })
}

useSeoMeta({
  title: computed(() => `${gallery.value.title ?? gallerySlug} — Photos — HarrisonFM`),
  description: computed(() => gallery.value.description ?? ''),
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
    <NuxtPage />
  </div>
</template>
