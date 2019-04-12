var cacheName = 'internetfactory-V3';

const offlineUrl = 'offline-index.php';
var filesToCache = [
    '/offline-index.php',
    '/_css/style.css',
    '/_css/jquery-ui.min.css',
    '/_css/select2.min.css',
    '/_fonts/neosans_bold-webfont.eot',
    '/_fonts/neosans_bold-webfont.svg',
    '/_fonts/neosans_bold-webfont.ttf',
    '/_fonts/neosans_bold-webfont.woff',
    '/_fonts/neosans-webfont.eot',
    '/_fonts/neosans-webfont.svg',
    '/_fonts/neosans-webfont.ttf',
    '/_fonts/neosans-webfont.woff',
    '/_img/icons-checkmark-64.png',
    '/_img/snaptime_bg.jpg',
    '/_img/snaptime_logo.png',
    '/_img/ui-icons_444444_256x240.png',
    '/_img/ui-icons_555555_256x240.png',
    '/_js/sources/jquery-3.3.1.min.js',
    '/_js/sources/jquery-ui.min.js',
    '/_js/sources/select2.full.min.js',
];
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(cacheName)
        .then(function(cache) {
            console.info('[serviceworker.js] cached all files');
            return cache.addAll(filesToCache);
        })
    );
});

self.addEventListener('fetch', function(event) {
    if(navigator.onLine){
        event.respondWith(
            caches.match(event.request)
            .then(function(response) {
                if(response){
                    return response
                }else{
                    // clone request stream
                    // as stream once consumed, can not be used again
                    var reqCopy = event.request.clone();

                    return fetch(reqCopy, {credentials: 'include'}) // reqCopy stream consumed
                    .then(function(response) {
                        // bad response
                        // response.type !== 'basic' means third party origin request
                        if(!response || response.status !== 200 || response.type !== 'basic') {
                            return response; // response stream consumed
                        }
                        // clone response stream
                        // as stream once consumed, can not be used again
                        var resCopy = response.clone();
                        // add response to cache and return response
                        caches.open(cacheName)
                        .then(function(cache) {
                            const url = new URL(event.request.url);
                            if (filesToCache.includes(url.pathname)) return cache.put(reqCopy, resCopy); // reqCopy, resCopy streams consumed
                            return;
                        });
                        return response; // response stream consumed
                    })
                }
            })
        );
    }else{
        event.respondWith(
            fetch(event.request.url).catch(error => {
                // Return the offline page
                return caches.match(offlineUrl);
            })
        );
    }

});

self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys()
        .then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cName) {
                    if(cName !== cacheName){
                        return caches.delete(cName);
                    }
                })
            );
        })
    );
});
