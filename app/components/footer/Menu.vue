<template>
  <nav v-if="menu">
    <ul class="footer-list">
      <li class="footer-header">{{ menu.name }}</li>
      <li v-for="item in menu.items" :key="`menu_item_${item.ID}`" class="mr-4 last:mr-0">
        <NuxtLink :to="getUrlPath(item.url)" class="footer-link">
          {{ item.title }}
        </NuxtLink>
      </li>
    </ul>
  </nav>
</template>

<script setup lang="ts">
defineProps<{
  menu: Record<string, any> | null
}>()

function getUrlPath(url: string): string {
  try {
    return new URL(url).pathname
  } catch {
    return url
  }
}
</script>

<style scoped>
.footer-list {
  @apply mb-2 mr-4 lg:mb-4 lg:mr-8;
}
.footer-header {
  @apply font-bold mb-1 uppercase;
}
.footer-link {
  @apply hover:text-blue-500 focus:text-blue-500 text-white transition-colors duration-200;
}
</style>
