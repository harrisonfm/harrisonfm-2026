<template>
  <article class="overlay-article jiggle-on-hover">
    <NuxtLink :to="post.link">
      <!-- NuxtImg replaces the <v-style> background injection from the 2020 theme.
           The image is positioned absolutely so it fills the card, while the
           title and overlay sit above it in the normal flow. -->
      <NuxtImg
        v-if="post.featured?.images?.medium_large"
        :src="post.featured.images.medium_large.src"
        :alt="post.post_title"
        class="absolute inset-0 w-full h-full object-cover object-center"
        loading="lazy"
      />
      <div class="overlay" />
      <div class="title">{{ post.post_title }}</div>
    </NuxtLink>
  </article>
</template>

<script setup lang="ts">
defineProps<{
  post: Record<string, any>
}>()
</script>

<style scoped>
.jiggle-on-hover {
  @apply transition-transform duration-200 hover:scale-[1.01];
}
</style>
