<script setup lang="ts">
// Nuxt's default page key interpolates every route param (including idSlug),
// so swiping between photos was remounting this whole page on every
// navigation. Keying by gallerySlug alone keeps the component instance
// stable across photo swipes — Modal.vue's watch(() => props.idSlug, ...)
// updates it in place instead.
definePageMeta({
  key: (route) => route.params.gallerySlug as string,
})

const route = useRoute()
const gallerySlug = computed(() => route.params.gallerySlug as string)
const idSlug = computed(() => route.params.idSlug as string)

const postsStore = usePostsStore()
const storiesStore = useStoriesStore()

// Load gallery if not yet populated (direct navigation / SSR)
if (!postsStore.gallery.loaded) {
  await useAsyncData(`photos-gallery-${gallerySlug.value}`, async () => {
    await postsStore.fetchPage('photos')
    const genre = postsStore.page?.genres?.find((g: any) => g.slug === gallerySlug.value)
    if (genre) {
      postsStore.setGallery({ images: genre.gallery, loaded: true, gallerySlug: gallerySlug.value, title: genre.title, description: genre.description, featured: genre.featured })
      return
    }
    await storiesStore.fetchStoryImages(gallerySlug.value)
  })
}

const photo = computed(() => {
  const id = parseInt(idSlug.value.split('-')[0]!)
  return postsStore.gallery.images.find((p: any) => p.ID === id)
})

useSeoMeta({
  title: computed(() => `${photo.value?.post_title ?? 'Photo'} — ${postsStore.gallery.title ?? gallerySlug.value}`),
  description: computed(() => photo.value?.post_excerpt ?? postsStore.gallery.description ?? ''),
  ogImage: computed(() => photo.value?.images?.large?.src ?? photo.value?.images?.full?.src),
})
</script>

<template>
  <PhotosModal :idSlug="idSlug" :backPath="`/photos/${gallerySlug}`" />
</template>
