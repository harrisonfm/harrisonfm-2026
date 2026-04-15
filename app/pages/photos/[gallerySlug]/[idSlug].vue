<script setup lang="ts">
const route = useRoute()
const gallerySlug = route.params.gallerySlug as string
const idSlug = route.params.idSlug as string

const postsStore = usePostsStore()
const storiesStore = useStoriesStore()

// Load gallery if not yet populated (direct navigation / SSR)
if (!postsStore.gallery.loaded) {
  await useAsyncData(`photos-gallery-${gallerySlug}`, async () => {
    await postsStore.fetchPage('photos')
    const genre = postsStore.page?.genres?.find((g: any) => g.slug === gallerySlug)
    if (genre) {
      postsStore.setGallery({ images: genre.gallery, loaded: true, gallerySlug, title: genre.title, description: genre.description, featured: genre.featured })
      return
    }
    await storiesStore.fetchStoryImages(gallerySlug)
  })
}

const photo = computed(() => {
  const id = parseInt(idSlug.split('-')[0]!)
  return postsStore.gallery.images.find((p: any) => p.ID === id)
})

useSeoMeta({
  title: computed(() => `${photo.value?.post_title ?? 'Photo'} — ${postsStore.gallery.title ?? gallerySlug} — HarrisonFM`),
})
</script>

<template>
  <PhotosModal :idSlug="idSlug" :backPath="`/photos/${gallerySlug}`" />
</template>
