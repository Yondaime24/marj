window.addEventListener("load", function() {
  navigator.serviceWorker.register(ROOT_PATH + "worker.js");
});