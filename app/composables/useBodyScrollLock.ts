// Replaces the body-scroll-lock package from the 2020 theme.
// Disables body scroll when mounted, restores it when unmounted.
export function useBodyScrollLock() {
  onMounted(() => {
    document.body.style.overflow = 'hidden'
  })
  onBeforeUnmount(() => {
    document.body.style.overflow = ''
  })
}
