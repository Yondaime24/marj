<?php 
use classes\access;
use classes\auth;
use classes\user;
use classes\TimeSpent;
use classes\Task\User as Task;
require_once '../___autoload.php';
$auth = new auth();
$auth->isLogin();
if (!isset($_GET['cat_id'])) {
  print "Something went wrong!";
  exit;
}
$ac = new access();
$admin = $ac->check();
$ts = new TimeSpent();
$ts->type = 'st';
$initial_spent = $ts->getByDate();

$stmt = sql::con1()->prepare("SELECT * FROM topics_category where id=" .$_GET['cat_id']);
$stmt->execute();
$res = $stmt->fetchAll();
$task = new Task();
$task->taskCat($_GET['cat_id'])->done();
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
    <link rel="stylesheet" type="text/css" href="<?php print ROOT_PATH; ?>assets/quill/quill.snow.css" />
    <script src="<?php print ROOT_PATH; ?>config/config.js"></script>
    <style>
    *{box-sizing:border-box;outline:none!important;}
    body{ animation: fade 2s;-moz-animation:fade 2s;-ms-animation:fade 2s;-o-animation:fade 2s;-webkit-animation:fade 2s;}
    @keyframes fade {from {opacity: 0;}to {opacity: 1;}}
    @-webkit-keyframes fade {from {opacity: 0;}to {opacity: 1;}}
    @-moz-keyframes fade {from {opacity: 0;}to {opacity: 1;}}
    @-ms-keyframes fade {from {opacity: 0;}to {opacity: 1;}}
    /* .menu-container{width:300px;box-shadow:0px 0px 6px rgba(0,0,0,0.2);z-index:20;overflow-y:auto;height:100%;background-color:white;padding:2px;position:fixed;top:0;left:0;} */
    .right-container{position:relative;width:100%;height:100%;background-color:#eee;padding-left:300px;}
    .drop-list::before{position:absolute;width:10px;height:10px;background-color:#3498db;content:"";left:-6px;top:10px;border-radius:50%;}
    .drop-list{padding:5px;color:black;cursor:pointer;}
    .left-line{border-left:1px dotted black;position:relative;}
    .drop-wrap{padding:0 0px 0 10px;}
    .drop{padding-left:5px;display:none;}
    /* .main-button{background-color:#1b9ab3;padding:10px;color:white;cursor:pointer;border-bottom:1px solid #167e93;} */
    .right-plus-menu button{width:100%;border:none;margin-bottom:1px;text-align:left;}
    .ppsx-list{cursor:pointer;}
    .cog{color:#fda346;}
    .cog:hover{color:#ff8200;}
    .sub-menu-btn{width:100%;text-align:left;background-color:#ffbc43;color:white;border:none;border-bottom:1px solid #f5aa22;}
    .sub-menu-btn:hover{background-color:#e7ab3e;}
    .menu-btn-001{width:100%;border:none;background-color:#1bb3a2;border-bottom:1px solid #1ec7b4;color:white;text-align:left;padding:5px;}
    .menu-btn-001:hover{background-color:#1ca797;}
    .main-btn-0001-W{top:0px;right:10px;position:absolute;opacity:0;}
    .main-btn-0001{border:none;float:left;background-color:transparent;}
    .sub-btn-001{float:left;color:black;background-color:white;border:1px solid white;}
    .main-button:hover{background-color:lightgray;}
    .main-active{background-color:lightgray;}
    .drop-list:hover{background-color:#eee;}
    .drop-list:hover .sub-btn-001{background-color:#eee;border:1px solid #eee;}
    .sub-active{background-color:#eee;}
    .sub-active .sub-btn-001{background-color:#eee;border:1px solid #eee;}
    .item:hover .main-btn-0001-W{opacity:1;transition:.5s;}
    .sub_btn{opacity:0;}
    .left-line:hover .sub_btn{opacity:1;transition:.5s;}
      /* BULLETED LIST */
    .item-list {position: relative;padding-left: 10px;width: 100%;border-left: 1px dotted black;}
    .item-list .item {position: relative;line-height: 16px;margin-bottom: 5px;text-align: left;display: block;padding: 0 15px;font-size: 13px;cursor: pointer;}
    .item-list .item-description {align-items: justify;text-transform: none;font-weight: normal;color: rgb(136, 127, 127);font-style: italic;font-size: 10px;}
    .item-list .item:before {content: "";position: absolute;top: 8px;left: -10px;width: 10px;height: 1px; background-color: black;}
    .item-list .item:first-child:after {content: "";position: absolute;top: 0px;left: -12px;width: 5px;height: 8px;background: #fff;}
    .item-list .item:last-child:after {content: "";position: absolute;top: 9px;bottom: 0;left: -12px;width: 5px;background: #fff;}
    .item-list .item .item-label {position: relative;font-weight: bold;text-transform: uppercase;font-size: 13px;color: black;}
    .item-list .item .item-label:before {content: ''; position: absolute;top: 4px;left: -15px;width: 10px;height: 10px;border-radius: 100%;background: rgb(136, 127, 127)}
    </style>
  </head>
  <body>
    <div style="width:300px;box-shadow:0px 0px 6px rgba(0,0,0,0.2);z-index:20;overflow-y:auto;height:100%;background-color:white;padding:2px;position:fixed;top:0;left:0;">
        <div class="item-list menu-container">
        </div>
    </div>
    <div class="right-container">
      <div style="width:100%;height:100%;padding:10px;position:relative;">
        <div style="background-color:white;width:100%;height:100%;position:relative;border-radius:10px;box-shadow:0 0 3px rgba(0,0,0,0.3);">
          <div class="right-content" style="width:100%;height:100%;background-color:#eee;padding-top:50px;border-top-right-radius:10px;border-top-left-radius:10px;position:relative;">
            <?php if ($admin): ?>
            <div style="position:absolute;top:10px;right:105px;">
              <button class="menu-btn-001-m"><i class="fa fa-cog"></i> Menu</button>
            </div>
            <div class="menu-list-001" style="display:none;;position:absolute;top:25px;right:160px;background-color:#1bb3a2;height:200px;width:150px;border-top-left-radius:10px;border-bottom-left-radius:10px;border-bottom-right-radius:10px;padding:2px;z-index:1000;">
              <button class="menu-btn-001 main-topic-btn"><i class="fa fa-plus-circle"></i> Main Topic</button>
              <button class="menu-btn-001 sub-topic-btn"><i class="fa fa-plus-circle"></i> Sub Topic</button>
            </div>
            <?php endif; //end $admin ?>
            <div style="position:absolute;top:10px;left:10px;">
              <i class="fa fa-2x fa-book-reader" style="margin-right:5px;"></i> 
              <a style="font-weight: 700;" href="../index.php">Home </a><span style="margin:0px 5px 0px 5px;">/</span>
              <a style="font-weight: 700;" href="../user/profile.php">Profile </a><span style="margin:0px 5px 0px 5px;">/</span>
              <a style="font-weight: 700;" href="menu.php">Categories </a><span style="margin:0px 5px 0px 5px;">/</span>
              <a style="font-weight: 700;" href="Topics.php?cat_id=<?php echo $_GET['cat_id'] ?>"><?php echo $res[0]['title']; ?> </a><span id="main-span" style="margin:0px 5px 0px 5px;opacity:0;">/</span>
             <span style="font-weight: 700;text-transform:uppercase;" class="main-topic-title" id="main-topic-onchange"></span><span id="sub-span" style="margin:0px 5px 0px 5px;opacity:0;">/</span>
             <span style="font-weight: 700;" class="sub-topic-title"></span>
            </div>
            <div style="position:absolute;top:10px;right:10px;display:none;" class="content-right-menu">
              <?php if ($admin): ?>
               <div style="font-size:20px;cursor:pointer;display:inline-block;color:#00ab4c;" class="plus-sub-topic"><i class="fa fa-plus-circle"></i></div>
              <?php endif; ?>
              <div style="font-size:20px;cursor:pointer;display:inline-block;color:#1d96eb;" class="tv"><i class="fa fa-tv"></i></div>
            </div>
            <div style="z-index:100;width:150px;position:absolute;right:55px;top:30px;display:none;background-color:white;padding:10px;box-shadow:0 0 3px rgba(0,0,0,0.3);border-radius:10px 0px 10px 10px;" class="right-plus-menu">
              <!-- <button class="btn btn-info word_editor"><i class="fa fa-file-word"></i> 
              Word Editor</button> -->
              <button class="btn btn-warning ppsx_file"><i class="fa fa-file-powerpoint"></i> Powerpoint</button>
            </div>
            <div class="right-inner-content" rel="" style="width:100%;height:100%;background-color:white;overflow-y:auto;padding:10px;border-bottom-left-radius:10px;border-bottom-right-radius:10px;">
              <div class="container-fluid">
                <div class="row" id="rgen">

                  <div class="jumbotron" style="margin:0;">
                    <h1><?php echo $res[0]['title']; ?></h1>
                    <span style="font-size:18px;text-align:justify;"><?php echo $res[0]['des']; ?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="word_editor">
      <textarea id="word_input"></textarea>
    </div>
    </div>
    <div id="ppsx_editor">
      <div style="padding:20px;">
        <label>No.</label>
        <input type="text" name="ppsx-id" readonly="" class="form-control" />
        <label>Title</label>
        <input name="ppsx-title" placeholder="Enter title..." type="text" autocomplete="off" class="form-control" name="title" />
        <label>Select file(*.ppsx)</label>
        <input type="file" name="ppsx-file" class="form-control" style="padding: 0;"/>
        <br />
        <div class="gloader" style="height:20px;width:100%;border-radius:10px;background-color:#eee;">
          <div style="height:100%;width:0%;background-color:#1bb38d;border-radius:10px;background:linear-gradient(#56ff37, #011801);">
          </div>
        </div>
        <br />
        <button class="btn btn-info btn-sm" id="submit-ppsx">Submit</button>
      </div>
    </div>
    <div id="ppsx_cover_editor">
      <div style="padding:20px;">
        <label>No.</label>
        <input type="text" readonly="" name="cover_id" class="form-control" />
        <label>Select File (*.png, *.jpg)</label>
        <input type="file" name="cover_file" class="form-control" style="padding: 0;"/>
        <br />
        <div style="width:100%;height:5px;background-color:#eee;">
          <div style="height:100%;background-color:#00a6ff;width:0%;" id="cover_loader">
          </div>
        </div>
        <br />
        <button class="btn btn-info btn-sm cover_submit"><i class="fa fa-image"></i> Submit</button>
      </div>
    </div>
    <!-- main topic -->
    <div class="main-topic-form">
      <div style="padding:10px;">
        <input type="hidden" name="main-topic-id">
        <div>
          <label>Title</label>
          <input name="main-topic-title" type="text" class="form-control" />
        </div>
        <div>
          <label>Description</label>
          <textarea name="main-topic-des" class="form-control"></textarea>
        </div>
        <div style="margin-top:10px;">
          <button class="btn btn-info btn-sm main-topic-submit">
            <i class="fa fa-save"></i> Submit
          </button>
        </div>
      </div>
    </div>
    <div class="sub-topic-form">
      <div style="padding:10px;">
        <input type="hidden" name="sub-topic-id">
        <div>
          <label>Title</label>
          <input name="sub-topic-title" type="text" class="form-control" />
        </div>
        <div>
          <label>Description</label>
          <textarea name="sub-topic-des" class="form-control"></textarea>
        </div>
        <div style="margin-top:10px;">
          <button class="btn btn-info btn-sm sub-topic-submit">
            <i class="fa fa-save"></i> Submit
          </button>
        </div>
      </div>
    </div>
    <div id="time" style="position:fixed;bottom:30px;right:20px;min-width:100;height:20px;background-color:white;padding:5px;color:black;font-weight:bolder;font-size:20pt;">
    </div>
    <!-- end main topic -->
    <!-- sub topic -->
    <!-- end subtopic -->
    <script src="<?php print ROOT_PATH; ?>assets/ckeditor/ckeditor.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/cbox/cbox.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/jquery.min.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/sweetalert/dist/sweetalert2.min.js"></script>
    <script>
      // var initial_spent = <?php print $initial_spent; ?>;
      // var i_res = null;
      // var i_tmp = null;

      // var i_hour = null;
      // var i_min = null;
      // var i_sec = null;
      // setInterval(function() {  
      //   // i_res = parseInt(initial_spent / 86400);
      //   // i_tmp = initial_spent % 86400;
      //   // i_day = i_res;
      //   // i_res = i_tmp;

      //   i_res = parseInt(initial_spent / 3600);
      //   i_tmp = initial_spent % 3600;
      //   i_hour = i_res;
      //   i_res = i_tmp;   
        
      //   i_res = parseInt(initial_spent / 60);
      //   i_tmp = initial_spent % 60;
      //   i_min = i_res;
      //   i_sec = i_tmp;

      //   $("#time").html(i_hour + ":" + i_min + ":" + i_sec);
      //   initial_spent++;
      // }, 1000);
      var is_time_update = false;
      setInterval(function() {
        if (!is_time_update) {
          is_time_update = true;
          $.post(ROOT_PATH + "topics/route.php?r=topic/interval/update").then(function() {
            is_time_update = false
          }, function() {
            alert("Something went wrong!");
            is_time_update = false;
          });
        }
      }, 2000);
      var CAT_ID = '<?php print $_GET['cat_id']; ?>';
      // Main topic form
      var mainTopic = new cbox(".main-topic-form");
      mainTopic.title = "Main Topic";
      mainTopic.logo = "<i class=\"fa fa-plus-circle\"></i>";
      mainTopic.init();
      // end main topic form
      // sub topic
      var subTopic = new cbox(".sub-topic-form");
      subTopic.title = "Subtopic";
      subTopic.logo = '<i class="fa fa-plus-circle"></i>';
      subTopic.init();
      //

      // end sub topic 
      var admin = '<?php print $admin; ?>';
      var word_editor = $("#word_editor");
      var ppsx_editor = new cbox("#ppsx_editor");
      ppsx_editor.title = "PowerPoint Slide Show(PPSX)";
      ppsx_editor.logo = '<i class="fa fa-file-powerpoint"></i>';
      ppsx_editor.index = 1;
      ppsx_editor.init();
      // end ppsx_editor
      // word
      CKEDITOR.replace('word_input');
      CKEDITOR.editorConfig = function( config ) {
      config.toolbarGroups = [
        '/',
        { name: 'clipboard', groups: [ 'undo', 'clipboard' ] },
        { name: 'tools', groups: [ 'tools' ] },
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
        { name: 'forms', groups: [ 'forms' ] },
        { name: 'insert', groups: [ 'insert', 'Youtube', 'CodeSnippet' ] },
        { name: 'links', groups: [ 'links' ] },
        { name: 'styles', groups: [ 'styles' ] },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph', groups: [ 'align', 'list', 'indent', 'blocks', 'bidi', 'paragraph' ] },
        { name: 'colors', groups: [ 'colors' ] },
        { name: 'others', groups: [ 'others' ] },
        { name: 'about', groups: [ 'about' ] }
      ];

      config.removeButtons = 'Scayt';
    };
      var word = new cbox("#word_editor");
      word.width = 720;
      word.title = "Editor";
      word.logo = '<i class="fa fa-file-word"></i>';
      word.init();
      // end word
      var ppsx_cover = new cbox("#ppsx_cover_editor");
      ppsx_cover.title = "PowerPoint Cover Photo";
      ppsx_cover.index = 1;
      ppsx_cover.init();
      function fullscreen() {
        var f = $(".right-content")[0];
        try {
          f.requestFullScreen();
        } catch(er) {
          try {
            f.mozRequestFullScreen();
          } catch(er1) {
            try {
              f.webkitRequestFullScreen();
            } catch(er2) {
              f.msRequestFullScreen();
            }
          }
        }
      }
      $(".tv").on("click", function() { 
        fullscreen();
      });
      // $(window).on("keyup", function(evt) {
      //   if (evt.keyCode == 70)
      //     fullscreen();
      // });
      var gen = $(".menu-container");
      var right_content = $(".right-inner-content");
      var url = {
        catid: "",
        topic_id: "",
        sub_topic_id: "",
        topic_type: "",
        main_topic: function() {
          return $.ajax({
            url: ROOT_PATH + "topics/route.php?r=Main/topic/list",
            type: "post",
            data:{catid: this.catid}
          });
        },
        sub_topic: function() {
          return $.ajax({
            url: ROOT_PATH  + "topics/route.php?r=Sub/topic/list",
            type: "post",
            data: {topic_id: this.topic_id}
          });
        },
        sub_topic_data: function() {
          return $.ajax({
            url: ROOT_PATH + "topics/route.php?r=get/subtopic/data",
            type: "post",
            data: {objid: this.sub_topic_id, topic_type: this.topic_type}
          });
        }
      };
      var GLOBAl_MAIN_DATA = [];
      function loadMainTopic(catid) {
        url.catid = catid;
        url.main_topic().then(function(resp) {
          var data = JSON.parse(resp);
          var data_len = data.length;
          var i = 0;
          var d = '';
          GLOBAL_MAIN_DATA = data;
          var admin_data = '';
          if(data_len == 0){
            d += '\
            <div style="display:flex;justify-content:center;align-items:center;margin-top:300px;color:red;font-size:15px;font-weight:bold;">\
              NO DATA AVAILABLE!\
            </div>\
            ';
          }
          if (admin)
            admin_data = '<button class="main-btn-0001 main-edit" title="Edit">\
                    <i style="color:#0d6efd;" class="fas fa-pen"></i>\
                  </button>\
                  <button class="main-btn-0001 main-trash" title="Delete">\
                    <i style="color:#dc3545;" class="fas fa-trash"></i>\
                  </button>\
                  ';
          while (i < data_len) {
            d += '\
            <div class="item menu">\
                <div class="item-label main-button" index="' + i + '" rel="' + data[i].id + '" title="' + data[i].title + '" style="position:relative;">\
                  <span><i style="margin-right:5px;color:orange;" class="fas fa-folder-open"></i>' + data[i].title + '</span>\
                  <br>\
                  <small class="item-description">&nbsp;' + data[i].des + '</small>\
                <div class="main-btn-0001-W">\
                  ' + admin_data + '\
                </div>\
              </div>\
                  <div class="drop">\
                \
              </div>\
            </div>\
            ';
            i++;
          }
          gen.html(d);
        }, function() {
          alert("Something went wrong!");
        });
      }
      var GLOBAL_SUB_DATA = [];
      var SUB_INDEX = null;
      function loadSubTopic(topic_id, drop, load = false) {
        url.topic_id = topic_id;
        if (load)
          drop.html("Loading...");
        var admin_data = "";
        if (admin)
          admin_data = '<div class="sub_btn" style="position:absolute;top:5px;right:5px;">\
                          <button title="Edit" class="sub-btn-001 sub_edit"><i style="color:#0d6efd;" class="fa fa-edit"></i></button>\
                          <button title="Delete" class="sub-btn-001 sub_trash"><i style="color:#dc3545;" class="fa fa-trash"></i></button>\
                        </div>\
                    ';
        url.sub_topic().then(function(resp) {
          var data = JSON.parse(resp);
          var data_len = data.length;
          GLOBAL_SUB_DATA = data;
          var i = 0; 
          var d = '';
          if(data_len == 0){
            d += '\
            <div style="display:flex;justify-content:center;align-items:center;color:red;font-size:10px;font-weight:bold;">\
              NO DATA AVAILABLE!\
            </div>\
            ';
          }
          while (i < data_len) {
            d += '\
              <div class="left-line">\
                <div class="drop-wrap">\
                  <div class="drop-list" index="' + i  + '" rel="' + data[i].id + '" title="' + data[i].title + '">\
                    <i style="color:#3498db;" class="fas fa-file-lines"></i> <span class="sub-title">' + data[i].title + '</span>\
                  ' + admin_data + '\
                  </div>\
                </div>\
              </div>\
            ';
            i++;
          }
          drop.html(d);
          drop.slideToggle(150);
        });
      }
      function readData(sub_topic_id) {
        var rgen = $('#rgen');
        url.sub_topic_id = sub_topic_id;
        url.topic_type = "sub";
        url.sub_topic_data().then(function(resp) {
          var data = JSON.parse(resp);
          var data_len = data.length;
          var d = '';
          var h = '';
          if(data_len == 0){
            d += '\
            <div style="display:flex;justify-content:center;align-items:center;margin-top:200px;color:red;font-size:15px;font-weight:bold;">\
              No Available Contents Yet!\
            </div>\
            ';
          }
          for (var i = 0; i < data_len; i++) {
          /*  
          <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div style="width:100%;height:200px;background-color:green;border-radius:10px;position:relative;">
            </div>
          </div> */
            if (data[i].type == 'ppsx') {
              var data_admin  = '';
              if (admin) {
                data_admin = '<div style="position:absolute;top:5px;left:5px;font-size:15pt;cursor:pointer;" class="cog">\
                    <i class="fa fa-cog"></i>\
                  </div>\
                  <div class="sub-menu-wrap cover-sub-ppsx" style="display:none;width:150px;min-height:50px;background-color:white;box-shadow:0 0 3px rgba(0,0,0,0.3);position:absolute;top:20px;left:17px;padding:5px;border-top-right-radius:5px;border-bottom-right-radius:5px;border-bottom-left-radius:5px;">\
                    <button class="sub-menu-btn" style="border-top-right-radius:5px;border-top-left-radius:5px;">\
                      <i class="fa fa-image"></i> Cover\
                    </button>\
                    <button class="sub-menu-btn create-quiz-btn">\
                      <i class="fa fa-pen"></i> Create Quiz\
                    </button>\
                    <button class="sub-menu-btn edit-sub-ppsx">\
                      <i class="fa fa-edit"></i> Edit\
                    </button>\
                    <button class="sub-menu-btn remove-sub-ppsx" style="border-bottom-left-radius:5px;border-bottom-right-radius:5px;">\
                      <i class="fa fa-trash"></i> Remove\
                    </button>\
                  </div>\
                  ';
              }
              d += '\
              <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12" style="margin-bottom:5px;">\
                <div class="ppsx-list" rel="' + data[i].id + '" style="width:100%;height:200px;background-color:#ffffff;border-radius:10px;position:relative;box-shadow:0 0 3px rgba(0,0,0,0.3);background-image:url(\'' + ROOT_PATH + 'file/open.php?route=sub-topic-cover&id=' + data[i].id  + '\');background-repeat:no-repeat;background-size:cover;">\
                  <div style="display:none;position:absolute;z-index:10;background-color:rgba(0,0,0,0.5);width:100%;height:100%;" class="ppsx-loader01">\
                    <img src="' + ROOT_PATH + 'assets/images/loader.gif" style="width:50px;height:50px;position:absolute;top:0;left:0;right:0;bottom:0;margin:auto;">\
                  </div>\
                  <div class="ppsx-title01" style="left:-2px;border-top-right-radius:10px;border-bottom-right-radius:10px;font-size:12pt;bottom:20px;position:absolute;width:80%;height:35px;background-color:rgb(0 0 0 / 70%);color:white;padding:6px;">\
                  ' + data[i].title + '\
                  </div>\
                  ' + data_admin + '\
                </div>\
              </div>';
            } else if (data[i].type == 'html') {
              h += '';
            }
          }
          rgen.html(d + h);
        });
        $(".content-right-menu").show();
      }
      loadMainTopic('<?php print $_GET["cat_id"]; ?>');
      MAIN_INDEX = null;
      gen.on("click", ".main-button", function() {
        var elem = $(this);
        var id = $(this).attr("rel");
        var title = $(this).attr("title");
        $('.main-topic-title').text(title)
        $('#main-span').css("opacity", "1")
        $('.sub-topic-title').text("")
        $('#sub-span').css("opacity", "0")
        //$(".right-sider").show();
        MAIN_INDEX = elem.attr("index");
        loadSubTopic(id, elem.parents(".menu").find('.drop'), false);
        $(".main-button").removeClass("main-active");
        $(this).addClass("main-active");
      });
      gen.on("click", '.drop-list', function() {
         var sub_id = $(this).attr("rel");
        var sub_title = $(this).find(".sub-title").html();
        var title = $(this).attr("title");
        var parent = $(this).parents(".menu").find(".main-button").attr("title");
        $('.sub-topic-title').text(title);
        $('#sub-span').css("opacity", "1");
        $('.main-topic-title').text(parent)
        right_content.attr("rel", sub_id);
        readData(sub_id);
        $(".drop-list").removeClass("sub-active");
        $(this).addClass("sub-active");
      });
      gen.on("click", ".sub_edit", function(evt) {
        var index = $(this).parents(".drop-list").attr("index");
        var data = GLOBAL_SUB_DATA[index];
        sub_topic_clear();
        $("input[name=sub-topic-id]").val(data.id);
        $("input[name=sub-topic-title]").val(data.title);
        $("textarea[name=sub-topic-des]").val(data.des);
        subTopic.show();
        evt.stopPropagation();
      });
      gen.on("click", ".sub_trash", function(evt) {
        var index = $(this).parents(".drop-list").attr("index");
        var data = GLOBAL_SUB_DATA[index];
        var id = data.id;
        var drop = $(".left-line").eq(index);
        sub_topic_clear();
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
                  $.post(ROOT_PATH + "topics/route.php?r=sub/topic/trash", {id: id}).then(function(resp) {
                    var data = JSON.parse(resp);
                    if (data.ok) {
                      Swal.fire({
                            heightAuto: false,
                            icon: 'success',
                            title: 'Success!',
                            text: 'Subtopic Successfull Deleted!'
                      });
                      loadMainTopic();
                      drop.remove();
                    }
                  }, function() {
                    alert("Something went wrong!");
                  });

                }
              });
        evt.stopPropagation();
      });
      // right menu
      $(".plus-sub-topic").on("click", function() {
        $(".right-plus-menu").toggle();
        $(".menu-list-001").hide();
      });
      $(".ppsx_file").on("click", function() {
        $("input[name=ppsx-title]").val("");
        $("input[name=ppsx-file]").val("");
        $("input[name=ppsx-id]").val("");
        ppsx_editor.show();
      });
      $(".word_editor").on("click", function() {
        word.show();
      });
      // end right menu
      // upload power point
      $("#submit-ppsx").on("click", function() {
        var id = $("input[name=ppsx-id]").val();
        var title = $("input[name=ppsx-title]").val();
        var file = $("input[name=ppsx-file]")[0];
        var objid = $(".right-inner-content").attr("rel");
        var formData = new FormData();
        formData.append("title", title);
        if (file.files.length <= 0 ) {
        Swal.fire({
              heightAuto: false,
              icon: 'error',
              title: 'Oopx!',
              text: 'Please select a file'
          });
        }
        if (file.files[0].type != 'application/vnd.openxmlformats-officedocument.presentationml.slideshow') {
          Swal.fire({
              heightAuto: false,
              icon: 'error',
              title: 'Oopx!',
              text: 'Select PowerPoint Slide show only(*.ppsx)'
          });
        }else{
        $("input[name=ppsx-title]").attr("disabled", "");
        $("input[name=ppsx-file]").attr("disabled", "");
        $("#submit-ppsx").attr("disabled", "");
        formData.append("ppsx", file.files[0]);
        formData.append("type", "ppsx"); // ppsx of html
        formData.append("topic_type", "sub");
        formData.append("objid", objid);
        formData.append("id", id);
        var up = new XMLHttpRequest();
        up.onload = function() {
          var resp = up.responseText;
          var resp2 = JSON.parse(up.responseText);

          if (resp2.ok == 0) {
            Swal.fire({
              heightAuto: false,
              icon: 'error',
              title: 'Oopx!',
              text: 'Subtopic Already Existed'
            });
          } else {
            Swal.fire({
              heightAuto: false,
              icon: 'success',
              title: 'Success!',
            });
          }
          
          $("input[name=ppsx-title]").removeAttr("disabled");
          $("input[name=ppsx-file]").removeAttr("disabled");
          $("#submit-ppsx").removeAttr("disabled");
          $("input[name=ppsx-title]").val("");
          $("input[name=ppsx-file]").val("");
          $("input[name=ppsx-id]").val("");
          readData(objid);
        }
        up.upload.onprogress = function(evt) {
          var percent = parseInt((evt.loaded / evt.total) * 100);
          $(".gloader").find("div").css("width", percent + "%");
        }
        up.open("POST", ROOT_PATH + "topics/route.php?r=subtopic/upload/ppsx"); 
        up.send(formData);
       }
      });
      $("#rgen").on("click", ".ppsx-list", function() {
        var elem = $(this);
        elem.find(".ppsx-loader01").show();
        var data_id = $(this).attr("rel");
        var data = new FormData();
        data.append("id", data_id);
        var http =  new XMLHttpRequest();
        http.onload = function() {
          var res = JSON.parse(http.responseText);
          if (res.ok == '1') {
            $.ajax({
              url: 'http://localhost:8558/feed/' + SERVER_IP  + ROOT_PATH,
              type: "get"
            }).then(function(resp) {
              elem.find(".ppsx-loader01").hide();
            }, function() {
              alert("Please Install the FEED Extension!");
              elem.find(".ppsx-loader01").show().hide();
                Swal.fire({
                  heightAuto: false,
                  title: 'Install Now?',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, install!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    window.location.href = ROOT_PATH + "Download/FEED_extension.exe";
                    }
                });
            });
          } else {
            alert("No powerpoint added!");
            elem.find(".ppsx-loader01").hide();
          }
        }
        http.open("POST", ROOT_PATH + "api.php?route=ppsx/open");
        http.send(data);
      });
      // end upload power pint ppsx
      $("#rgen").on("click", '.cog', function(evt) {
        var sub_topic_id = right_content.attr("rel");
        $(this).parents(".ppsx-list").find(".sub-menu-wrap").toggle();;
        evt.stopPropagation();
      });
      //
      $("#rgen").on("click", ".edit-sub-ppsx", function(evt) {
        var id = $(this).parents(".ppsx-list").attr("rel");
        var title = $(this).parents(".ppsx-list").find(".ppsx-title01").html().trim();
        $("input[name=ppsx-id]").val(id);
        $("input[name=ppsx-title]").val(title);
        ppsx_editor.show();
        evt.stopPropagation();
      });
      //
      $("#rgen").on("click", ".cover-sub-ppsx", function(evt) {
        var id = $(this).parents(".ppsx-list").attr("rel");
        $("input[name=cover_id]").val(id);
        $("input[name=cover_file]").val("");
        ppsx_cover.show();
        evt.stopPropagation();
      });
      
      $(".cover_submit").on("click", function() {
        var id = $("input[name=cover_id]");
        var file = $("input[name=cover_file]");
        var filedata = file[0];
        var data = new FormData();
        data.append("id", id.val());
        if (id == "") return alert("Something went wrong!");
        if (filedata.files.length > 0) {
          var type = filedata.files[0].type;
          if (!(type == 'image/png' || type == "image/jpg" || type == "image/jpeg")) return alert("File is not Image!");
          data.append("file", filedata.files[0]); 
          var http = new XMLHttpRequest();
          http.onload = function() {
            var resp = http.responseText;
            readData(right_content.attr("rel"));
          }
          http.upload.onprogress = function(evt) {
            $("#cover_loader").css("width", 
              (parseInt((evt.loaded / evt.total) * 100))  + "%"
            );
          }
          http.open("POST", ROOT_PATH + "topics/route.php?r=sub/cover/pic/upload");
          http.send(data);
        } else {
            Swal.fire({
                  heightAuto: false,
                  icon: 'error',
                  title: 'Oopx!',
                  text: 'Please select a file'
              });
        }
      });

      $("#rgen").on("click", '.ppsx-loader01', function(evt) {
        evt.stopPropagation();
      });
      $("#rgen").on("click", ".remove-sub-ppsx", function(evt) {
        var id = right_content.attr("rel");
        var ppsx_id = $(this).parents(".ppsx-list").attr("rel");
            Swal.fire({
                heightAuto: false,
                title: 'Delete this topic data?',
                text: 'You wont be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                    url: ROOT_PATH + "topics/route.php?r=topic/data/delete",
                    type: "post",
                    data: {id: ppsx_id}
                  }).then(function() {
                    readData(id);
                  }, function() {
                    alert("Something went wrong!");
                  });
                }
              });
        evt.stopPropagation();
      });
      $("#rgen").on("click", ".create-quiz-btn", function(evt) {
        var id = right_content.attr("rel");
              Swal.fire({
                heightAuto: false,
                title: 'Create Quiz',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
              if (result.isConfirmed) {
                location.href = ROOT_PATH + 'Quiz/CreateQuiz.php?obj=' + id  + '&type=SUBTOPIC&group_id=';
                }
              });
              evt.stopPropagation();
      });
      $(".menu-btn-001-m").on("click", function() {
        $(".menu-list-001").toggle();
        $(".right-plus-menu").hide();
      });
      $(".main-topic-btn").on("click", function() {
        main_topic_clear();
        mainTopic.show();
      });
      $(".sub-topic-btn").on("click", function() {
        if (MAIN_INDEX == null) {
          Swal.fire({
                  heightAuto: false,
                  icon: 'error',
                  title: 'Oopx!',
                  text: 'Please click/select a main topic first!'
              });
          return;
        }
        sub_topic_clear();
        subTopic.show();
      });
      /* submit the form */
      function main_topic_clear() {
        $("input[name=main-topic-id]").val("");
        $("input[name=main-topic-title]").val("");
        $("textarea[name=main-topic-des]").val("");
      }
      $(".main-topic-submit").on("click", function() {
        var id = $("input[name=main-topic-id]").val();
        var title = $("input[name=main-topic-title]").val();
        var des = $("textarea[name=main-topic-des]").val();
        var data = new FormData();
        data.append("id", id);
        data.append("des", des);
        data.append("title", title);
        data.append("cat_id", CAT_ID);
        main_topic_clear();
        mainTopic.close();
        var http = new XMLHttpRequest();
        http.onload = function() {
          var resp = JSON.parse(http.responseText);
          if (resp.ok) {
            Swal.fire({
                  heightAuto: false,
                  icon: 'success',
                  title: 'Success!'
              });
            loadMainTopic(CAT_ID);
          } else {
            alert(resp.msg);
          }
        }
        http.open("POST", ROOT_PATH + "topics/route.php?r=main/topic/save");
        http.send(data);
      });
      function sub_topic_clear() {
        $("input[name=sub-topic-id]").val("");
        $("input[name=sub-topic-title]").val("");
        $("textarea[name=sub-topic-des]").val("");
      }
       $(".sub-topic-submit").on("click", function() {
        var id = $("input[name=sub-topic-id]").val();
        var title = $("input[name=sub-topic-title]").val();
        var des = $("textarea[name=sub-topic-des]").val();
        var data = new FormData();
        data.append("id", id);
        data.append("des", des);
        data.append("title", title);
        data.append("topic_id", GLOBAL_MAIN_DATA[MAIN_INDEX].id);
        sub_topic_clear();
        subTopic.close();
        var http = new XMLHttpRequest();
        http.onload = function() {
          var resp = JSON.parse(http.responseText);
          if (resp.ok) {
            Swal.fire({
                  heightAuto: false,
                  icon: 'success',
                  title: 'Success!'
              });
            var drop = $(".drop").eq(MAIN_INDEX);
            loadSubTopic(GLOBAL_MAIN_DATA[MAIN_INDEX].id, drop);
          } else {
            alert(resp.msg);
          }
        }
        http.open("POST", ROOT_PATH + "topics/route.php?r=sub/topic/save");
        http.send(data);
      });
      /* End submit */
      gen.on("click", ".main-edit", function(evt) {
        var index = $(this).parents(".main-button").attr("index");
        var data = GLOBAL_MAIN_DATA[index];
        $("input[name=main-topic-id]").val(data.id);
        $("input[name=main-topic-title]").val(data.title);
        $("textarea[name=main-topic-des]").val(data.des);
        mainTopic.show();
        evt.stopPropagation();
      });
      gen.on("click", ".main-trash", function(evt) {
        var index = $(this).parents(".main-button").attr("index");
        var data = GLOBAL_MAIN_DATA[index];
          Swal.fire({
                heightAuto: false,
                title: 'Are you sure?',
                text: 'This will also delete all the subtopics!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.isConfirmed) {
                $.post(ROOT_PATH + "topics/route.php?r=main/topic/trash", {id: data.id}).then(function(resp) {
                    var data = JSON.parse(resp);
                    if (data.ok) {
                      Swal.fire({
                            heightAuto: false,
                            icon: 'success',
                            title: 'Success!',
                            text: 'Topic has been removed successfully!'
                        });
                      loadMainTopic(CAT_ID);
                    }
                  }, function() {
                    alert("Something went wrong!");
                  });
                }
              });
        evt.stopPropagation();
      });

    </script>
  </body>
</html>