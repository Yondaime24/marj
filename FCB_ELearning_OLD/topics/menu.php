<?php 
  use classes\access;
  use classes\auth;
  use classes\user;
  use classes\TimeSpent;
  use classes\usertask;
  use classes\UserQuiz;
  require_once '../___autoload.php';
  $auth = new auth();
  $auth->isLogin();
  $ac = new access();
  $admin = $ac->check();
  $user = new user();
  $user->load();
  $fullname = strtoupper($user->fname." ".$user->lname);
  $timeSpent = new TimeSpent();
  $timeSpent->type = "st";
  $allSpent = $timeSpent->allTime();

  $userQuiz = new UserQuiz();
  $ut = new usertask();
  $total_quiz_completed = $userQuiz->completed();
  $total_topic_completed = $ut->topic_completed();


  $search = isset($_GET['id']) ? $_GET['id'] : '';

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php print ROOT_PATH; ?>assets/images/feed-logo.png">
    <link rel="icon" type="image/png" href="<?php print ROOT_PATH; ?>assets/images/feed-logo.png">
    <title>F.E.E.D. | Topics</title>

    <link rel="stylesheet" type="text/css" href="<?php print ROOT_PATH; ?>assets/css/bootstrap3.min.css">
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/all.min.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/calendar4.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/chatBox.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/index.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/loader.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/loading_gif.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/loader_t.css" />
    <link rel="stylesheet" type="text/css" href="<?php print ROOT_PATH; ?>assets/sweetalert/dist/sweetalert2.min.css">

    <!-- main css -->
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/topics_menu.css" />
    <!-- /main css -->

    <script src="<?php print ROOT_PATH; ?>config/config.js"></script>
    
  </head>
  <body>
    <!-- profile information -->
    <button class="bar01 bars"><i class="fas fa-bars"></i></button>
    <div class="sider">
      <div class="profile">
        <button class="bar01 barclose"><i class="fas fa-close"></i></button>
        <div class="up-div">
        <center>
          <div class="profile-pic" style="background-image:url('<?php echo ROOT_PATH; ?>user/route.php?r=profile&id=<?php echo $user->idno; ?>');background-size:cover;background-repeat:no-repeat;">
          
          </div>
        </center>
        <center>
          <div class="profile-name"><?php print $fullname; ?></div>
        </center>
        <center>
          <div class="branch-des"><?php print $user->branchDes." (".$user->branchCode.")"; ?></div>
        </center>
        </div>
        <div class="topics-div">
          <span class="topics-f">Total Topics Finished</span>
          <div class="vl"></div>
          <div class="circleBase type">
            <span><?php print $total_topic_completed; ?></span>
          </div>
          <span class="spent-c"><?php print $allSpent; ?> <p>Spent</p></span>
          <div class="circleBase2 type2">
            <span><?php print $total_quiz_completed; ?></span>
          </div>
          <div class="vl2"></div>
          <span class="quiz-f">Total Quiz Finished</span>
        </div>
      </div>
    </div>
    <!-- end profile information -->
    </div>
    <div class="content" id="content">

      <div class="nav-btn">
        <a id="profile" href="../user/profile.php" title="Profile"><i class="fas fa-user"></i></a>
     </div>

      <!-- content -->
      <div class="container-fluid" style="margin-bottom:30px;padding:10px 40px 40px 40px;">
        <div style="position:fixed;z-index:1;width: 30vw;box-shadow: 0 15px 10px -15px #111;-moz-box-shadow: 0 15px 10px -15px #111;">
          <input id="searchbar" type="text" placeholder="Search..." autocomplete="off" class="form-control" />
          <button id="search_btn"><i class="fa fa-search"></i></button>
        </div>
        <div class="row" id="gen001" style="margin-top:40px;">
          <?php if ($admin): ?>
          <!-- <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="card01 card01-plus">
              <div style="width:100px;height:100px;color:#606060;position:absolute;top:0;bottom:0;right:0;left:0;margin:auto;">  
                <i class="fa fa-plus-circle" style="font-size:75pt;"></i>
              </div>      
            </div>
          </div> -->
          <?php endif; ?>
        </div>
      </div>
      <!-- end content -->
      <a href="#top" id="myBtn"><i class="fas fa-arrow-up"></i></a>
    </div>
  <div id="category-form">
    <div style="padding:20px;">
      <input type="hidden" name="cat_id" id="cat_id" />
      <div class="form-group">
        <label>Title</label>
        <input type="text" id="title" class="form-control" placeholder="Enter title" /> 
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea class="form-control" id="des" placeholder="Enter Description"></textarea>
      </div>
      <div style="text-align: center;padding: 3%;">
        <!-- <label>Cover(JPG,PNG)</label>
        <input type="file" name="file" id="file" class="form-control" style="cursor:pointer;" /> -->
          <label for="file" style="cursor: pointer;">
            Add Cover(JPG,PNG)
            <br>
            <i class="fa fa-2x fa-camera"></i>
            <input type="file" name="file" id="file" style="display: none;" />
            <br>
            <span id="imageName" style="color: green;"></span>
          </label>
      </div> 
      <div class="form-group">
        <button type="submit" id="submit" class="btn btn-info btn-sm btn-block"><i class="fa fa-save"></i> Save</button> 
      </div>        
    </div>
  </div>

  <div class="modal fade" id="loaderModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <div class="loading_bar">
            <img src="<?php print ROOT_PATH; ?>assets/images/loader.gif" width="50px">
          </div>
        </div>
          <div class="footer">
            <span>Loading...</span>
          </div>
      </div>
    </div>
</div>
            
  <script src="<?php print ROOT_PATH; ?>assets/js/jquery.min.js"></script>
  <script src="<?php print ROOT_PATH; ?>assets/js/bootstrap3.min.js"></script>
  <script src="<?php print ROOT_PATH; ?>assets/cbox/cbox.js"></script>
  <script src="<?php print ROOT_PATH; ?>assets/sweetalert/dist/sweetalert2.min.js"></script>
  <script>
    var admin = <?php print $admin ? "1" : "0";  ?>;
    var gen = $("#gen001");
    var cat_form = new cbox("#category-form");
    cat_form.title = "Category";
    cat_form.logo = '<i class="fa fa-list"></i>';
    cat_form.init();
    function open_modal() {

    }
    gen.on('click', '.card01-about-icon', function(evt) {
      $(".card01-des").fadeOut();
      var me = $(this);
      var des = me.parents('.card01').find(".card01-des");
      des.addClass("trans");
      des.show();
      evt.stopPropagation();
    });
    gen.on('click', '.bookmark', function() {
      var i = $(this).attr('index');
      var id = global_data[i].id;
      var title = global_data[i].title;
            Swal.fire({
                heightAuto: false,
                title: 'Bookmark?',
                text: 'Contents will be added to bookmarks!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, bookmark!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(ROOT_PATH + 'user/bookmark.php', {r: 'add', type: 'cat', id: id, title: title}).then(function(resp) {
                      var data = JSON.parse(resp);
                        Swal.fire({
                            heightAuto: false,
                            icon: 'success',
                            title: 'Success!',
                            text: 'Contents has been added to bookmarks!'
                        });
                    }, function() {
                      alert('Something went wrong!');
                    });
                    load_grid();




                    // beforeSend:function(){
                    //     $('#loaderModal').modal('show');
                    //     $("#gen001").html("<div style='width:100%;height:100%;display:flex;justify-content:center;align-items:center;'><div class='loader_t'></div>");
                    // },
                }
            })
    });
    $(".bars").on('click', function() {
      $('.sider').css("display", "block");
    });
    $(".barclose").on('click', function() {
      $('.sider').css("display", "none");
    });
    $("#searchbar").on("keyup", function(evt) {
      if(evt.keyCode == 13) {
        load_grid($("#searchbar").val(), true);
      }
    });
    $('#search_btn').on('click', function() {
      load_grid($("#searchbar").val(), true);
    });
    gen.on("click", ".card01-plus", function(evt) {
      form_clear();
      cat_form.show();
      evt.stopPropagation();
    });
    load_grid('<?php print $search; ?>');
    $("#submit").on("click", function() {
      var cat_id = $("#cat_id");
      var title = $("#title");
      var des = $("#des");
      var file = $("#file");
      title.removeClass("red");
      des.removeClass("red");
      title.removeClass("green");
      des.removeClass("green");
      if (title.val().trim() == "") {
        title.addClass("red");
        return;
      } else {
        title.addClass("green");
      }
      if (des.val().trim() == "") {
        des.addClass("red");
        return;
      } else {
        des.addClass("green");
      }
      var data = new FormData();
      data.append("cat_id", cat_id.val());
      data.append("title", title.val());
      data.append("des", des.val());
      data.append("file", file[0].files[0]);
      var xml = new XMLHttpRequest();
      xml.onload = function() {
        var resp = JSON.parse(xml.responseText);
        $("#submit").html(`<i class="fa fa-save"></i> Save`);
        cat_form.close();
        load_grid();
        form_clear();
        cat_form.close();
      }
      xml.upload.onprogress = function(evt) {
        var loaded = evt.loaded;
        var total = evt.total;
        var percent = parseInt((loaded / total) * 100);
        $("#submit").html("Uploading... "+ percent + "%"); 
      }

      xml.open("POST", ROOT_PATH + "topics/route.php?r=Category/Save");
      xml.send(data);
    });
    
    function load_grid(search = "", load = false) {
      if (load) {
        $("#gen001").html("Searching...");
      }
      $.ajax({
        url: ROOT_PATH + "topics/route.php?r=category/list",
        type: "post",
        data: {search: search}
      }).then(function(resp) {
        global_data = JSON.parse(resp);
        var len = global_data.length;
        var t = "";
        var btn = "";
        if(len == 0){
          t += '<div style="color:red;">'; 
          t += 'No Added Contents Yet!'; 
          t += '</div>'; 
        }
        for (var i = 0; i < len; i++) {
          console.log(global_data[i].objid);
          if (admin)
            btn = '<button class="btn btn-default btn-md trash menu-btn" index="' + i + '"  rel="' + global_data[i].id + '"><i class="fa fa-trash" style="color:#d9534f;"></i></button>\
            <button class="btn btn-default btn-md edit menu-btn" index="' + i + '"  rel="' + global_data[i].id + '"><i class="fa fa-edit" style="color:#337ab7;"></i></button>\
            ';        
          t += '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">\
            <div class="card01">\
              <div class="card01-img">\
                <img style=\"width:100%;height:100%;object-fit:cover;;border-top-right-radius:10px;border-top-left-radius:10px;\" src=\"' + ROOT_PATH+'file/open.php?route=cat&id=' + global_data[i].id + '\" />\
                <div class="card01-title">' + global_data[i].title + '</div>\
                <div class="card01-about-icon"><i class="fas fa-exclamation-circle"></i></div>\
              </div>\
              <div class="card01-des">\
                Description:\
                <div style="margin-left:20px;margin-top:10px;text-indent:20px;text-align:justify;">\
                ' + global_data[i].des + '\
                </div>\
                <button class="card01-close"><i class="fa fa-close"></i></button>\
              </div>\
              <div class="card01-button">\
                <div class="btn-group">\
                  ' + btn + '\
                  <button class="btn btn-default btn-md start menu-btn" rel="' + global_data[i].id + '"><i class="fa fa-flag"></i> Start</button>\
                </div>';  
          if(global_data[i].objid == null){
          t += '  <div index="' + i + '" class="bookmark" style="font-weight:bold;font-size:25pt;position:absolute;bottom:0px;left:10px;color:#fe9400;color:gray;">\
                  <i class="fa fa-bookmark hover"></i>\
                </div>\
              </div>\
            </div>\
          </div>';
        }else{
          t += '  <div style="font-weight:bold;font-size:25pt;position:absolute;bottom:0px;left:10px;color:#fe9400;color:orange;cursor:default;">\
                  <i class="fa fa-bookmark"></i>\
                </div>\
              </div>\
            </div>\
          </div>';
        }
        }
        if (admin)
        t += '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">\
            <div class="card01 card01-plus">\
              <div style="width:100px;height:100px;color:#606060;position:absolute;top:0;bottom:0;right:0;left:0;margin:auto;">\
                <i class="fa fa-plus-circle" style="font-size:75pt;"></i>\
              </div>\
            </div>\
          </div>';
        $("#gen001").html(t);
      }, function() {
        alert("Something went wrong!");
      });
    }

    function form_clear() {
      $("#cat_id").val("");
      $("#title").val("");
      $("#des").val("");
      $("#file").val("");
      $("#imageName").text("");
      var cat_id = $("#cat_id");
      var title = $("#title");
      var des = $("#des");
      var file = $("#file");
      title.removeClass("red");
      des.removeClass("red");
      title.removeClass("green");
      des.removeClass("green");
    }
    gen.on("click", ".trash", function(evt) {
      var i = $(this).attr("index");
            Swal.fire({
                heightAuto: false,
                title: 'Are you sure?',
                text: 'You wont be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                    url: ROOT_PATH + "topics/route.php?r=category/trash",
                    type: "post",
                    data: {id: global_data[i].id},
                     beforeSend:function(){
                        $('#loaderModal').modal('show');
                        $("#gen001").html("<div style='width:100%;height:100%;display:flex;justify-content:center;align-items:center;'><div class='loader_t'></div>");
                    },
                    success: function(data) {
                         $('#loaderModal').modal('hide');
                         load_grid("", false);
                        Swal.fire({
                            heightAuto: false,
                            icon: 'success',
                            title: 'Success!',
                            text: 'Category Successfull Deleted!'
                        });
                    }
                  });
                }
              });
      evt.stopPropagation();
    });
    gen.on("click", '.edit', function(evt) {
      form_clear();
      var id = $(this).attr("rel");
      var i = $(this).attr("index");
      cat_form.show();
      $("#cat_id").val(global_data[i].id);
      $("#title").val(global_data[i].title);
      $("#des").val(global_data[i].des);
      evt.stopPropagation();
    });
    gen.on("click", ".start", function(evt) {
      var id = $(this).attr("rel");
      window.location.href = ROOT_PATH + "topics/Topics.php?cat_id=" + id;
      evt.stopPropagation();
    });
    var global_data = [];
    gen.on("click", ".card01-img", function(evt) {
      var id = $(this).parents('.card01').find(".start").attr("rel");
      window.location.href = ROOT_PATH + "topics/Topics.php?cat_id=" + id;
      evt.stopPropagation();
    });
    gen.on("click", ".card01-close", function(evt) {
      $(this).parents('.card01-des').fadeOut();
      evt.stopPropagation();
    });
    gen.on("mouseover", ".card01",function(evt) {
      //$('.card01').removeClass("card01-hover");;
      $(this).addClass("card01-hover");
      evt.stopPropagation();
    });

//  SCROLL TO TOP   
    $(document).ready(function(){
      $("a[href='#top']").click(function() {
      $("html, body").animate({scrollTop: 0}, "slow");
      return false;
      });
      $("body").scroll(function(){
        var $this = $(this),$mybutton = $('#myBtn');
        if($this.scrollTop() > 20){
          ($mybutton).css('display', 'block');
        }else{
          ($mybutton).css('display', 'none');
        }
      });
    });
//FILE
    var input = document.getElementById("file");
    var imageName = document.getElementById("imageName");
    input.addEventListener("change", function(){
      var inputImage = document.querySelector("input[type=file]").files[0];
      imageName.innerText = inputImage.name;
    });

  </script>
  </body>
</html>