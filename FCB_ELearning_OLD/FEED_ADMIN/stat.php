<?php 
  require_once "../___autoload.php";
?>
<style>
.ubox{background-color:white;min-height:260px;width:100%;margin-bottom:10px;border-radius:10px;box-shadow:0 0 3px rgba(0,0,0,0.3);position:relative;cursor:pointer;}
.profile-image{width:100%;height:120px;background-color:#3ea8cd;position:absolute;top:0;left:0;border-top-right-radius:10px;border-top-left-radius:10px;}
.profile-label{width:100%;height:140px;position:absolute;bottom:0;left:0;border-bottom-right-radius:10px;border-bottom-left-radius:10px;padding:10px;}
.online{color:green;}
.offline{color:red;}
</style>
<div style="padding:10px;">
  <div class="container-fluid" style="position:relative;">
    <input id="search_user" type="text" class="form-control" placeholder="Search user..." style="width:200px;margin-bottom:10px;" />
    <div style="font-size:13pt;position:absolute;top:5px;right:5px;"> <i class="fa fa-folder-open" style="color:orange;font-size:20pt;"></i> Records</div>
    <hr/>
    <div class="row" id="data_row">
      
    </div>
  </div>
</div>
<script>
  var is_search_loaded = true;
  function loadUser(search = "") {
    if (is_search_loaded) {
      is_search_loaded = false;
      $.post("<?php print ADMIN_PATH ?>route.php?r=searchUser", {search: search}).then(function(resp) {
        is_search_loaded = true;
        var data = JSON.parse(resp);
        var data_len = data.length;
        var t = '';
        var status = '';
        for (var i = 0; i < data_len; i++) {
          status = data[i]["status"] == 1 ? '<span class="online">Online</span>' : '<span class="offline">Offline</span>';
          t += '\
          <div class="col-lg-3 col-sm-6 col-md-4">\
            <div class="ubox" rel="' + data[i]["uid"] + '">\
              <div class="profile-image" style="background-image:url(' + ROOT_PATH  + 'user/route.php?r=profile&id=' + data[i]["uid"] + ');background-size:cover;background-repeat:no-repeat;">\
              </div>\
              <div class="profile-label">\
                <div>id: <span style="font-weight:bold;">' + data[i]['uid']  + '</span></div>\
                <div>Name: <span style="font-weight:bold;">' + data[i]['name'] + '</span></div>\
                <div>Branch: ' + data[i]['branch'] + '</div>\
                <div>Status: ' + status  + '</div>\
              </div>\
            </div>\
          </div>';
        }
        $('#data_row').html(t);
      });
    }
  }
  loadUser();
  $("#search_user").on("keyup", function(evt) {
    if (evt.keyCode == 13) {
      loadUser($(this).val());
    }
  });
  $("#data_row").on("click", ".ubox", function() {
    var uid = $(this).attr("rel");
    mainroute("<?php echo ADMIN_PATH; ?>stat_view.php?uid=" + uid + "&prev=<?php echo ADMIN_PATH; ?>stat.php?uid=" + uid);
  });
</script>