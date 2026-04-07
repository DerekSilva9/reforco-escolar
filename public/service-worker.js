const CACHE_PREFIX = 'jardim-do-saber';
const CACHE_VERSION = 'v2';
const STATIC_CACHE = `${CACHE_PREFIX}-${CACHE_VERSION}-static`;
const RUNTIME_CACHE = `${CACHE_PREFIX}-${CACHE_VERSION}-runtime`;
const IMAGE_CACHE = `${CACHE_PREFIX}-${CACHE_VERSION}-images`;

const CORE_ASSETS = [
  '/',
  '/offline',
  '/manifest.json',
  '/pwa-init.js',
  '/images/favicon-32x32.png',
  '/images/icon-192x192.png',
  '/images/icon-192x192-maskable.png',
  '/images/icon-512x512.png',
  '/images/icon-512x512-maskable.png',
  '/images/badge-72x72.png',
  '/images/shortcut-presenca-96x96.png',
  '/images/shortcut-financeiro-96x96.png',
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(STATIC_CACHE).then((cache) => cache.addAll(CORE_ASSETS))
  );

  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => Promise.all(
      cacheNames
        .filter((cacheName) => cacheName.startsWith(CACHE_PREFIX) && !cacheName.includes(CACHE_VERSION))
        .map((cacheName) => caches.delete(cacheName))
    ))
  );

  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  if (request.method !== 'GET' || url.origin !== self.location.origin) {
    return;
  }

  if (request.mode === 'navigate') {
    event.respondWith(handleNavigationRequest(request));
    return;
  }

  if (request.destination === 'image') {
    event.respondWith(cacheFirst(request, IMAGE_CACHE));
    return;
  }

  if (
    request.destination === 'style' ||
    request.destination === 'script' ||
    request.destination === 'manifest' ||
    request.destination === 'font'
  ) {
    event.respondWith(staleWhileRevalidate(request, RUNTIME_CACHE));
  }
});

async function handleNavigationRequest(request) {
  try {
    return await fetch(request);
  } catch (error) {
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
      return cachedResponse;
    }

    const offlinePage = await caches.match('/offline');
    if (offlinePage) {
      return offlinePage;
    }

    return new Response('Offline', {
      status: 503,
      headers: { 'Content-Type': 'text/plain; charset=utf-8' },
    });
  }
}

async function cacheFirst(request, cacheName) {
  const cachedResponse = await caches.match(request);
  if (cachedResponse) {
    return cachedResponse;
  }

  const networkResponse = await fetch(request);
  return cacheResponse(cacheName, request, networkResponse);
}

async function staleWhileRevalidate(request, cacheName) {
  const cachedResponse = await caches.match(request);
  const networkResponsePromise = fetch(request)
    .then((response) => cacheResponse(cacheName, request, response))
    .catch(() => null);

  if (cachedResponse) {
    return cachedResponse;
  }

  const networkResponse = await networkResponsePromise;
  return networkResponse || Response.error();
}

async function cacheResponse(cacheName, request, response) {
  if (!response || !response.ok) {
    return response;
  }

  const cache = await caches.open(cacheName);
  cache.put(request, response.clone());

  return response;
}
