<script setup lang="ts">
useSeoMeta({ title: 'HarrisonFM' })

const postsStore = usePostsStore()
const api = useWpApi()

// Fetch hero image and first page of posts in parallel on SSR
const { data: homeData } = await useAsyncData('home', () => api.getHome())
await useAsyncData('posts-home', () => postsStore.fetchPosts({ per_page: 8, page: 1 }))

// Fetch recent harrigrams after posts resolve (shown at bottom of grid)
const harrigramsLoaded = ref(false)
onMounted(async () => {
  await postsStore.fetchHarrigrams()
  harrigramsLoaded.value = true
})
</script>

<template>
  <div>
    <LayoutHero :img="homeData?.hero ?? {}" :title="true" />
    <PostGrid type="home" :page="1" :harrigramsLoaded="harrigramsLoaded" />
  </div>
</template>
