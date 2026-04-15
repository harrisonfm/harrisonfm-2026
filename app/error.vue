<template>
  <div class="post-container">
    <div class="post">
      <h1 class="text-3xl mb-5">
        {{ error.statusCode === 404 ? 'Content not found!' : 'Something went wrong' }}
      </h1>
      <p class="text-xl mb-5">
        <template v-if="error.statusCode === 404">
          That page doesn't exist or has no results. Go back
          <NuxtLink class="underline" to="/">home</NuxtLink>?
        </template>
        <template v-else>
          An unexpected error occurred. Go back
          <NuxtLink class="underline" to="/">home</NuxtLink>?
        </template>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { NuxtError } from '#app'

const props = defineProps<{ error: NuxtError }>()

useSeoMeta({ title: 'Not Found — HarrisonFM' })
</script>
