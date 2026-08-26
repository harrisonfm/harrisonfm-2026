<template>
  <article class="overlay-article jiggle-on-hover">
    <NuxtLink :to="post.link">
      <img
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
  @apply transition-transform duration-200;
}
@media (hover: hover) {
  .jiggle-on-hover:hover {
    @apply scale-[1.01];
  }
}
</style>
