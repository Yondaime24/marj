!(function () {
  "use strict";
  function t(t, e) {
    var n;
    for (n in e) e.hasOwnProperty(n) && (t[n] = e[n]);
    return t;
  }
  function n(t) {
    if (!this || this.find !== n.prototype.find) return new n(t);
    if (((this.length = 0), t))
      if (
        ("string" == typeof t && (t = this.find(t)),
        t.nodeType || t === t.window)
      )
        (this.length = 1), (this[0] = t);
      else {
        var e = t.length;
        for (this.length = e; e; ) this[(e -= 1)] = t[e];
      }
  }
  (n.extend = t),
    (n.contains = function (t, e) {
      do {
        if ((e = e.parentNode) === t) return !0;
      } while (e);
      return !1;
    }),
    (n.parseJSON = function (t) {
      return window.JSON && JSON.parse(t);
    }),
    t(n.prototype, {
      find: function (t) {
        var e = this[0] || document;
        return (
          "string" == typeof t &&
            (t = e.querySelectorAll
              ? e.querySelectorAll(t)
              : "#" === t.charAt(0)
              ? e.getElementById(t.slice(1))
              : e.getElementsByTagName(t)),
          new n(t)
        );
      },
      hasClass: function (t) {
        return (
          !!this[0] &&
          new RegExp("(^|\\s+)" + t + "(\\s+|$)").test(this[0].className)
        );
      },
      addClass: function (t) {
        for (var e, n = this.length; n; ) {
          if (!(e = this[(n -= 1)]).className) return (e.className = t), this;
          if (this.hasClass(t)) return this;
          e.className += " " + t;
        }
        return this;
      },
      removeClass: function (t) {
        for (
          var e, n = new RegExp("(^|\\s+)" + t + "(\\s+|$)"), i = this.length;
          i;

        )
          (e = this[(i -= 1)]).className = e.className.replace(n, " ");
        return this;
      },
      on: function (t, e) {
        for (var n, i, s = t.split(/\s+/); s.length; )
          for (t = s.shift(), n = this.length; n; )
            (i = this[(n -= 1)]).addEventListener
              ? i.addEventListener(t, e, !1)
              : i.attachEvent && i.attachEvent("on" + t, e);
        return this;
      },
      off: function (t, e) {
        for (var n, i, s = t.split(/\s+/); s.length; )
          for (t = s.shift(), n = this.length; n; )
            (i = this[(n -= 1)]).removeEventListener
              ? i.removeEventListener(t, e, !1)
              : i.detachEvent && i.detachEvent("on" + t, e);
        return this;
      },
      empty: function () {
        for (var t, e = this.length; e; )
          for (t = this[(e -= 1)]; t.hasChildNodes(); )
            t.removeChild(t.lastChild);
        return this;
      },
      first: function () {
        return new n(this[0]);
      },
    }),
    "function" == typeof define && define.amd
      ? define(function () {
          return n;
        })
      : ((window.blueimp = window.blueimp || {}), (window.blueimp.helper = n));
})();
