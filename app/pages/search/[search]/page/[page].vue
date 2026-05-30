<script setup lang="ts">
const route = useRoute()
const search = route.params.search as string
const page = parseInt(route.params.page as string)
useSeoMeta({ title: `Search: ${search} — Page ${page}` })
const postsStore = usePostsStore()
await useAsyncData(`posts-search-${search}-${page}`, () =>
  postsStore.fetchPosts({ per_page: 8, page, search })
)
</script>

<template>
  <PostGrid type="search" :slug="(route.params.search as string)" :page="page" />
</template>
