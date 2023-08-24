<?php 
  use classes\url;
  use classes\user;
  use classes\TimeSpent;
  use classes\UserQuiz;
  use classes\usertask;
  use classes\QuizFull;
  use classes\Date as Date2;
  use classes\number;
  require '../___autoload.php';
  $uid = url::get("uid");
  $prev = url::get("prev");
  $user = user::getInstance();
  $user_data = $user->searchOnline($uid);
  
  if (count($user_data) == 0)exit;

  $ts = new TimeSpent($uid);
  $ts->type = "st";
  $spent = $ts->thisWeek();  

  $userQuiz = new UserQuiz($uid);

  $ut = new usertask($uid);
  $quiz = new QuizFull($uid);
  $full = $quiz->getQuiz();
  $full_len = count($full);
?>
<style>
  .dot{display:inline-block;width:10px;height:10px;background-color:#787676;border-radius:50%;}
</style>
<div style="padding:20px;">
  <button id="back" class="btn btn-primary btn-sm" rel="<?php echo $prev; ?>"><i class="fa fa-refresh"></i> Back</button>
  <hr />
  <div style="border-bottom:1px solid grey;padding-bottom:5px;height:120px;">
    <div style="background-color:white;box-shadow:3px 3px 3px rgba(0,0,0,0.3);width:120px;height:100px;float:left;border-radius:10px;background-image:url('<?php echo ROOT_PATH; ?>user/route.php?r=profile&id=<?php echo $uid; ?>');background-size:cover;background-repeat:no-repeat;">

    </div>
    <div style="float:left;padding-left:10px;">
      <div>IDNo: <span><?php echo $user_data[0]["uid"]; ?></span></div>
      <div>Name: <span><?php echo $user_data[0]["name"]; ?></span></div>
      <div>Branch: <span><?php echo $user_data[0]["branch"]; ?></span></div>   
     </div>
  </div>
  <div>
    <div>Total topic completed: <?php echo $ut->topic_completed(); ?> </div>
    <div>Total quiz completed: <?php echo $userQuiz->completed(); ?></div>
    <div>Timespent: <span><?php echo $spent; ?></span></div>
  </div>
  <div style="padding:10px;background-color:white;box-shadow:3px 3px 5px rgba(0,0,0,0.3);border-radius:10px;">
    <div style="font-weight:bold;">
     <i class="fa fa-pen"></i> Quizzes Prepared
    </div>
    <table class="table table-sm table-striped">
      <tr>
        <td>Action</td>
        <td>Rank</td>
        <td>Topics</td>
        <td>Title</td>
        <!-- <td>Date Started</td> -->
        <td>Score</td>
        <td>Percentage</td>
        <td>Status</td>
      </tr>
      <?php 
      for ($i = 0; $i < $full_len; $i++): 
        $userQuiz->question_prepared_id = $full[$i]["id"];
        $score = $userQuiz->getScore();
      ?>
      <tr>
        <td>
          <button class="btn btn-primary btn-sm view" rel="<?php echo $full[$i]["id"]; ?>"><i class="fa fa-eye"></i> View</button>
        </td>
        <td>
          <?php
          $rank = $userQuiz->rank($full[$i]["id"], $uid);
          if ($rank > 0) {
            echo number::ordinal((int)$rank); 
          }
          ?> 
        </td>
        <td>
          <?php echo $full[$i]["tc_title"].' <div class="dot"></div> '.$full[$i]["t_title"].' <div class="dot"></div> '.$full[$i]["st_title"]; ?>
        </td>
        <td>
          <?php echo $full[$i]["title"]; ?>
        </td>
        <!-- <td>
          <?php if (!empty($full[$i]["datestarted"])) echo date("Y-m-d H:i:s", strtotime($full[$i]["datestarted"]))." (" . Date2::Ago($full[$i]["datestarted"]) . " ago)"; ?>
        </td> -->
        <td>
          <?php if ($full[$i]["done"] == 1) echo $score["correct"]. '/' . $score["total"]; ?>
        </td>
        <td>
          <?php if ($full[$i]["done"] == 1) echo number_format(round((($score["correct"] / $score["total"]) * 100), 2), 2)." %"; ?>
        </td>
        <td>
          <?php echo $full[$i]["done"] == 1 ? '<span style="color:green;"><i class="fa fa-check"></i> Finished</span>' : '<span style="color:red;"><i class="fa fa-close"></i> Not Started</span>'; ?>
        </td>
      </tr>
      <?php endfor; ?>
    </table>
  </div>
</div>
<script>
  var uid = "<?php echo $uid; ?>";
  $("#back").off("click");
  $(".view").off("click");

  $("#back").on("click", function() {
    var href = $(this).attr("rel");
    mainroute(href);
  });
  $(".view").on("click", function() {
    var id = $(this).attr("rel");
    mainroute("<?php echo ADMIN_PATH; ?>view_question.php?id=" + id + "&uid=" + uid + "&prev=<?php echo $prev; ?>");
  });
</script>