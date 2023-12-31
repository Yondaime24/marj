!(function (t) {
  "use strict";
  "function" == typeof define && define.amd
    ? define(["./blueimp-helper", "./blueimp-gallery"], t)
    : t(window.blueimp.helper || window.jQuery, window.blueimp.Gallery);
})(function (r, t) {
  "use strict";
  r.extend(t.prototype.options, {
    indicatorContainer: "ol",
    activeIndicatorClass: "active",
    thumbnailProperty: "thumbnail",
    thumbnailIndicators: !0,
  });
  var i = t.prototype.initSlides,
    e = t.prototype.addSlide,
    n = t.prototype.resetSlides,
    o = t.prototype.handleClick,
    a = t.prototype.handleSlide,
    s = t.prototype.handleClose;
  return (
    r.extend(t.prototype, {
      createIndicator: function (t) {
        var i,
          e,
          n = this.indicatorPrototype.cloneNode(!1),
          o = this.getItemProperty(t, this.options.titleProperty),
          a = this.options.thumbnailProperty;
        return (
          this.options.thumbnailIndicators &&
            (a && (i = this.getItemProperty(t, a)),
            void 0 === i &&
              (e = t.getElementsByTagName && r(t).find("img")[0]) &&
              (i = e.src),
            i && (n.style.backgroundImage = 'url("' + i + '")')),
          o && (n.title = o),
          n
        );
      },
      addIndicator: function (t) {
        if (this.indicatorContainer.length) {
          var i = this.createIndicator(this.list[t]);
          i.setAttribute("data-index", t),
            this.indicatorContainer[0].appendChild(i),
            this.indicators.push(i);
        }
      },
      setActiveIndicator: function (t) {
        this.indicators &&
          (this.activeIndicator &&
            this.activeIndicator.removeClass(this.options.activeIndicatorClass),
          (this.activeIndicator = r(this.indicators[t])),
          this.activeIndicator.addClass(this.options.activeIndicatorClass));
      },
      initSlides: function (t) {
        t ||
          ((this.indicatorContainer = this.container.find(
            this.options.indicatorContainer
          )),
          this.indicatorContainer.length &&
            ((this.indicatorPrototype = document.createElement("li")),
            (this.indicators = this.indicatorContainer[0].children))),
          i.call(this, t);
      },
      addSlide: function (t) {
        e.call(this, t), this.addIndicator(t);
      },
      resetSlides: function () {
        n.call(this), this.indicatorContainer.empty(), (this.indicators = []);
      },
      handleClick: function (t) {
        var i = t.target || t.srcElement,
          e = i.parentNode;
        if (e === this.indicatorContainer[0])
          this.preventDefault(t), this.slide(this.getNodeIndex(i));
        else {
          if (e.parentNode !== this.indicatorContainer[0])
            return o.call(this, t);
          this.preventDefault(t), this.slide(this.getNodeIndex(e));
        }
      },
      handleSlide: function (t) {
        a.call(this, t), this.setActiveIndicator(t);
      },
      handleClose: function () {
        this.activeIndicator &&
          this.activeIndicator.removeClass(this.options.activeIndicatorClass),
          s.call(this);
      },
    }),
    t
  );
});
