<script setup lang="ts">
useSeoMeta({ title: 'Photos — HarrisonFM' })

const postsStore = usePostsStore()
await useAsyncData('photos-page', () => postsStore.fetchPage('photos'))

const page = computed(() => postsStore.page)
</script>

<template>
  <div class="post-container" v-if="page">
    <LayoutLoader v-if="!page.post_title" />
    <TransitionGroup name="fade" tag="div">
      <h1 key="title" class="page-title mb-2 lg:mb-4">{{ page.post_title }}</h1>
      <div key="desc" class="floating-desc dark:text-gray-100" v-if="page.post_content" v-html="page.post_content" />
      <PhotosSection key="stories" v-if="page.stories" :section="page.stories">Stories</PhotosSection>
      <PhotosSection key="genres" v-if="page.genres" :section="page.genres">Highlighted Genres</PhotosSection>
    </TransitionGroup>
  </div>
</template>
