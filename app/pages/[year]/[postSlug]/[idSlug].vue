<script setup lang="ts">
const route = useRoute()
const postSlug = route.params.postSlug as string
const idSlug = route.params.idSlug as string

const postsStore = usePostsStore()

// Always fetch the parent post — the gallery must belong to this post specifically.
// Skipping on gallery.loaded is unsafe: the loaded gallery may be from a different post.
await useAsyncData(`post-${postSlug}`, () => postsStore.fetchPost(postSlug))

const photo = computed(() => {
  const id = parseInt(idSlug.split('-')[0]!)
  return postsStore.gallery.images.find((p: any) => p.ID === id)
})

const backPath = `/${route.params.year}/${postSlug}`

useSeoMeta({
  title: computed(() => `${photo.value?.post_title ?? 'Photo'} — ${postsStore.single?.post_title ?? ''}`),
  description: computed(() => photo.value?.post_excerpt ?? postsStore.single?.post_excerpt ?? ''),
  ogImage: computed(() => photo.value?.images?.large?.src ?? photo.value?.images?.full?.src),
})
</script>

<template>
  <PhotosModal :idSlug="idSlug" :backPath="backPath" />
</template>
