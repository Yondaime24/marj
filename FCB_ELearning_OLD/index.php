<?php 
  use classes\key;
  use classes\auth;
  use classes\access;
  use classes\user;
  use classes\ckeditor;
  use classes\carousel_display;
  use classes\info_about;
  use classes\lib\sql;

  require_once "___autoload.php";
  $con = sql::getInstance();
  $key = new key();
  $admin = access::getInstance();
  $user = user::getInstance();
  $user->sql = $con;
  $user->load();

  $admin_access = $admin->check();

  $objCarousel = new Carousel_display();
  $carousel = $objCarousel->getImages();

  $objInfo = new Info_about();
  $info = $objInfo->getAllInfoContent();
  $objAbout = new Info_about();
  $about = $objAbout->getFeedAbout();
  $objFCBAbout = new Info_about();
  $fcbabout = $objFCBAbout->getFcbAbout();
  auth::isLogin();
  $user->online();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="apple-touch-icon" sizes="76x76" href="<?php print ROOT_PATH; ?>assets/images/feed-logo.png">
  <link rel="icon" type="image/png" href="<?php print ROOT_PATH; ?>assets/images/feed-logo.png">
  <title>F.E.E.D. | Homepage</title>

  <link rel="stylesheet" type="text/css" href="<?php print ROOT_PATH; ?>assets/css/bootstrap3.min.css">
  <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/all.min.css" />
  <link rel="stylesheet" type="text/css" href="<?php print ROOT_PATH; ?>assets/css/index.css">
  <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/calendar4.css" />
  <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/chatBox.css" />
  <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/loader.css" />
  <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/loading_gif.css" />
  <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/loader_t.css" />
  <link rel="stylesheet" type="text/css" href="<?php print ROOT_PATH; ?>assets/sweetalert/dist/sweetalert2.min.css">
  <script src="<?php print ROOT_PATH; ?>config/config.js"></script>
  <script>
    window.guid = '<?php print $key->idno; ?>';
    window.myrole = '<?php print access::getInstance()->check() ? 'admin' : '' ?>';
  </script>
  <script>var admin_access = <?php print $admin_access ? "1" : "0";  ?>;</script>
  <style>
   .top-loader{width:90%;height:5px;z-index:1000;position:fixed;top:0px;left:0px;}
    .top-loader div{width:100%;height:100%;background-color:#d73801;animation:tow ease-in 10s}
    @keyframes tow{
      0%{width:0%} 5%{width:30%} 50%{width:60%} 100%{width:width:90%;}
    }
  </style>  
</head>
<body>
<div class="top-loader"><div></div></div>

<section class="homepage">
    <div class="left-div">
      <div class="logo">
        <img src="assets/images/feed-logo.png">
      </div>
      <div class="icons">
        <a href="user/profile.php"><img title="Profile" src="<?php print ROOT_PATH; ?>assets/images/user2.png"></a>

        <a href="#" id="notif-btn"><img title="Notifications" src="<?php print ROOT_PATH; ?>assets/images/notification-bell.png">
          <div id="notif-badge" style="display:none;"></div>
        </a>

        <a href="user/gallery.php"><img title="Gallery" src="<?php print ROOT_PATH; ?>assets/images/gallery.png"></a>
        <a href="#" id="about_us"><img title="About" src="<?php print ROOT_PATH; ?>assets/images/info.png"></a>
      </div>
        <div class="left-div-card">
 
        </div>
      </div>


    <div class="center-div">

      <div class="img_carousel">
        <div id="my-pics" class="carousel slide carousel-fade" data-ride="carousel" data-interval="5000">
          <?php if (count($carousel) == 0){ ?>  
          <div class="empty-image">
              <strong>No Image Available Yet! </strong>
              <?php if ($admin->check($key->ulevel)) { ?>
              <a href="<?php print ROOT_PATH; ?>user/imageSliderViewer.php" onclick="window.open('<?php print ROOT_PATH; ?>user/imageSliderViewer.php', 'newwindow', 'width=1200,height=600'); return false;"  title="Edit"><i class="far fa-edit"></i>  </a>
              <?php  } ?>
            </div>
          <?php } ?>
          <ol class="carousel-indicators">
            <?php $c = 0; foreach ($carousel as $row => $carousels) { ?>
            <li data-target="#my-pics" data-slide-to="<?php echo $c++; ?>" class="<?php echo $row === 0 ? 'active' : ''; ?>"></li>
            <?php } ?>
          </ol>
            <div class="carousel-inner" role="listbox">
              <?php foreach ($carousel as $row => $carousels) { ?>
              <div class="item <?php echo $row === 0 ? 'active' : ''; ?>">
                <div class="loader_t_float"></div>
                <img class="carousel_img" style="height: 58vh;width:49.76vw;image-rendering:-webkit-optimize-contrast;" data-src="<?php print ROOT_PATH; ?>file/image_file.php?image_id=<?php echo $carousels["id"]; ?>" alt="" />
                  <?php if($admin->check($key->ulevel)): ?>
                    <p class="title"><?php echo $carousels['title'] ?></p>
                      <div class="overlay"></div>
                      <div class="button">
                        <a href="user/imageSliderViewer.php" onclick="window.open('<?php print ROOT_PATH; ?>user/imageSliderViewer.php', 'newwindow', 'width=1200,height=600'); return false;"  title="Edit"><i class="far fa-edit"></i>  </a>
                      </div>
                    <?php else: ?>
                      <p class="title"><?php echo $carousels['title'] ?></p>
                      <div class="overlay"></div>
                    <?php endif; ?>
                    </div>
                  <?php } ?>
              </div> 
        </div>
      </div>
      <div class="center-cards">
        <div class="quote-card">
          <div class="center-quote-card">
         
        </div> 
        </div>
        <div class="archive-card">
          <div class="panel panel-default">
            <div class="panel-heading">
              <div id="arch-dr">
                <span>Archive</span><br>
                <span id="dr_span">(Daily Reads)</span> 
                <i id="dr_i" class="fas fa-book-open"></i>
              </div>
              <div id="arch-mt">
                <span>Archive</span><br>
                <span id="mt_span">(Motivational Tips)</span> 
                <i id="mt_i" class="fas fa-lightbulb"></i>
              </div>
            </div>
            <a id="dr-a" style="position:absolute;top:30px;right:25px;font-size:18px;z-index:1;cursor:pointer;"><i class="fas fa-chevron-down"></i></a>
            <a id="mt-a" style="position:absolute;top:30px;right:25px;font-size:18px;z-index:1;display:none;cursor:pointer;"><i class="fas fa-chevron-up"></i></a>
            <div class="panel-body">
              <div id="archive-content">
                
              </div>
            </div>
             <div class="panel-footer"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="right-div">
      <div class="info">
        <span><?php print $key->idno; ?></span>
        <a class="btn btn-success" href="javascript:window.close()" title="Back to Netlinkz"><i class="fas fa-arrow-left"></i></a>
        <br>
        <span><?php print $key->fname . " ". $key->lname; ?></span>
      </div>
      <div class="date-div">
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
      </div>
        <div class="card" id="card-calendar">
            <div id="calendar" class="card-body about-body p-0 light">
                  <!-- CALENDAR -->
                  <div class="calendar">
                    <div class="calendar-header">
                        <span class="month-picker" id="month-picker">May</span>
                        <div class="year-picker">
                            <span class="year-change" id="prev-year">
                                <pre style="background-color:transparent;"><</pre>
                            </span>
                            <span id="year">2022</span>
                            <span class="year-change" id="next-year">
                                <pre style="background-color:transparent;">></pre>
                            </span>
                        </div>
                    </div>
                    <div class="calendar-body">
                        <div class="calendar-week-day">
                            <div>Sun</div>
                            <div>Mon</div>
                            <div>Tue</div>
                            <div>Wed</div>
                            <div>Thu</div>
                            <div>Fri</div>
                            <div>Sat</div>
                        </div>
                        <div class="calendar-days"></div>
                    </div>
                    <div class="month-list"></div>
                </div>
                <!-- /CALENDAR -->
            </div> 
          </div>
          <div class="card-msg">
            <!-- chat content -->
            <div class="card panel panel-default">
              <div class="panel-heading card-header" id="m-head">
                <span id="title_msg"> <i id="msg-icon" class="far fa-comments"></i> Chatbox  </span>
              </div>
              <div class="panel-body" id="m-body">
                <div class="users-list">
                <!-- DISPLAY USERS LIST -->
                </div>
              </div>
              <div class="msg-ui">
                <div class="head">
                  <div class="h-left">
                    <a id="msg-back"><i class="fas fa-arrow-left"></i></a>
                  </div>
                  <div class="h-center">
                    <span id="u-name" class="chatboxtoname">User Name Here</span>
                    <small id="ol-status"></small>
                  </div>
                  <div class="h-bottom">
                    <a id="b-info"><i style="" class="fas fa-circle-info"></i></a>
                    <span id="b-res">Branch: HED</span>
                  </div>
                </div>
                <div class="body body2 chatBox">
                
                </div>
                <div class="foot">
                  <div class="foot-left">
                    <textarea placeholder="Message" class="form-control chatBoxTextField" name="feed_message" id="feed_message"></textarea>
                  </div>
                  <div class="foot-right">
                    <button class="btn btn-sm btn-success send_btn" id="chatBoxSend"><i class="fas fa-paper-plane"></i></button>
                  </div>
                </div>
              </div>
            </div>

            <!-- end chat content  -->

          </div>
    </div>
  </section>


<!-- MODAL ABOUT -->
<div class="modal fade" id="modal-about" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="modal_daily_reads">
    
    </div>
  </div>
</div>

    <!-- Modal Quote -->
<div class="modal fade" id="modal-quote" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="modal_motivational_tips">
   
    </div>
  </div>
</div>

    <!-- Modal Archive -->
<div class="modal fade" id="modal-archive" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="modal_archive">
    
    </div>
  </div>
</div>

<div id="aboutus_modal">
  <div style="padding:5px;">
  <table class="table">
        <tbody id="about_table">
          <tr>
            <td class="text-center">
              <span><b>About F.E.E.D.</b></span>
            </td>
            <td class="text-center" style="display:flex;flex-direction:column;">
              <?php foreach ($info as $row => $infos) { ?> 
                <?php if ($infos['name'] == 'about_feed' && $infos['status'] == 'No Content') { ?>
                  <span style="color:red;margin-bottom:5px;">No Content!</span>
                <?php }else if ($infos['name'] == 'about_feed' && $infos['status'] == 'Has Content') { ?>
                  <a href="#" data-toggle="modal" data-target="#feedabout_display" class="btn btn-info btn-sm viewinfo_btn" style="margin-bottom:5px;font-size:15px;">View</a>
                <?php } ?>
              <?php } ?>
                <?php if ($admin->check($key->ulevel)) { ?>
                  <?php foreach ($info as $row => $infos) { ?> 
                    <?php if ($infos['name'] == 'about_feed' && $infos['status'] == 'No Content') { ?>
                      <a href="#" id="1" data-toggle="modal" data-target="#feedaboutModal" class="btn btn-primary btn-sm aboutfeed">Add</a>
                    <?php }else if ($infos['name'] == 'about_feed' && $infos['status'] == 'Has Content') { ?>
                      <a href="#" id="1" data-toggle="modal" data-target="#feedaboutModal" class="btn btn-primary btn-sm aboutfeed">Edit</a>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
            </td>
          </tr>
          <tr>
            <td class="text-center">
              <span><b>About FCB</b></span>
            </td>
            <td class="text-center" style="display:flex;flex-direction:column;">
              <?php foreach ($info as $row => $infos) { ?> 
                <?php if ($infos['name'] == 'about_fcb' && $infos['status'] == 'No Content') { ?>
                  <span style="color:red;margin-bottom:5px;">No Content!</span>
                <?php }else if ($infos['name'] == 'about_fcb' && $infos['status'] == 'Has Content') { ?>
                  <a href="#" data-toggle="modal" data-target="#fcbabout_display" class="btn btn-info btn-sm viewinfo_btn" style="margin-bottom:5px;font-size:15px;">View</a>
                <?php } ?>
              <?php } ?>
                <?php if ($admin->check($key->ulevel)) { ?>
                  <?php foreach ($info as $row => $infos) { ?> 
                    <?php if ($infos['name'] == 'about_fcb' && $infos['status'] == 'No Content') { ?>
                      <a href="#" id="2" data-toggle="modal" data-target="#fcbaboutModal" class="btn btn-primary btn-sm aboutfcb">Add</a>
                    <?php }else if ($infos['name'] == 'about_fcb' && $infos['status'] == 'Has Content') { ?>
                      <a href="#" id="2" data-toggle="modal" data-target="#fcbaboutModal" class="btn btn-primary btn-sm aboutfcb">Edit</a>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
            </td>
          </tr>
        </tbody>
    </table>
  </div>
</div>

            <!-- MESSAGE MODAL -->
        <div class="modal fade" id="msg_modal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <?php if ($admin->check($key->ulevel)) { ?>
                <span><i class="far fa-envelope"></i> Messages</span>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php }else{ ?>
                <span><i class="far fa-envelope"></i> Admin Messages</span>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php } ?>
                <a href="#" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
              </div>
              <div class="modal-body">
              <table class="chatBox">
              </table>
              </div>
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

<div class="modal fade-scale" id="feedaboutModal" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="border:none;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form method="post" id="1">
          <input type="hidden" name="info_id" id="info_id">
          <?php foreach ($about as $row => $abouts) { ?>
          <textarea name="feedabout_info" id="feedabout_info" class="form-control"><?php echo $abouts['content'] ?></textarea>
          <?php } ?>
          <input type="submit" class="save_btn" id="save_about" name="save_about" value="Save">
      </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade-scale" id="fcbaboutModal" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="border:none;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form method="post" id="2">
            <input type="hidden" name="info_id2" id="info_id2">
            <?php foreach ($fcbabout as $row => $fcbabouts) { ?>
            <textarea name="fcbabout_info" id="fcbabout_info" class="form-control"><?php echo $fcbabouts['content'] ?></textarea>
            <?php } ?>
            <input type="submit" class="save_btn" id="save_about2" name="save_about2" value="Save">
        </form>
      </div>
    </div>
  </div>
</div>

<!-- MODAL INFO -->
<div class="modal fade" id="feedabout_display" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <?php  
         foreach ($about as $row => $abouts) { 
           
            echo $abouts['content'];
          }
        ?>
      </div>
    </div>
  </div>
</div>
<!-- MODAL INFO -->
<div class="modal fade" id="fcbabout_display" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <?php  
         foreach ($fcbabout as $row => $fcbabouts) { 
           
            echo $fcbabouts['content'];
          }
        ?>
      </div>
    </div>
  </div>
</div>

<!-- DAILY READS MODAL -->
<div class="modal fade-scale" id="modal-daily-reads" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="border:none;">
        <span style="font-size:18px;font-weight:bold;font-family:cursive;color:#5cb85c;"><i style="color:orange;" class="fas fa-book-open"></i> Daily Reads</span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div style="display:flex;margin-top:10px;position:relative;">
          <button class="btn btn-success btn-sm" id="search_btn"><i class="fa fa-search"></i></button>
          <input type="text" id="searchbar" autocomplete="off" class="form-control" style="width:250px;" placeholder="Search...">
          <a class="btn btn-sm btn-success" href="<?php print ROOT_PATH; ?>user/addAbout.php" style="margin-left:10px;position:absolute;right:0px;"><i style="font-size:12px;" class="fas fa-book-open"></i>&nbsp;&nbsp;Add New Content</a>
          </div>
      </div>
      <div class="modal-body" style="height:75vh;overflow-y:auto;overflow-x:hidden;padding:0px 10px 0px 10px;">
        <table class="table table-bordered">
          <thead style="background-color:#eee;">
            <th scope="col" class="text-center">Date Added</th>
            <th scope="col" class="text-center">Title</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-center">Option</th>
          </thead>
            <tbody class="daily_reads_content">
            
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- MOTIVATIONAL TIPS MODAL -->
<div class="modal fade-scale" id="modal-motivational-tips" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="border:none;">
        <span style="font-size:18px;font-weight:bold;font-family:cursive;color:#5cb85c;"><i style="color:orange;" class="fas fa-lightbulb"></i> Motivational Tips</span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div style="display:flex;margin-top:10px;position:relative;">
          <button class="btn btn-success btn-sm" id="search_btn2"><i class="fa fa-search"></i></button>
          <input type="text" id="searchbar2" autocomplete="off" class="form-control" style="width:250px;" placeholder="Search...">
          <a class="btn btn-sm btn-success" href="<?php print ROOT_PATH; ?>user/addQuote.php" style="margin-bottom:10px;margin-left:10px;position:absolute;right:0px;"><i style="font-size:12px;" class="fas fa-lightbulb"></i>&nbsp;&nbsp;Add New Content</a>
      </div>
      </div>
      <div class="modal-body" style="height:75vh;overflow-y:auto;overflow-x:hidden;padding:0px 10px 0px 10px;">
     
          <table class="table table-bordered">
            <thead style="background-color:#eee;">
              <th scope="col" class="text-center">Date Added</th>
              <th scope="col" class="text-center">Title</th>
              <th scope="col" class="text-center">Status</th>
              <th scope="col" class="text-center">Option</th>
            </thead>
              <tbody class="motivational_tips_content">
              
              </tbody>
          </table>
      </div>
    </div>
  </div>
</div>

<div id="notif001">
  <div class="content"></div>
</div>

    <script src="<?php print ROOT_PATH; ?>assets/js/jquery.min.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/bootstrap3.min.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/datetime.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/calendar2.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/docs.min.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/sweetalert/dist/sweetalert2.min.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/cbox/cbox.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/notification.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/ckeditor/ckeditor.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/loader_notif.js"></script> 
    <script src="<?php print ROOT_PATH; ?>assets/js/chatbox.js"></script>

    <!-- START ABOUT FCB AND ABOUT FEED JS -->
    <script src="<?php print ROOT_PATH; ?>assets/js/infoabout.js"></script>
    <!-- END ABOUT FCB AND ABOUT FEED JS -->
    
    <!-- START DAILY READS, MOTIVATIONAL TIPS, AND ARCHIVE JS -->
    <!-- <script src="<?php print ROOT_PATH; ?>assets/js/reads_tips.js"></script> -->
    <!-- END DAILY READS, MOTIVATIONAL TIPS, AND ARCHIVE JS -->

    <script>

  $("#feed_message").focus(function(){
    $(this).attr("placeholder", "Type a message...");
   });
   $("#feed_message").on('input', function(){
    $(".foot-right").css("display", "flex");
    $(".send_btn").css("opacity", "1");
    $(".foot-left").css("padding-right", "0px");
   });

   window.onload = function(){
     var img = $(".carousel_img");
    for(var i=0; i<img.length; i++){
      var temp_path = img.eq(i).attr("data-src");

      img.eq(i).attr("src", temp_path);
      img[i].onload = function() {
        $(this).parents(".item").find(".loader_t_float").remove();
      }
    }
   };

    ////////DAILY READS//////////
 load_dailyreads();
 load_dailyreads_onModal();
 load_motivationaltips();
 load_motivationaltips_onModal();

 $('.left-div-card').on("click", "#view_daily_reads", function() {
   getAllDailyReads();
 });
  $('#modal_daily_reads').on("click", "#view_daily_reads", function() {
   getAllDailyReads();
   $('#modal-about').modal('hide');
 });

 $('.left-div-card').on("click", "#see_more", function() {
   load_dailyreads_onModal();
 });

   $("#searchbar").on("keyup", function(evt) {
       if(evt.keyCode == 13) {
           load_grid($("#searchbar").val(), true);
       }
   });
   $('#search_btn').on('click', function() {
       load_grid($("#searchbar").val(), true);
   });

 function load_dailyreads() {
   $.ajax({url: ROOT_PATH + 'user/indexRoute.php?route=getDisplayedReads', type: 'post', data:{}}).then(function(resp) {
     var dailyReads = JSON.parse(resp);
     var dailyReads_len = dailyReads.length; 

     if(dailyReads_len==0){
       var t = '';
         t += '<div class="content">'; 
         t += '<div class="img-container">'; 
         t += ' <img id="empty_content1" src="'+ ROOT_PATH +'assets/images/feed-logo.png" >'; 
         t += '</div>'; 
         t += '</div>'; 
         t += '<div class="footer">';
         if (admin_access){
         t += '<button data-toggle="modal" data-target="#modal-daily-reads" id="view_daily_reads" style="float:right;margin-right:30px;border-radius:50px;" class="btn-success"><i class="fa fa-eye"></i> View Daily Reads</button>';
         }else{
           t += '<div style="display:flex;justify-content:center;align-items:center;"><strong style="color:red;">Daily Reads Not Yet Available!</strong></div>';
         }
         t += '</div>';
       $(".left-div-card").html(t);
     }else{
         var t = '';
         for (var i = 0; i < dailyReads_len; i++) {
           t += '<div class="content">'; 
           t += ''+ dailyReads[i]["content"] +''; 
           t += '</div>'; 
           t += '<div class="footer">';
           t += ' <a id="see_more" href="#" data-toggle="modal" data-target="#modal-about">See More>></a>';
           t += '</div>';
         $(".left-div-card").html(t);
         }
     }
   });
 }
 function load_dailyreads_onModal() {
   $.ajax({url: ROOT_PATH + 'user/indexRoute.php?route=getDisplayedReads', type: 'post', data:{}}).then(function(resp) {
     var dailyReads = JSON.parse(resp);
     var dailyReads_len = dailyReads.length; 
         var t = '';
         for (var i = 0; i < dailyReads_len; i++) {
           t += '<div class="modal-header" style="position:relative;padding-bottom:0px;">';
           t += '<span style="font-size:18px;font-weight:bold;"> '+ dailyReads[i]["title"] +'</span>';
           if (admin_access){
           t += '<div style="position:absolute;top:0px;width:100%;">';
           t += '<button  data-toggle="modal" data-target="#modal-daily-reads" id="view_daily_reads" style="margin-right:50px;float:right;margin-top:5px;" class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View Daily Reads</button>';
           t += '<a  style="margin-right:5px;float:right;margin-top:5px;" href="'+ROOT_PATH+'user/editAbout.php?about_id='+ dailyReads[i]["about_id"] +'" class="editBtn btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
           t += '</div>';
           }
           t += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
           t += '</div>';
           
           t += '<div class="modal-body">';
           t += ''+ dailyReads[i]["content"] +''; 
           t += '</div>';
          
         $("#modal_daily_reads").html(t);
         }
   });
 }

     function getAllDailyReads() {
         $.ajax({url: ROOT_PATH + 'user/route.php?r=getAllDailyReads', type: 'post', data:{}}).then(function(resp) {
           var dailyReads = JSON.parse(resp);
           var dailyReads_len = dailyReads.length; 
               if (dailyReads_len == 0){
                   var t = '';
                       t += '<div style="display:flex;justify-content:center;align-items:center;height:50vh;position:absolute;width:100%;">'; 
                       t += '<h4 style="color:red;font-weight:bold;">No Added Daily Reads Yet!</h4>'; 
                       t += '</div>';
                   $(".daily_reads_content").html(t);  
               }else{
                   var t = '';
                   for (var i = 0; i < dailyReads_len; i++) {

                       t += '<tr>';
                       t += '<td class="text-center" width="190">'+ dailyReads[i]["dt"] +'</td>';
                       t += '<td class="text-center">'+ dailyReads[i]["title"] +'</td>';

                       if(dailyReads[i]["status"]=="Not Shown"){
                       t += '<td class="text-center" width="120" style="color:red;">'+ dailyReads[i]["status"] +'</td>';
                       }else{
                       t += '<td class="text-center" width="120" style="color:#2ecc71;">'+ dailyReads[i]["status"] +'</td>'; 
                       }

                       t += '<td class="text-center" width="100">';
                       t += '<a href="'+ ROOT_PATH +'user/editAbout.php?about_id='+ dailyReads[i]["about_id"] +'" class="btn btn-sm btn-primary" style="width:100%; margin-bottom:5px;"><i class="fas fa-edit"></i> Edit</a>';
                       
                       if(dailyReads[i]["status"]=="Not Shown"){
                           t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-info display" index="' + dailyReads[i]["about_id"] + '"  rel="' + dailyReads[i]["about_id"] + '"><i class="fa fa-eye" style="color:white;"></i> Display</button>';
                       }else{
                           t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-warning undisplay" index="' + dailyReads[i]["about_id"] + '"  rel="' + dailyReads[i]["about_id"] + '"><i class="fa fa-eye" style="color:white;"></i> Undisplay</button>';
                       }
                       
                       t += '<button style="width:100%;" class="btn btn-sm btn-danger trash" index="' + dailyReads[i]["about_id"] + '"  rel="' + dailyReads[i]["about_id"] + '"><i class="fa fa-trash" style="color:white;"></i> Delete</button>';
                       t += '</td>';
                       t += '</tr>';

                   }
                   $(".daily_reads_content").html(t);
               }
       });
     }
     function load_grid(search = "", load = false) {
       load_dailyreads();
       if (load) {
           $(".daily_reads_content").html("Searching...");
       }
       $.ajax({
           url: ROOT_PATH + "user/route.php?r=getSearchReads",
           type: "post",
           data: {search: search}
       }).then(function(resp) {
           var global_data = JSON.parse(resp);
           var len = global_data.length;
           var t = "";
           if(len == 0){
            $(".daily_reads_content").html("No Data Found!");
           }else{

           for (var i = 0; i < len; i++) {

               t += '<tr>';
               t += '<td class="text-center" width="190">' + global_data[i]["dt"] + '</td>';
               t += '<td class="text-center">' + global_data[i].title + '</td>';

               if(global_data[i].status=="Not Shown"){
               t += '<td class="text-center" width="120" style="color:red;">' + global_data[i].status + '</td>';
               }else{
               t += '<td class="text-center" width="120" style="color:#2ecc71;">' + global_data[i].status + '</td>'; 
               }

               t += '<td class="text-center" width="100">';
               t += '<a href="'+ ROOT_PATH +'user/editAbout.php?about_id='+ global_data[i].about_id +'" class="btn btn-sm btn-primary" style="width:100%; margin-bottom:5px;"><i class="fas fa-edit"></i> Edit</a>';
                       
               if(global_data[i].status=="Not Shown"){
                   t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-info display" index="' + global_data[i].about_id + '"  rel="' + global_data[i].about_id + '"><i class="fa fa-eye" style="color:white;"></i> Display</button>';
               }else{
                   t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-warning undisplay" index="' + global_data[i].about_id + '"  rel="' + global_data[i].about_id + '"><i class="fa fa-eye" style="color:white;"></i> Undisplay</button>';
               }
                       
               t += '<button style="width:100%;" class="btn btn-sm btn-danger trash" index="' + global_data[i].about_id + '"  rel="' + global_data[i].about_id + '"><i class="fa fa-trash" style="color:white;"></i> Delete</button>';
               t += '</td>';
               t += '</tr>';
               
           }
           $(".daily_reads_content").html(t);
       }
       }, function() {
           alert("Something went wrong!");
       });
   }
 $('.daily_reads_content').on("click", ".trash", function(evt) {
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
               url: ROOT_PATH + "user/route.php?r=deleteDailyReads",
               type: "post",
               data: {id: i},
                beforeSend:function(){
                   $('#loaderModal').modal('show');
               },
               success: function(data) {
                    $('#loaderModal').modal('hide');
                    load_grid("", false);
                    getNotShownReads();
                   Swal.fire({
                       heightAuto: false,
                       icon: 'success',
                       title: 'Success!',
                       text: 'Content Successfully Deleted!'
                   });
               }
             });
           }
         });
 evt.stopPropagation();
});
$('.daily_reads_content').on("click", ".display", function(evt) {
 var i = $(this).attr("index");
   Swal.fire({
           heightAuto: false,
           title: 'Are you sure?',
           text: 'Previous content will be undisplayed!',
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Yes, display it!'
       }).then((result) => {
         if (result.isConfirmed) {
             $.ajax({
               url: ROOT_PATH + "user/route.php?r=displayDailyReads",
               type: "post",
               data: {id: i},
                beforeSend:function(){
                   $('#loaderModal').modal('show');
               },
               success: function(data) {
                    $('#loaderModal').modal('hide');
                    load_grid("", false);
                    getNotShownReads();
                   Swal.fire({
                       heightAuto: false,
                       icon: 'success',
                       title: 'Success!',
                       text: 'Content Successfully Displayed!'
                   });
               }
             });
           }
         });
 evt.stopPropagation();
});
$('.daily_reads_content').on("click", ".undisplay", function(evt) {
 var i = $(this).attr("index");
   Swal.fire({
           heightAuto: false,
           title: 'Are you sure?',
           text: 'Content will be undisplayed!',
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Yes, undisplay it!'
       }).then((result) => {
         if (result.isConfirmed) {
             $.ajax({
               url: ROOT_PATH + "user/route.php?r=undisplayDailyReads",
               type: "post",
               data: {id: i},
                beforeSend:function(){
                   $('#loaderModal').modal('show');
               },
               success: function(data) {
                    $('#loaderModal').modal('hide');
                    load_grid("", false);
                    getNotShownReads();
                   Swal.fire({
                       heightAuto: false,
                       icon: 'success',
                       title: 'Success!',
                       text: 'Content Successfully Undisplayed!'
                   });
               }
             });
           }
         });
 evt.stopPropagation();
});
////////MOTIVATIONAL TIPS//////////
$('.center-quote-card').on("click", "#view_motivational_tips", function() {
 getAllMotivationalTips();
});
$('#modal_motivational_tips').on("click", "#view_motivational_tips", function() {
  getAllMotivationalTips();
  $('#modal-quote').modal('hide');
});

$("#searchbar2").on("keyup", function(evt) {
 if(evt.keyCode == 13) {
   load_grid2($("#searchbar2").val(), true);
 }
});
$('#search_btn2').on('click', function() {
   load_grid2($("#searchbar2").val(), true);
});
$('.center-quote-card').on("click", "#see_more", function() {
 load_motivationaltips_onModal();
});

function load_motivationaltips() {
   $.ajax({url: ROOT_PATH + 'user/indexRoute.php?route=getDisplayedTips', type: 'post', data:{}}).then(function(resp) {
     var motivationaltips = JSON.parse(resp);
     var motivationaltips_len = motivationaltips.length; 

     if(motivationaltips_len==0){
       var t = '';
         t += '<div class="content">'; 
         t += '<div style="display:flex;width:100%;justify-content:center;align-items:center;margin-top:20px;">'; 
         t += ' <img id="empty_content1" src="'+ ROOT_PATH +'assets/images/feed-logo.png">'; 
         t += '</div>'; 
         t += '</div>'; 
         t += '<div class="content-footer">';
         if (admin_access){
         t += '<button data-toggle="modal" data-target="#modal-motivational-tips" id="view_motivational_tips" style="float:right;margin-right:30px;border-radius:50px;" class="btn-success"><i class="fa fa-eye"></i> View Motivational Tips</button>';
         }else{
           t += '<div style="display:flex;justify-content:center;align-items:center;"><strong style="color:red;">Motivational Tips Not Yet Available!</strong></div>';
         }
         t += '</div>';
       $(".center-quote-card").html(t);
     }else{
         var t = '';
         for (var i = 0; i < motivationaltips_len; i++) {
           t += '<div class="content">'; 
           t += ''+ motivationaltips[i]["content"] +''; 
           t += '</div>'; 
           t += '<div class="content-footer">';
           t += ' <a id="see_more" href="#" data-toggle="modal" data-target="#modal-quote">See More>></a>';
           t += '</div>';
         $(".center-quote-card").html(t);
         }
     }
   });
 }
 function getAllMotivationalTips() {
         $.ajax({url: ROOT_PATH + 'user/route.php?r=getAllMotivationalTips', type: 'post', data:{}}).then(function(resp) {
           var motivationaltips = JSON.parse(resp);
           var motivationaltips_len = motivationaltips.length; 
               if (motivationaltips_len == 0){
                   var t = '';
                       t += '<div style="display:flex;justify-content:center;align-items:center;height:50vh;position:absolute;width:100%;">'; 
                       t += '<h4 style="color:red;font-weight:bold;">No Added Motivational Tips Yet!</h4>'; 
                       t += '</div>';
                   $(".motivational_tips_content").html(t);  
               }else{
                   var t = '';
                   for (var i = 0; i < motivationaltips_len; i++) {

                       t += '<tr>';
                       t += '<td class="text-center" width="190">'+ motivationaltips[i]["dt"] +'</td>';
                       t += '<td class="text-center">'+ motivationaltips[i]["title"] +'</td>';

                       if(motivationaltips[i]["status"]=="Not Shown"){
                       t += '<td class="text-center" width="120" style="color:red;">'+ motivationaltips[i]["status"] +'</td>';
                       }else{
                       t += '<td class="text-center" width="120" style="color:#2ecc71;">'+ motivationaltips[i]["status"] +'</td>'; 
                       }

                       t += '<td class="text-center" width="100">';
                       t += '<a href="'+ ROOT_PATH +'user/editQuote.php?quote_id='+ motivationaltips[i]["quote_id"] +'" class="btn btn-sm btn-primary" style="width:100%; margin-bottom:5px;"><i class="fas fa-edit"></i> Edit</a>';
                       
                       if(motivationaltips[i]["status"]=="Not Shown"){
                           t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-info display" index="' + motivationaltips[i]["quote_id"] + '"  rel="' + motivationaltips[i]["quote_id"] + '"><i class="fa fa-eye" style="color:white;"></i> Display</button>';
                       }else{
                           t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-warning undisplay" index="' + motivationaltips[i]["quote_id"] + '"  rel="' + motivationaltips[i]["quote_id"] + '"><i class="fa fa-eye" style="color:white;"></i> Undisplay</button>';
                       }
                       
                       t += '<button style="width:100%;" class="btn btn-sm btn-danger trash" index="' + motivationaltips[i]["quote_id"] + '"  rel="' + motivationaltips[i]["quote_id"] + '"><i class="fa fa-trash" style="color:white;"></i> Delete</button>';
                       t += '</td>';
                       t += '</tr>';

                   }
                   $(".motivational_tips_content").html(t);
               }
       });
     }
     function load_grid2(search = "", load = false) {
       load_motivationaltips();
       if (load) {
           $(".motivational_tips_content").html("Searching...");
       }
       $.ajax({
           url: ROOT_PATH + "user/route.php?r=getSearchTips",
           type: "post",
           data: {search: search}
       }).then(function(resp) {
           var global_data = JSON.parse(resp);
           var len = global_data.length;
           var t = "";
           if(len == 0){
            $(".motivational_tips_content").html("No Data Found!");
           }else{

           for (var i = 0; i < len; i++) {

               t += '<tr>';
               t += '<td class="text-center" width="190">' + global_data[i]["dt"] + '</td>';
               t += '<td class="text-center">' + global_data[i].title + '</td>';

               if(global_data[i].status=="Not Shown"){
               t += '<td class="text-center" width="120" style="color:red;">' + global_data[i].status + '</td>';
               }else{
               t += '<td class="text-center" width="120" style="color:#2ecc71;">' + global_data[i].status + '</td>'; 
               }

               t += '<td class="text-center" width="100">';
               t += '<a href="'+ ROOT_PATH +'user/editQuote.php?quote_id='+ global_data[i].quote_id +'" class="btn btn-sm btn-primary" style="width:100%; margin-bottom:5px;"><i class="fas fa-edit"></i> Edit</a>';
                       
               if(global_data[i].status=="Not Shown"){
                   t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-info display" index="' + global_data[i].quote_id + '"  rel="' + global_data[i].quote_id + '"><i class="fa fa-eye" style="color:white;"></i> Display</button>';
               }else{
                   t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-warning undisplay" index="' + global_data[i].quote_id + '"  rel="' + global_data[i].quote_id + '"><i class="fa fa-eye" style="color:white;"></i> Undisplay</button>';
               }
                       
               t += '<button style="width:100%;" class="btn btn-sm btn-danger trash" index="' + global_data[i].quote_id + '"  rel="' + global_data[i].quote_id + '"><i class="fa fa-trash" style="color:white;"></i> Delete</button>';
               t += '</td>';
               t += '</tr>';
               
           }
           $(".motivational_tips_content").html(t);
       }
       }, function() {
           alert("Something went wrong!");
       });
   }
   function load_motivationaltips_onModal() {
   $.ajax({url: ROOT_PATH + 'user/indexRoute.php?route=getDisplayedTips', type: 'post', data:{}}).then(function(resp) {
     var motivationalTips = JSON.parse(resp);
     var motivationalTips_len = motivationalTips.length; 
         var t = '';
         for (var i = 0; i < motivationalTips_len; i++) {
           t += '<div class="modal-header" style="position:relative;padding-bottom:0px;">';
           t += '<span style="font-size:18px;font-weight:bold;"> '+ motivationalTips[i]["title"] +'</span>';
           if (admin_access){
           t += '<div style="position:absolute;top:0px;width:100%;">';
           t += '<button data-toggle="modal" data-target="#modal-motivational-tips" id="view_motivational_tips" style="margin-right:50px;float:right;margin-top:5px;" class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View Motivational Tips</button>';
           t += '<a  style="margin-right:5px;float:right;margin-top:5px;" href="'+ROOT_PATH+'user/editQuote.php?quote_id='+ motivationalTips[i]["quote_id"] +'" class="editBtn btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
           t += '</div>';
           }
           t += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
           t += '</div>';
           
           t += '<div class="modal-body">';
           t += ''+ motivationalTips[i]["content"] +''; 
           t += '</div>';
          
         $("#modal_motivational_tips").html(t);
         }
   });
 }
 $('.motivational_tips_content').on("click", ".trash", function(evt) {
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
               url: ROOT_PATH + "user/route.php?r=deleteMotivationalTips",
               type: "post",
               data: {id: i},
                beforeSend:function(){
                   $('#loaderModal').modal('show');
               },
               success: function(data) {
                    $('#loaderModal').modal('hide');
                    load_grid2("", false);
                    getNotShownTips();
                   Swal.fire({
                       heightAuto: false,
                       icon: 'success',
                       title: 'Success!',
                       text: 'Content Successfully Deleted!'
                   });
               }
             });
           }
         });
 evt.stopPropagation();
});
$('.motivational_tips_content').on("click", ".display", function(evt) {
 var i = $(this).attr("index");
   Swal.fire({
           heightAuto: false,
           title: 'Are you sure?',
           text: 'Previous content will be undisplayed!',
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Yes, display it!'
       }).then((result) => {
         if (result.isConfirmed) {
             $.ajax({
               url: ROOT_PATH + "user/route.php?r=displayMotivationalTips",
               type: "post",
               data: {id: i},
                beforeSend:function(){
                   $('#loaderModal').modal('show');
               },
               success: function(data) {
                    $('#loaderModal').modal('hide');
                    load_grid2("", false);
                    getNotShownTips();
                   Swal.fire({
                       heightAuto: false,
                       icon: 'success',
                       title: 'Success!',
                       text: 'Content Successfully Displayed!'
                   });
               }
             });
           }
         });
 evt.stopPropagation();
});
$('.motivational_tips_content').on("click", ".undisplay", function(evt) {
 var i = $(this).attr("index");
   Swal.fire({
           heightAuto: false,
           title: 'Are you sure?',
           text: 'Content will be undisplayed!',
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Yes, undisplay it!'
       }).then((result) => {
         if (result.isConfirmed) {
             $.ajax({
               url: ROOT_PATH + "user/route.php?r=undisplayMotivationalTips",
               type: "post",
               data: {id: i},
                beforeSend:function(){
                   $('#loaderModal').modal('show');
               },
               success: function(data) {
                    $('#loaderModal').modal('hide');
                    load_grid2("", false);
                    getNotShownTips();
                   Swal.fire({
                       heightAuto: false,
                       icon: 'success',
                       title: 'Success!',
                       text: 'Content Successfully Undisplayed!'
                   });
               }
             });
           }
         });
 evt.stopPropagation();
});

$("#dr-a").on("click", function(){
 $("#arch-dr").slideUp();
 $("#arch-mt").slideDown();
 $("#arch-mt").css("display", "block");
 $("#mt-a").css("display", "block");
 $("#dr-a").css("display", "none");
 getNotShownTips();
});
$("#mt-a").on("click", function(){
 $("#arch-mt").slideUp();
 $("#arch-dr").slideDown();
 $("#arch-dr").css("display", "block");
 $("#dr-a").css("display", "block");
 $("#mt-a").css("display", "none");
 getNotShownReads();
});
 getNotShownReads();
 var global_daily_reads = null;
 function getNotShownReads() {
   $.ajax({url: ROOT_PATH + 'user/indexRoute.php?route=geNotDisplayedReads', type: 'post', data:{}}).then(function(resp) {
     var dailyReads = JSON.parse(resp);
     var dailyReads_len = dailyReads.length; 
     global_daily_reads = dailyReads;

     $("#arch-mt").css("display", "none");
     $("#arch-dr").css("display", "block");
     $("#dr-a").css("display", "block");
     $("#mt-a").css("display", "none");

     if(dailyReads_len==0){
       var t = '';
         t += '<div style="height:20vh;display:flex;justify-content:center;align-items:center;">'; 
         t += '<span style="color:red;font-weight:bold;">No Contents Added Yet!</span>'; 
         t += '</div>';
       $("#archive-content").html(t);
     }
     else{
         var t = '';
         for (var i = 0; i < dailyReads_len; i++) {
           t +=  '<li style="position:relative;list-style:none;">'; 
           t +=  '<a  maxlength="2" id="daily_reads_archive" href="#" index="'+ i +'" data-toggle="modal" data-target="#modal-archive">'+ dailyReads[i]["title"] +'</a>'; 
           t +=  '<small style="position:absolute;color:gray;font-style:italic;right:0px;bottom:0px;font-size:10px;">Created On: '+ dailyReads[i]["dt"] +'</small>'; 
           t +=  '</li>'; 
         $("#archive-content").html(t);
         }
     }
   });
 }
 var global_motivational_tips = null;
 function getNotShownTips() {
   $.ajax({url: ROOT_PATH + 'user/indexRoute.php?route=geNotDisplayedTips', type: 'post', data:{}}).then(function(resp) {
     var motivationaltips = JSON.parse(resp);
     var motivationaltips_len = motivationaltips.length; 
     global_motivational_tips = motivationaltips;

     $("#arch-mt").css("display", "block");
     $("#arch-dr").css("display", "none");
     $("#dr-a").css("display", "none");
     $("#mt-a").css("display", "block");

     if(motivationaltips_len==0){
       var t = '';
         t += '<div style="height:20vh;display:flex;justify-content:center;align-items:center;">'; 
         t += '<span style="color:red;font-weight:bold;">No Contents Added Yet!</span>'; 
         t += '</div>';
       $("#archive-content").html(t);
     }
     else{
         var t = '';
         for (var i = 0; i < motivationaltips_len; i++) {
           t +=  '<li style="position:relative;list-style:none;">'; 
           t +=  '<a id="motivational_tips_archive" href="#" index="'+ i +'" data-toggle="modal" data-target="#modal-archive">'+ motivationaltips[i]["title"] +'</a>'; 
           t +=  '<small style="position:absolute;color:gray;font-style:italic;right:0px;bottom:0px;font-size:10px;">Created On: '+ motivationaltips[i]["dt"] +'</small>'; 
           t +=  '</li>'; 
         $("#archive-content").html(t);
         }
     }
   });
 }

 $('#archive-content').on("click", "#daily_reads_archive", function(evt) {
   var id = $(this).attr("index");
   var data = global_daily_reads[id];
   var t = '';
   t += '<div class="modal-header" style="position:relative;padding-bottom:0px;">';
   t += '<span style="font-size:18px;font-weight:bold;"> '+ data.title +'</span>';
   if (admin_access){
     t += '<div style="position:absolute;top:0px;width:100%;">';
           t += '<a  style="margin-right:50px;float:right;margin-top:5px;" href="'+ROOT_PATH+'user/editAbout.php?about_id='+ data.about_id +'" class="editBtn btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
           t += '</div>';
    }
   t += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
   t += '</div>';
           
   t += '<div class="modal-body">';
   t += ''+ data.content +''; 
   t += '</div>';
   $("#modal_archive").html(t);
 });

 $('#archive-content').on("click", "#motivational_tips_archive", function(evt) {
   var id = $(this).attr("index");
   var data2 = global_motivational_tips[id];
   var t = '';
   t += '<div class="modal-header" style="position:relative;padding-bottom:0px;">';
   t += '<span style="font-size:18px;font-weight:bold;"> '+ data2.title +'</span>';
   if (admin_access){
     t += '<div style="position:absolute;top:0px;width:100%;">';
           t += '<a  style="margin-right:50px;float:right;margin-top:5px;" href="'+ROOT_PATH+'user/editQuote.php?quote_id='+ data2.quote_id +'" class="editBtn btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
           t += '</div>';
    }
   t += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
   t += '</div>';
           
   t += '<div class="modal-body">';
   t += ''+ data2.content +''; 
   t += '</div>';
   $("#modal_archive").html(t);
 });

    </script>
    <script src="<?php print ROOT_PATH; ?>assets/s.js"></script>
</body>
</html>