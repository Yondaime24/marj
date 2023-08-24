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
            <div class="empty-image">
              <strong>No Image Available Yet! </strong>
              <?php if ($admin->check($key->ulevel)) { ?>
              <a href="<?php print ROOT_PATH; ?>user/imageSliderViewer.php" onclick="window.open('<?php print ROOT_PATH; ?>user/imageSliderViewer.php', 'newwindow', 'width=1200,height=600'); return false;"  title="Edit"><i class="far fa-edit"></i>  </a>
              <?php  } ?>
            </div>
          <ol class="carousel-indicators">
            <?php $c = 0; foreach ($carousel as $row => $carousels) { ?>
            <li data-target="#my-pics" data-slide-to="<?php echo $c++; ?>" class="<?php echo $row === 0 ? 'active' : ''; ?>"></li>
            <?php } ?>
          </ol>
            <div class="carousel-inner" role="listbox">
              <?php foreach ($carousel as $row => $carousels) { ?>
              <div class="item <?php echo $row === 0 ? 'active' : ''; ?>">
                <img style="height: 58vh;width:49.76vw;image-rendering:-webkit-optimize-contrast;" src="data:image;base64,<?php echo $carousels['image'] ?>" alt="" />
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
                  <!-- DISPLAY ONLINE USERS LIST -->
                  </div>
                  <div class="users-list2">
                    <!-- DISPLAY OFFLINE USERS LIST -->
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
    <script src="<?php print ROOT_PATH; ?>assets/js/reads_tips.js"></script>
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

    </script>
    <script src="<?php print ROOT_PATH; ?>assets/s.js"></script>
</body>
</html>