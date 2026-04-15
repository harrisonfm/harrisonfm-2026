import { defineStore } from 'pinia'

type Theme = 'light' | 'dark' | 'system'

export const useThemeStore = defineStore('theme', {
  state: () => ({
    theme: 'system' as Theme,
    prefersDark: false,
  }),

  getters: {
    // The effective theme to render — resolves 'system' to the actual preference.
    effectiveTheme(state): 'light' | 'dark' {
      if (state.theme === 'system') {
        return state.prefersDark ? 'dark' : 'light'
      }
      return state.theme
    },
  },

  actions: {
    // Call once on app mount. Reads system preference and any persisted choice.
    // Must only run on the client — localStorage and matchMedia are not available
    // during SSR.
    initTheme() {
      if (!import.meta.client) return

      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
      this.prefersDark = prefersDark

      const validThemes: Theme[] = ['light', 'dark', 'system']
      const stored = localStorage.getItem('theme')
      this.theme = (stored && validThemes.includes(stored as Theme) ? stored as Theme : null) ?? (prefersDark ? 'dark' : 'light')

      this._applyTheme()
    },

    toggleTheme() {
      const map: Record<Theme, Theme> = { light: 'dark', dark: 'system', system: 'light' }
      const next: Theme = map[this.theme] ?? 'light'
      this.theme = next

      if (import.meta.client) {
        localStorage.setItem('theme', next)
        this._applyTheme()
      }
    },

    // Sync the `dark` class on <html> so Tailwind's dark mode works.
    _applyTheme() {
      if (!import.meta.client) return
      document.documentElement.classList.toggle('dark', this.effectiveTheme === 'dark')
    },
  },
})
