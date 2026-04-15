<template>
  <div class="adj-nav dark:text-gray-100 mt-4">
    <!-- Story context banner -->
    <h2 v-if="storyLabel" class="font-open font-bold text-xl mb-4" v-html="storyLabel" />

    <div v-if="post.prev || post.next" class="flex flex-col-reverse md:flex-row">
      <div v-if="post.prev" class="w-full md:w-1/2 mt-2 md:mt-0 md:mr-2 lg:mr-4">
        <h3 class="text-base mb-0">Previously</h3>
        <PostCard :post="post.prev" />
      </div>
      <div v-if="post.next" class="w-full md:w-1/2">
        <h3 class="text-base mb-0" :class="{ 'md:text-right': post.prev }">Next</h3>
        <PostCard :post="post.next" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  post: Record<string, any>
}>()

const storyLabel = computed(() => {
  if (!props.post.story) return null
  const { name, slug, prev, next } = props.post.story
  const link = `<a class="title-link" href="/stories/${slug}">${name}</a>`
  if (!prev) return `This is the beginning of the ${link} story:`
  if (!next) return `This is the end of the ${link} story:`
  return `This is part of the ${link} story:`
})
</script>
