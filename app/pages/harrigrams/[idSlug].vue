<script setup lang="ts">
const route = useRoute()
const idSlug = route.params.idSlug as string
const postsStore = usePostsStore()

// Load all harrigrams if gallery isn't populated yet (direct navigation / SSR)
if (!postsStore.gallery.loaded) {
  await useAsyncData('harrigrams-all', () => postsStore.fetchHarrigrams(true))
}

const photo = computed(() => {
  const id = parseInt(idSlug.split('-')[0]!)
  return postsStore.gallery.images.find((p: any) => p.ID === id)
})

useSeoMeta({
  title: computed(() => `${photo.value?.post_title ?? 'Photo'} — Harrigrams`),
  ogImage: computed(() => photo.value?.images?.large?.src ?? photo.value?.images?.full?.src),
})
</script>

<template>
  <PhotosModal :idSlug="idSlug" backPath="/harrigrams" />
</template>
