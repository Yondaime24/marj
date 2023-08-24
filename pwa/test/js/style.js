navigator.serviceWorker.register("worker.js");

function testApp() {
  alert("testing the application for");
}

var test = {
  load: function() {
    console.log("testing the program");
  }
};