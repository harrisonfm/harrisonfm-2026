<script setup lang="ts">
const route = useRoute()
const postSlug = route.params.postSlug as string

const postsStore = usePostsStore()
const { data: post } = await useAsyncData(`post-${postSlug}`, () => postsStore.fetchPost(postSlug))

const gallery = computed(() => postsStore.gallery)
const loaded = computed(() => !!post.value?.post_content)

if (!post.value) {
  throw createError({ statusCode: 404, message: 'Post not found' })
}

useSeoMeta({
  title: `${post.value?.post_title ?? ''} — HarrisonFM`,
  description: post.value?.post_excerpt ?? '',
})
</script>

<template>
  <div v-if="post">
    <LayoutHero :img="post.featured ?? {}" />
    <div class="post-container">
      <div class="mb-8 mx-auto max-w-4xl">
        <h1 class="page-title">{{ post.post_title }}</h1>
        <Transition name="fade">
          <p class="title-meta" v-if="loaded">
            {{ post.post_date }} in
            <NuxtLink
              class="text-link"
              :to="`/category/${post.categories?.[0]?.slug}`"
            >{{ post.categories?.[0]?.name }}</NuxtLink>
          </p>
        </Transition>
      </div>

      <Transition name="fade">
        <div class="post" v-if="loaded" v-html="post.post_content" />
      </Transition>

      <PostGallery
        v-if="gallery.images.length && loaded"
        :gallery="gallery"
        :basePath="`/${route.params.year}/${postSlug}`"
      />

      <PostTags v-if="post.tags?.length && loaded" :tags="post.tags" />

      <PostAdjacentNav v-if="(post.prev || post.next) && loaded" :post="post" />

      <!-- Photo modal opens as child route -->
      <NuxtPage />
    </div>
  </div>
</template>
