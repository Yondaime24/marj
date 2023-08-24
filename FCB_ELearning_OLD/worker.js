// var ROOT_PATH = "/FCB_Elearning/";
// const url_list = [
//   ROOT_PATH + "assets/js/jquery.min.js",
//   ROOT_PATH + "assets/js/bootstrap3.min.js",
//   ROOT_PATH + "assets/images/loader.gif"  
// ];
// self.addEventListener("install", function(e) {
//   console.log("installing");
//   e.waitUntil(
//     caches.open("feed17")
//     .then(function(cache) {
//       cache.addAll(url_list);
//     })
//   );
// });
// self.addEventListener("fetch", function(e) {
//   alert(1); 
//   if (e.request.method != "GET")
//     return;
//   e.respondWith(
//     (async function() {
//         const cache = await caches.open("feed17");
//         const resp = await cache.match(e.request);
//         if (resp) {
//           return resp;
//         }
//         return fetch(e.request);
//     })()
//   );
// });

function SearchExtension(s) {
  if (s.search(".js") > -1) return true;
  if (s.search(".css") > -1) return true;
  
  return false;
}
self.addEventListener("fetch", function(e) {  
  console.log("fetching the request");
  if (e.request.method != "GET")
    return;
  e.respondWith(
    (async function() {
      // if (SearchExtension(e.request.url)) {
      //   const cache = await caches.open("feed17");
      //   const resp = await cache.match(e.request);
      //   if (resp) {
      //     return resp;
      //   }
      //   caches.open("feed17").then(function(c) {
      //     c.add(e.request.url);
      //   });
      //   return fetch(e.request);
      // } else
      let data = fetch(e.request);
      
      return data;
    })()
  );
});