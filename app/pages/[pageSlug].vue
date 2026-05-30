<script setup lang="ts">
const route = useRoute()
const pageSlug = route.params.pageSlug as string

// Pages that don't show a hero (privacy policy, newsletter standalone)
const NO_HERO_PAGES = ['privacy-policy', 'newsletter']
const showHero = !NO_HERO_PAGES.includes(pageSlug)

const postsStore = usePostsStore()

// Try fetching as a WP page first. If that fails, fall back to a post lookup
// (some old links go to /slug without the /year/ prefix).
const { error: pageError } = await useAsyncData(`page-${pageSlug}`, () =>
  postsStore.fetchPage(pageSlug)
)

if (postsStore.error) {
  // Page not found — try as a post (fallback for old-style links)
  const { error: postError } = await useAsyncData(`page-fallback-${pageSlug}`, () =>
    postsStore.fetchPost(pageSlug)
  )

  if (!postError.value && postsStore.single) {
    // Redirect to canonical post URL: /{year}/{slug}
    const year = postsStore.single.post_date?.substring(0, 4) ?? ''
    await navigateTo(`/${year}/${pageSlug}`, { redirectCode: 301 })
  } else {
    throw createError({ statusCode: 404, message: 'Page not found' })
  }
}

const page = computed(() => postsStore.page)
const gallery = computed(() => postsStore.gallery)

// Harrigrams page: also load all harrigrams
const harrigramsLoaded = ref(false)
if (pageSlug === 'harrigrams') {
  onMounted(async () => {
    await postsStore.fetchHarrigrams(true)
    harrigramsLoaded.value = true
  })
}

useSeoMeta({
  title: `${page.value?.post_title ?? pageSlug}`,
  description: page.value?.post_excerpt ?? '',
  ogImage: page.value?.featured?.images?.large?.src ?? page.value?.featured?.images?.full?.src,
})
</script>

<template>
  <div v-if="page">
    <LayoutHero v-if="showHero" :img="page.featured ?? {}" />
    <div class="post-container">
      <h1 class="page-title mx-auto max-w-4xl">{{ page.post_title }}</h1>

      <Transition name="fade">
        <div class="post" v-if="page.post_content" v-html="page.post_content" />
      </Transition>

      <PageWebProjects v-if="page.projects" :projects="page.projects" />

      <!-- Newsletter page gets an inline subscribe form -->
      <FooterNewsletter v-if="pageSlug === 'newsletter'" />

      <!-- Harrigrams page: full photo gallery -->
      <PostGallery
        v-if="gallery.images.length && harrigramsLoaded"
        :gallery="gallery"
        title="All Harrigrams"
        basePath="/harrigrams"
        :isHarrigrams="true"
      />

      <NuxtPage />
    </div>
  </div>
</template>
