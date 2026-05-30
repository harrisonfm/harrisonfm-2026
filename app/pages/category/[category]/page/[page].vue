<script setup lang="ts">
const route = useRoute()
const category = route.params.category as string
const page = parseInt(route.params.page as string)
useSeoMeta({ title: `Category: ${category} — Page ${page}` })
const postsStore = usePostsStore()
await useAsyncData(`posts-category-${category}-${page}`, () =>
  postsStore.fetchPosts({ per_page: 8, page, category })
)
</script>

<template>
  <PostGrid type="category" :slug="(route.params.category as string)" :page="page" />
</template>
