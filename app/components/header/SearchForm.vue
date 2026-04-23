<template>
  <div class="search-form" @keyup.enter="search" @keyup.esc="emit('toggle-search')">
    <input
      ref="input"
      type="text"
      class="search-input"
      placeholder="Search..."
      v-model="searchInput"
    />
    <button @click="search" class="fa-icon" type="button" aria-label="Submit search">
      <!-- magnifying glass icon -->
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5" fill="currentColor">
        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
      </svg>
    </button>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const emit = defineEmits<{ (e: 'toggle-search'): void }>()

const searchInput = ref('')
const input = ref<HTMLInputElement | null>(null)

function search() {
  if (searchInput.value) {
    navigateTo(`/search/${encodeURIComponent(searchInput.value)}`)
    emit('toggle-search')
  } else {
    input.value?.focus()
  }
}

onMounted(() => {
  input.value?.focus()
})
</script>

<style scoped>
.search-form {
  @apply flex items-center dark:bg-gray-800 dark:text-white;
}
.search-input {
  @apply p-4 h-full w-full bg-transparent dark:placeholder-gray-600;
}
.fa-icon {
  @apply cursor-pointer text-2xl pr-2 ml-2 sm:mx-2 lg:mx-4 sm:p-0 transition-colors duration-150 hover:text-blue-500 dark:text-white;
}
@screen sm {
  .search-form {
    @apply absolute w-1/2 md:w-1/3 xl:w-1/4 h-16 top-full right-0 border-l-2 border-b-2 border-gray-600 z-neg bg-white bg-opacity-95 dark:border-gray-700;
  }
}
</style>
