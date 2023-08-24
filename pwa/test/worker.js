// const url_cache = [
//   "js/style.js"
// ];

// self.addEventListener("install", function(e) {
//   e.waitUntil(
//     caches.open("fcb")
//     .then(function(cache) {
//       cache.addAll(url_cache);
//     })
//   );
// });

// self.addEventListener("activate", function(e) {

// });
function SearchExtension(s) {
  if (s.search(".js") > -1) return true;
  if (s.search(".css") > -1) return true;
  
  return false;
}
self.addEventListener("fetch", function(e) {  
  if (e.request.method != "GET")
    return;
  e.respondWith(
    (async function() {
      if (SearchExtension(e.request.url)) {
        const cache = await caches.open("fcb");
        const resp = await cache.match(e.request);
        if (resp) {
          return resp;
        }
        caches.open("fcb").then(function(c) {
          c.add(e.request.url);
        });
        return fetch(e.request);
      } else 
        return fetch(e.request);
    })()
  );
});