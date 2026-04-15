<script setup lang="ts">
useSeoMeta({ title: 'Harrigrams — HarrisonFM' })

const postsStore = usePostsStore()

// Fetch the WP page for metadata, then load all harrigrams photos
await useAsyncData('harrigrams-page', () => postsStore.fetchPage('harrigrams'))

const harrigramsLoaded = ref(false)
onMounted(async () => {
  await postsStore.fetchHarrigrams(true)
  harrigramsLoaded.value = true
})

const page = computed(() => postsStore.page)
const gallery = computed(() => postsStore.gallery)
</script>

<template>
  <div class="post-container">
    <h1 class="page-title mx-auto max-w-4xl">{{ page?.post_title ?? 'Harrigrams' }}</h1>
    <div class="post mx-auto max-w-4xl" v-if="page?.post_content" v-html="page.post_content" />
    <LayoutLoader v-if="!harrigramsLoaded" />
    <PostGallery
      v-if="gallery.images.length && harrigramsLoaded"
      :gallery="gallery"
      title="All Harrigrams"
      basePath="/harrigrams"
      :isHarrigrams="true"
    />
    <NuxtPage />
  </div>
</template>
