<template>
  <Transition name="fade">
    <div
      class="photo-modal"
      ref="modalEl"
      tabindex="0"
      @keyup.right="goToNext"
      @keyup.left="goToPrev"
      @keyup.up="back"
      @keyup.down="back"
      @keyup.esc="back"
      @keyup.space.prevent="photoStore.toggleSlideshow()"
      @keyup.enter="photoStore.toggleInfo()"
      @touchstart.passive="onTouchStart"
      @touchend.passive="onTouchEnd"
    >
      <template v-if="photo">
        <!-- ── Controls panel (full) ────────────────────────── -->
        <Transition name="slide-right">
          <div class="controls controls-full" v-if="photoStore.galleryInfo">
            <!-- Back -->
            <button class="controls-icon" @click="back" type="button" aria-label="Back to gallery">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-5 h-5" fill="currentColor">
                <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/>
              </svg>
            </button>

            <!-- Toggle info -->
            <button class="controls-icon" @click="photoStore.toggleInfo()" type="button" aria-label="Toggle info">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512" class="w-5 h-5" fill="currentColor">
                <path d="M48 80a48 48 0 1 1 96 0A48 48 0 1 1 48 80zM0 224c0-17.7 14.3-32 32-32H96c17.7 0 32 14.3 32 32V448h32c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H64V256H32c-17.7 0-32-14.3-32-32z"/>
              </svg>
              <Transition name="fade">
                <div class="controls-msg" v-if="hover.info">Hide photo info</div>
              </Transition>
            </button>

            <!-- Slideshow -->
            <button
              v-if="photoStore.nextPhoto?.images"
              class="controls-icon"
              @click="photoStore.toggleSlideshow(); handleSlideshow()"
              type="button"
              :aria-label="photoStore.slideshow ? 'Pause slideshow' : 'Start slideshow'"
            >
              <!-- pause -->
              <svg v-if="photoStore.slideshow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-5 h-5" fill="currentColor">
                <path d="M48 64C21.5 64 0 85.5 0 112V400c0 26.5 21.5 48 48 48H80c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H48zm192 0c-26.5 0-48 21.5-48 48V400c0 26.5 21.5 48 48 48h32c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H240z"/>
              </svg>
              <!-- play -->
              <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-5 h-5" fill="currentColor">
                <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"/>
              </svg>
              <Transition name="fade">
                <div class="controls-msg" v-if="hover.slideshow">Toggle slideshow (5s)</div>
              </Transition>
            </button>

            <!-- Like -->
            <button class="controls-icon" @click="photoStore.likePhoto()" type="button" :aria-label="`Like photo (${photoStore.likes} likes)`">
              <span class="relative inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5" :class="{ 'text-red-500': photoStore.liked }" fill="currentColor">
                  <path d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z"/>
                </svg>
                <span v-if="photoStore.likes" class="absolute inset-0 flex items-center justify-center text-white text-[9px] font-bold">
                  {{ photoStore.likes }}
                </span>
              </span>
              <Transition name="fade">
                <div class="controls-msg" v-if="hover.like">Give some love</div>
              </Transition>
            </button>

            <!-- Share -->
            <button class="controls-icon" @click="share" type="button" aria-label="Share photo">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-5 h-5" fill="currentColor">
                <path d="M352 224c53 0 96-43 96-96s-43-96-96-96s-96 43-96 96c0 4 .2 8 .7 11.9l-94.1 47C145.4 170.2 121.9 160 96 160c-53 0-96 43-96 96s43 96 96 96c25.9 0 49.4-10.2 66.6-26.9l94.1 47c-.5 3.9-.7 7.8-.7 11.9c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-25.9 0-49.4 10.2-66.6 26.9l-94.1-47c.5-3.9 .7-7.9 .7-11.9s-.2-8-.7-11.9l94.1-47C302.6 213.8 326.1 224 352 224z"/>
              </svg>
              <Transition name="fade">
                <div class="controls-msg" v-if="hover.share">Share photo</div>
                <div class="controls-msg" v-else-if="shareCopied">Copied to clipboard!</div>
              </Transition>
            </button>
          </div>
        </Transition>

        <!-- ── Mini controls tab (when info is hidden) ─────── -->
        <Transition name="slide-right">
          <button
            class="controls controls-mini"
            v-if="!photoStore.galleryInfo"
            @click="photoStore.toggleInfo()"
            type="button"
            aria-label="Show photo info"
          >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-4 h-4" fill="currentColor">
              <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/>
            </svg>
          </button>
        </Transition>

        <!-- ── Main image ───────────────────────────────────── -->
        <Transition name="fade">
          <div
            class="photo-box"
            :class="photoStore.galleryInfo ? 'pb-2 lg:pb-4' : 'my-auto'"
            v-show="loaded"
          >
            <img
              :srcset="buildSrcset(photo)"
              :sizes="imgSizes"
              :src="photo.images?.['2048x2048']?.src ?? photo.images?.large?.src ?? photo.images?.full?.src"
              :alt="photo.post_title"
              class="max-h-full m-auto"
              @load="onMainLoad"
            />
          </div>
        </Transition>

        <!-- ── Mobile caption (below image, hidden on sm+) ──── -->
        <div
          class="w-full text-center px-2 my-2 dark:text-gray-100 lg:px-4 lg:my-4 sm:hidden"
          :class="{ hidden: !photoStore.galleryInfo }"
        >
          <h2 class="leading-none text-2xl mb-0">{{ photo.images ? photo.post_title : 'Loading...' }}</h2>
          <Transition name="fade">
            <p class="mb-0 mt-2 leading-5" v-if="photo.images && (photo.post_excerpt || locDate)">
              <span>{{ photo.post_excerpt }}</span>
              <span class="block" v-if="locDate">{{ photo.post_excerpt ? '—' : '' }} {{ locDate }}</span>
            </p>
          </Transition>
        </div>

        <!-- ── Info nav (thumbnails + caption) ────────────────── -->
        <Transition name="slide-up">
          <nav class="photo-infonav" v-if="photoStore.galleryInfo">
            <!-- Prev thumbnail -->
            <LayoutLoader v-if="loaded && photoStore.prevPhoto?.images && !prevLoaded" classes="photo-thumb prev" />
            <Transition name="fade">
              <img
                v-if="photoStore.prevPhoto?.images"
                width="75"
                height="75"
                class="thumb"
                :class="{ invisible: !prevLoaded }"
                :src="photoStore.prevPhoto.images?.thumbnail?.src"
                alt="Previous photo"
                :title="photoStore.prevPhoto.post_title"
                @click="goToPrev"
                @load="prevLoaded = true"
              />
            </Transition>

            <!-- Caption -->
            <div class="infonav-text">
              <h2 class="infonav-title mb-0">{{ photo.images ? photo.post_title : 'Loading...' }}</h2>
              <Transition name="fade">
                <p class="mb-0 mt-2 lg:mt-4" v-if="photo.images && (photo.post_excerpt || locDate)">
                  <span>{{ photo.post_excerpt }}</span>
                  <span class="infonav-locdate" v-if="locDate">{{ photo.post_excerpt ? '—' : '' }} {{ locDate }}</span>
                </p>
              </Transition>
            </div>

            <!-- Next thumbnail -->
            <LayoutLoader v-if="loaded && photoStore.nextPhoto?.images && !nextLoaded" classes="photo-thumb next" />
            <Transition name="fade">
              <img
                v-if="photoStore.nextPhoto?.images"
                width="75"
                height="75"
                class="thumb"
                :class="{ invisible: !nextLoaded }"
                :src="photoStore.nextPhoto.images?.thumbnail?.src"
                alt="Next photo"
                :title="photoStore.nextPhoto.post_title"
                @click="goToNext"
                @load="nextLoaded = true"
              />
            </Transition>
          </nav>
        </Transition>
      </template>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps<{
  idSlug: string
  backPath: string  // e.g. '/harrigrams' or '/2024/my-post' or '/photos/my-gallery'
}>()

const photoStore = usePhotoStore()
const postsStore = usePostsStore()
const route = useRoute()

// ── UI state ──────────────────────────────────────────────────────
const loaded = ref(false)
const prevLoaded = ref(false)
const nextLoaded = ref(false)
const shareCopied = ref(false)
const imgSizes = ref('100vw')
const modalEl = ref<HTMLElement | null>(null)
const hover = ref({ back: false, info: false, slideshow: false, like: false, share: false })

// ── Body scroll lock ──────────────────────────────────────────────
useBodyScrollLock()

// ── Photo data ────────────────────────────────────────────────────
const photo = computed(() => photoStore.photo)

const locDate = computed(() => {
  const p = photo.value
  if (!p) return ''
  if (p.date && p.location) return `${p.location} - ${p.date}`
  return p.date || p.location || ''
})

function parseId(idSlug: string): number {
  return parseInt(idSlug.split('-')[0]!)
}

function buildSrcset(p: Record<string, any>): string {
  if (!p?.images) return ''
  const keys = ['medium_large', 'large', '1536x1536', '2048x2048']
  return keys
    .filter(k => p.images[k]?.src)
    .map(k => `${p.images[k].src} ${p.images[k].width}w`)
    .join(', ')
}

function computeSizes(p: Record<string, any>): string {
  if (!p?.images?.full || !import.meta.client) return '100vw'
  const imgRatio = p.images.full.width / p.images.full.height
  const winRatio = window.innerWidth / window.innerHeight
  if (winRatio >= imgRatio) {
    return `${Math.round((window.innerHeight * imgRatio / window.innerWidth) * 100)}vw`
  }
  return '100vw'
}

function initPhoto(idSlug: string) {
  if (!postsStore.gallery.loaded) return  // watch on gallery.loaded will re-init once ready
  const id = parseId(idSlug)
  const gallery = postsStore.gallery.images
  const idx = gallery.findIndex((p: any) => p.ID === id)
  if (idx === -1) {
    throw createError({ statusCode: 404, message: 'Photo not found' })
  }
  loaded.value = false
  prevLoaded.value = false
  nextLoaded.value = false
  photoStore.setPhoto(gallery[idx])
  photoStore.setGalleryIndex(idx)
  imgSizes.value = computeSizes(gallery[idx])
}

// ── Navigation ────────────────────────────────────────────────────
function goToNext() {
  if (!photoStore.nextPhoto?.ID) return
  if (photoStore.slideshow) { photoStore.toggleSlideshow(); clearSlideshow() }
  const next = photoStore.nextPhoto
  navigateTo(`${props.backPath}/${next.ID}-${next.post_name}`, { replace: true })
}

function goToPrev() {
  if (!photoStore.prevPhoto?.ID) return
  if (photoStore.slideshow) { photoStore.toggleSlideshow(); clearSlideshow() }
  const prev = photoStore.prevPhoto
  navigateTo(`${props.backPath}/${prev.ID}-${prev.post_name}`, { replace: true })
}

function back() {
  if (photoStore.slideshow) { photoStore.toggleSlideshow(); clearSlideshow() }
  navigateTo(props.backPath, { replace: true })
}

// ── Image load handlers ───────────────────────────────────────────
// Replaces the manual new Image() preload approach in the 2020 theme —
// Vue's @load event does the same thing more cleanly.
function onMainLoad() {
  loaded.value = true
  handleSlideshow()
  // prev/next thumbnails start loading automatically once src is set;
  // their @load events flip prevLoaded/nextLoaded
}

// ── Slideshow ─────────────────────────────────────────────────────
let slideshowTimer: ReturnType<typeof setTimeout> | null = null

function handleSlideshow() {
  clearSlideshow()
  if (photoStore.slideshow && photoStore.nextPhoto?.images) {
    slideshowTimer = setTimeout(goToNext, 5000)
  }
}

function clearSlideshow() {
  if (slideshowTimer) { clearTimeout(slideshowTimer); slideshowTimer = null }
}

// ── Share ─────────────────────────────────────────────────────────
function share() {
  if (navigator.share) {
    navigator.share({
      url: document.location.href,
      title: photo.value?.post_title,
      text: [photo.value?.post_excerpt, locDate.value].filter(Boolean).join(' '),
    })
  } else {
    navigator.clipboard.writeText(document.location.href)
    shareCopied.value = true
    setTimeout(() => { shareCopied.value = false }, 1500)
  }
}

// ── Swipe ─────────────────────────────────────────────────────────
let touchStartX = 0

function onTouchStart(e: TouchEvent) {
  touchStartX = e.touches[0]!.clientX
}

function onTouchEnd(e: TouchEvent) {
  const dx = e.changedTouches[0]!.clientX - touchStartX
  if (Math.abs(dx) < 50) return
  if (dx < 0) goToNext()
  else goToPrev()
}

// ── Resize handler ────────────────────────────────────────────────
function onResize() {
  if (photo.value) imgSizes.value = computeSizes(photo.value)
}

// ── Lifecycle ─────────────────────────────────────────────────────
onMounted(() => {
  modalEl.value?.focus()
  window.addEventListener('resize', onResize)
  initPhoto(props.idSlug)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', onResize)
  clearSlideshow()
})

// Re-init when navigating between photos (same component, new idSlug)
watch(() => props.idSlug, (newSlug) => {
  initPhoto(newSlug)
})

// Re-init when gallery loads after component is already mounted
watch(() => postsStore.gallery.loaded, (loaded) => {
  if (loaded) initPhoto(props.idSlug)
})
</script>

<style scoped>
.photo-modal {
  @apply fixed inset-0 bg-white flex flex-col items-center z-30 font-open dark:bg-gray-800;
}
.controls {
  @apply absolute flex items-center right-0 top-0 z-10;
}
.controls-full {
  @apply flex-col py-3 px-2 text-lg rounded-bl-lg bg-white bg-opacity-25 md:text-xl;
}
.controls-icon {
  @apply cursor-pointer p-1 leading-none relative shadow rounded-full md:p-2 bg-black bg-opacity-10;
}
.controls-icon:not(:first-of-type) {
  @apply mt-3;
}
.controls-mini {
  @apply bg-gray-300 opacity-50 w-6 h-8 px-1 cursor-pointer border-4 border-t-0 border-r-0 border-gray-600 rounded-bl-lg flex items-center;
}
.controls-msg {
  @apply absolute text-sm not-italic right-full mr-2 bg-gray-800 rounded-lg p-2 top-0 text-gray-300 whitespace-nowrap z-10;
}
.photo-box {
  @apply overflow-auto mt-auto lg:my-auto w-full flex;
}
.photo-infonav {
  @apply relative w-full flex items-center justify-between mt-auto mx-auto p-2 bg-gray-100 lg:p-4 xxl:rounded-lg xxl:shadow xxl:w-4/5 dark:bg-gray-700 dark:text-gray-100;
}
@media (max-height: 500px) {
  .photo-infonav {
    @apply mb-0 rounded-none shadow-none;
  }
}
.infonav-text {
  @apply text-center px-4 hidden m-auto sm:flex sm:flex-col sm:justify-center min-h-[75px];
}
.infonav-title {
  @apply leading-none text-2xl lg:text-4xl;
}
.infonav-locdate {
  @apply text-gray-700 dark:text-gray-400;
}
.thumb {
  @apply cursor-pointer object-cover w-[75px] h-[75px] shrink-0;
}

/* Transitions */
.slide-right-enter-active,
.slide-right-leave-active {
  transition: transform 0.2s ease, opacity 0.2s ease;
}
.slide-right-enter-from,
.slide-right-leave-to {
  transform: translateX(20px);
  opacity: 0;
}
.slide-up-enter-active,
.slide-up-leave-active {
  transition: transform 0.2s ease, opacity 0.2s ease;
}
.slide-up-enter-from,
.slide-up-leave-to {
  transform: translateY(20px);
  opacity: 0;
}
</style>
