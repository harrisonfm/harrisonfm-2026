<script setup lang="ts">
const route = useRoute()
const postSlug = route.params.postSlug as string
const idSlug = route.params.idSlug as string

const postsStore = usePostsStore()

// Load the parent post if gallery isn't available (direct navigation / SSR)
if (!postsStore.gallery.loaded) {
  await useAsyncData(`post-${postSlug}`, () => postsStore.fetchPost(postSlug))
}

const photo = computed(() => {
  const id = parseInt(idSlug.split('-')[0]!)
  return postsStore.gallery.images.find((p: any) => p.ID === id)
})

const backPath = `/${route.params.year}/${postSlug}`

useSeoMeta({
  title: computed(() => `${photo.value?.post_title ?? 'Photo'} — ${postsStore.single?.post_title ?? ''} — HarrisonFM`),
})
</script>

<template>
  <PhotosModal :idSlug="idSlug" :backPath="backPath" />
</template>
