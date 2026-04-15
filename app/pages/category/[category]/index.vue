<script setup lang="ts">
const route = useRoute()
const category = route.params.category as string
useSeoMeta({ title: `Category: ${category} — HarrisonFM` })
const postsStore = usePostsStore()
await useAsyncData(`posts-category-${category}-1`, () =>
  postsStore.fetchPosts({ per_page: 8, page: 1, category })
)
</script>

<template>
  <PostGrid type="category" :slug="(route.params.category as string)" :page="1" />
</template>
