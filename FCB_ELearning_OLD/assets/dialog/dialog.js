$('body').prepend('<div id="dialog-box"></div>');
var dialog_box = new cbox("#dialog-box");
dialog_box.width = 250;
dialog_box.height = 150;
dialog_box.logo = '<i class="fa fa-lock"></i>';
dialog_box.title = "Confirmation";
dialog_box.backgroundcolor_header = "#f3bc08a6";
dialog_box.backgroundcolor = "";
dialog_box.init();
dialog_box.document.append('<div class="dialog001" style="background-color:white;padding:24px;border-radius:10px;font-weight:bolder;"><div class="txt"></div><div class="buttons"><button style="background-color:#0ba70b;color:white;border:1px solid green;border-radius:5px;" class="yes"><i class="fa fa-check"></i> Yes</button><button class="no" style="background-color:red;color:white;border:1px solid red;color:white;border-radius:5px;margin-left:5px;"><i class="fa fa-close"></i> No</button></div></div>');
var dialog = {
  confirm: function(msg, callback) {
    dialog_box.document.find(".dialog001").find(".txt").html(msg);
    dialog_box.document.find(".dialog001").find('.yes').off("click");
    dialog_box.document.find(".dialog001").find('.no').off("click");
    dialog_box.document.find(".dialog001").find('.yes').on("click", callback);
    dialog_box.document.find(".dialog001").find('.no').on("click", function() {
      dialog.close();
    });
    dialog_box.show();
  },
  close: function() {
    dialog_box.close();
  }
};