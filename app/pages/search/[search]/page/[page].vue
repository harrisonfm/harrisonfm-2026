<script setup lang="ts">
// Keyed by search term alone so paging within the same search updates in
// place (smooth crossfade) instead of remounting on every page click.
definePageMeta({
  key: (route) => route.params.search as string,
})

const route = useRoute()
const search = computed(() => route.params.search as string)
const page = computed(() => parseInt(route.params.page as string))
useSeoMeta({ title: computed(() => `Search: ${search.value} — Page ${page.value}`) })
const postsStore = usePostsStore()
await useAsyncData(
  () => `posts-search-${search.value}-${page.value}`,
  () => postsStore.fetchPosts({ per_page: 8, page: page.value, search: search.value }),
  { watch: [search, page] }
)
</script>

<template>
  <PostGrid type="search" :slug="search" :page="page" />
</template>
