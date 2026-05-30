// composables/useWpApi.ts
export type WPParams = Record<string, unknown>;

export type WPPost = any; // replace `any` with a stricter type as you iterate
export type WPPage = any;
export type WPHome = any;
export type WPMenu = any;
export type WPStories = any;
export type WPStory = any;
export type WPMedia = any;

export const useWpApi = () => {
  const config = useRuntimeConfig();
  // On the server, use the private HTTP URL to avoid Lando SSL cert issues.
  // Node.js doesn't trust Lando's CA, so HTTPS requests fail silently.
  const base = (import.meta.server ? (config.wpBaseServer as string) : null)
    ?? (config.public.wpBase as string);

  // small helper to call endpoints (keeps signatures consistent)
  async function call<T = any>(path: string, opts?: { params?: WPParams; method?: string; body?: unknown; }): Promise<T> {
    const url = `${base}${path}`;
    const fetchOpts: RequestInit & { params?: WPParams } = {};
    if (opts?.method) fetchOpts.method = opts.method;
    if (opts?.body) fetchOpts.body = opts.body as any;
    // use $fetch on the server and client (Nuxt exposes it)
    // pass params as query string
    return await $fetch<T>(url, { params: opts?.params, method: opts?.method as any, body: opts?.body as any }) as T;
  }

  // Endpoints — fully typed parameters to avoid TS7006
  async function getHome(): Promise<WPHome> {
    return await call<WPHome>('home');
  }

  async function getPage(slug: string): Promise<WPPage> {
    if (!slug) throw new Error('getPage: slug is required');
    return await call<WPPage>('page', { params: { slug } });
  }

  async function getPosts(params?: WPParams): Promise<{ posts: WPPost[]; max_num_pages?: number }> {
    return await call<{ posts: WPPost[]; max_num_pages?: number }>('posts', { params });
  }

  async function getPost(slug: string): Promise<WPPost | null> {
    if (!slug) throw new Error('getPost: slug is required');
    return await call<WPPost>('post', { params: { slug } });
  }

  async function getMenu(location: string): Promise<WPMenu> {
    if (!location) throw new Error('getMenu: location is required');
    return await call<WPMenu>('menu', { params: { location } });
  }

  async function getStories(): Promise<WPStories> {
    return await call<WPStories>('stories');
  }

  // Example with a params object
  async function getStory(params: { slug: string; queryForTerm?: boolean; }): Promise<WPStory> {
    if (!params?.slug) throw new Error('getStory: slug is required');
    return await call<WPStory>('story', { params });
  }

  async function getStoryMedia(params: { slug: string; }): Promise<{ media: WPMedia[]; term?: unknown }> {
    if (!params?.slug) throw new Error('getStoryMedia: slug is required');
    return await call<{ media: WPMedia[]; term?: unknown }>('storymedia', { params });
  }

  async function like(photoId: number, likes: number): Promise<boolean> {
    if (typeof photoId !== 'number') throw new Error('like: photoId must be a number');
    return await call<boolean>(`media/${photoId}/like`, { method: 'PUT', body: { likes } });
  }

  async function getHarrigrams(params?: { fetchAll?: boolean }): Promise<{ images: WPMedia[] }> {
    return await call<{ images: WPMedia[] }>('harrigrams', { params });
  }

  // Newsletter subscription — hits a separate Mailchimp plugin endpoint,
  // not the hfm/v1 namespace.
  async function subscribe(email: string): Promise<{ code: number }> {
    return await $fetch<{ code: number }>('/api/wp/gmt-mailchimp/v1/subscribe', {
      method: 'POST',
      body: { email },
    });
  }

  return {
    getHome,
    getPage,
    getPosts,
    getPost,
    getMenu,
    getStories,
    getStory,
    getStoryMedia,
    like,
    getHarrigrams,
    subscribe,
  };
};

export type UseWpApi = ReturnType<typeof useWpApi>;
