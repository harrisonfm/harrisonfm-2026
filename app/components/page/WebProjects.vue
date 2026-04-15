<template>
  <div class="project-container">
    <article
      v-for="(project, idx) in projects"
      :key="project.title"
      class="project"
      :class="{ on: openIndex === idx }"
      @click="toggle(idx)"
    >
      <div class="title">{{ project.title }}</div>

      <!-- Responsive background image — replaces the <v-style> injection -->
      <NuxtImg
        v-if="project.image?.images?.medium_large"
        :src="project.image.images.medium_large.src"
        :alt="project.title"
        class="absolute inset-0 w-full h-full object-cover object-center -z-10"
        loading="lazy"
      />

      <div class="project-description">
        <p class="p-2 lg:p-4">
          <a target="_blank" :href="project.link" class="text-link" @click.stop>Link</a>
          &mdash; {{ project.description }}
        </p>
      </div>
    </article>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

defineProps<{
  projects: Array<{
    title: string
    link: string
    description: string
    image?: Record<string, any>
  }>
}>()

const openIndex = ref<number | null>(null)

function toggle(idx: number) {
  openIndex.value = openIndex.value === idx ? null : idx
}
</script>

<style scoped>
.project-container {
  @apply w-full mx-auto max-w-4xl border-black dark:border-gray-700 border-l-2 border-r-2 sm:border-l-4 sm:border-r-4;
}
.project {
  @apply relative bg-cover bg-gray-500 dark:bg-gray-600 flex flex-col justify-between cursor-pointer border-black dark:border-gray-700 border-t-2 sm:border-t-4 h-[40px] max-h-[500px] transition-all duration-500 sm:h-[70px] overflow-hidden;
}
.project:last-of-type {
  @apply border-b-2 h-[44px] sm:border-b-4 sm:h-[74px];
}
.project.on {
  height: calc(100vw / 2);
  max-height: 500px;
}
.project .title {
  @apply bg-white dark:bg-gray-800 dark:text-gray-100 font-bold inline-flex items-center px-4 self-start relative border-black dark:border-gray-700 border-b-2 border-r-2 sm:border-r-0 sm:border-b-4 sm:text-3xl text-black ml-0 mt-0 dark:font-normal leading-[40px] sm:leading-[66px];
}
.project-description {
  @apply bg-white dark:bg-gray-800 dark:text-gray-100 opacity-0 transition-opacity duration-500 absolute bottom-0 left-0 right-0 cursor-auto text-sm xs:text-base;
}
.project.on .project-description {
  @apply opacity-100;
}
</style>
