/*
  Smarter service worker
  - Precache app shell
  - Runtime caching strategies:
    - Images & static assets: cache-first with LRU limit
    - API calls (/api/): network-first with cache fallback
    - Navigation requests: network-first, fallback to cached '/' (or offline.html)
*/

const PRECACHE = 'eni-precache-v1';
const RUNTIME_IMAGE_CACHE = 'eni-images-v1';
const RUNTIME_API_CACHE = 'eni-api-v1';

const PRECACHE_URLS = [
  '/',
  '/eni.png',
  // Add the main CSS/JS bundles to precache if desired; Vite outputs fingerprinted names so consider runtime caching for them
];

// Simple cache trimming helper (LRU-like): remove oldest entries when over limit
async function trimCache(cacheName, maxEntries) {
  const cache = await caches.open(cacheName);
  const keys = await cache.keys();
  if (keys.length > maxEntries) {
    for (let i = 0; i < keys.length - maxEntries; i++) {
      await cache.delete(keys[i]);
    }
  }
}

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(PRECACHE).then(cache => cache.addAll(PRECACHE_URLS))
  );
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  const currentCaches = [PRECACHE, RUNTIME_IMAGE_CACHE, RUNTIME_API_CACHE];
  event.waitUntil(
    caches.keys().then(cacheNames => Promise.all(
      cacheNames.map(name => {
        if (!currentCaches.includes(name)) return caches.delete(name);
      })
    ))
  );
  self.clients.claim();
});

self.addEventListener('fetch', event => {
  const request = event.request;
  const url = new URL(request.url);

  // Only handle same-origin requests
  if (url.origin !== self.location.origin) return;

  // Navigation requests (HTML) - network-first, fallback to cache
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request).then(response => {
        // Optionally cache navigation responses
        return response;
      }).catch(() => caches.match('/'))
    );
    return;
  }

  // API requests - network-first, fallback to cache
  if (url.pathname.startsWith('/api/') || url.pathname.includes('/api/')) {
    event.respondWith(
      fetch(request).then(response => {
        if (response && response.status === 200) {
          const copy = response.clone();
          caches.open(RUNTIME_API_CACHE).then(cache => cache.put(request, copy));
        }
        return response;
      }).catch(() => caches.match(request))
    );
    return;
  }

  // Images & static assets - cache-first with limit
  if (request.destination === 'image' || request.destination === 'font' || request.destination === 'style') {
    event.respondWith(
      caches.match(request).then(cached => {
        if (cached) return cached;
        return fetch(request).then(response => {
          if (response && response.status === 200) {
            const copy = response.clone();
            caches.open(RUNTIME_IMAGE_CACHE).then(async cache => {
              await cache.put(request, copy);
              // Keep cache size reasonable
              trimCache(RUNTIME_IMAGE_CACHE, 50);
            });
          }
          return response;
        }).catch(() => caches.match('/'));
      })
    );
    return;
  }

  // Default: try cache, then network
  event.respondWith(
    caches.match(request).then(cached => cached || fetch(request).then(response => {
      // Optionally cache GET requests
      if (request.method === 'GET' && response && response.status === 200) {
        const copy = response.clone();
        caches.open(PRECACHE).then(cache => cache.put(request, copy));
      }
      return response;
    }).catch(() => caches.match('/')))
  );
});

