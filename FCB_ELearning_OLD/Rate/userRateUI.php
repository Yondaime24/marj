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
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/user_rate.css" />
    <link rel="stylesheet" type="text/css" href="<?php print ROOT_PATH; ?>assets/sweetalert/dist/sweetalert2.min.css">
</head>
<body>
<div class="top-loader"><div></div></div>

  <main>
      <header>
        <div class="nav-btn">
           <a id="profile-btn" href="<?php print ROOT_PATH; ?>user/profile.php" title="Profile"><i class="fas fa-user"></i></a>
           <a id="history" href="#" title="History" data-toggle="modal" data-target="#historyModal"><i class="fas fa-history"></i></a>
        </div>
      </header>
    <section>
      <div class="title">
        <h1>Rate Us</h1>
      </div>
      <div class="content">
          <small id="rate_help"><i>Please select rating!</i></small>
        <form method="post" id="rateus_form">
          <input type="text" id="user_id" name="user_id" value="<?php print $user->idno; ?>" hidden>
          <div class="rate">
            <input type="radio" id="star5" name="rate_value" value="5">
            <label for="star5" title="5 star">5 stars</label>
            <input type="radio" id="star4" name="rate_value" value="4">
            <label for="star4" title="4 star">4 stars</label>
            <input type="radio" id="star3" name="rate_value" value="3">
            <label for="star3" title="3 star">3 stars</label>
            <input type="radio" id="star2" name="rate_value" value="2">
            <label for="star2" title="2 star">2 stars</label>
            <input type="radio" id="star1" name="rate_value" value="1">
            <label for="star1" title="1 star">1 star</label>
          </div>
           <div id="rate_error"><span></span></div>
      </div>
        <div class="form__group">
          <textarea id="rate_cmt" class="form__field" placeholder="What can we do to improve?" name="rate_cmt" autofocus="on"></textarea>
          <label for="message" class="form__label">What can we do to improve?</label>
          <small id="comment_help"><i id="i">Please leave a comment!</i></small>
        </div>
      <div class="foot">
         <div class="submit_btn">
           <input type="hidden" name="rate_id" id="rate_id" />
           <input type="hidden" name="operation" id="operation" value="Submit" />
           <input type="submit" name="submit_btn" id="submit_btn" value="Send Feedback" class="btn btn-primary btn-sm" />
        </div>
      </div>
      </form>
    </section>
  </main>

            <!-- MESSAGE MODAL -->
        <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <span class="header-title"><i class="fas fa-history"></i> History</span>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                  <div class="rating">
                    <span class="body-title">Recent Rating</span>
                    <span class="rate_result"><span id="rated_value"></span></span>
                    <div class="stars_content">
                        <div class="rating_stars">
                          <input type="radio" id="star5" value="5">
                          <label class="ustar" for="star5" title="5 star">5 stars</label>
                          <input type="radio" id="star4" value="4" >
                          <label class="ustar" for="star4" title="4 star">4 stars</label>
                          <input type="radio" id="star3" value="3">
                          <label class="ustar" for="star3" title="3 star">3 stars</label>
                          <input type="radio" id="star2" value="2">
                          <label class="ustar" for="star2" title="2 star">2 stars</label>
                          <input type="radio" id="star1" value="1">
                          <label class="ustar" for="star1" title="1 star">1 star</label>
                        </div>
                    </div>
                  </div>
              </div>
              <div class="modal-foot">
                    <div class="bordr-div">
                      <span style="">My F.E.E.D. Review/s</span>
                    </div>
                      <div class="comment-div">
                         
                      </div>
              </div>
            </div>
          </div>
        </div>

    <script src="<?php print ROOT_PATH; ?>config/config.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/jquery.min.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/sweetalert/dist/sweetalert2.min.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/bootstrap3.min.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/user_rate.js"></script>

</body>
</html>