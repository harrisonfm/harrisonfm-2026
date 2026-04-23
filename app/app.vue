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
