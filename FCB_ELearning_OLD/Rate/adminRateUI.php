<?php 
  use classes\key;
  use classes\auth;
  use classes\access;
  use classes\user;

  require_once "../___autoload.php";
  $key = new key();
  $admin=new access(['PR']);
  $user = new user();
  $user->load();

  auth::isLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/images/feed-logo.png">
    <link rel="icon" type="image/png" href="../assets/images/feed-logo.png">
    <title>F.E.E.D. | Rate Us</title>

    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/bootstrap3.min.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/all.min.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/admin_rate.css" />
    <link rel="stylesheet" type="text/css" href="<?php print ROOT_PATH; ?>assets/sweetalert/dist/sweetalert2.min.css">
</head>
<body>
<div class="top-loader"><div></div></div>

        <div class="nav-btn">
           <a id="profile" href="../user/profile.php" title="Profile"><i class="fas fa-user"></i></a>
           <a id="add_review" href="userRateUI.php" title="Add Review"><i class="fas fa-plus"></i></a>
        </div>

    <main>
      <section class="upper_sec">
        <div class="upper_left">
          <span class="title"><b>Ratings & Reviews</b></span id="title">
          <div class="containerdiv">
            <div>
              <img src="../assets/images/stars_blank.png" alt="img">
            </div>
            <div class="cornerimage">
              <img src="../assets/images/stars_full.png">
            </div>
          </div>
          <div class="count_avg">
            <p><span id="rate01"></span><span id="count01"></span></p>
          </div>
        </div>
        <div class="upper_right">
          <div class="barcontainer"> 
            <div class="barcontainerheader"></div>

            <div class="bar" id="bar5">
               <p id="star5avg">0<span>%</span></p>
              <div class="barlabel">
                <i class="fa fa-star gold"></i>
                <i class="fa fa-star gold"></i>
                <i class="fa fa-star gold"></i>
                <i class="fa fa-star gold"></i>
                <i class="fa fa-star gold"></i>
              </div>
            </div>

            <div class="bar" id="bar4">
               <p id="star4avg">0<span>%</span></p>
              <div class="barlabel">
                <i class="fa fa-star gold"></i>
                <i class="fa fa-star gold"></i>
                <i class="fa fa-star gold"></i>
                <i class="fa fa-star gold"></i>
              </div>
            </div>

            <div class="bar" id="bar3">
               <p id="star3avg">0<span>%</span></p>
              <div class="barlabel">
                <i class="fa fa-star gold"></i>
                <i class="fa fa-star gold"></i>
                <i class="fa fa-star gold"></i>
              </div>
            </div>

            <div class="bar" id="bar2">
               <p id="star2avg">0<span>%</span></p>
              <div class="barlabel">
                <i class="fa fa-star gold"></i>
                <i class="fa fa-star gold"></i>
              </div>
            </div>

            <div class="bar" id="bar1">
               <p id="star1avg">0<span>%</span></p>
              <div class="barlabel">
                <i class="fa fa-star gold"></i>
              </div>
            </div>

          </div>
        </div>
      </section>
      <section class="lower_sec">

        <div class="lower_div">
          <span>Reviews</span>
          <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="filter_rating" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Filter Reviews <i class="fas fa-chevron-down"></i></button>
            <ul class="dropdown-menu" aria-labelledby="filter_rating">
              <li><a id="filter_5star" href="#">5 stars</a></li>
              <li><a id="filter_4star" href="#">4 stars</a></li>
              <li><a id="filter_3star" href="#">3 stars</a></li>
              <li><a id="filter_2star" href="#">2 stars</a></li>
              <li><a id="filter_1star" href="#">1 star</a></li>
            </ul>
          </div>
        </div>

        <div class="cmt_res" id="cmt_result">

        </div>
         
      </section>
    </main>
    <a href="#top" id="myBtn"><i class="fas fa-arrow-up"></i></a>

                <!-- PREVIOUS MODAL -->
                <div class="modal fade-scale" id="prev_modal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <form  method="post" id="prev_form">
                        <input type="hidden" id="user_id" name="user_id">
                        <input type="hidden" id="operation2" name="operation2">
                      <div class="modal-header">
                        <span style="font-size: 24px;font-weight:bold;"><i class="far fa-star" style="color: orange;"></i> Previous Reviews</span>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <a href="#" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                      </div>
                      <div class="modal-body">
                        <div id="result">

                        </div>
                      </div>
                    </form>
                    </div>
                  </div>
                </div>

    <script src="<?php print ROOT_PATH; ?>config/config.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/jquery.min.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/sweetalert/dist/sweetalert2.min.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/bootstrap3.min.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/admin_rate.js"></script>

</body>
</html>