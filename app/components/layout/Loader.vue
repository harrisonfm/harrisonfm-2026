<template>
  <div class="loader-container" :class="classes">
    <span class="whirly-loader" />
  </div>
</template>

<script setup lang="ts">
defineProps<{
  classes?: string
}>()
</script>

<style scoped>
.loader-container {
  @apply h-72 flex justify-center items-center;
}
.loader-container.photo-thumb {
  @apply w-[75px] h-[75px] absolute;
}
.loader-container.prev {
  left: 1rem;
}
.loader-container.next {
  right: 1rem;
}
</style>
