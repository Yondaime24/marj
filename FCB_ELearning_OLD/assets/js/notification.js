
var n = new cbox("#notif001");
function notification_get() {
  n.document.find(".content").html("<div style=\"padding:20px;font-size:15pt;\"><i class=\"fa fa-refresh rotate\"></i> Loading notification...</div>");
  n.show();
  $.ajax({url: ROOT_PATH + "notification/index.php?route=get", type: "post"}).then(function(resp) {
    var data = JSON.parse(resp);
    n.document.find('.content').html();
    var len = data.length;
    var t = '<style>.lll0110:hover{background-color:#eee!important;}</style><div class="list-group">';
    for (var i = 0; i < len; i++) {
      var data_msg = JSON.parse(data[i]["msg"]);
      var query = '#';
      if (data_msg["code"] == "TOPIC") {
        query = '';
        query += ROOT_PATH + 'topics/menu.php';
        query += '?cat_id=' + data_msg["id"]["cat_id"];
        query += '&main_id=' + data_msg["id"]["main_id"];
        query += '&sub_id=' + data_msg["id"]["sub_id"];
        //?cat_id=14&main_id=33&sub_id=33
      } else if (data_msg['code'] == 'QUIZ') {
        query = '';
        query += ROOT_PATH + 'QUIZ/';
      } else if (data_msg['code'] == 'ST') {
        query = '';
        query += ROOT_PATH + 'topics/menu.php';
      }
      t += '<a href="' + query + '" style="text-decoration:none;"><div class="list-group-item lll0110"><i class="fa fa-earth" style="color:#e77805;font-size:10pt;"></i> <span>' + data_msg["msg"] + '</span> <br /> <span style="font-size:9pt;"> ' + data[i]["dt_ago"] + ' ago</span></div></a>';
    }
    t += '</div>';
    n.document.find(".content").html(t);
    n.document.find(".content").scrollTop(0);
  }, function() {
    alert("something went wrong!");
  });
}
function check_notif() {
  $.ajax({url: ROOT_PATH + "notification/index.php?route=check", type: "post"}).then(function(resp) {
    if (resp > 0) {
      $(".notif-badge-txt").show();
      $("#notif-badge").show();  
    } else {
      $(".notif-badge-txt").hide();
      $("#notif-badge").hide();
    }
    $(".notif-badge-txt").html(resp);
    $("#notif-badge").html(resp);
  });
}
check_notif();
$('body').prepend("<div id=\"notif001\">");
n.title = "Notification";
n.width = window.screen.availWidth * 0.6;
n.logo = '<i class="fa fa-earth"></i>';
n.init();
n.document.append('<div class="content" style="height:250px;overflow-y:auto;"></div>');
setInterval(function() {
  check_notif();
}, 2000);
