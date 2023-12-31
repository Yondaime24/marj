!(function (e) {
  "use strict";
  "function" == typeof define && define.amd
    ? define(["./blueimp-helper", "./blueimp-gallery"], e)
    : e(window.blueimp.helper || window.jQuery, window.blueimp.Gallery);
})(function (e, l) {
  "use strict";
  e.extend(l.prototype.options, { fullScreen: !1 });
  var n = l.prototype.initialize,
    t = l.prototype.close;
  return (
    e.extend(l.prototype, {
      getFullScreenElement: function () {
        return (
          document.fullscreenElement ||
          document.webkitFullscreenElement ||
          document.mozFullScreenElement ||
          document.msFullscreenElement
        );
      },
      requestFullScreen: function (e) {
        e.requestFullscreen
          ? e.requestFullscreen()
          : e.webkitRequestFullscreen
          ? e.webkitRequestFullscreen()
          : e.mozRequestFullScreen
          ? e.mozRequestFullScreen()
          : e.msRequestFullscreen && e.msRequestFullscreen();
      },
      exitFullScreen: function () {
        document.exitFullscreen
          ? document.exitFullscreen()
          : document.webkitCancelFullScreen
          ? document.webkitCancelFullScreen()
          : document.mozCancelFullScreen
          ? document.mozCancelFullScreen()
          : document.msExitFullscreen && document.msExitFullscreen();
      },
      initialize: function () {
        n.call(this),
          this.options.fullScreen &&
            !this.getFullScreenElement() &&
            this.requestFullScreen(this.container[0]);
      },
      close: function () {
        this.getFullScreenElement() === this.container[0] &&
          this.exitFullScreen(),
          t.call(this);
      },
    }),
    l
  );
});
