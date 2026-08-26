<template>
  <nav class="nav-menu" v-if="menu">
    <ul class="header-menu-top" :class="{ '!flex': showMobileMenu }">
      <HeaderMenuItem
        v-for="item in menu.items"
        :key="`menu_item_${item.ID}`"
        :item="item"
        :isMobile="isMobile"
        class="header-menu-item"
      />
      <li class="header-menu-search">
        <button
          class="hidden sm:flex fa-icon"
          :class="{ 'text-blue-500': showSearch }"
          @click="emit('toggle-search')"
          type="button"
          aria-label="Toggle search"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5" fill="currentColor">
            <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
          </svg>
        </button>
        <!-- Mobile: inline form in the dropdown menu -->
        <HeaderSearchForm v-if="isMobile" @toggle-search="emit('toggle-search')" />
      </li>
    </ul>

    <!-- Mobile hamburger toggle -->
    <button class="header-icon sm:hidden" @click="toggleMobileMenu" type="button" aria-label="Toggle menu">
      <!-- bars icon -->
      <svg v-if="!showMobileMenu" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-6 h-6" fill="currentColor">
        <path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"/>
      </svg>
      <!-- times/x icon -->
      <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-6 h-6" fill="currentColor">
        <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/>
      </svg>
    </button>

    <!-- Dark mode toggle -->
    <button class="header-icon" @click="themeStore.toggleTheme()" type="button" aria-label="Toggle theme">
      <!-- sun -->
      <svg v-if="themeStore.effectiveTheme === 'light' && themeStore.theme !== 'system'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-6 h-6" fill="currentColor">
        <path d="M361.5 1.2c5 2.1 8.6 6.6 9.6 11.9L391 121l107.9 19.8c5.3 1 9.8 4.6 11.9 9.6s1.5 10.7-1.6 15.2L441.5 256l67.7 90.4c3.1 4.5 3.7 10.2 1.6 15.2s-6.6 8.6-11.9 9.6L391 391 371.1 498.9c-1 5.3-4.6 9.8-9.6 11.9s-10.7 1.5-15.2-1.6L256 441.5l-90.4 67.7c-4.5 3.1-10.2 3.7-15.2 1.6s-8.6-6.6-9.6-11.9L121 391 13.1 371.1c-5.3-1-9.8-4.6-11.9-9.6s-1.5-10.7 1.6-15.2L70.5 256 2.8 165.6C-.3 161.1-.9 155.4 1.2 150.4s6.6-8.6 11.9-9.6L121 121 140.9 13.1c1-5.3 4.6-9.8 9.6-11.9s10.7-1.5 15.2 1.6L256 70.5 346.4 2.8c4.5-3.1 10.2-3.7 15.1-1.6zM160 256a96 96 0 1 1 192 0 96 96 0 1 1 -192 0z"/>
      </svg>
      <!-- moon -->
      <svg v-else-if="themeStore.effectiveTheme === 'dark' && themeStore.theme !== 'system'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-6 h-6" fill="currentColor">
        <path d="M223.5 32C100 32 0 132.3 0 256S100 480 223.5 480c60.6 0 115.5-24.2 155.8-63.4c5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6c-96.9 0-175.5-78.8-175.5-176c0-65.8 36-123.1 89.3-153.3c6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z"/>
      </svg>
      <!-- system preference: half-filled circle (auto/system) -->
      <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-6 h-6" fill="currentColor">
        <path d="M448 256c0-106-86-192-192-192V448c106 0 192-86 192-192zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z"/>
      </svg>
    </button>
  </nav>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'

const props = defineProps<{
  menu: Record<string, any> | null
  showSearch: boolean
}>()

const emit = defineEmits<{
  (e: 'toggle-search', value?: boolean): void
}>()

const themeStore = useThemeStore()
const route = useRoute()

const showMobileMenu = ref(false)
const isMobile = ref(false)

function toggleMobileMenu() {
  showMobileMenu.value = !showMobileMenu.value
}

function handleResize() {
  if (window.innerWidth < 640) {
    isMobile.value = true
  } else {
    isMobile.value = false
    showMobileMenu.value = false
    emit('toggle-search', false)
  }
}

watch(() => route.fullPath, () => {
  showMobileMenu.value = false
})

onMounted(() => {
  window.addEventListener('resize', handleResize)
  handleResize()
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', handleResize)
})
</script>

<style scoped>
.nav-menu {
  @apply flex justify-end items-center w-full uppercase font-bold dark:text-gray-100;
}
.header-menu-top {
  @apply hidden sm:flex flex-col items-end absolute top-full right-0 border-l-2 border-b-2 border-gray-600 z-neg bg-white bg-opacity-95 dark:bg-gray-900 sm:bg-transparent dark:sm:bg-transparent sm:flex-row sm:static sm:border-0 sm:z-0 sm:items-center;
}
.header-menu-item {
  @apply relative w-full justify-end flex-wrap sm:w-auto sm:justify-center sm:items-center sm:border-solid sm:border-r-16 sm:border-transparent sm:h-16 sm:flex-nowrap;
}
.header-menu-search {
  @apply flex items-center sm:h-16;
}
.header-icon {
  @apply py-5 pl-5;
}
.fa-icon {
  @apply text-2xl cursor-pointer transition-colors duration-150 hover:text-blue-500;
}
</style>
