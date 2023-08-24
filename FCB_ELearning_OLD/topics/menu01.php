<?php 
  use classes\topiccategories;
  use classes\topics;
  use classes\user;
  use classes\auth;
  use classes\Task;
  use classes\access;
  require_once '../___autoload.php';
  $auth = new auth();
  $auth::isLogin();
  $user = new user();
  $cat = new topiccategories();
  $topic = new topics();
  // render the current task
  $task = new Task();
  $user->load();
  $cat->status = '1';
  $data = $cat->getAll();
  $len = count($data);
  $gdata = [];
  for ($i = 0; $i < $len; $i++) {
    $topic->status = '1';
    $topic->catid = $data[$i]['id'];
    $gdata[$i] = [$data[$i], $topic->get()];
  }
  // Checking if task of the users
  $active_task = $task->active()['task'];
  $ac = new access(['PR']);
  $admin = $ac->check($user->ulevel);
  // debugging
  // foreach ($_SERVER as $key => $row)
  //   print $key .' - '. $row.'<br />';
  // ///netlinkz
  // exit;
  // end debug
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/style.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/topics.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/all.min.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/pptxjs.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/nv.d3.min.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/admin.topic.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/admin.category.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/loader.css" />
    <script src="<?php print ROOT_PATH; ?>assets/js/UserQuiz.js"></script>        
    <script src="<?php print ROOT_PATH; ?>config/config.js"></script>
    <style>
      *{box-sizing:border-box;}
      /*.glr{color:white;background-color:#22af226e;box-shadow:0px 0px 3px rgba(0,0,0,0.3);width:50px;position:absolute;top:0px;bottom:0px;margin:auto;height:40px;border:none;border-radius:10px;}
      .llg{left:20px;}
      .rrg{right:20px;}      
      .glr:hover{background-color:#1c811cc4;transform:scale(1.2);transition:0.1s;}*/
      .lflf{display:none;}
      .gtrans{animation:trn 1s ease;-webkit-animation:trn 1s ease;-moz-animation:trn 1s ease;-o-animation:trn 1s ease;-ms-animation:trn 1s ease;}
      @keyframes trn {
        from{opacity:0.0;}to{opacity:1;}
      }
      .drop{display:none;}
      #list-q-modal .list-group .list-group-item{cursor:pointer;position:relative;}
      /*#list-q-modal .list-group .list-group-item:hover{background-color:#9aff9a!important;}*/
      @keyframes c{from{background-color:#1dad6e;}to{background-color:#0567a7;}}
      .startqb{animation:c 2s ease infinite;}
    </style>
  </head>
  <body>
    <?php if($admin):  ?>
    <!-- ======================================== Admin ============================= -->
    <!-- Category Panel -->
    <div id="category-panel">
      <div class="container-fluid">
        <button class="btn btn-danger" id="close">Close Panel</button>
      </div>
      <div class="container-fluid" style="padding-top:20px;">
        <h5>Topics Category Panel</h5>
        <hr/>
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-3" style="padding-bottom:10px;">
              <div class="card">
                <div class="card-header">Category Form</div>
                <div class="card-body">
                <form id="cat-form">
                  <input type="hidden" name="id" />
                  <div class="form-group">
                    <label>Caption</label>
                    <input type="text" class="form-control" name="title" />
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="des"></textarea>
                  </div>
                    <div class="form-group" style="margin-top:10px;">
                      <button class="btn btn-info btn-sm"><i class="fa fa-save"></i> Save</button>
                    </div>
                  </form>
                </div>
              </div>  
            </div>
            <div class="col-md-9" id="catgen">
              
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Category Panel -->
    
    <!-- Topic panel -->
    <div id="topic-panel">
      <div class="container-fluid">
        <div class="btn-group">
          <button class="btn btn-danger" id="close"><i class="fa fa-close"></i> close</button>
          <button class="btn btn-primary" id="catbtn"><i class="fa fa-plus"></i> New Category</button>
          <a href="<?php print ROOT_PATH; ?>Quiz/CreateQuiz.php" class="btn btn-info"><i class="fa fa-plus"></i> Create Quiz</a>
        </div>
      </div>
      <div class="container-fluid" style="padding-top:10px;">
        <h5>Topics Panel</h5>
        <hr/>
        <div class="row">
          <div class="col-md-3" style="padding-bottom:10px;">
            <div class="card">
              <div class="card-header">New Topics</div>
              <div class="card-body">

                <form id="panel-topic-form">
                  <input type="hidden" name="id" />
                  <div class="form-group">
                    <label>Category</label>
                    <select class="form-control" name="catid" required="">
                      <option value="">[ Select Category ]</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" autocomplete='false' name="title" placeholder="Enter Topics Title" />
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="des" placeholder="Enter the Description of the topic"></textarea>
                  </div>
                  <div class="form-group" style="margin-top:10px;">
                    <button class="btn btn-primary save"><i class="fa fa-save"></i> Save Topics</button>
                  </div>
                </form>
                
              </div> 
            </div>
          </div>
          <div class="col-md-9">
            <div class="card">
              <div class="card-header">Topics List</div>
              <div class="card-body" id="panel-topic-list">

              </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Topic Panel -->

    <!-- Sub Topic Panel -->
    <div id="sub-topic-panel">
      <div class="container-fluid">
        <button class="btn btn-danger btn-sm" id="close"><i class="fa fa-close"></i></button>
        <hr/>
        <div style="position:relative;">
          <h5>Sub Topics</h5>
          <div class="row">
            <div class="col-md-3" id="left" style="display:none;padding-bottom:10px;">
              <div class="card">
                <div class="card-header" style="position:relative;">
                  <span>Sub Topic Form</span> <button class="btn btn-danger" id="close1" style="height:30px;width:30px;padding:2px;position:absolute;top:1px;right:5px;"><i class="fa fa-close"></i></button>
                </div>
                <div class="card-body">
                  <form id="sub-topic-form">
                    <input type="hidden" name="id" />
                    <div class="form-group">
                      <label>Title</label>
                      <input type="text" class="form-control" name="title" autocomplete="off" />
                    </div>
                    <div class="form-group">
                      <label>Description</label>
                      <textarea type="text" class="form-control" name="des"></textarea>
                    </div>
                    <div class="form-group" style="margin-top:10px;">
                      <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-save"></i></button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-12" id="right">
              <div class="card">
                <div class="card-header">
                 <span id="subtitle" style="font-weight:bolder;"></span> 
                </div>
                <div class="card-body">
                  <button class="new btn btn-info btn-sm"><i class="fa fa-plus"></i> New Sub Topic</button>
                  <div id="table">

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Sub Topic Panel -->
    
    <!-- Sub Topic Information per Id -->
    <div id="sub-topic-info">
      <div class="container-fluid">
        <button class="btn btn-danger btn-sm" id="close"><i class="fa fa-close"></i></button>
        <hr />
        <div> Category: <span id="cat_id"></span></div>
        <div> Topic: <span id="topic_id"></span> - <span id="subtopic_id"></span></div>
        <hr />
        <div class="row">
          <div class="col-md-3">
            <div class="card">
              <div class="card-header">Power Point Presentation</div>
              <div class="card-body">
                <form id="sub-topic-form-upload">
                  <div class="form-group">
                    <label>PowerPoint</label>
                    <input type="file" class="form-control" id="pptx-file" />
                  </div>
                  <div class="form-group" style="margin-top:10px;">
                    <button class="btn btn-primary btn-sm upload"><i class="fa fa-upload"></i> Upload</button>
                  </div>

                  <div class="progress-loader">
                    <div></div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="card">
              <div class="card-header">Powerpoint</div>
              <div class="card-body" id="gen">
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Sub Topic -->

    <!-- Powerpoint viewer -->
    <div id="topic-power-point-viewer">
      <button class="btn btn-danger btn-sm" id="close" style="position:fixed;top:20px;left:20px;"><i class="fa fa-close"></i></button>
      <div class="pptx-viewer" style="height:100%;width:100%;">
        
      </div>
    </div>
    <!-- End powerpoint viewer -->
    <div class="upload-pptx-wrap" style="display:none;position:fixed;top:0px;left:0px;z-index:2000;width:100%;height:100%;">
      <div style="width:480px;background-color:white;height:300px;position:absolute;top:0px;right:0px;bottom:0px;left:0px;margin:auto;border-radius:20px;box-shadow:0 0 3px rgba(0,0,0,0.3);">
        <div style="padding:20px;">
          <h5>Upload Slide Show</h5>
          <button style="position:absolute;top:10px;right:10px;" onclick="$(this).parents('.upload-pptx-wrap').hide();" class="btn btn-danger btn-sm"><i class="fa fa-close"></i></button>
        </div>
        <div style="border-bottom:1px solid grey;height:1px;"></div>
        <div style="" style="padding:20px;">
          <input type="file" class="form-control" id="ppsx-input" />
          <div style="padding:20px;text-align:center;">
            <button class="btn btn-primary btn-sm" id="uploadnow-ppsx" rel="">
              <i class="fa fa-save"></i> Upload
            </button>
          </div>
        </div>
         <div style="padding:20px;" id="msg-ppsx">
          </div>
      </div>
    </div>
    <!-- ========================== upload cover ============================================== -->
    <div class="upload-cover-wrap" style="display:none;position:fixed;top:0px;left:0px;z-index:2000;width:100%;height:100%;">
      <div style="width:480px;background-color:white;height:350px;position:absolute;top:0px;right:0px;bottom:0px;left:0px;margin:auto;box-shadow:0 0 3px rgba(0,0,0,0.3);">
        <div style="padding:20px;">
          <h5>Upload Slide Show Cover</h5>
          <button style="position:absolute;top:10px;right:10px;" onclick="$(this).parents('.upload-cover-wrap').hide();" class="btn btn-danger btn-sm"><i class="fa fa-close"></i></button>
        </div>
        <div style="border-bottom:1px solid grey;height:1px;"></div>
        <div style="" style="padding:20px;">
          <input type="file" class="form-control" id="cover-input" />
          <div style="padding:20px;text-align:center;">
            <button class="btn btn-primary btn-sm" id="uploadnow-ppsx-cover" rel="">
              <i class="fa fa-save"></i> Upload
            </button>
          </div>
        </div>
        <div class="img01" style="height:165px;overflow-y:auto;display:none;"></div>
        <div style="padding:20px;" id="msg-ppsx">
        </div>
      </div>
    </div>
    <script>
      window.addEventListener('load', function() {
        var isUploaded = true;
        $('#cover-input').on('change', function() {
          var img = $(this);
          var file = img[0].files[0];
          if (file.type != 'image/gif' && file.type != 'image/jpeg' && file.type && 'image/jpg' && file.type != 'image/png') {
            img.val('');
            return alert('Invalid Image!');
          }
          var data = URL.createObjectURL(file);
          $('.img01').html('<img style="width:100%;height:auto;" src="' + data + '" />');
          $('.img01').show();
          setTimeout(function() {
            URL.revokeObjectURL(data);
          }, 2000);
        });
        $('#upload-ppsx01').on('click', function() {
          $('.upload-cover-wrap').show();
        });
        $('#uploadnow-ppsx-cover').on('click', function() {
          var img = $('#cover-input');
          var file = img[0].files[0];
          if (file.type != 'image/gif' && file.type != 'image/jpeg' && file.type && 'image/jpg' && file.type != 'image/png') {
            img.val('');
            return alert('Invalid Image!');
          }
          var id = $(this).attr('rel');
          var data = new FormData();
          data.append('file', file);
          data.append('id', id);
          if (isUploaded == false) return;
          isUploaded = false;
          var http = new XMLHttpRequest();
          http.addEventListener('readystatechange', function() {
            if (http.readyState == 4) {
              /*
              ** after the upload success
              */
              isUploaded = true;
              //$('#msg-ppsx').html('<div class="alert alert-success">Uploaded</div>');
              alert('cover uploaded successfully');
              loadSide(id);
            }
          });
          http.upload.addEventListener('progress', function(evt) {
            /*
            ** This area is for the progress bar of the uploads
            */
            var total = evt.total;
            var loaded = evt.loaded;
            var per = 0;
            if (loaded > 0)
              per = parseInt((loaded / total) * 100);
            //$('#msg-ppsx').html('<div style="border:1px solid grey;width:100%;height:20px;"><div style="background-color:green;height:100%;width:' + per + '%"></div></div><div style="margin-top:5px;">' + per + ' %</div>');
          });
          //http.setRequestHeader('Content-type', 'formdata/x-www-formdata');
          http.open('POST','<?php print ROOT_PATH; ?>topics/r.php?r=upload-ppsx-cover');
          http.send(data);
        });
      });
    </script>
    <!-- ========================== upload cover ============================================== -->
    <!-- ======================== End Admin ==================================== -->
  <?php endif; //end of the if ($admin) ?>
  <?php if($admin): ?>
    <style>
      #btntopicbtn button{height:45px;width:150px;background-color:#05a777;color:white;border-radius:10px;padding:10px;border:none;box-shadow:0 0 3px rgba(0,0,0,0.5);}
      #btntopicbtn button:hover{background-color:#057c59;}
    </style>
    <div id="btntopicbtn" style="position:fixed;z-index:20;right:10px;bottom:10px;">
      <button id="addTopic"><i class="fa fa-pencil"></i> Add Topic</button> 
      <button class="back101"><i class="fa fa-close"></i> Back</button>
    </div>
  <?php endif; ?>
    <!-- User Topics Design  -->
    <style>
      .cat-item{padding:5px;border-bottom:2px solid #e7e7e7;}
      .cat-content{padding-left:10px;position:relative;display:none;}
      .topic-main-item{padding-left:10px;margin-top:7px;}
      .topic-sub{padding-left:10px;}
      .topic-sub-item{border-left:2px solid #cfcfcf;padding:2px;position:relative;padding-left:10px;}
      .topic-sub-content{background-color:#e1e1e1;padding:5px;height:80px;border-radius:0px 10px 10px 10px;cursor:pointer;}
      .topic-sub-content:hover{background-color:#c7c7c7;}
      .topic-sub-item::before{content:'';position:absolute;top:30px;left:-9px;width:15px;height:15px;border-radius:50%;background-color:#6cc36d;}
      .topic-plus-btn{font-size:7pt;border:none;width:20px;height:20px;border-radius:5px;font-weight:bolder;background-color:#1d8d5c;color:white;}
      .topic-plus-btn:hover{background-color:grey;}
      .cat-plus-btn{font-size:15pt;border:none;width:30px;height:30px;border-radius:5px;font-weight:bolder;background-color:white;color:#565656;}
      /*.cat-plus-btn:hover{background-color:grey;}*/
      .topic-main-content{display:none;}
      .topic-main-label label{font-style:italic;padding-left:28px;}
      .topic-main-label{position:relative;}
      .topic-main-label button{position:absolute;left:2px;top:3px;z-index:2;}
      .cat-label{position:relative;}
      .cat-label label{font-size:15pt;}
      .box-cont{display:inline-block;}
      .cc01{background-color:#006600;border:none;padding:10px;border-right:1px solid white;color:white;border-radius:10px;}
      .cc01:hover{background-color:#00ad00;transform:scale(1.1);}
    </style>
    <script>
      window.topic = {
        category: function() {
        },
        mainTopic: function() {
        },
        subTopic: function() {
        }
      };
    </script>
    <div class="sidebar" style="background-color:white;width:300px;overflow-y:auto;position:absolute;box-shadow:0px 1px 3px rgba(0,0,0,0.3);">
      <div style="width:100%;height:120px;background-color:#f1f1f1;padding:10px;">
        <center>
          <img src="<?php print ROOT_PATH; ?>assets/images/feed-logo.png" style="height:100px;width:auto;"/>
        </center>
      </div>
      <div class="user-topic">
        <?php $len = count($gdata); ?>
        <?php for($i = 0; $i < $len; $i++): ?>
        <?php $ilen = count($gdata[$i][1]); ?>
        <div class="cat-item" rel="<?php print $gdata[$i][0]["id"]; ?>">
          <div class="cat-label"><button class="cat-plus-btn"><i class="fa fa-circle-chevron-right"></i></button> <label><?php print $gdata[$i][0]['title']; ?></label></div>
          <div class="cat-content">
            <?php for($j = 0; $j < $ilen; $j++): ?>
            <div class="topic-main-item">
              <div class="topic-main-label"><button class="topic-plus-btn" topicid='<?php print $gdata[$i][1][$j]['id']; ?>'><i class="fa fa-plus-circle"></i></button> <label><?php print $gdata[$i][1][$j]['title']; ?></label></div>
              <div class="topic-main-content">
                <div class="topic-sub">
                  <!-- <div class="topic-sub-item">
                    <div class="topic-sub-content">Subtopic 1</div>
                  </div> -->
                </div>
              </div>
            </div>
            <?php endfor; ?>
          </div>
        </div>
        <?php endfor; ?>
      </div>
    </div>
    <div style="width:100%;height:100vh;padding-left:300px;">
      <div id="contents" style="padding:10px 20px 20px 20px;width:100%;">
        <div style="font-weight:bolder;"><i class="fa fa-book-reader" style="color:#ff8d00;font-size:15pt;"></i> TOPICS</div>
        <div id="box-content" style="border-radius:10px;box-shadow:0 0 5px rgba(0,0,0,0.3);padding:15px;display:none;position:relative;">
          <div style="color:#818181;" class="topic_title"></div>
          <div id="pageno" style="position:absolute;top:10px;right:30px;"></div>
          <div style="border-radius:5px;position:relative;" class="pptx-file">
            
          </div>
          <div class="lflf">
            <!-- <button class="glr llg"><i class="fa fa-circle-chevron-left"></i></button>
            <button class="glr rrg"><i class="fa fa-circle-chevron-right"></i></button> -->
            <div class="btn-group btn-menu01" style="position:absolute;bottom:20px;left:20px;right:0px;margin:auto;">
              <button href="" id="start-quiz-btn" class="cc01"><i class="fa fa-pencil-square"></i> Start Quiz</button>
              <?php if ($admin): ?>            
              <button href="" id="create-quiz" class="cc01"><i class="fa fa-pencil-alt"></i> Create Quiz</button>
              <button id="upload-ppsx01" href="" class="cc01"><i class="fa fa-upload"></i> Upload Cover</button>
              <button id="upload-ppsx" href="" class="cc01"><i class="fa fa-upload"></i> Upload PPSX</button>
              <?php endif; ?>
              <button href="" id="openfile" class="cc01"><i class="fa fa-file-powerpoint"></i> Open Topic</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- list of quiz -->
    <div style="display:none;width:100%;height:100%;position:fixed;top:0;left:0;z-index:3000;overflow-y:auto;" id="list-q-modal">
        <div style="position:relative;width:480px;height:300px;background-color:white;border-radius:20px;box-shadow:0 0 6px rgba(0,0,0,0.5);margin:auto;margin-top:10%;overflow-y:auto;">
          <button onclick="$(this).parents('#list-q-modal').hide();" class="close" style="border-radius:10px;color:white;background-color:#c12828;border:1px solid #ff5050;position:absolute;top:10px;right:10px;"><i class="fa fa-close"></i></button>
          <div style="padding:20px;padding-top:50px;">
            <div class="list-group" id="q-list-gen">
            </div>
          </div>
        </div>
    </div>
    <!-- end list of quiz -->
    <div id="file-loader" style="display:none;width:100%;height:100%;position:fixed;top:0;left:0;z-index:2000;">
      <div style="font-size:20pt;padding:25px 20px 20px 20px;border-radius:10px;box-shadow:0 0 5px #0000003b;width:300px;height:100px;background-color:white;position:absolute;top:0;left:0;right:0;bottom:0;margin:auto;">
        <i class="fa fa-refresh rotate" style="color:#05a777;"></i> Please wait....
      </div>
    </div>
    <script>
      var g_cat_id = '<?php print isset($_GET["cat_id"]) ? $_GET["cat_id"] : ""; ?>';
      var g_main_id = '<?php print isset($_GET["main_id"]) ? $_GET["main_id"] : ""; ?>';
      var g_sub_id = '<?php print isset($_GET["sub_id"]) ? $_GET["sub_id"] : ""; ?>';
      var SERVER_HOST = '<?php print_r($_SERVER['SERVER_ADDR']); ?>';
      var SERVER_ROOT = '<?php print SERVER_ROOT; ?>';
    </script>
    <script src="<?php print ROOT_PATH; ?>assets/js/topic_menu.js"></script>  <!-- Function of this page -->
    <!-- End User Topics Design -->
    <script src="<?php print ROOT_PATH; ?>assets/js/jquery.min.js"></script>
    <!-- <script type="text/javascript" src="../assets/pptx/jszip.min.js"></script>
    <script type="text/javascript" src="../assets/pptx/filereader.js"></script>
    <script type="text/javascript" src="../assets/pptx/d3.min.js"></script>
    <script type="text/javascript" src="../assets/pptx/nv.d3.min.js"></script>
    <script type="text/javascript" src="../assets/pptx/pptxjs.js"></script>
    <script type="text/javascript" src="../assets/pptx/divs2slides.js"></script>
    <script type="text/javascript" src="../assets/pptx/jquery.fullscreen-min.js"></script> -->
    <?php if ($admin): ?>
    <script src="<?php print ROOT_PATH; ?>assets/js/usertopics.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/topics.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/topic.panel.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/category.panel.js"></script>
    <?php endif; ?>
    <script src="<?php print ROOT_PATH; ?>assets/cbox/cbox.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/notification.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/dialog/dialog.js"></script>
    <script>
       $(".back101").on("click", function() {
          dialog.confirm("Are you sure?", function() {
            window.location.href = "<?php print ROOT_PATH; ?>user/profile.php";
          });
        });
    </script>
  </body>
  </html>