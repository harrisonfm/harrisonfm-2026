import { defineStore } from 'pinia'
import type { WPMenu } from '~/composables/useWpApi'

export const useMenusStore = defineStore('menus', {
  state: () => ({
    menus: {} as Record<string, WPMenu>,
  }),

  actions: {
    // Only fetch a menu if it hasn't been loaded yet — menus don't change
    // between navigations so there's no need to re-fetch.
    async fetchMenu(name: string) {
      if (this.menus[name]) return

      const api = useWpApi()
      const menu = await api.getMenu(name)
      this.menus[name] = menu
    },
  },
})
