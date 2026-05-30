<script setup lang="ts">
const route = useRoute()
const tag = route.params.tag as string
const page = parseInt(route.params.page as string)
useSeoMeta({ title: `Tagged: ${tag} — Page ${page}` })
const postsStore = usePostsStore()
await useAsyncData(`posts-tag-${tag}-${page}`, () =>
  postsStore.fetchPosts({ per_page: 8, page, tag })
)
</script>

<template>
  <PostGrid type="tag" :slug="(route.params.tag as string)" :page="page" />
</template>
