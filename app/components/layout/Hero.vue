<template>
  <div class="hero" :class="{ 'has-image': hasImage }">
    <!-- Responsive background image via NuxtPicture positioned behind content.
         This replaces the dynamic <style> injection used in the 2020 theme with
         a proper <picture> element — better for performance and SSR. -->
    <img
      v-if="hasImage"
      :src="img.images.full?.src ?? img.images.large?.src"
      class="hero-image"
      alt=""
      loading="eager"
      aria-hidden="true"
    />

    <div class="overlay" v-if="title" aria-hidden="true" />

    <Transition name="fade">
      <!-- Homepage logo title -->
      <h2 class="title text-7xl" v-if="title === true && hasImage">
        <span class="hidden sm:inline">Harrison </span>
        <img alt="" :src="boltSrc" class="icon rotate-180" />
        <span class="font-bold"><span class="sm:hidden">H</span>FM</span>
        <img alt="" :src="boltSrc" class="icon" />
      </h2>
      <!-- Story/page title -->
      <h2 class="title text-3xl sm:text-6xl" v-else-if="title && hasImage">{{ title }}</h2>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import boltSrc from '~/assets/bolt.svg'

const props = defineProps<{
  img: Record<string, any>
  title?: string | boolean
}>()

const hasImage = computed(() => !!(props.img && props.img.images))
</script>

<style scoped>
.hero {
  @apply bg-cover bg-center h-article md:h-hero bg-gray-500 dark:bg-gray-700 flex relative z-neg p-2 lg:p-4 overflow-hidden;
}
@screen xxl {
  .hero {
    @apply w-screen h-heroDesktop;
    margin-left: calc((100vw - theme('screens.xxl')) / -2);
  }
}
.hero-image {
  @apply absolute inset-0 w-full h-full object-cover object-center;
}
.overlay {
  @apply absolute inset-0 bg-black bg-opacity-25;
}
.title {
  @apply text-center m-auto text-white uppercase flex items-center relative;
}
.icon {
  @apply invert mx-1 h-16;
}
</style>
