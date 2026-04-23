<template>
  <div class="post-gallery" :class="{ 'harrigrams-grid': isHarrigrams }">
    <h4 v-if="title" class="gallery-header">
      <NuxtLink v-if="isHarrigrams" to="/harrigrams" class="gallery-header-link">{{ title }}</NuxtLink>
      <span v-else>{{ title }}</span>
    </h4>
    <NuxtLink
      v-for="photo in displayImages"
      :key="photo.ID"
      :to="`${basePath}/${photo.ID}-${photo.post_name}`"
      class="gallery-link"
    >
      <NuxtImg
        :src="photo.images?.square_gallery?.src ?? photo.images?.thumbnail?.src"
        :alt="photo.post_title ?? ''"
        class="gallery-img"
        loading="lazy"
        width="150"
        height="150"
      />
      <div class="overlay" />
    </NuxtLink>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  gallery: { images: any[] }
  title?: string
  basePath: string        // e.g. '/harrigrams' or '/2024/my-post'
  isHarrigrams?: boolean
  limit?: number
}>()

const displayImages = computed(() =>
  props.limit ? props.gallery.images.slice(0, props.limit) : props.gallery.images
)
</script>

<style scoped>
.post-gallery {
  @apply grid grid-cols-2 gap-2 xs:grid-cols-3 lg:gap-4 lg:grid-cols-4;
}
.harrigrams-grid {
  @apply grid-cols-2 md:grid-cols-4 xl:grid-cols-8;
}
.gallery-link {
  @apply block relative overflow-hidden;
}
.gallery-img {
  @apply rounded shadow w-full h-full object-cover dark:bg-gray-700;
}
.gallery-header {
  @apply font-bold mb-0 uppercase col-span-full dark:text-gray-100;
}
.overlay {
  @apply absolute inset-0 bg-black opacity-0 transition-opacity duration-200;
}
.gallery-link:hover .overlay {
  @apply opacity-20;
}
</style>
