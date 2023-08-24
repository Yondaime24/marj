<?php 
// rendering the quiz
use classes\QuizFull;

require_once '../___autoload.php';
$quiz = new QuizFull();
$data = $quiz->getQuiz('qp.title, qp.question_prepared_id as id');
$data_len = count($data);

?>
<style>
  .quiz_list,.ulist,.essay001{cursor:pointer;}
  .active01{background-color:#14adb9!important;color:white;}
</style>
<div class="container-fluid">
  <div style="padding:20px;">
    <div class="row">
      <div class="col-md-3">
         <?php if ($data_len > 0): ?>
          <div style="padding:10px;background-color:white;">
             <div class="list-group" style="margin-bottom:1px;">
               <div class="list-group-item" style="font-weight:bold;">
                <i class="fa fa-pen"></i> Quiz Title
               </div>
             </div>
             <div class="list-group">
                <?php for ($i = 0; $i < $data_len; $i++): ?>
                <div class="list-group-item quiz_list" rel="<?php print $data[$i]['id']; ?>">
                  <?php print $data[$i]['title']; ?>
                </div>
                <?php endfor; ?>
             </div>
          </div>
         <?php endif; ?>
      </div>
      <div class="col-md-3">
        <div style="background-color:white;min-height:100px;" id="user-list">
          
        </div>
      </div>
      <div class="col-md-3">
        <div style="background-color:white;min-height:100px;padding:10px;" id="essay">
          
        </div>
      </div>
      <div class="col-md-3">
        <div style="background-color:white;min-height:100px;">
          <div style="padding:10px;">
            <div class="form-group">
              <label>Total Points</label>
              <input type="text" id="total_points" class="form-control" disabled=""  />
            </div>
            <div class="form-group">
              <label>Give Points</label>
              <input type="number" class="form-control" id="give_points" />
            </div>
            <div class="form-group" id="submit_point" style="margin-top:10px;">
              <button class="btn btn-secondary btn-sm">
                Submit
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="essay_cont">
  <div style="padding:10px;height:230px;overflow-y:auto;">
    <p id="ans_cont" style="text-indent:20px;text-align:justify;">

    </p>
  </div>
</div>
<script src="<?php print ROOT_PATH; ?>assets/cbox/cbox.js"></script>
<script>
  var info = undefined;
  
  info = new cbox('#essay_cont');
  info.title = 'Quiz Essay Ans.';
  info.logo = '<i class="fa fa-pen"></i>';
  info.init();

  var QUIZ_ID = '';
  var UID = '';
  var ESSAY_ID = '';

  $('.quiz_list').on('click', function() {
    var id = $(this).attr('rel');
    var user_list = $('#user-list');
    UID = '';
    QUIZ_ID = id;
    $('.quiz_list').removeClass('active01');
    $(this).addClass('active01');
    $.post('<?php print ADMIN_PATH; ?>route.php?r=userlist',{question_prepared_id: id}).then(function(resp) {
      var data = JSON.parse(resp);
      var data_len = data.length;
      var h = '';
      if (data_len > 0) {
        h += '<div style="padding:10px;">';
          h += '<div class="list-group" style="margin-bottom:1px;">';
          h += '<div class="list-group-item" style="font-weight:bold;">';
          h += '<i class="fa fa-user"></i> User';
          h += '</div>';
          h += '</div>';
          h += '<div class="list-group">';
          for (var i = 0; i < data_len; i++) {
            h += '<div class="list-group-item ulist" rel="' + data[i]['uid'] + '">';
            h += data[i]['name'];
            h += '</div>';
          }
          h += '</div>';
        h += '</div>';  
      }
      user_list.html(h);
    });
    $("#essay").html('');
  });
  $('#user-list').on('click', '.ulist', function() {
    var uid = $(this).attr('rel');
    UID = uid;
    $('.ulist').removeClass('active01');
    $(this).addClass('active01');
    $('#give_points').val('');
    $('#total_points').val('');    
    $.post('<?php print ADMIN_PATH; ?>route.php?r=essay-list', {prepared_id: QUIZ_ID, uid: UID}, function(resp) {
      var h = '';
      var data = JSON.parse(resp);
      var data_len = data.length;
      if (data_len > 0) {
        h += '<div>';
          h += '<div class="list-group">';
            for (var i = 0; i < data_len; i++) {
              h += '<div class="list-group-item essay001" rel="' + data[i]['question_id']  + '" max_score="' + data[i]['points'] + '">';
              h += data[i]['Question'];
              h += '</div>';
            }
          h += '</div>';
        h += '</div>';
      }
      $("#essay").html(h);
    });
  });
  $('#essay').on('click', '.essay001', function() {
    $('.essay001').removeClass('active01');
    $(this).addClass('active01');
    ESSAY_ID = $(this).attr('rel');
    var max = $(this).attr('max_score');
    $.post('<?php print ADMIN_PATH ?>route.php?r=essay_data', {uid: UID, question_id: ESSAY_ID, prepared_id: QUIZ_ID}, function(resp) {
      var data = JSON.parse(resp);
      var data_len = data.length;
      if (data_len > 0) {
        $('#give_points').val(data[0]['score']);
        $('#total_points').val(max);
        $('#ans_cont').html('');
        info.show();
        $('#ans_cont').html(atob(data[0]['text_ans']));
      } else {
        alert('he/she did not answer this essay!');
        $('#give_points').val('');
        $('#total_points').val('');        
      }
    });    
  });
  $('#submit_point').on('click', function() {
    var total_points = parseFloat($('#total_points').val());
    var give_points = parseFloat($('#give_points').val());
    if (give_points > total_points)
      return alert('the max point is ' + total_points);
    if (QUIZ_ID != '' && UID != '' && ESSAY_ID != '') {
      $.post('<?php print ADMIN_PATH; ?>/route.php?r=essay_points', {score: give_points, uid: UID, prepared_id: QUIZ_ID, question_id: ESSAY_ID}).then(function(resp) {
        var data = JSON.parse(resp);    
        if (data.ok) {
          alert(data.msg);
        }
      });
    } else {
      alert('failed');
    }
  });
</script>