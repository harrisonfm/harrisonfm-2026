<template>
  <div class="flex flex-wrap items-center mt-4">
    <h3 class="text-base dark:text-gray-100 mr-2 mb-2">Tags:</h3>
    <NuxtLink
      v-for="tag in tags"
      :key="tag.id"
      :to="`/tag/${tag.slug}`"
      class="button ml-2 mb-2"
    >
      {{ tag.name }}
    </NuxtLink>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  tags: Array<{ id: number; slug: string; name: string }>
}>()
</script>
