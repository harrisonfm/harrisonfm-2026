<template>
  <footer class="site-footer" :class="{ 'xxl:pt-20': overlaysFooter }">
    <div class="footer-content">
      <FooterMenu :menu="menus['writing'] ?? null" />
      <FooterMenu :menu="menus['sitemap'] ?? null" />
      <FooterNewsletter />
      <p class="text-center w-full mt-2">
        &copy; Harrison June {{ year }}
        | <button @click="scrollToTop" class="text-link cursor-pointer">Top</button>
        | <a href="mailto:june@harrisonfm.com" class="text-link">Contact Me</a>
      </p>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { computed } from 'vue'

defineProps<{
  overlaysFooter?: boolean
}>()

const menusStore = useMenusStore()
const { menus } = storeToRefs(menusStore)

const year = computed(() => new Date().getFullYear())

function scrollToTop() {
  document.getElementById('app')?.scrollIntoView({ behavior: 'smooth' })
}

</script>

<style scoped>
.site-footer {
  @apply bg-black text-gray-100 flex items-center justify-between flex-wrap px-6 py-4;
}
.footer-content {
  @apply w-full max-w-8xl mx-auto flex flex-wrap lg:justify-center;
}
.text-link {
  @apply hover:text-blue-500 focus:text-blue-500 text-gray-100 transition-colors duration-200 no-underline bg-transparent border-0;
}
</style>
