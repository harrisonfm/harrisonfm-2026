<template>
  <!-- Item with children: renders a dropdown -->
  <li v-if="item.child_items" class="menu-parent" ref="el">
    <div class="flex items-center justify-end cursor-pointer" @click="toggleSubMenu">
      <span class="menu-link">{{ item.title }}</span>
      <i class="menu-link-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-3 h-3 transition-transform duration-150" :class="{ 'rotate-180': showSubMenu }" fill="currentColor">
          <path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/>
        </svg>
      </i>
    </div>
    <Transition name="slide-down">
      <ul v-if="showSubMenu" class="header-submenu" :class="isMobile ? 'header-submenu-mobile' : 'header-submenu-desktop'">
        <li v-for="child in item.child_items" :key="`menu_child_${child.ID}`" class="submenu-item">
          <NuxtLink :to="getUrlPath(child.url)" class="submenu-link">{{ child.title }}</NuxtLink>
        </li>
      </ul>
    </Transition>
  </li>

  <!-- Leaf item -->
  <li v-else class="flex w-max">
    <NuxtLink :to="getUrlPath(item.url)" class="menu-link">{{ item.title }}</NuxtLink>
  </li>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps<{
  item: Record<string, any>
  isMobile: boolean
}>()

const showSubMenu = ref(false)
const el = ref<HTMLElement | null>(null)

useClickOutside(el, () => { showSubMenu.value = false })

function toggleSubMenu() {
  showSubMenu.value = !showSubMenu.value
}

function getUrlPath(url: string): string {
  try {
    return new URL(url).pathname
  } catch {
    return url
  }
}
</script>

<style scoped>
.menu-link {
  @apply block hover:text-blue-800 focus:text-blue-800 dark:hover:text-blue-600 dark:focus:text-blue-600 transition-colors duration-200 p-2 sm:p-0;
}
.menu-link-icon {
  @apply ml-2 inline pr-2 sm:hidden;
}
.menu-parent {
  @apply flex flex-col justify-end sm:justify-center sm:flex-row;
}
.header-submenu {
  @apply border-2 bg-gray-500 text-gray-100 dark:bg-gray-900 dark:border-gray-600;
}
.header-submenu-desktop {
  @apply absolute top-full -right-1/2 border-t-0 w-max z-neg sm:shadow;
}
.header-submenu-mobile {
  @apply w-full flex flex-col items-end;
}
.submenu-item {
  @apply hover:bg-blue-500 focus:bg-blue-500 dark:hover:bg-blue-600 dark:focus:bg-blue-600 transition-colors duration-200 w-full text-right sm:text-left;
}
.submenu-link {
  @apply block p-2 md:p-4;
}
.router-link-active {
  font-weight: bolder;
}
</style>
