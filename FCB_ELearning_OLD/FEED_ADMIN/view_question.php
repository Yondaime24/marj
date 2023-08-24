<?php 
  use classes\url;
  use classes\UserQuiz;
  require '../___autoload.php';
  $uid = url::get("uid"); // userid
  $id = url::get("id"); // question_prepared_id

  $u = new UserQuiz($uid);
  $u->question_prepared_id = $id;

  $gdata = $u->getQuestion();
  $data = $gdata["data"];
  $data_len = count($data);
  $prev = url::get("prev");
?>
<div style="padding:20px;">
  <button id="back01" class="btn btn-primary btn-sm">
    <i class="fa fa-refresh"></i> Back
  </button>
</div>
<hr />
<?php if ($data_len > 0): ?>
<div style="padding:20px;">
  <div style="width:100%;background-color:white;box-shadow:3px 3px 8px rgba(0,0,0,0.4);margin:auto;padding:20px;border-radius:10px;">
    <div style="margin-bottom:10px;">
      Title: <span style="font-weight:bold;"><?php echo $gdata["title"]; ?></span>
    </div>
    <?php 
    for ($i = 0; $i < $data_len; $i++): 
      $item = $data[$i]["item"];
      $item_len = count($item);
      
      $ans = $data[$i]["ans"];
      $ans_len = count($ans);
    ?>  
     <div>
      <div style="font-weight:bold;">
        <?php echo $i + 1; ?>.) <?php echo $data[$i]["question"]; ?>
        <?php if ($data[$i]["type"] == "ESSAY"):  ?>
         : (<?php echo $data[$i]["points"]; ?> points)
        <?php endif; ?>  
      </div>
      <?php if ($item_len > 0): ?>
      <ul style="list-style-type:circle;margin:0;">
        <?php for ($j = 0; $j < $item_len; $j++): ?>
        <li>
          <?php if ($item[$j]["IsAnsKey"] == 1): ?>
           <span style="color:green;"><?php echo $item[$j]["Des"]; ?></span>
          <?php else: ?>
           <?php echo $item[$j]["Des"]; ?> 
          <?php endif; ?>
        </li>
        <?php endfor; ?>
      </ul> 
      <?php endif; ?>
      <p style="text-indent:10px;margin:0;color:red;">My Answer:</p>
      <?php if($ans_len > 0): ?>
      <ul style="list-style-type:square;">
      <?php for ($k = 0; $k < $ans_len; $k++): ?>
        <li>
        <?php echo $ans[$k]["text_ans"]; ?>
        <?php if ($data[$i]["type"] == "ESSAY"):  ?>
          <span style="color:red;"> (<?php echo $ans[$k]["score"]; ?> points)</span>
        <?php endif; ?>
        </li>
      <?php endfor; ?>
      </ul>
      <?php endif; ?>
    </div>
    <?php endfor; ?>
  </div>
</div>
<?php endif; ?>

<script>
  $("#back01").on("click", function() {
    mainroute("<?php echo ADMIN_PATH; ?>stat_view.php?uid=<?php echo $uid; ?>&prev=<?php echo $prev; ?>");
  });
</script>