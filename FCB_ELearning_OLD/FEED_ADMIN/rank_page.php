<?php 
use classes\QuizFull;
require "../___autoload.php";
$q = new QuizFull();
$data = $q->getQuiz();
$data_len = count($data);
$id = isset($_GET["id"]) ? $_GET["id"] : "";
?>
<div class="container-fluid">
  <div style="padding-top:10px;">
    <select class="form-control" id="rankid">
      <?php for ($i = 0; $i < $data_len; $i++): ?>
      <option <?php echo $data[$i]["id"] == $id ? "selected" : ""; ?> value="<?php echo $data[$i]["id"]; ?>">
        <?php echo $data[$i]["title"];  ?> : <?php echo $data[$i]["tc_title"]." - ".$data[$i]["t_title"]." - ".$data[$i]["st_title"]; ?>
      </option>
      <?php endfor; ?>
    </select>
  </div>
  <div class="row">
    <div class="col-md-12" id="generator">

    </div>
  </div>
</div>
<script>
  $("#rankid").off("change");
  function loadRank(id) {
    $("#generator").html("Loading....Please wait...");
    $.post("<?php echo ADMIN_PATH; ?>rank.php?id=" + id).then(function(html) {
      $("#generator").html(html);
    });
  }
  $("#rankid").on("change", function() {
    loadRank($("#rankid").val());
  });
  loadRank($("#rankid").val());
</script>