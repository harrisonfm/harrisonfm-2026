<script setup lang="ts">
// Keyed by tag alone so paging within the same tag updates in place
// (smooth crossfade) instead of remounting on every page click.
definePageMeta({
  key: (route) => route.params.tag as string,
})

const route = useRoute()
const tag = computed(() => route.params.tag as string)
const page = computed(() => parseInt(route.params.page as string))
useSeoMeta({ title: computed(() => `Tagged: ${tag.value} — Page ${page.value}`) })
const postsStore = usePostsStore()
await useAsyncData(
  () => `posts-tag-${tag.value}-${page.value}`,
  () => postsStore.fetchPosts({ per_page: 8, page: page.value, tag: tag.value }),
  { watch: [tag, page] }
)
</script>

<template>
  <PostGrid type="tag" :slug="tag" :page="page" />
</template>
