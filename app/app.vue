<template>
  <div id="app">
    <NuxtLoadingIndicator color="#2563eb" :height="3" />
    <NuxtRouteAnnouncer />
    <LayoutHeader />
    <div class="site-content">
      <main>
        <NuxtPage :transition="{ name: 'fade', mode: 'out-in' }" />
      </main>
    </div>
    <LayoutFooter />
  </div>
</template>

<script setup lang="ts">
const themeStore = useThemeStore()

const { data: siteInfo } = await useAsyncData('site-info', () => $fetch('/api/site-info'))

const menusStore = useMenusStore()
await useAsyncData('menus', () => Promise.all([
  menusStore.fetchMenu('header'),
  menusStore.fetchMenu('writing'),
  menusStore.fetchMenu('sitemap'),
]))
useSeoMeta({
  titleTemplate: (title) => title ? `${title} | ${siteInfo.value?.name}` : (siteInfo.value?.name ?? ''),
  ogSiteName: () => siteInfo.value?.name,
  description: () => siteInfo.value?.description,
})
useHead({
  link: computed(() => siteInfo.value?.iconUrl
    ? [{ rel: 'icon', href: siteInfo.value.iconUrl }]
    : []
  ),
})

onMounted(() => {
  themeStore.initTheme()
})

if (import.meta.dev) {
  onErrorCaptured((err, instance, info) => {
    console.error('[vue]', info, err)
  })

  useNuxtApp().hook('vue:error', (err, instance, info) => {
    console.error('[nuxt:vue]', info, err)
  })

  useNuxtApp().hook('app:error', (err) => {
    console.error('[nuxt:app]', err)
  })
}
</script>
