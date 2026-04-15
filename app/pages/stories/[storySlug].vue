<script setup lang="ts">
const route = useRoute()
const storySlug = route.params.storySlug as string

const storiesStore = useStoriesStore()
await useAsyncData(`story-${storySlug}`, () => storiesStore.fetchStory(storySlug))

const { currentStory } = storeToRefs(storiesStore)

if (!currentStory.value.loaded && storiesStore.error) {
  throw createError({ statusCode: 404, message: 'Story not found' })
}

useSeoMeta({
  title: computed(() => `${currentStory.value.term.title || storySlug} — Stories — HarrisonFM`),
  description: computed(() => currentStory.value.term.description ?? ''),
})
</script>

<template>
  <div>
    <LayoutHero :img="currentStory.term.featured ?? {}" :title="currentStory.term.title || ''" />
    <div class="post-container">
      <LayoutLoader v-if="!currentStory.loaded" />
      <TransitionGroup name="fade" tag="div">
        <div class="floating-desc mb-4 dark:text-gray-100" key="desc" v-if="currentStory.term.description">
          <p>{{ currentStory.term.description }}</p>
        </div>
        <StoryArticle
          v-for="post in currentStory.posts"
          :key="(post as any).ID"
          :article="{
            image: (post as any).featured,
            title: (post as any).post_title,
            link: (post as any).link,
            desc: (post as any).post_excerpt,
          }"
        />
      </TransitionGroup>
    </div>
  </div>
</template>
