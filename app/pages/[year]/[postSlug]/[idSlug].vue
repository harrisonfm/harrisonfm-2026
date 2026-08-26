<script setup lang="ts">
// Nuxt's default page key interpolates every route param (including idSlug),
// so swiping between photos was remounting this whole page on every
// navigation. Keying by postSlug alone keeps the component instance stable
// across photo swipes — Modal.vue's watch(() => props.idSlug, ...) updates
// it in place instead.
definePageMeta({
  key: (route) => route.params.postSlug as string,
})

const route = useRoute()
const postSlug = computed(() => route.params.postSlug as string)
const idSlug = computed(() => route.params.idSlug as string)

const postsStore = usePostsStore()

// Always fetch the parent post — the gallery must belong to this post specifically.
// Skipping on gallery.loaded is unsafe: the loaded gallery may be from a different post.
await useAsyncData(`post-${postSlug.value}`, () => postsStore.fetchPost(postSlug.value))

const photo = computed(() => {
  const id = parseInt(idSlug.value.split('-')[0]!)
  return postsStore.gallery.images.find((p: any) => p.ID === id)
})

const backPath = computed(() => `/${route.params.year}/${postSlug.value}`)

useSeoMeta({
  title: computed(() => `${photo.value?.post_title ?? 'Photo'} — ${postsStore.single?.post_title ?? ''}`),
  description: computed(() => photo.value?.post_excerpt ?? postsStore.single?.post_excerpt ?? ''),
  ogImage: computed(() => photo.value?.images?.large?.src ?? photo.value?.images?.full?.src),
})
</script>

<template>
  <PhotosModal :idSlug="idSlug" :backPath="backPath" />
</template>
