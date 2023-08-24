<?php 
  use classes\auth;
  use classes\user;
  use classes\usertask;
  use classes\access;
  use classes\TimeSpent;
  use classes\UserQuiz;
  use classes\Date as Date2;
  use classes\Bookmark\FeedBookmark as bookmark;
  require_once '../___autoload.php';
  auth::isLogin();
  $user = user::getInstance();
  $user->load();
  $user->online();
  $ut = new usertask();
  $userQuiz = new UserQuiz();
  $week_time_spent = $ut->week_time_spent();
  $total_quiz_completed = $userQuiz->completed();
  $total_topic_completed = $ut->topic_completed();
  $access = new access();
  $admin = $access->check();

  $ts = new TimeSpent();
  $ts->type = "st";
  $spent = $ts->thisWeek();  
  $book = new bookmark();
  $num_book_mark = $book->getBadge();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/images/feed-logo.png">
    <link rel="icon" type="image/png" href="../assets/images/feed-logo.png">
    <title>F.E.E.D. | Profile</title>

    <link rel="stylesheet" href="<?php print ROOT_PATH ?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH ?>assets/css/all.min.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH ?>assets/css/user_profile.css" />
    <link rel="stylesheet" type="text/css" href="<?php print ROOT_PATH; ?>assets/sweetalert/dist/sweetalert2.min.css">
    <style>
   .top-loader{width:90%;height:5px;z-index:1000;position:fixed;top:0px;left:0px;}
    .top-loader div{width:100%;height:100%;background-color:#d73801;animation:tow ease-in 10s}
    @keyframes tow{
      0%{width:0%} 5%{width:30%} 50%{width:60%} 100%{width:width:90%;}
    }
    .list00001:hover{background-color:#eee;}
  </style>  
  </head>
  <body>
    <div class="top-loader"><div></div></div>
    <main>

      <section class="left-div">
        <div class="user-prof-info">
          <div class="user-profile">
            <?php if ($admin): ?>
            <div class="div-access">
              <div class="vl"></div>
              <a href="<?php print ADMIN_PATH; ?>" title="FEED Admin"><i class="fas fa-key"></i></a>
            </div>
            <?php endif; ?>
            <img id="profile-pic01" src="<?php echo ROOT_PATH; ?>user/route.php?r=profile&id=<?php echo $user->idno; ?>" alt="user" />
            <div style="position:absolute;bottom:10px;right:20px;">
              <button id="pic-upload" style="border-radius:5px;border:1px solid grey;background-color:white;">
                <i class="fa fa-camera"></i>
              </button>
            </div>
          </div>
          <div class="user-info">
           <div class="user-details">
              <span><?php print $user->idno; ?></span><br>
              <span class="name"><?php print $user->fname.' '.$user->lname; ?></span><br>
              <span><?php print $user->position; ?></span><br>
              <span><?php print $user->branchDes; ?></span>
           </div>
          </div>
        </div>
        <div class="user-options">
          <div class="option-left-div"></div>
            <div class="option-right-div" >
              <div class="welcome">
              <span id="wel_msg">Welcome back <?php print strtoupper($user->fname); ?>!</span><br>
              <span id="wel_sub">You're doing great. Keep it up!</span>
              </div>
              <div class="btn1">
              <a href="#" id="notif-data" style="position:relative;"><img src="<?php print ROOT_PATH ?>assets/images/notify.png"> Notifications <div class="notif-badge-txt badge badge-primary" style="font-weight:bold;position:absolute;right:10px;top:10px;padding:5px;border-radius:50%;background-color:#ff6f6f;"></div></a> 
              </div>
              <div class="btn2">
              <a href="<?php print ROOT_PATH ?>topics/menu.php"><img src="<?php print ROOT_PATH ?>assets/images/check-list.png"> Topics</a>
              </div>
              <div class="btn3">
              <a href="<?php print ROOT_PATH ?>Quiz/"><img src="<?php print ROOT_PATH ?>assets/images/choose.png"> Quizzes</a>
              </div>
              <div class="btn4"  style="position:relative;">
                <a href="#" id="bookmark-btn"><img src="<?php print ROOT_PATH ?>assets/images/bookmark.png"> Bookmarks
                  
                </a>
                <!-- <div class="badge badge-primary" style="font-weight:bold;position:absolute;left:280px;top:20px;padding:5px;border-radius:50%;background-color:#ff6f6f;"><?php print $num_book_mark; ?></div> -->
              </div>
              <div class="btn5">
              <a href="#" id="memo-btn" ><img src="<?php print ROOT_PATH ?>assets/images/note.png"> Memos</a>
              </div>
              <div class="btn6">
              <a href="#" id="msg-btn"><img src="<?php print ROOT_PATH ?>assets/images/message.png"> Messages</a>
              </div>
              <div class="btn7">
              <a href="<?php print ROOT_PATH ?>certificates"><img src="<?php print ROOT_PATH ?>assets/images/certificate.png"> Certificates</a>
              </div>
              <div class="btn8">
                 <?php if ($admin): ?>
                    <a href="<?php print ROOT_PATH ?>Rate/adminRateUI.php"><img src="<?php print ROOT_PATH ?>assets/images/rating.png"> Rate Us</a>
                <?php else: ?>
                    <a href="<?php print ROOT_PATH ?>Rate/userRateUI.php"><img src="<?php print ROOT_PATH ?>assets/images/rating.png"> Rate Us</a>
                <?php endif; ?>
              </div>
            </div>
        </div>
      </section>

      <section class="right-div">
        <div class="upper-cont">
          <div class="datetime">
           <div class="date">
            <span id="dayname">Day</span>,  
            <span id="month">Month</span> 
            <span id="daynum">00</span>,  
            <span id="year">Year</span>
          </div>
          <div class="time">
            <span id="hour">00</span>:  
            <span id="minutes">00</span>: 
            <span id="seconds">00</span>  
            <span id="period">AM</span>
          </div>
          </div>
          <a id="home-btn" href="<?php print ROOT_PATH ?>index.php" title="Home"><i class="fas fa-home"></i></a>
            <div>
              <div class="searchbar">
                <input autocomplete="off" type="text" class="searchbar_input" name="search" placeholder="What do you want to learn?">
                <button type="submit" class="searchbar_button"><i class="fas fa-search"></i></button>
              </div>
            </div>
        </div>
            <div class="stats-container">
              <h2>Statistics</h2>
              <div class="card">
                <div class="body">
                  <div class="right-body">
                    <span><?php print $total_topic_completed; print ($total_topic_completed > 1) ? " Topics" : " Topic"; ?> completed</span><br>
                    <span><?php print $total_quiz_completed; print ($total_quiz_completed > 1) ? " Quizzes" : " Quizz"; ?> completed</span><br>
                    <span><?php print $spent;  ?> spent this week</span>
                  </div>
                  <div class="left-body"><img src="../assets/images/reputation.png"></div>
                </div>
              </div>
            </div>
            <div class="logo">
              <img src="<?php print ROOT_PATH ?>assets/images/feed-logo.png">
            </div>
      </section>
    </main>
    <div id="search_result">
      <div style="padding:10px;background-color:white;">
        <div class="searchbar">
          <input autocomplete="off" type="text" class="searchbar_input" name="search" placeholder="What do you want to learn?">
          <button type="submit" class="searchbar_button"><i class="fas fa-search"></i></button>
        </div>
        <div style="padding:5px;font-weight:bold;">Search Result: </div>
        <div id="search_gen" style="padding:5px;height:300px;overflow-y:auto;">

        </div>
      </div>
    </div>
    <div id="memo">

      <div style="display:flex;margin-top:10px;position:relative;padding:0px 20px; 0px 20px">
        <button class="btn btn-success btn-sm" id="search_btn"><i class="fa fa-search"></i></button>
          <input type="text" id="searchbar" autocomplete="off" class="form-control" style="width:250px;" placeholder="Search...">
      </div>

      <div id="content" style="padding:20px;height:200px;overflow-y:auto;">
      </div>
    </div>
    <!-- booksmark -->
    <div id="bookmark">
      <div id="content">
        
      </div>
    </div>
    <!-- end booksmark -->
    <div id="msg-con">
    </div>
    <div id="img-uploader">
      <div style="padding:20px;">
        <div class="form-group">
          <label>Profile</label>
          <input type="file" id="profile-file" class="form-control" />
          <button class="btn btn-primary btn-sm" id="upload-pro-btn" style="margin-top:10px;width:100%;">
            <i class="fa fa-camera"></i> Save
          </button>
        </div>
      </div>
    </div>
    <script src="<?php print ROOT_PATH ?>config/config.js"></script>
    <script src="<?php print ROOT_PATH ?>assets/js/jquery.min.js"></script>
    <script src="<?php print ROOT_PATH ?>assets/js/all.min.js"></script>
    <script src="<?php print ROOT_PATH ?>assets/js//bootstrap.min.js"></script>
    <script src="<?php print ROOT_PATH ?>assets/js/datetime.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/sweetalert/dist/sweetalert2.min.js"></script>
    <script>
      window.addEventListener('load', function() {
        document.querySelector('.top-loader').style.display = 'none';
        initClock();
      });
    </script>
    <script src="<?php print ROOT_PATH ?>assets/cbox/cbox.js"></script>
    <script src="<?php print ROOT_PATH ?>assets/js/notification.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/chatbox/chat.js"></script>
    <script>
      var search = new cbox("#search_result");
      search.logo = '<i class="fa fa-search"></i>';
      search.title = 'Search Result';
      search.backgroundcolor = "";
      search.fontcolor_header = "#5c5c5c";
      search.backgroundcolor_header = "rgb(201 203 201 / 51%)";
      search.width = window.screen.availWidth * 0.6;
      search.height = window.screen.availHeight * 0.4;
      search.init();
      function loadSearch(query) {
        //window.location.href = ROOT_PATH +  "query/search.php";
        search.show();
        $("#search_gen").html("Searching... please wait...");
        $.ajax({url: ROOT_PATH + "search/result.php", type: "post", data: {search: query}}).then(function(resp) {
          var data = JSON.parse(resp);
          var data_len = data.length;
          var t = '<div class="list-group">';
          for (var i = 0; i < data_len; i++) {
            if (data[i]["type"] == "subtopic") {
              /**
               * For Topics design 
               */
              var subdata = JSON.parse(atob(data[i]["data"]));
              var sub_topic_title = subdata["sub_title"];
              var sub_topic_ago = subdata["sub_dt_ago"];
              var cat_title = subdata["cat_title"];
              var main_title = subdata["main_title"];
              var query = "cat_id=" + subdata['cat_id'];
              query += '&main_id=' + subdata["main_id"];
              query += '&sub_id=' + subdata["sub_id"];
              t += '<a href="' + ROOT_PATH + 'topics/menu.php?' + query + '" style="text-decoration:none;color:black;">';
              t += '<div class="list00001 list-group-item" style="position:relative;">';
              t += '<i class="fa fa-book"></i> ';
              t +=  '<span style="font-weight:bold;">' + sub_topic_title + '</span>';
              t += '<br/>';
              t += '<div style="padding-left:30px;font-size:10pt;">';
              t += cat_title + ' <i class="fa fa-circle-dot" style="color:green;font-size:8pt;"></i> ' + main_title;
              t += '</div>';
              t += '<div style="position:absolute;right:10px;top:5px;">';
              t +=  sub_topic_ago + " ago";
              t += '</div>';
              t += '</div>';
              t += '</a>';
              /**
               * Dnd For Topics design 
               */
            }
          }
          t += '</div>';
          $("#search_gen").html(t);
          if (data_len <= 0)
            $("#search_gen").html("No result found!");
        });
      }
      $("#notif-data").on("click", function() {
        notification_get();
      });
      $(".searchbar").find("input").on("keyup", function(evt) {
        var input = $(this);
        if (evt.keyCode == 13) {
          loadSearch(input.val());
          input.val("");
        }
      });
      $(".searchbar").find(".searchbar_button").on("click", function() {
        var input = $(this).parents(".searchbar").find("input");
        loadSearch(input.val());
        input.val("");
      });
      // memo
      var memo = new cbox("#memo");
      memo.title = "Memo List";
      memo.logo = '<i class="fa fa-note-sticky"></i>';
      memo.width = window.screen.availWidth * 0.7;
      memo.height = window.screen.availHeight * 0.47;
      memo.init();
      $(memo.elem).find("#content").css("height", (memo.height - -145) + "px");
      $("#memo-btn").on("click", function() {
        $.ajax({url: ROOT_PATH + "memo/r.php?r=get-memo-all", type: "post", data:{}}).then(function(resp) {
          var data = JSON.parse(resp);
          var data_len = data.length;
          t = '<div class="list-group">';
          for (var m1 = 0; m1 < data_len; m1++) {
            var name = data[m1]["name"];
            var id = data[m1]["id"];
            var date = data[m1]["date"];
            var ago = data[m1]["m_date_ago"];
            var loc = data[m1]["loc"];
            t += '<div class="list-group-item" style="position:relative;padding-right:50px;text-aligh:justify;">';
            t += '<i class="fa fa-warning" style="color:#ffc107;"></i> ';
            t += '<button style="border:none;background-color:transparent;text-align:left;" class="memo_dl" href="<?php echo NETLINKZ_ROOT; ?>categorize/'+ loc +'" download><u style="color:#3498db;">'+ name +'</u></button>';
            t += '<div style="position:absolute;top:5px;right:5px;">';
            t += ago + " ago";
            t += '</div>';
            t += '</div>';
          }
          t += '</div>';
          $(memo.elem).find("#content").html(t);
          memo.show();
        });
      });
      $(memo.elem).on("click", ".memo_dl", function(){
        var href = $(this).attr("href");
        $.get("http://127.0.0.1:8558/file/" + href).then(function (){
        },function(){
          alert('FEED extension not installed!')
        })
      }); 
      $('#search_btn').on('click', function() {
       load_memo($("#searchbar").val(), true);
      });
      $("#searchbar").on("keyup", function(evt) {
        if(evt.keyCode == 13) {
          load_memo($("#searchbar").val(), true);
        }
      });
      function load_memo(search = "", load = false) {
       if (load) {
        $(memo.elem).find("#content").html("Searching...");
       }
       $.ajax({
           url: ROOT_PATH + "memo/r.php?r=getSearchMemos",
           type: "post",
           data: {search: search}
       }).then(function(resp) {
           var global_data = JSON.parse(resp);

           var len = global_data.length;
           var t = "";
           if(len == 0){
            $(memo.elem).find("#content").html("No Data Found!");
           }else{
           for (var i = 0; i < len; i++) {
            t += '<div class="list-group-item" style="position:relative;padding-right:50px;text-aligh:justify;">';
            t += '<i class="fa fa-warning" style="color:#ffc107;"></i> ';
            t += '<button style="border:none;background-color:transparent;text-align:left;" class="memo_dl" href="<?php echo NETLINKZ_ROOT; ?>categorize/'+ global_data[i].attach_file +'" download><u style="color:#3498db;">'+ global_data[i].m_name +'</u></button>';
            t += '<div style="position:absolute;top:5px;right:5px;">';
            t += global_data[i]["dt"] + " ago";
            t += '</div>';
            t += '</div>';
           }
           $(memo.elem).find("#content").html(t);
       }
       }, function() {
           alert("Something went wrong!");
       });
   }
      var mark_data = [];
      var bmark = new cbox("#bookmark");
      bmark.backgroundcolor_header = "#878787";
      bmark.logo = '<i class="fa fa-bookmark"></i>';
      bmark.title = 'My Bookmarks';
      bmark.width = window.screen.width * 0.5;
      bmark.height = 300;
      bmark.index = 1;
      bmark.init();
      $("#bookmark-btn").on("click", function() {
        var book = $('#bookmark #content');
        $.post(ROOT_PATH + 'user/bookmark.php', {r: 'my-bookmark'}, function(resp) {
          var data = JSON.parse(resp);
          mark_data = data;
          var data_len = data.length;
          var t = '';
          var type ='';
          if (data_len > 0) {
            t += '<div class="list-group">';
            for (var i = 0; i < data_len; i++) {
              if (data[i].type == 'cat')
                type = 'Category of topic';
              t += '<div class="list-group-item" style="position:relative;">';
                t += '<i class="fa fa-star" style="color:orange;"></i> ' + data[i].title + ' - ' + type;
                t += ' - ' + data[i].ago;
                t += '<div class="btn-group" style="position:absolute;top:5px;right:5px;">';
                t += '<button class="view btn btn-primary btn-sm" index="' + i + '">';
                t += '<i class="fa fa-eye"></i>';
                t += '</button>';
                t += '<button class="btn btn-danger btn-sm delete" index="' + i + '">';
                t += '<i class="fa fa-trash"></i>';
                t += '</button>';
                t += '</div>';
              t += '</div>';
            }
            t += '</div>';
          } else {
            t += '<div style="padding:10px;color:#ff7676;"><i class="fa fa-exclamation-circle"></i> No bookmark added!</div>';
          }
          book.html(t);
        });
        bmark.show();
      });
      $('#bookmark').on('click', '.view', function() {
        var i = $(this).attr('index');
        var id = mark_data[i].objid;
        var type = mark_data[i].type;
        if (type == 'cat') 
          window.location.href = ROOT_PATH + 'topics/menu.php?id=' + id;
      });
      $('#bookmark').on('click', '.delete', function() {
        var i = $(this).attr('index');
        var elem = $(this).parents('.list-group-item');
        var id = mark_data[i].objid;
        var type = mark_data[i].type;
        Swal.fire({
                heightAuto: false,
                title: 'Remove?',
                text: 'Bookmark will be deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove!'
            }).then((result) => {
                if (result.isConfirmed) {
                  $.post(ROOT_PATH + 'user/bookmark.php', {r: 'delete', type: type, id: id}, function(resp) {
                    var data = JSON.parse(resp);
                      if (data.ok) {
                        Swal.fire({
                          heightAuto: false,
                          icon: 'success',
                          title: 'Success!',
                          text: 'Bookmark has been deleted!'
                        });
                        elem.remove();
                      } else {
                        alert(data.msg);
                      }
                  });
                    
                }
            })
      });
      $("#msg-btn").on("click", function() {
        ChatBox.show();
        return false;
      });
      // end message
      var uploader = new cbox("#img-uploader");
      uploader.title = "Profile Picture";
      uploader.logo = '<i class="fa fa-camera"></i>';
      uploader.init();
      $("#pic-upload").on("click", function() {
        uploader.show();
      });
      var xml = new XMLHttpRequest();
      $("#upload-pro-btn").on("click", function() {
        var img = document.querySelector("#profile-file");
        var files = img.files;
        var type = "";
        if (files.length > 0) {
          type = files[0]["type"]
          if (
            type != "image/jpeg" &&
            type != "image/jpg"  &&
            type != "image/png"
          ) return alert("Please select a valid image");
          var data = new FormData();
          data.append("files", files[0]);
          xml.onload = function() {
            var src = $("#profile-pic01").attr("src");
            $("#profile-pic01").attr("src", src);
          }
          xml.upload.progress = function() {

          }
          xml.open("POST", "<?php echo ROOT_PATH; ?>user/route.php?r=updatePic");
          xml.send(data);
        } else {
          alert("Select a file");
        }

      });
      $(".cclose").on("click", function(){
       $('#searchbar').val('');
      });
    </script>
  </body>
</html>
