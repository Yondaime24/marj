<?php 
  use classes\QuizFull;
  use classes\Html;
  use classes\Date as Date2;
  use classes\UserQuiz;
  use classes\Task\User as Task;

  require_once "../___autoload.php";
  $quiz = new QuizFull();
  $quiz_data = $quiz->getQuiz();
  $quiz_data_len = count($quiz_data);
  $userQuiz = new UserQuiz();
  $task = new Task();
  $task->objtype = 'sub';
  $task->type = 'topic';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="../assets/css/topics.css" />
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/pptxjs.css" />
    <link rel="stylesheet" href="../assets/css/nv.d3.min.css" />
    <link rel="stylesheet" href="../assets/css/admin.topic.css" />
    <link rel="stylesheet" href="../assets/css/admin.category.css" />
    <link rel="stylesheet" href="../assets/css/loader.css" />
    <script src="<?php print ROOT_PATH; ?>config/config.js"></script>
    <script src="../assets/js/UserQuiz.js"></script>        
    <style>
      *{box-sizing:border-box;}
      .main{width:100%;height:100%;position:fixed;top:0px;left:0px;overflow-y:auto;}
    </style>
  </head>
  <body>
    <div class="main">
      <div style="padding:20px;">
        <h1 style="text-align:left;color:green;"><i class="fa fa-pen"></i> Quizzes</h1>
        <?php if ($quiz_data_len > 0): ?>
        <?php for ($i = 0; $i < $quiz_data_len; $i++): 
          
        ?>
        <div style="width:100%;min-height:100px;background-color:white;box-shadow:0 0 3px rgba(0,0,0,0.3);border-radius:10px;position:relative;margin-bottom:10px;">
          <div style="padding:10px;">
            <div style="color:#92aebf;font-weight:bold;">
            <i class="fa fa-pen"></i>
            <?php print Html::escape_r($quiz_data[$i]['tc_title']); ?>
            -
            <?php print Html::escape_r($quiz_data[$i]['t_title']); ?>
            -
            <?php print Html::escape_r($quiz_data[$i]['st_title']); ?>
            </div>
            <div style="font-weight:bolder;font-size:15pt;"><?php print Html::escape_r($quiz_data[$i]["title"]);  ?></div>
              <div style="text-indent:20px;">
                <div></div>
                <div>Duration: <?php print Date2::spentFull(Html::escape_r($quiz_data[$i]["timelimit"])); ?></div>
                <?php if ($quiz_data[$i]['done'] == 1): ?>
                  <div>Status: <span style="color:green;">Finished</span></div>
                <?php endif; ?>
              </div>
          </div>
          <button class="<?php print $quiz_data[$i]['done'] == 1 ? 'view-score' : 'start_btn'; ?>" rel="<?php print $quiz_data[$i]["id"];  ?>" style="position:absolute;top:25px;right:10px;background-color:#00ad00;color:white;padding:10px;border-radius:5px;border:1px solid #03d941;">
            <?php if ($quiz_data[$i]['done'] == 1): ?>
              <i class="fa fa-check"></i> View Score
            <?php else: ?>
              <i class="fa fa-pen"></i> Start Quiz
            <?php endif; ?>
          </button>
        </div>
        <?php endfor;  ?>
        <?php else: ?>
        <div style="color:red;"><i class="fa fa-exclamation-circle"></i> No Quiz added!</div>
        <?php endif; ?>
      </div>
    </div>
    <script src="<?php print ROOT_PATH; ?>assets/js/jquery.min.js"></script>
    <script>
      $(".view-score").on("click", function() {
        var id = $(this).attr('rel');
        location.href = ROOT_PATH + "Quiz/start.php?qid=" + btoa(id);
      });
      $(".start_btn").on("click", function() {
        var id = $(this).attr("rel");
        var btn = $(this);
        btn.html('<i class="fa fa-refresh"></i> Starting...');
        $.ajax({
          url: ROOT_PATH + "Quiz/u.php?route=start",
          type: "post",
          data: {id: id}
        }).then(function() {
          btn.html('<i class="fa fa-pen"></i> Start Quiz');
          location.href = ROOT_PATH + "Quiz/start.php?qid=" + btoa(id);
        }, function() {
          alert("Something went wrong!");
        });
      });
    </script>
  </body>
</html>