const CACHE_NAME = 'cashly-v1';

// Archivos que se guardan en caché para uso sin internet
const CACHE_URLS = [
    '/',
    '/dashboard',
    '/manifest.json',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
    'https://cdn.jsdelivr.net/npm/chart.js',
];

// Instalación — guarda los archivos en caché
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(CACHE_URLS);
        })
    );
    self.skipWaiting();
});

// Activación — limpia cachés viejos
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys.filter(key => key !== CACHE_NAME)
                    .map(key => caches.delete(key))
            );
        })
    );
    self.clients.claim();
});

// Fetch — sirve desde caché si no hay internet
self.addEventListener('fetch', event => {
    // Solo cachea GET, no POST (formularios siempre van al servidor)
    if (event.request.method !== 'GET') return;

    event.respondWith(
        caches.match(event.request).then(cached => {
            // Si está en caché y no hay internet, devuelve el caché
            return fetch(event.request)
                .then(response => {
                    // Actualiza el caché con la respuesta nueva
                    if (response.status === 200) {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then(cache => {
                            cache.put(event.request, clone);
                        });
                    }
                    return response;
                })
                .catch(() => cached); // Sin internet, usa caché
        })
    );
});
