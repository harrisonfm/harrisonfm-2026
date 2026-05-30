<script setup lang="ts">
useSeoMeta({ title: 'Stories' })

const storiesStore = useStoriesStore()
await useAsyncData('stories', () => storiesStore.fetchStories())

const { stories, storyHero, storyDescription } = storeToRefs(storiesStore)
</script>

<template>
  <div>
    <LayoutHero :img="storyHero ?? {}" />
    <div class="post-container">
      <h3 class="page-title">{{ stories.length ? 'Stories' : 'Loading...' }}</h3>
      <TransitionGroup name="fade" tag="div">
        <StoryArticle
          v-for="story in stories"
          :key="story.term_id"
          :article="{
            image: story.featured ?? {},
            title: story.name,
            link: `/stories/${story.slug}`,
            desc: story.description,
          }"
        />
      </TransitionGroup>
    </div>
  </div>
</template>
