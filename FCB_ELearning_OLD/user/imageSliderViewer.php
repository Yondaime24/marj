<?php  
  use classes\auth;
  use classes\access;

  require_once '../___autoload.php';
  $auth = new auth();
  $auth::isLogin();
  $ac = new access();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/images/feed-logo.png">
  <link rel="icon" type="image/png" href="../assets/images/feed-logo.png">
  <title>F.E.E.D. | Image Carousel Viewer</title>

  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/image_carousel.css">
  <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/loader_t.css" />
  <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/all.min.css" />
  <link rel="stylesheet" type="text/css" href="../assets/css/material.indigo-pink.min.css">
  <link rel="stylesheet" type="text/css" href="../assets/sweetalert/dist/sweetalert2.min.css">

</head>
<body>
<?php if ($ac->check()): ?>
  <input type="radio" name="Photos" id="check1" checked>
  <input type="radio" name="Photos" id="check2">

  <div class="container">
    <div class="modal-btn">
        <a class="text-center" id="add_button" href="#" data-bs-toggle="modal" data-bs-target="#userModal">Add Image <i class="fas fa-plus"></i> </a>
        <br>
        <span></span>
    </div>
    <div class="top-content">
      <label class="upload" for="check1">Uploaded Images</label>
      <label class="display" for="check2">Displayed Images</label>
    </div>
    <div class="photo-gallery" id="result">
    </div>
    <br/>
  </div>
<?php else: ?>
    Unauthorized Access!
<?php endif; ?>
</body>

<div id="userModal" style="display: none">
    <div style="padding: 5px;">
        <form method="post" id="user_form" enctype="multipart/form-data">
                <div class="mb-3">
                  <span style="font-size: 15px">Image Title</span>
                  <input type="text" name="image_title" id="image_title" class="form-control" autocomplete="off">
                  <small style="color: red; display: none;" id="help-text"><i>Please enter image title!</i></small>
                </div>
                <div class="form-group">
                    <div class="file-upload mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--green-500 mdl-color-text--white">
                        <span>BROWSE</span>
                        <input type="file" name="carousel_image" id="carousel_image" class="upload"/>
                    </div>
                        <input type="text" id="fileuploadurl" readonly placeholder="Select image to upload">
                        <span id="user_uploaded_image"></span>
                </div>
                <input type="hidden" name="carousel_id" id="carousel_id" />
                <input type="hidden" name="operation" id="operation" />
                <input  type="submit" name="action" id="action" value="Add" class="btn btn-primary img-btn_input">
        </form>
    </div>
</div>

    <div class="modal collapsing" id="loaderModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content" style="background-color:transparent;border:none;">
              <div class="modal-body" style="border:none;">
                <div class="loading_bar">
                     <img src="../assets/images/loader.gif" width="50px">
                </div>
               </div>
               <div class="footer" style="display:flex;justify-content:center;align-items:center;">
                     <span style="color:white;margin-top: 20px;">Loading...</span>
                </div>
            </div>
    </div>
</div>

  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/material.min.js"></script>
  <script src="../assets/js/popper.min.js"></script>
  <script src="../assets/js/bootstrap3.min.js"></script>
  <script src="../assets/sweetalert/dist/sweetalert2.min.js"></script>
  <script src="../assets/js/carousel_ajax.js"></script>
  <script src="../assets/cbox/cbox.js"></script>

</html>