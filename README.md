# harrisonfm-2026-theme

Nuxt 4 frontend for [harrisonfm.com](https://harrisonfm.com). Headless WordPress setup — WordPress handles content and the REST API; this theme is the Vue 3 SPA that sits on top.

Covers blog posts, stories, photography, harrigrams, and newsletter subscription.

## Stack

- **Nuxt 4** / Vue 3
- **Pinia** for state
- **Tailwind CSS** via `@nuxtjs/tailwindcss`
- **WordPress REST API** (`/wp-json/hfm/v1/`) as the backend

## Dev

```bash
npm install
npm run dev      # http://localhost:3000
npm run build
npm run preview
```

Requires a running WordPress instance. Set `WP_CMS_URL` in a `.env` file to point at it (defaults to `http://hfm-2025.lndo.site`).
