<!-- Renders a grid of gallery/story cards that link to /photos/:slug.
     Replaces Photos/HomeSection.vue, using PostCard instead of <v-style>
     background injection. -->
<template>
  <section>
    <h1 class="page-title mb-2 lg:mb-4"><slot /></h1>
    <div class="photos-grid">
      <article
        v-for="item in section"
        :key="item.slug"
        class="overlay-article jiggle-on-hover"
      >
        <NuxtLink :to="`/photos/${item.slug}`">
          <NuxtImg
            v-if="item.featured?.images?.medium_large"
            :src="item.featured.images.medium_large.src"
            :alt="item.title"
            class="absolute inset-0 w-full h-full object-cover object-center"
            loading="lazy"
          />
          <div class="overlay" />
          <div class="title">{{ item.title }}</div>
        </NuxtLink>
      </article>
    </div>
  </section>
</template>

<script setup lang="ts">
defineProps<{
  section: Array<{
    slug: string
    title: string
    featured?: Record<string, any>
  }>
}>()
</script>

<style scoped>
.photos-grid {
  @apply w-full grid gap-2 lg:gap-4 grid-cols-1 md:grid-cols-2;
}
.jiggle-on-hover {
  @apply transition-transform duration-200 hover:scale-[1.01];
}
</style>
