<template>
  <form class="subscribe-form" @submit.prevent="subscribe">
    <h5 class="font-bold mb-1 uppercase">Newsletter</h5>
    <p class="max-w-xs mb-2">{{ message }}</p>
    <div class="xs:flex" v-if="showInput">
      <input
        required
        type="email"
        name="email"
        placeholder="e-mail"
        class="subscribe-email"
        v-model="email"
      />
      <button class="subscribe-button button" type="submit">{{ buttonText }}</button>
    </div>
  </form>
</template>

<script setup lang="ts">
const { email, message, showInput, buttonText, subscribe } = useNewsletter(
  'Check out my newsletter to stay up to date on my essays, photography and life updates. Typically a monthly issue.'
)
</script>

<style scoped>
.subscribe-form {
  @apply max-w-md;
}
.subscribe-email {
  @apply text-gray-100 bg-transparent border-gray-100 border-2 placeholder-gray-600 p-2;
}
.subscribe-button {
  @apply ml-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold transition-colors duration-150;
}
</style>
