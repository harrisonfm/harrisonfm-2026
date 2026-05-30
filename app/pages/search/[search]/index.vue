<script setup lang="ts">
const route = useRoute()
const search = route.params.search as string
useSeoMeta({ title: `Search: ${search}` })
const postsStore = usePostsStore()
await useAsyncData(`posts-search-${search}-1`, () =>
  postsStore.fetchPosts({ per_page: 8, page: 1, search })
)
</script>

<template>
  <PostGrid type="search" :slug="(route.params.search as string)" :page="1" />
</template>
