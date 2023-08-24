/**
 * Draggable modals
 * Created June 8, 2022 @ 13:21
 * 
 * var test = new cbox(".elementname");
 * test.init();
 * test.show();
 *
 *
 * Requirement: Jquery, 
 * Version: 1.0.0
 *
 * Created by: FCB Software Developer
 */
function cbox(elem) {
  this.x = 0;
  this.y = 0;
  this.elem = elem;
  this.is_hold = false;
  this.top = 0;
  this.left = 0;
  this.title = ""; //plaintxt
  this.logo = ""; // <i class="fa fa-logo"></i>
  this.backgroundcolor_header = "#2abb39";
  this.fontcolor_header = "white";
  this.backgroundcolor = "white";
  this.window_width = window.screen.availWidth;
  this.window_height = window.screen.availHeight;
  this.width = this.window_width * 0.3;
  this.height = this.window_height * 0.2;
  this.document = null;
  this.index = 100000;
  this.close = function() {
    $(this.elem).hide();
  }
  this.show = function() {
    $(this.elem).show();
  }
  this.init = function() {
    this.document = $(this.elem);
    if (this.top == 0)
      this.top = (this.window_height / 2) - this.height;
    if (this.left == 0)
      this.left = (this.window_width / 2) - (this.width / 2);
    $(this.elem).prepend('<style>*{box-sizing:border-box;user-select:none;-webkit-user-select:none;-moz-user-select:none;-o-user-select:none;-ms-user-select:none;-khtml-user-select:none;}</style><div style="position:relative;box-shadow:0 0 3px rgba(0,0,0,0.2);width:100%;height:50px;background-color:' + this.backgroundcolor_header + ';border-top-right-radius:10px;border-top-left-radius:10px;" class="box-in">\
        <div class="cbox-font-color" style="color:' + this.fontcolor_header + ';font-weight:bold;padding:10px;"> <span class="cbox-logo">' + this.logo + '</span>  <span class="cbox-title">' + this.title + '</span></div>\
        <div style="position:absolute;top:5px;right:5px;">\
          <button class="cclose" style="font-weight:8pt;width:30px;height:30px;color:white;border:1px solid #dfdfdf;background-color:#ff5b5b;font-weight:bold;border-radius:0px;border-top-right-radius:5px;border-bottom-right-radius:5px;border-top-left-radius:5px;border-bottom-left-radius:5px;float:left;"><i class="fa fa-window-close"></i></button>\
        </div>\
      </div>');
    $(this.elem).css({
      "display": "none",
      "border-top-right-radius" : "10px",
      "border-top-left-radius" : "10px",
      "background-color" : this.backgroundcolor,
      "box-shadow" : "0px 0px 6px rgba(0,0,0,0.5)",
      "width" : this.width + "px",
      "min-height": this.height + "px",
      "margin": "auto",
      "position": "fixed",
      "top":  this.top + "px",
      "left": this.left + "px",
      "z-index": "" + this.index + "",
      "border-bottom-left-radius" : "10px",
      "border-bottom-right-radius" : "10px"
    });
    this.event();
  }
  this.update = function() {
    $(this.elem).find(".cbox-logo").html(this.logo);
    $(this.elem).find(".cbox-title").html(this.title);
    $(this.elem).find(".box-in").css("background-color", this.backgroundcolor_header);
    $(this.elem).find(".cbox-font-color").css("color", this.fontcolor_header);
    $(this.elem).css({
      "display": "none",
      "border-top-right-radius" : "10px",
      "border-top-left-radius" : "10px",
      "background-color" : this.backgroundcolor,
      "box-shadow" : "0px 0px 6px rgba(0,0,0,0.5)",
      "width" : this.width + "px",
      "min-height": this.height + "px",
      "margin": "auto",
      "position": "fixed",
      "top":  this.top + "px",
      "left": this.left + "px",
      "z-index": "" + this.index + "",
      "border-bottom-left-radius" : "10px",
      "border-bottom-right-radius" : "10px"
    });
  }
  this.event = function() {
    var me = this;
    $(this.elem).on("mousedown", ".box-in", function(evt) {
      me.is_hold = true;
      me.x = evt.offsetX;
      me.y = evt.offsetY;
      $(".box-in").parent("div").css("z-index", "" + me.index - 10 + "");
      $(this).css("cursor", "move");
      $(me.elem).css("z-index", "" + (me.index + 10) + "");
    });
    $(this.elem).on("mouseup", ".box-in", function(evt) {
      me.is_hold = false;
      $(this).css("cursor", "");  
    });
    $(window).on("mouseup", function() {
      me.is_hold = false;
      $(me.elem).find('.box-in').css("cursor", "");
    });
    $(window).on("mousemove", function(evt) {
      if (me.is_hold) {
        var top = (evt.clientY - me.y);
        var left = (evt.clientX - me.x);
        if (top < 0) top = 0;
        if (left < 0) left = 0;
        if ((left + me.width) > me.window_width)
          left = me.window_width - me.width;
        if ((me.window_height - top) <= 121)
          top = me.window_height - 121;
        $(me.elem)[0].style.top = top + "px";
        $(me.elem)[0].style.left = left + "px";
      }
      evt.stopPropagation();
    });
    $(this.elem).on("mousemove", ".box-in", function(evt) {
      if (me.is_hold) {
        var top = (evt.clientY - me.y);
        var left = (evt.clientX - me.x);
        if (top < 0) top = 0;
        if (left < 0) left = 0;
        if ((left + me.width) > me.window_width)
          left = me.window_width - me.width;
        if ((me.window_height - top) <= 121)
          top = me.window_height - 121;
        $(me.elem)[0].style.top = top + "px";
        $(me.elem)[0].style.left = left + "px";
      }
      evt.stopPropagation();
    });
    $(this.elem).on("mousedown", ".cclose", function(evt) {
      evt.stopPropagation();
    });
    $(this.elem).on("click", ".cclose", function(evt) {
      $(this).parents(me.elem).hide();
      evt.stopPropagation();
    }); 
    $(window).on("resize", function() {
      me.window_width = window.screen.availWidth;
      me.window_height = window.screen.availHeight;
      me.top = (me.window_height / 2) - me.height;
      me.left = (me.window_width / 2) - (me.width / 2);
      $(me).css({
        "width" : this.width + "px",
        "min-height": this.height + "px",
        "top":  this.top + "px",
        "left": this.left + "px"
      });
    });
  }
}