<template>
  <div class="post-container">
    <h3 class="page-title" v-if="title">{{ title }}</h3>

    <LayoutLoader v-if="!posts.length && loading" />

    <TransitionGroup name="fade" tag="div" class="posts-grid">
      <PostCard v-for="post in posts" :key="post.ID" :post="post" />

      <!-- Pagination -->
      <div v-if="posts.length" class="full-grid pagination" key="pagination">
        <NuxtLink :to="prevLink" v-if="currentPage > 1" class="mr-auto button">Previous</NuxtLink>
        <NuxtLink :to="nextLink" v-if="hasNextPage" class="ml-auto button">Next</NuxtLink>
      </div>

      <!-- Harrigrams preview at bottom of listing pages -->
      <PostGallery
        v-if="gallery.images.length && harrigramsLoaded"
        class="full-grid"
        :gallery="gallery"
        title="Recent Harrigrams"
        basePath="/harrigrams"
        :isHarrigrams="true"
        key="harrigrams"
      />
    </TransitionGroup>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { storeToRefs } from 'pinia'

const props = defineProps<{
  type?: 'home' | 'category' | 'tag' | 'search'
  slug?: string
  page?: number
  harrigramsLoaded?: boolean
}>()

const postsStore = usePostsStore()
const { posts, maxPages, gallery, loading } = storeToRefs(postsStore)

const currentPage = computed(() => props.page ?? 1)

const hasNextPage = computed(() => currentPage.value < maxPages.value)

// Base path for pagination links: '' for home, '/category/slug' for filtered
const basePath = computed(() => {
  if (props.type === 'category') return `/category/${props.slug}`
  if (props.type === 'tag') return `/tag/${props.slug}`
  if (props.type === 'search') return `/search/${props.slug}`
  return ''
})

const prevLink = computed(() => {
  const p = currentPage.value - 1
  return p <= 1 ? `${basePath.value}/` : `${basePath.value}/page/${p}`
})

const nextLink = computed(() => `${basePath.value}/page/${currentPage.value + 1}`)

const title = computed(() => {
  if (!props.type || props.type === 'home') {
    return maxPages.value > 1 && currentPage.value > 1
      ? `Page ${currentPage.value} of ${maxPages.value}`
      : ''
  }

  const slug = props.slug?.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) ?? ''
  const prefix = {
    category: `Category: ${slug}`,
    tag: `Tagged: ${slug}`,
    search: `Search: ${slug}`,
  }[props.type]

  return maxPages.value > 1
    ? `${prefix} | Page ${currentPage.value} of ${maxPages.value}`
    : prefix
})
</script>

<style scoped>
.posts-grid {
  @apply grid gap-2 lg:gap-4 grid-cols-1 md:grid-cols-2;
}
.full-grid {
  @apply w-full md:col-span-2;
}
.pagination {
  @apply flex;
}
</style>
