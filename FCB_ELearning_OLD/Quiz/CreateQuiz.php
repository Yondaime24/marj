<?php 
  use classes\auth;
  use classes\Question\Group;
  use classes\Q;
  use classes\Date as Date2;
  use classes\access;
  use classes\user;
  use classes\Question\Type;

  require_once '../___autoload.php';
  $auth = new auth();
  $auth::isLogin();
  if (!isset($_GET['obj']) || !isset($_GET['type']) || !isset($_GET['group_id'])) { 
    print 'Something Went Wrong!';
    exit;
  }
  $typeObj = new Type();
  $obj = $_GET['obj'];
  $type = $_GET['type'];
  $group_id = (int)$_GET['group_id'];
  $groupData = [];
  $group = new Group();
  $info = [];
  $q_type = $typeObj->get();
  $q = new Q();
  $q->objId = $obj;
  $q->type = $_GET['type'];
  $gdata = $q->get();
  $glen = count($gdata);
  $ulist = [];
  for($d = 0; $d < $glen; $d++) {
    $ulist[] = '<div class="ll">'.$gdata[$d].'</div>';
    $ulist[] = '<div class="ll circle"></div>';
  }
  if (count($ulist) > 0) {
    unset($ulist[count($ulist) - 1]);
  }
  $ulistLen = count($ulist);
  if (empty($group_id)) {
    // display all groups
    $group->Status = '1';
    $group->QCCode = $type;
    $group->ObjId = $obj;
    $groupData = $group->GetAll();
  } else {
    // Load the content of the id
    $group->Status = '1';
    $group->QGId = $group_id;
    $info = $group->get();
    if (count($info) == 0) {
      print 'Page not found!';
      exit;
    }
  }
  $user = new user();
  $ac = new access(['PR']);
  $admin = $ac->check($user->ulevel);
  if (!$admin) {
    print 'Unauthorized access!';
    exit;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FEED - Quiz Creator</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="../assets/css/topics.css" />
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/pptxjs.css" />
    <link rel="stylesheet" href="../assets/css/nv.d3.min.css" />
    <link rel="stylesheet" href="../assets/css/admin.topic.css" />
    <link rel="stylesheet" href="../assets/css/admin.category.css" />
    <link rel="stylesheet" href="../assets/css/loader.css" />        
    <style>
      *{box-sizing:border-box;outline:none;}
      .ctrl{display:inline;}
      body{position:relative;height:100%;width:100%;}
      .tf-select div{background-color:#01ad0b;width:100px;height:35px;border-radius:5px;display:inline;padding:5px 10px 5px 10px;border-right:1px solid white;color:white;cursor:pointer;}
       .tf-select .active{background-color:#05c3e9;}
       .lside .list-group .list-group-item {cursor:pointer;}
       /*.lside .list-group .list-group-item:hover{background-color:#e2ffe7;}*/
       .gactive{background-color:#00cd61;color:white;}
      .listtype button{width:100%;background-color:#009100;color:white;border:none;border-bottom:2px solid #b3b3b3;}
      .listtype button:hover{background-color:#0e7c0e;}
      .listtype{display:none;position:absolute;}
      .choicelist-0101:hover{background-color:#c0ffc9;}
      .menu-choice .list-group-item{cursor:pointer;}
      .menu-choice .list-group-item:hover{background-color:#d9d9d9;}
      .top-loader{width:90%;height:5px;z-index:1000;position:fixed;top:0px;left:0px;}
      .top-loader div{width:100%;height:100%;background-color:#d73801;animation:tow ease-in 10s}
      @keyframes tow{
        0%{width:0%} 5%{width:30%} 50%{width:60%} 100%{width:width:90%;}
      }
      .ll{display:inline-block;font-style:italic;}
      .circle{width:8px;height:8px;background-color:#bbbbbb;border-radius:50%;}
    </style>
  </head>
<body>
<div class="top-loader"><div></div></div>
<div style="background-color:white;z-index:15;position:fixed;top:0px;left:0px;text-align:center;color:#09c778;font-weight:bolder;width:100%;padding:10px;background-color:white;border:5px solid #00c341;"><i class="fa fa-pencil"></i> <span style="color:#ffbc00;font-weight:bolder;">FEED</span> Question Editor
  <div style="color:#bbbbbb;text-align:left;">
    <?php for ($c = 0; $c < $ulistLen; $c++): ?>
      <?php print $ulist[$c]; ?>
    <?php endfor; ?>    
  </div>
</div>
<div style="padding:0px 0px 0px 20px;margin-top:100px;">
</div>
<?php if (!empty($group_id)): ?>
  <!-- Question logic  -->
  <div style="padding:20px;">
    <h3>Quiz for <span id="group_txt"></span></h3>
    <?php if (isset($_GET['remove'])): ?>
    <div style="width:200px;height:75px;padding:20px;background-color:white;" class="confirm">
       <span style="font-weight:bold;"> Remove ?</span>
        <div class="btn-group">
          <button class="btn btn-danger btn-sm cyes">Yes</button>
          <button class="btn btn-primary btn-sm cno">No</button>
        </div>
        <script>
          window.addEventListener('load', function() {
           let link = 'CreateQuiz.php?obj=<?php print $obj; ?>&type=<?php print $_GET["type"]; ?>&group_id=';
           $('.cno').on('click', function() {
               window.location.href = link;
            });
           $('.cyes').on('click', function() {
              $.ajax({url: '../Quiz/r.php?route=grem', type: 'post', data: {id: '<?php print $group_id; ?>'}}).then(function() {
                window.location.href = link;
              }, function() {
                console.log('Problem Connecting to the server!');
              });
              //window.location.href = link;
            });
          });
        </script>
    </div>
    <?php endif; ?>
  </div>
  <div style="padding:10px;">
    <div id="gen" style="background-color:white;padding:10px;margin-top:10px;">   
    </div>
  </div>
  <div style="padding:10px;">
    <div class="btn-group">
      <button id="createqbtn" class="btn btn-info btn-sm" rel="off"><i class="fa fa-plus"></i> Create Question</button>
      <button class="btn btn-primary btn-sm qprepare"><i class="fa fa-pencil"></i> Prepare Question</button>
    </div>
    <div style="background-color:white;padding:5px 5px 0px 5px;width:150px;z-index:20;position:absolute;bottom:45px;left:0px;" class="listtype">
        <?php foreach ($q_type as $key => $value): ?>
        <button rel="<?php print $key; ?>" class="typebtn">
          <?php print $value; ?>  
        </button>
        <?php endforeach; ?>   
    </div>
  </div>
  <div style="width:100%;height:100%;position:fixed;top:0;left:0;z-index:100;display:none;" id="prepareq">
    <div style="width:480px;height:330px;margin:auto;margin-top:50px;position:relative;padding:10px;background-color:white;border-radius:10px;box-shadow:0 0 6px rgba(0,0,0,0.3);">
      <button onclick="$(this).parents('#prepareq').slideUp();" class="btn btn-danger btn-sn" style="position:absolute;top:10px;right:10px;"><i class="fa fa-close"></i></button>
      <div style="padding-top:40px;">
        <h5>Prepare a Quiz</h5>
        <form method="post" id="prepareform">
            <input type="hidden" value="" name="id" />
            <div class="form-group">
              <label>Title</label>
              <input name="title" type="text" class="form-control" />
            </div>
            <div class="form-group">
              <label>Number of Items</label>
              <input name="itemno" type="number" class="form-control"/>
            </div>
            <div class="form-group">
              <label>Time Limit</label>
              <input required="" name="timelimit" type="number" placeholder="Enter total number of seconds" class="form-control" />
            </div>
            <div class="form-group" style="margin-top:10px;">
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-save"></i> Submit</button>
            </div>
        <form>
      </div>
    </div>
  </div>
  <script>
    function PREPARE() {
      this.id = '';
      this.group_id = '';
      this.title = '';
      this.itemno = '';
      this.timelimit = '';
      this.submit = function() {
        return $.ajax({url: '../Quiz/r.php?route=prepare', type: 'post', data: {
          id: this.id,
          group_id: this.group_id,
          title: this.title,
          itemno: this.itemno,
          timelimit: this.timelimit
        }});
      }
    }
    window.addEventListener('load', function() {
      $('#prepareform').on('submit', function() {
        var form = $(this);
        var prepare = new PREPARE();
        prepare.id = form.find('input[name=id]').val();
        prepare.group_id = quiz.group_id;
        prepare.title = form.find('input[name=title]').val();
        prepare.itemno = form.find('input[name=itemno]').val();
        prepare.timelimit = form.find('input[name=timelimit]').val();
        prepare.submit().then(function(resp) {
          var o = JSON.parse(resp);
          if (o.ok == 0) {
            // error
            alert(o.msg);
          }
          if (o.ok == 1) {
            alert(o.msg);
            form.find('input[name=id]').val('');
            form.find('input[name=title]').val('');
            form.find('input[name=itemno]').val('');
            form.find('input[name=timelimit]').val('');
          }
        }, function() {
          alert('Something went wrong!/ Server is offline!');
        });
        return false;
      });
    });
  </script>
<?php else: ?>
  <!-- list of the Groups -->
  <div style="padding:20px;">
    <button class="btn btn-primary btn-sm create-new-quiz-btn" style="margin-bottom:10px;">
      <i class="fa fa-pencil"></i> Create New Quiz
    </button>
    <h5>List of Quiz</h5>
  </div>
   <div style="margin-top:10px;">
    <ul style="list-style-type:circle;">
    <?php foreach ($groupData as $row): ?>
      <li>
        <?php print $row['Title']." - "; print Date2::Ago($row['DateCreated']).' ago'; ?>
        <a href="CreateQuiz.php?obj=<?php print $obj; ?>&type=<?php print $row['QCCode']; ?>&group_id=<?php print $row['QGId']; ?>" style="margin-right:10px;">View</a>
        <a href="CreateQuiz.php?obj=<?php print $obj; ?>&type=<?php print $row['QCCode']; ?>&group_id=<?php print $row['QGId']; ?>&remove">Remove</a>
      </li>
    <?php endforeach; ?>
    </ul>
  </div>
  <script>
    window.addEventListener('load', function() {
      $('.create-new-quiz-btn').on('click', function() {
        if (!confirm('Are you sure?')) return;
        // creating new quiz
        $.ajax({url: '../Quiz/r.php?route=addNewGroup', type: 'post', data:{group_id:quiz.group_id,
          type: quiz.type,
          obj: quiz.obj 
        }}).then(function(resp) {
          window.location.reload();
        });
      });
    });
  </script>  
<?php endif; ?>
<!-- End Popup -->
<script>
  var quiz = {
    obj: '<?php print $obj; ?>',
    group_id: '<?php print $group_id; ?>',
    type: '<?php print $_GET['type']; ?>'
  };
</script>
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/Quiz.js"></script>
<script>
  window.addEventListener('load', function() {
    //document.querySelector('.top-loader').style.display = 'none';
  });
  $('.qprepare').on('click', function() {
    $('#prepareq').slideDown();
  });
</script>
</body>
</html>