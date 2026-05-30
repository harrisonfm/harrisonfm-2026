<script setup lang="ts">
const route = useRoute()
const page = parseInt(route.params.page as string)
useSeoMeta({ title: `Page ${page}` })

const postsStore = usePostsStore()
const api = useWpApi()

const { data: homeData } = await useAsyncData('home', () => api.getHome())
await useAsyncData(`posts-home-${page}`, () =>
  postsStore.fetchPosts({ per_page: 8, page })
)
</script>

<template>
  <div>
    <LayoutHero :img="homeData?.hero ?? {}" :title="true" />
    <PostGrid type="home" :page="page" />
  </div>
</template>
