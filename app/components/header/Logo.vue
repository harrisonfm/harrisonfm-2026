<template>
  <NuxtLink to="/" class="logo">
    <img alt="" :src="boltSrc" class="logo-bolt rotate-180 dark:invert" />
    <span class="font-bold mx-1 text-[42px]">HFM</span>
    <img alt="" :src="boltSrc" class="logo-bolt dark:invert" />
  </NuxtLink>
</template>

<script setup lang="ts">
import boltSrc from '~/assets/bolt.svg'
</script>

<style scoped>
.logo {
  @apply flex items-center h-16 z-10 dark:text-gray-100;
}
</style>
