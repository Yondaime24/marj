<?php 
  use classes\auth;
  use classes\edit_about;

  require_once '../___autoload.php';
  $auth = new auth();
  $auth::isLogin();

  if (!isset($_GET['about_id'])) { 
    print 'Something Went Wrong!';
    exit;
  }
  $stmt = sql::con1()->prepare("SELECT * FROM feed_about where about_id=" . $_GET["about_id"]);
  $stmt->execute();
  $res = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
	  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="apple-touch-icon" sizes="76x76" href="../assets/images/feed-logo.png">
	  <link rel="icon" type="image/png" href="../assets/images/feed-logo.png">
	  <title>F.E.E.D. | Edit</title>

    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap3.min.css">
	  <link rel="stylesheet" type="text/css" href="../assets/css/editabout.css">
	  <link rel="stylesheet" href="../assets/css/all.min.css" />
	  <link rel="stylesheet" type="text/css" href="../assets/sweetalert/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../assets/css/loading_gif.css" />

    <style>
        .red::placeholder{
            color:red;
            opacity: .5;
            font-style:italic;
        }
    </style>

</head>
<body>

  <main>
    <section class="sec-left">
    <a class="btn btn-sm btn-success" href="addAbout.php" style="margin-bottom:10px;margin-left:10px;" title="Add Daily Reads"><i style="font-size:10px;" class="fas fa-book-open"></i></a>
    <a class="btn btn-sm btn-success" href="../index.php" style="margin-left:10px;" title="Return to Home"><i style="font-size:11px;" class="fas fa-home"></i></a>
    </section>
    <section class="sec-mid">
      <div class="editor">  
      <form method="post" id="edit_about">
          <div style="display:flex;">
            <div style="flex:1 1 0;padding:0px 10px 0px 10px;">
                <input type="text" id="title" name="title" class="form-control" value="<?php echo $res[0]['title']; ?>">
            </div>
            <div style="flex:1 1 0;padding:0px 10px 10px 10px;position:relative;">
              <i class="fas fa-save" style="color:white;position:absolute;right:72px;top:8px;"></i>
              <input type="submit" style="float:right;padding-left:30px;" class="btn btn-primary" id="save" name="save" value="Update">
            </div>
          </div>
          <input type="hidden" name="about_id" id="about_id" value="<?php echo $res[0]['about_id']; ?>">
          <textarea name="editor1" id="editor1" class="form-control"><?php echo $res[0]['content']; ?></textarea>
      </form>
    </div>          
    </section>
    <section class="sec-right">
    </section>
  </main> 

<div class="modal fade" id="loaderModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <div class="loading_bar">
            <img src="../assets/images/loader.gif" width="50px">
          </div>
        </div>
          <div class="footer">
            <span  style="margin-top: 20px;">Loading...</span>
          </div>
      </div>
    </div>
</div>

	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/ckeditor/ckeditor.js"></script>
	<script src="../assets/js/editor.js"></script>
	<script src="../assets/sweetalert/dist/sweetalert2.min.js"></script>
  <script src="../assets/js/editor_content.js"></script>
  <script src="../assets/js/bootstrap3.min.js"></script>

</body>
</html>