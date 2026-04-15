<template>
  <article class="story-article">
    <NuxtLink class="md:w-1/2 shrink-0" :to="article.link">
      <img
        v-if="article.image?.images"
        :srcset="buildSrcset(article.image.images)"
        :src="article.image.images.large?.src"
        sizes="(max-width: 767px) 100vw, 50vw"
        :alt="article.title"
        class="w-full h-full object-cover"
        loading="lazy"
      />
    </NuxtLink>
    <div class="description">
      <NuxtLink :to="article.link">
        <h2 class="font-bold text-2xl leading-none">{{ article.title }}</h2>
      </NuxtLink>
      <p class="text mt-2" v-html="article.desc" />
    </div>
  </article>
</template>

<script setup lang="ts">
defineProps<{
  article: {
    image: Record<string, any>
    title: string
    link: string
    desc?: string
  }
}>()

function buildSrcset(images: Record<string, any>): string {
  const sizes = ['medium_large', 'large', '1536x1536']
  return sizes
    .filter(k => images[k]?.src)
    .map(k => `${images[k].src} ${images[k].width}w`)
    .join(', ')
}
</script>

<style scoped>
.story-article {
  @apply flex flex-col md:flex-row mb-4 lg:mb-8 dark:text-gray-100;
}
.description {
  @apply p-4 flex flex-col justify-center;
}
.text {
  @apply mt-2 text-gray-700 dark:text-gray-300;
}
</style>
