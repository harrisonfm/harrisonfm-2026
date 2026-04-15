<script setup lang="ts">
const route = useRoute()
const tag = route.params.tag as string
useSeoMeta({ title: `Tagged: ${tag} — HarrisonFM` })
const postsStore = usePostsStore()
await useAsyncData(`posts-tag-${tag}-1`, () =>
  postsStore.fetchPosts({ per_page: 8, page: 1, tag })
)
</script>

<template>
  <PostGrid type="tag" :slug="(route.params.tag as string)" :page="1" />
</template>
