<?php  
use classes\auth;
use classes\image;

require_once '../../___autoload.php';
$auth = new auth();
$auth::isLogin();


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="76x76" href="../images/feed-logo.png">
    <link rel="icon" type="image/png" href="../images/feed-logo.png">
	<title>F.E.E.D. | Image Viewer</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../css/ckimage_gallery.css">
    <link rel="stylesheet" type="text/css" href="../css/material.indigo-pink.min.css">
    <link rel="stylesheet" type="text/css" href="../sweetalert/dist/sweetalert2.min.css">

</head>
<body>
    <div class="container-fluid">
        <div class="modal-btn">
             <a class="text-center" id="add_button" href="#">Add Image <i class="fas fa-plus"></i> </a>
        </div>
        <div class="photo-gallery" id="result">
        </div>
        <hr>
    </div>
</body> 

<div id="userModal">
  <div style="padding:5px;">

    <form method="post" id="user_form" enctype="multipart/form-data">
        <div class="form-group">
            <div class="file-upload mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--green-500 mdl-color-text--white">
                <span>BROWSE</span>
                <input type="file" name="user_image" id="user_image" class="upload" />
            </div>
                <input type="text" id="fileuploadurl" readonly placeholder="Select image to upload">
                <span id="user_uploaded_image"></span>
        </div>
        <input type="hidden" name="user_id" id="user_id" />
        <input type="hidden" name="operation" id="operation" />
        <input  type="submit" name="action" id="action" value="Add" class="btn btn-primary btn_input">
    </form>

  </div>
</div>


<div class="modal collapsing" id="loaderModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content" style="background-color:transparent;border:none;">
              <div class="modal-body" style="border:none;">
                <div class="loading_bar">
                     <img src="../images/loader.gif" width="50px">
                </div>
               </div>
               <div class="footer" style="display:flex;justify-content:center;align-items:center;">
                     <span style="color:white;margin-top: 20px;">Loading...</span>
                </div>
            </div>
    </div>
</div>


    <script src="../js/jquery.min.js"></script>
    <script src="../js/material.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap3.min.js"></script>
    <script src="../sweetalert/dist/sweetalert2.min.js"></script>
    <script src="../js/ckajax.js"></script>
    <script src="../cbox/cbox.js"></script>

    <script>
        var CKEditorFuncNum = "<?php echo $_REQUEST['CKEditorFuncNum']; ?>";
        var url = "http://<?php echo $_SERVER['SERVER_NAME']; ?>/FCB_ELearning/assets/imageFolder/images/";
            function selectImage(imgName) {
                url += imgName;
                window.opener.CKEDITOR.tools.callFunction(CKEditorFuncNum, url);
                window.close();
            }  


        $(document).ready(function(){
              var userModal = new cbox("#userModal");
              userModal.title = "Add Image ";
              userModal.backgroundcolor = "white";
              userModal.logo = '<i class="fa fa-plus"></i>';
              userModal.init();
            $('#add_button').click(function(){
              userModal.show();
            });
            $('#action').click(function(){
              userModal.close();
            });
      });


    </script>

</html>