const CACHE_NAME = 'product-form-cache-v1';
const OFFLINE_URL = '/offline.html';
const IMAGE_CACHE = 'product-images-cache-v1';

const ASSETS_TO_CACHE = [
    '/css/admin/products/form.css',
    '/js/admin/products/create.js',
    '/offline.html',
    '/images/placeholder.png'
];

// Установка Service Worker
self.addEventListener('install', (event) => {
    event.waitUntil(
        Promise.all([
            caches.open(CACHE_NAME).then((cache) => {
                return cache.addAll(ASSETS_TO_CACHE);
            }),
            caches.open(IMAGE_CACHE)
        ])
    );
});

// Активация и очистка старых кэшей
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME && name !== IMAGE_CACHE)
                    .map((name) => caches.delete(name))
            );
        })
    );
});

// Обработка запросов
self.addEventListener('fetch', (event) => {
    // Стратегия для изображений: сначала кэш, потом сеть
    if (event.request.url.match(/\.(jpg|jpeg|png|gif|webp)$/)) {
        event.respondWith(
            caches.open(IMAGE_CACHE).then((cache) => {
                return cache.match(event.request).then((response) => {
                    return (
                        response ||
                        fetch(event.request).then((networkResponse) => {
                            cache.put(event.request, networkResponse.clone());
                            return networkResponse;
                        })
                    );
                });
            })
        );
        return;
    }

    // Стратегия для остальных ресурсов: сеть, потом кэш
    event.respondWith(
        fetch(event.request)
            .then((response) => {
                if (response.status === 200) {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                return caches.match(event.request).then((response) => {
                    if (response) {
                        return response;
                    }
                    // Показываем офлайн-страницу, если ресурс не найден в кэше
                    if (event.request.mode === 'navigate') {
                        return caches.match(OFFLINE_URL);
                    }
                    return new Response('', { status: 408, statusText: 'Request timed out' });
                });
            })
    );
});

// Синхронизация отложенных запросов
self.addEventListener('sync', (event) => {
    if (event.tag === 'product-form-sync') {
        event.waitUntil(syncProductForm());
    }
});

// Обработка отложенных запросов
async function syncProductForm() {
    try {
        const db = await openDB();
        const pendingUploads = await db.getAll('pendingUploads');
        
        for (const upload of pendingUploads) {
            try {
                const response = await fetch('/api/products', {
                    method: 'POST',
                    body: upload.formData,
                });
                
                if (response.ok) {
                    await db.delete('pendingUploads', upload.id);
                }
            } catch (error) {
                console.error('Failed to sync:', error);
            }
        }
    } catch (error) {
        console.error('Sync failed:', error);
    }
}