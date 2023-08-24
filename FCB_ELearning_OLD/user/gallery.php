<?php  
  use classes\key;
  use classes\access;
  use classes\user;
  use classes\auth;
  use classes\img_folder;

  require_once '../___autoload.php';
  $auth = new auth();
  $auth::isLogin();

  $key = new key();
  $admin=new access();
  $user = new user();
  $user->load();

  $objFolder = new Img_folder();
  $folder = $objFolder->getAllFolderName();

?>
<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" sizes="76x76" href="../assets/images/feed-logo.png">
	<link rel="icon" type="image/png" href="../assets/images/feed-logo.png">
	<title>F.E.E.D. | Gallery</title>

    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap3.min.css">
  	<link rel="stylesheet" href="../assets/css/all.min.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/loader_t.css" />
  	<link rel="stylesheet" type="text/css" href="../assets/sweetalert/dist/sweetalert2.min.css">
  	<link rel="stylesheet" type="text/css" href="../assets/css/gallery.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/material.indigo-pink.min.css">
    <link rel="stylesheet" href="../assets/css/loading_gif.css" />

    <style>
   .top-loader{width:90%;height:5px;z-index:1000;position:fixed;top:0px;left:0px;}
    .top-loader div{width:100%;height:100%;background-color:#d73801;animation:tow ease-in 10s}
    @keyframes tow{
      0%{width:0%} 5%{width:30%} 50%{width:60%} 100%{width:90%;}
    }
  </style>
</head>
<body>
<div class="top-loader"><div></div></div>


    <div id="wrapper">
        <!-- SIDEBAR -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                   <?php if ($admin->check($key->ulevel)) { ?>
                        <a id="add_folder_btn" href="#"><i id="eye" class="fas fa-plus"></i> New Album</a> 
                    <?php }else{ ?>
                        <span class="page-title">F.E.E.D. Gallery</span>
                    <?php } ?>
                </li>
                <div id="folder_result">
                    
                </div>
            </ul>
        </div>
        <!-- /SIDEBAR WRAPPER -->
        <!-- PAGE CONTENT -->
        <div class="toggle-bar">
            <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fas fa-bars"></i></a>
            <?php if ($admin->check($key->ulevel)) { ?>
            <div id="clear_all">
                <a href="#" class="btn btn-danger btn_sm del_all_img" title="Delete All Images"><i style="color: #d9534f;" class="fas fa-trash"></i></a>
                <input type="hidden" name="del_operation">
                <input type="hidden" name="folder_id">
            </div>
            <?php } ?>
            <a id="home-btn" href="../index.php" title="Home"><i class="fas fa-home"></i></a>
        </div>
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 photo-gallery" id="result">
                        <div id="links" class="links">




                    <div class="logo">
                        <img src="../assets/images/feed-logo.png">
                    </div>




                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /PAGE CONTENT WRAPPER -->
    </div>

    <a href="#top" id="myBtn"><i class="fas fa-arrow-up"></i></a>
  

    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
      <div class="slides"></div>
      <h3 class="title"></h3>
      <a id="eye_icon" title="View or Click Image To View" href="#"><i id="eye" class="fa fa-eye"></i></a>
      <a class="prev">‹</a>
      <a class="next">›</a>
      <a class="close">×</a>
      <a class="play-pause" id="play_pause">Play</a>
      <ol class="indicator"></ol>
    </div>

<div class="modal fade" id="loaderModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <div class="loading_bar">
            <img src="../assets/images/loader.gif" width="50px">
          </div>
        </div>
          <div class="footer">
            <span>Loading...</span>
          </div>
      </div>
    </div>
</div>

    <div id="folderModal" style="display: none">
        <div style="padding: 10px;">
            <form method="post" id="folder_form">
                <div class="mb-3" id="folder-div">
                  <span style="font-size: 15px">Album Name:</span>
                  <input type="text" name="folder_name" id="folder_name" class="form-control" placeholder="Enter folder name" autocomplete="off">
                  <small style="color: red; display: none;" id="help-text"><i>Please enter album name!</i></small>
                </div>
                 <input type="hidden" name="folder_id" id="folder_id" />
                 <input type="hidden" name="operation" id="operation" />
                 <input  type="submit" name="action" id="action" value="Add" class="btn btn-primary btn_input">
            </form>
        </div>
    </div>

    <div id="imgModal" style="display: none">
        <div style="padding: 10px;">
            <form method="post" id="img_form">
                <div class="mb-3">
                  <span style="font-size: 15px">Image Title:</span>
                  <input type="text" name="img_title" id="img_title" class="form-control" placeholder="Enter image title (optional)">
                </div>
                <div class="mb-3">
                  <span style="font-size: 15px">Description:</span>
                  <textarea type="text" name="img_desc" id="img_desc" class="form-control" placeholder="Enter image description (optional)"></textarea>
                </div>
                 <div class="mb-3" id="b-img">
                    <div class="file-upload mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--green-500 mdl-color-text--white" id="browse_div">
                        <span>BROWSE</span>
                        <input type="file" name="img_name" id="img_name" value="" class="upload" />
                    </div>
                        <input type="text" id="fileuploadurl" readonly placeholder="Select image to upload">
                        <div id="user_uploaded_image" style="display:none;">
                            
                        </div>
                </div>
                <input type="hidden" name="folder_to_img_id" id="folder_to_img_id" />
                    <input type="hidden" name="img_id" id="img_id" />
                    <input type="hidden" name="operation2" id="operation2" />
                    <input  type="submit" name="img_action" id="img_action" value="Upload" class="btn btn-primary img_btn_input">
            </form>
        </div>
    </div>


	  <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap3.min.js"></script>
    <script src="../assets/js/material.min.js"></script>
    <script src="../assets/sweetalert/dist/sweetalert2.min.js"></script>
    <script src="../assets/js/gallery_ajax.js"></script>
    <script src="../assets/cbox/cbox.js"></script>
    <!-- IMAGE VIEW JS-->
    <script src="../assets/js/gallery_fullscreen.js"></script>
    <script src="../assets/js/gallery_indicator.js"></script>
    <script src="../assets/js/gallery.js"></script>
    <script src="../assets/js/gallery_helper.js"></script>
    <script src="../assets/js/blueimp_gallery.js"></script>

    <script>
       $('#play_pause').click(function() {
        var s = $(this);
        var originalText = s.text();
        $('#play_pause').text('Play');
        s.text(originalText);
        s.html(s.text() == 'Play' ? 'Stop' : 'Play')
       });

        var state=true;
        $('#eye_icon').click(function() {
          if (state) {
            $("#eye_icon").css("color", "#48b02b");
            $(".title").css("opacity", "0");
            $(".prev").css("opacity", "0");
            $(".next").css("opacity", "0");
            $(".close").css("opacity", "0");
            $(".play-pause").css("opacity", "0");
            $(".indicator").css("opacity", "0");
            state = false;

          }else{
            $("#eye_icon").css("color", "#fff");
            $(".title").css("opacity", "1");
            $(".prev").css("opacity", "1");
            $(".next").css("opacity", "1");
            $(".close").css("opacity", "1");
            $(".play-pause").css("opacity", "1");
            $(".indicator").css("opacity", "1");
            state = true;
          }
        });

        $("a[href='#top']").click(function() {
          $("html, body").animate({scrollTop: 0}, "slow");
          return false;
        });
        var mybutton = document.getElementById("myBtn");
          window.onscroll = function() {scrollFunction()};
          function scrollFunction() {
            if(document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
              mybutton.style.display = "block";
            }else{
              mybutton.style.display = "none";
            }
          }
    </script>

</body>
</html>