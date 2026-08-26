<script setup lang="ts">
// Keyed by category alone so paging within the same category updates in
// place (smooth crossfade) instead of remounting on every page click.
definePageMeta({
  key: (route) => route.params.category as string,
})

const route = useRoute()
const category = computed(() => route.params.category as string)
const page = computed(() => parseInt(route.params.page as string))
useSeoMeta({ title: computed(() => `Category: ${category.value} — Page ${page.value}`) })
const postsStore = usePostsStore()
await useAsyncData(
  () => `posts-category-${category.value}-${page.value}`,
  () => postsStore.fetchPosts({ per_page: 8, page: page.value, category: category.value }),
  { watch: [category, page] }
)
</script>

<template>
  <PostGrid type="category" :slug="category" :page="page" />
</template>
