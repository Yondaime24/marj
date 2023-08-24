<?php 
  use classes\access;
  use classes\Factory\Stat;
  require_once '../___autoload.php'; 
  $access = new access();
  if (!$access->check()) {
    print 'Unauthorized access!';
    exit;
  }
?>
<?php if (REQ == 'get'): ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FEED - Quiz Start</title>
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/all.min.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/select2/select2.min.css" />
    <style>
      body{background-color:#eee;}
      .sider{z-index:1000;height:100%;width:200px;background-color:white;position:fixed;top:0;left:0;}
      .content{width:100%;padding-left:200px;padding-top:50px;}
      .sider-title, .top{background-color:#00a747;width:100%;height:50px;position:relative;}
      .top{background-color:#009b42;padding-left:200px;position:fixed;top:0;left:0;z-index:100;}
      .sider-label{color:white;font-size:15pt;position:absolute;top:8px;left:5px;}
      .sider-bars{color:white;font-weight:bold;padding:10px;position:absolute;top:3px;right:3px;cursor:pointer;}
      
      .top-r{border-top-left-radius:5px;border-top-right-radius:7px;}
      .bot-r{border-color:#afadad!important;border-bottom-left-radius:5px;border-bottom-right-radius:7px;}
      .menu-btn{width:100%;padding:5px;}
      .main-btn{border:1px solid #afadad;width:100%;background-color:white;color:black;padding:5px;text-align:left;padding-left:20px;}
      .active{background-color:#eee!important;}

      .drop .drop-down{padding:0 5px 0 5px;}
      .drop .drop-down button{padding:3px;width:100%;border:none;text-align:left;padding-left:20px;color:black;border:1px solid #c3c3c3;background-color:white;}
      .drop .drop-down{display:none;}
      .sub-btn{color:#525151!important;}
    </style>
  </head>
  <body>
    <div class="sider">
      <div class="sider-title">
        <label class="sider-label"><i class="fa fa-cog"></i> FEED Admin</label>
        <div class="sider-bars">
          <i class="fa fa-bars"></i>
        </div>
      </div>
      <div class="menu">
        <div class="menu-btn">
          <button link="<?php print ADMIN_PATH; ?>" class="main-btn top-r"><i class="fa fa-home"></i> Dashboard</button>
          <div class="drop">
            <button link="" class="main-btn"><i class="fa fa-book"></i> Quiz</button>
            <div class="drop-down">
              <button class="sub-btn" link="<?php print ADMIN_PATH; ?>checkquiz.php"><i class="fa fa-pen"></i> Check Quiz</button>
            </div>
            <div class="drop-down">
              <button class="sub-btn" link="<?php print ADMIN_PATH; ?>stat.php"><i class="fa fa-user"></i> User Stats</button>
            </div>
            <div class="drop-down">
              <button class="sub-btn" link="<?php print ADMIN_PATH; ?>rank_page.php"><i class="fa fa-trophy"></i> Rank</button>
            </div>
          </div>
          <!-- <div class="drop">
            <button link="" class="main-btn"><i class="fa fa-file-pdf"></i> Reports</button>
            <div class="drop-down">
              <button class="sub-btn" link="<?php print ADMIN_PATH; ?>ustat.php"><i class="fa fa-certificate"></i> Statistics</button>
            </div>
          </div> -->
          <div class="drop">
            <button link="" class="main-btn"><i class="fa fa-cog"></i> Settings</button>
            <div class="drop-down">
              <button class="sub-btn" link="<?php print ADMIN_PATH; ?>access.php"><i class="fa fa-unlock"></i> Access</button>
              <button class="sub-btn" link="<?php print ADMIN_PATH; ?>cert.php"><i class="fa fa-pen"></i> Cert Signatories</button>
              <button class="sub-btn" link="<?php print ADMIN_PATH; ?>certCont.php"><i class="fa fa-file-word"></i> Certificate Content</button>
            </div>
          </div>
          <button link="<?php print ADMIN_PATH; ?>about.php" class="main-btn bot-r"><i class="fa fa-question-circle"></i> About</button>
        </div>
      </div>
    </div>
    <div class="top">
      
    </div>
    <div class="content">
<?php endif; ?>
<?php 
  $user = Stat::create("user");
  $sub = Stat::create("subtopic");
  $qp = Stat::create("QuizPrepare");
?>
<style>
  .feed-box{margin-bottom:10px;box-shadow:1px 1px 3px rgba(0,0,0,0.5);width:100%;height:100px;background-color:white;border-radius:10px;position:relative;}
  .feed-icon{font-size:50pt;position:absolute;z-index:0;top:0px;left:10px;color:#009b423b;}
  .text-value{position:absolute;bottom:20px;right:20px;font-size:20pt;color:#6a6a6a;}
  .feed-label{width:100%;height:35px;background-color:#57c586;position:absolute;top:0;left:0;border-top-right-radius:10px;border-top-left-radius:10px;padding:5px;color:#ffffff;}
</style>
<div class="container-fluid">
  <h3>Admin Dashboard</h3>
  <hr />
  <div class="row">
    <div class="col-md-3">
      <div class="feed-box">
        <div class="feed-label">
          Feed User
        </div>
        <div class="feed-icon">
          <i class="fa fa-book"></i>
        </div>
        <div class="text-value">
          <?php echo $user->count();  ?>
        </div>
      </div>
    </div> 
    <div class="col-md-3">
      <div class="feed-box">
        <div class="feed-label">
          Online User
        </div>
        <div class="feed-icon">
          <i class="fa fa-user-group"></i>
        </div>
        <div class="text-value">
          <?php echo count($user->getOnline());  ?>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="feed-box">
        <div class="feed-label">
          Total Topic
        </div>
        <div class="feed-icon">
          <i class="fa fa-pen"></i>
        </div>
        <div class="text-value">
          <?php print $sub->count();  ?>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="feed-box">
        <div class="feed-label">
          Quiz Prepare
        </div>
        <div class="feed-icon">
          <i class="fa fa-file-word"></i>
        </div>
        <div class="text-value">
          <?php echo $qp->count();;  ?>
        </div>
      </div>
    </div>

  </div>
</div>
<?php if (REQ == 'get'): ?>
    </div> <!-- End .content  -->
    <script src="<?php print ROOT_PATH; ?>config/config.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/jquery.min.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/select2/select2.min.js"></script>
    <script src="<?php echo ROOT_PATH; ?>assets/chart/chart.min.js"></script>
    <script>
      var http = new XMLHttpRequest();  
      function mainroute(url) {
        content.html("<div style=\"padding:20px;font-weight:bolder;color:orange;\">Loading...</div>");
        http.onload = function() {
          is_loading = false;
          if (http.status == 404)
            content.html('<div style="color:red;padding:20px;">Sorry the page is not found!</div>');
          if (http.status == 200)
            content.html(http.response);
        }
        http.open('POST', url);
        http.send(null);
      }
      var is_loading = false;
      var content = $('.content');
      $('.main-btn').on('click', function() {
        if (is_loading) return;
        var url = $(this).attr('link');
        $('.main-btn').removeAttr('disabled');
        $(this).attr('disabled', '');
        if (url != '') {
          $('.drop').find('.drop-down').slideUp();
          $('.main-btn').removeClass('active');
          $('.sub-btn').removeClass('active');
          is_loading = true;
          $('.content').html('<div style="padding:20px;">Loading...</div>');
          $(this).addClass('active');
          mainroute(url);
        } else {
          // open the drop down
          $('.drop').find('.drop-down').slideUp();
          var drop =  $(this).parents('.drop').find('.drop-down');
          if (drop.attr('style') != 'display: block;')
            drop.slideDown();
        }
      });
      $('.sub-btn').on('click', function() {
        if (is_loading) return;
        var url = $(this).attr('link');
        $('.main-btn').removeClass('active');
        $('.sub-btn').removeClass('active');
        is_loading = true;
        $('.content').html('<div style="padding:20px;">Loading...</div>');
        $(this).addClass('active');
        mainroute(url);
      });
      // window.addEventListener("contextmenu", function(evt) {
      //   if (evt.button == 2) {
      //     alert("rightclick");
      //   }
      //   evt.preventDefault();
      // });
    </script>
  </body>
</html>
<?php endif; ?>