<script setup lang="ts">
// /blog is an alias for the homepage — redirect to keep URLs canonical
await navigateTo('/', { redirectCode: 301 })
</script>

<template>
  <div />
</template>
