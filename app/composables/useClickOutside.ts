import { onMounted, onBeforeUnmount, type Ref } from 'vue'

// Replaces the vue-clickaway mixin from the 2020 theme.
// Pass a template ref and a callback; the callback fires whenever a click
// lands outside that element.
export function useClickOutside(target: Ref<HTMLElement | null>, callback: () => void) {
  const handler = (event: MouseEvent) => {
    if (target.value && !target.value.contains(event.target as Node)) {
      callback()
    }
  }

  onMounted(() => document.addEventListener('click', handler, { capture: true }))
  onBeforeUnmount(() => document.removeEventListener('click', handler, { capture: true }))
}
