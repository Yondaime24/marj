<?php 
  use classes\auth;
  use classes\access;
  use classes\userlevel;
  require_once "../___autoload.php";
  auth::isLogin();
  $ac = new access();
  $ul = new userlevel();
  $ul->status = "active";
  $admin = $ul->getall();
  $len = count($admin);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/all.min.css" />    
    <style>
      *{box-sizing:border-box;}
      @media(max-width:700px){
        #modal1{width:90%!important;}
      }
      @media(max-width:360px){
        #modal1{width:360px!important;}
      }
      @keyframes round{
      from{transform:rotate(0deg);}to{transform:rotate(360deg);}
      }
      .rotate{animation:round 1s ease infinite;}
    </style>
  </head>
  <body>
    <?php if ($ac->check()): ?>
    <div class="container">
      <div class="row">
        <div class="col-md-8 offset-md-2">
          <div style="padding:20px 0  0 0;">
            <button id="add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add new Admin</button>
          </div>
          <div class="card" style="margin-top:20px;">
            <div class="card-body"><i class="fa fa-user-alt"></i> Authorized User</div>
            <div class="table-responsive">
              <table class="table table-sm" style="margin-bottom:0px!important;">
                <thead>
                  <tr>
                    <th>ID No.</th>
                    <th>Name</th>
                    <th>Branch</th>
                    <th>Access Level</th>
                    <th style="width:150px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php $i = 0; while ($i < $len): ?>
                  <tr class="tr">
                    <td><?php print $admin[$i]["user_id"]; ?></td>
                    <td><?php print $admin[$i]["fname"] . " " . $admin[$i]["lname"]; ?></td>
                    <td><?php print $admin[$i]["des"] . " (".$admin[$i]["branch"].")"; ?></td>
                    <td><?php print $admin[$i]["level"] . " (".$admin[$i]["ulevel"].")"; ?></td>
                    <td> 
                      <div class="btn-group">
                        <button rel="<?php echo $admin[$i]["user_id"]; ?>" class="rem btn btn-danger btn-sm"><i class="fa fa-close"></i> <span>Remove Access</span></button>
                      </div>
                    </td>
                  </tr>
                <?php $i++; endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="con" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,0.1);">
      <div style="position:relative;width:700px;min-height:200px;background-color:white;margin:auto;margin-top:10%;border-radius:10px;box-shadow:0 0 6px rgba(0,0,0,0.3);padding:10px;" id="modal1">
        <button onclick="$(this).parents('#con').hide();window.location.reload();" style="width:25px;height:25px;font-size:8pt;background-color:#e50000;color:white;border:1px solid #eee;border-radius:10px;position:absolute;top:5px;right:5px;">
          <i class="fa fa-close"></i>
        </button>
        <div style="float:left;"><i class="fa fa-search"></i> Search</div>
        <br />
        <hr />
        <input id="search" type="search" class="form-control" placeholder="Search Name or ID" />
        <div id="result" style="height:200px;overflow-y:auto;margin-top:10px;">
        </div>
      </div>
    </div>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/cbox/cbox.js"></script>
    <script src="../assets/dialog/dialog.js"></script>
    <script>
      $(".rem").on("click", function() {
        var id = $(this).attr("rel");
        var btn = $(this).parents(".tr");
        dialog.confirm("Remove access?", function() {
          $.ajax({url: "../access/r.php?route=deactive", type: "post", data:{idno:id}}).then(function() {
            alert("Removed!");
            dialog.close();
            btn.remove();
          }, function() {
            alert("Something went wrong!");
          });
        });
      });
    </script>
    <?php else: ?>
      Unauthorized Access!
    <?php endif; ?>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/cbox/cbox.js"></script>
    <script src="../assets/dialog/dialog.js"></script>
    <script>
      $("#add").on("click", function() {
        $("#con").show();
      });
      var res = $("#result");
      var t = "";
      $("#search").on("keyup", function(evt) {
        var search = $(this);
        if (evt.keyCode == 13) {  
          res.html("<i class=\"fa fa-refresh rotate\"></i> Searching...");
          $.ajax({url: "../access/r.php?route=search", type: "post", data: {search: search.val()}}).then(function(o) {
            t = "";
            search.val("");  
            res.html(""); 
            var data = JSON.parse(o);
            var len = data.length;
            if (len > 0) {
              t += "<table class=\"table table-sm\">";
              for (var i = 0; i < len; i++) {
                t += "<tr>";
                t += "<td>";
                t += "<div class=\"btn-group\">";
                t += "<button rel=\"" + data[i]["idno"] + "\"  class=\"add btn btn-primary btn-sm\"><i class=\"fa fa-user-plus\"></i></button>";
                t += "</div>";
                t += "</td>";
                t += "<td>" + data[i]["idno"] + "</td>";
                t += "<td>" + data[i]["fname"] + " " + data[i]["lname"] + "</td>";
                t += "<td>" + data[i]["des"].toUpperCase() + "</td>";
                t += "</tr>";
              }
              t += "</table>";
            } else {
              t += "<div style=\"\">";
              t += "No Result!";
              t += "</div>";
            }
            res.html(t);
          }, function() {
            alert("Something went wrong!");
          });
        }
      });
      $("#result").on("click", ".add", function() {
        var idno = $(this).attr("rel");
        dialog.confirm("Access as an Admin?", function() {
          $.ajax({url: "../access/r.php?route=add", type: "post", data:{idno: idno}}).then(function() {
            dialog.close();
          }, function() {
            alert("Something went wrong!");
            dialog.close();
          });
        });
      });
    </script>
  </body>
</html>