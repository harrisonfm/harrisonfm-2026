<template>
  <header class="main-nav">
    <HeaderLogo />
    <HeaderMenu :menu="menus['header'] ?? null" @toggle-search="toggleSearch" :showSearch="showSearch" />
    <Transition name="slide-down">
      <HeaderSearchForm v-if="showSearch" @toggle-search="toggleSearch" />
    </Transition>
  </header>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

const route = useRoute()
const menusStore = useMenusStore()
const { menus } = storeToRefs(menusStore)

const showSearch = ref(false)

function toggleSearch(value?: boolean) {
  showSearch.value = typeof value === 'boolean' ? value : !showSearch.value
}

// Close search when navigating
watch(() => route.fullPath, () => {
  showSearch.value = false
})

onMounted(() => {
  menusStore.fetchMenu('header')
})
</script>

<style scoped>
.main-nav {
  @apply font-open flex justify-between items-center border-b-2 border-gray-600 bg-white bg-opacity-95 dark:bg-gray-800 dark:border-gray-700 px-2 lg:px-4 sticky -top-px z-20;
}
</style>
