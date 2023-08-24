<?php 
  use classes\UserAns;
  use classes\UserQuiz;
  use classes\user;
  use classes\Date as Date2;
  use classes\lib\sql;
  $qid = isset($_GET['qid']) ? $_GET['qid'] : '';
  $qid = base64_decode($qid);
  //session_destroy();
  require_once '../___autoload.php';
  $feed = new sql();
  $is_done = false;
  $uq = new UserQuiz();
  $uq->feed = $feed; 
  $uq->question_prepared_id = $qid;
  $uq->init();
  $uq->create();

  $quiz_data = $uq->get('
    qp.id,
    tc.title as tc_title,
    t.title as t_title,
    st.title as st_title,
    qp.title as qp_title,
    qp.timelimit,
    uq.datestarted,
    uq.done
  ');
  
  $QUIZ = $quiz_data;
  
  $date_started = strtotime(date('Y-m-d H:i:s', strtotime($quiz_data['header'][0]['datestarted'])));
  $current_date = strtotime(Date2::getDate());
  $user_ans = json_encode($uq->getAnswered());
  $is_done = $quiz_data['header'][0]['done'];
  $score = json_encode([]);
  $gdata = json_encode($quiz_data['data']);
  $time_limit = $quiz_data['header'][0]['timelimit'];

  $score = json_encode($uq->getScore());

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FEED - Quiz Start</title>

    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/style.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/topics.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/all.min.css" />

    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/pptxjs.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/nv.d3.min.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/admin.topic.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/admin.category.css" />
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/loader.css" />        
    <style>
    *{box-sizing:border-box;}
    body,html{margin:0;padding:0;}
    body{width:100%;height:100%}
    .right,.left{cursor:not-allowed!important;background-color:#d1d1d1;font-weight:bolder;border:none;border-radius:10px;color:white;width:100px;height:50px;}
    .right{position:absolute;right:20px;bottom:20px;}
    .left{position:absolute;left:20px;bottom:20px;}
    .bactive:hover{background-color:#18a918;box-shadow:0px 0px 3px 1px rgba(0,0,0,0.3);transform:scale(1.2);transition:0.1s;}
    .bactive{background-color:#1acb1a;cursor:pointer!important;}
    .qcon{display:none;background-color:white;margin:auto;box-shadow:0px 0px 3px rgba(0,0,0,0.3);height:100%;width:100%;border-radius:20px;position:relative;}
    .startnow{background-color:#348d34;}
    .startnow:hover{background-color:green;}
    .con-data{padding:20px;font-size:18pt;}
    .choice{width:30px;height:30px;position:absolute;top:4px;left:4px;background-color:white;border:2px solid #eee;border-radius:50%;cursor:pointer;display:inline-block;}
    .choice:hover{border-color:#4be54b;}
    .mul-active{background-color:#0dd30d;border-color:#4be54b;}
    .mul{position:relative;}
    .ctxt{padding-left:40px;}
    .rd{padding:10px;background-color:#1acb1a;border:none;border-radius:5px;margin-top:5px;color:white;font-weight:bold;font-size:15pt;}
    .rd:hover{background-color:#22e322;}
    <?php if (isset($QUIZ)):  ?>
    .intro{display:none;} 
    .qcon{display:block;}
    <?php endif; ?>
    </style>
    <script>
      var gquiz = null;
    <?php if (isset($QUIZ)):?>
      gquiz = '<?php print json_encode($QUIZ); ?>';
    <?php endif; ?>
      var quiz_id = '';
      var qid = '';
      if (gquiz != null) {
        var tmp_01 = JSON.parse(gquiz);
        quiz_id = tmp_01[0].id;
        qid = tmp_01[0].qid; 
      }
      var ans_list = JSON.parse('<?php print $user_ans; ?>');
      var score = JSON.parse('<?php print $score ?>');
    </script>
    <style>
      #progress{border-radius:5px;height:20px;width:100%;background-color:#d3d3d3;margin-bottom:10px;position:relative;}
      #progress-width{background-color:#0091df;height:100%;border-radius:5px;}
      #timer{position:fixed;top:10px;right:20px;font-size:20pt;padding:10px;background-color:#666666;color:white;border-radius:10px;}    
    </style>
    <script src="<?php print ROOT_PATH; ?>config/config.js"></script>
    <script src="<?php print ROOT_PATH; ?>assets/js/jquery.min.js"></script>
  </head>
  <body>
    <?php if (!$is_done): // if not done continue answering the quiz ?>
     <div id="timer" style="display:none;"></div>
    <div style="width:100vw;height:100vh;padding:100px;display:none;" id="t01">
      <div id="progress">
        <div id="progress-width"></div>
      </div>
      <div class="qcon">
        <div class="con-data">
          <label style="font-weight:bold;" class="title" id="title" quiz_id="" question_id=""></label>
          <div style="padding-left:50px;margin-top:10px;" class="items">
          </div>
          <!-- <div style="padding-left:50px;margin-top:10px;" class="items">
            <div>
              A. Elon Musk
            </div>
            <div>
              B. Bill Gates  
            </div>
            <div>
              C. Layla 
            </div>
          </div> -->
        </div>
        <button class="left"><i class="fa fa-circle-chevron-left"></i> Prev</button>
        <button class="right bactive"><i class="fa fa-circle-chevron-right"></i> Next</button>        
      </div>      
    </div>
    <div style="width:100%;height:100%;position:fixed;top:0px;left:0px;z-index:1000;background-color:#00000012;" class="intro">
      <div style="padding:100px;position:relative;width:480px;height:300px;background-color:white;border-radius:20px;box-shadow:0 0 2px rgba(0,0,0,0.5);margin:auto;margin-top:10%;overflow-y:auto;">
        <button class="startnow" style="height:100px;width:260px;color:white;font-size:20pt;border-radius:20px;border:none;font-weight:bold;"><i class="fa fa-pencil"></i> START NOW</button>
      </div>
    </div>
    <div class="done" style="background-color:#00000029;display:none;position:fixed;top:0;right:0;width:100%;height:100%;z-index:1000;">
      <div style="box-shadow:0 0 3px #00000061;position:absolute;top:0px;right:0px;bottom:0px;left:0px;margin:auto;width:400px;height:140px;background-color:white;border-radius:10px;">
        <div style="padding:10px;">
          <button style="width:100%;" class="rd review-btn">Review</button>
          <button style="width:100%;" class="rd done-btn">Done?</button>
        </div>
      </div>
    </div>  
     <div id="file-loader" style="display:none;width:100%;height:100%;position:fixed;top:0;left:0;z-index:2000;">
      <div style="font-size:20pt;padding:25px 20px 20px 20px;border-radius:10px;box-shadow:0 0 5px #0000003b;width:300px;height:100px;background-color:white;position:absolute;top:0;left:0;right:0;bottom:0;margin:auto;">
        <i class="fa fa-refresh rotate" style="color:#05a777;"></i> wait....
      </div>
    </div>
    <script src="<?php print ROOT_PATH; ?>assets/c/c.js"></script>
    <script>
    var check_interval = null;
    include("Date2.js");
    var d = new Date2();
    d.target_date = parseInt(<?php print $date_started;  ?>);
    d.current_date = parseInt(<?php print $current_date; ?>);
    d.limit = parseInt(<?php print $time_limit; ?>);
    var is_finished = false;
    setTimeout(function() {
      $("#t01").show();
    }, 1300);
    check_interval = setInterval(function() {
      d.current_date += 1;
      var t = d.elapsed();
      is_finished = t <= '0' ? true : false;
      if (is_finished) {
        $('#timer').hide();
        $("#t01").prepend('<span style="font-size:20pt;font-weight:bold;color:#fff;">Time\'s up!');
        clearInterval(check_interval);
      } else {
        $("#timer").show();
      }
      $("#timer").html(t);
    }, 1000);
    var ID = '<?php print $qid; ?>';
    var INDEX = 0;
    var QUIZ = function() {
      this.qid = '';
      this.quiz_id = '';
      this.cid = '';
      this.question_id = '';
      this.type = '';
      this.ctxt = '';
      this.start = function() {
        return $.ajax({url: '<?php print ROOT_PATH; ?>Quiz/u.php?route=start', type: 'post', data:{id:ID}});
      }
      this.load = function() {
        return $.ajax({url: '<?php print ROOT_PATH; ?>Quiz/u.php?route=load', type: 'post', data:{index: INDEX}});
      }
      this.done = function() {
        return $.ajax({url: '<?php print ROOT_PATH; ?>Quiz/u.php?route=done', type: "post", data:{quiz_id: this.quiz_id}});
      }
      this.submitAns = function() {
        return $.ajax({url: '<?php print ROOT_PATH; ?>Quiz/u.php?route=submitI', type: 'post', data:{quiz_id: this.quiz_id, qid: this.qid, cid: this.cid, question_id: this.question_id, type: this.type, ctxt: this.ctxt}});
      }
    }
    var my_ans_g = {
      question_id: null,
      load: function() {
        return $.ajax({
          url: ROOT_PATH + "Quiz/u.php?route=myAns",
          type: "post", 
          data: {question_id: this.question_id}
        });
      }
    };
    var question = null;
    var q_len = 0;
    var my_ans = [];
    window.addEventListener('load', function() {
      try {
        win_open(0);
      } catch(err) {console.log(err);}
      var q = new QUIZ();
      function restoreCheck(cid) {
        var ans_list_len = ans_list.length;
        for (var b = 0; b < ans_list_len; b++)
          if (cid == ans_list[b].question_item_choice_id)
            return true;
        return false;
      }
      
      function loadEnum() {
        var item_list = '';
        var q1 = $("#title").attr("question_id");
            
        my_ans_g.question_id = q1;
        my_ans_g.load().then(function(resp) {
          item_list += '<div class="list-group my-enum-list">';
          item_list += '<input placeholder="Enumeration: Write your answer" type="text" class="form-control my-enum-ans"/>';
          item_list += '<div style="margin-top:20px;">';
          item_list += 'My answer: ';
          item_list += '</div>';

          var data = JSON.parse(resp);
          var data_len = data.length;
          for (var i = 0; i < data_len; i++) {
            item_list += '<div class="list-group-item" style="position:relative;min-height:45px;">';
            item_list += atob(data[i].text_ans);
            item_list += '<button data_id="' + data[i].id + '" style="position:absolute;top:8px;right:10px;" class="btn btn-danger btn-sm enum-remove"><i class="fa fa-trash"></i></button>';
            item_list += '</div>';
          }
          $('.items').html(item_list);
        }, function() {
          alert("Something went wrong!");
        });
      }
      function win_open(i) {
        if (question == null) {
          var alldata = JSON.parse(gquiz);
          var qdata = alldata['data'];
          question = qdata;
          q_len = qdata.length;
        }
        INDEX += i;
        // state of prev button
        if (INDEX <= 0) {
          $('.left').removeClass('bactive');
          $('.left').attr('disabled', '');
        } else {
          $('.left').removeClass('bactive');
          $('.left').addClass('bactive');
          $('.left').removeAttr('disabled');
        }
        if (INDEX >= q_len) {
          $('.right').removeClass('bactive');
          $('.right').attr('disabled', '');
          // popup the finished button
          $('.done').show();
        } else {
          $('.right').removeClass('bactive');
          $('.right').addClass('bactive');
          $('.right').removeAttr('disabled');
        }        // state of the prev button
        if (INDEX < 0)
          INDEX = 0;
        if (INDEX >= q_len)
          INDEX = q_len - 1;
        $('#title').html((INDEX + 1) + ". " + question[INDEX]['QuestionItem']['Question']);
        $('#title').attr('question_id', question[INDEX]['QuestionItem']['id']);
        // load the choices
        var top = (INDEX + 1);
        var bot = q_len;
        var w = parseInt((top / bot) * 100);
        $('#progress-width').css('width', w + "%");
        var items = question[INDEX]['ChoiceItem'];
        var items_len = items.length;
        var type = '';
        var item_list = '';
        for (var j = 0; j < items_len; j++) {
          if (items[j]['Type'] == 'MUL' || items[j]['Type'] == "BL") {
            if (typeof my_ans[INDEX] != 'undefined') {
              if (my_ans[INDEX][0] == j) {
                // add selected when index is matched
                item_list += '<div class="mul" index="' + INDEX + '" item_index="' + j + '"  rel="' + items[j].id + '"><div class="choice mul-active"></div><div class="ctxt">' + items[j]['Des'] + '</div></div>';
              } else {
                item_list += '<div class="mul" index="' + INDEX + '" item_index="' + j + '"  rel="' + items[j].id + '"><div class="choice"></div><div class="ctxt">' + items[j]['Des'] + '</div></div>';
              }
            } else {
              if (restoreCheck(items[j].id)) {
                // add active check if the ans is restored
                item_list += '<div class="mul" index="' + INDEX + '" item_index="' + j + '"  rel="' + items[j].id + '"><div class="choice mul-active"></div><div class="ctxt">' + items[j]['Des'] + '</div></div>';
              } else {
                item_list += '<div class="mul" index="' + INDEX + '" item_index="' + j + '"  rel="' + items[j].id + '"><div class="choice"></div><div class="ctxt">' + items[j]['Des'] + '</div></div>';
              }
            }
          } else if (items[j]["Type"] == "ENUM") {
            // retrieve the answer 
            loadEnum();
            // generation of my answer
            // end generation 
            break;
          }
        }
        $('.items').html(item_list);
        // q.load().then(function(resp) { 
        //   var o = JSON.parse(resp);
        //   if (o.ok == 1) {
        //     console.log(resp);
        //   } else {
        //     if (i == -1) {
        //       INDEX += 1;
        //     } 
        //     if (i == 1) {
        //       INDEX -= 1;
        //     }
        //   }
        //   flag = 0;
        // }, function() {
        //   alert('Something went wrong!');
        //   flag = 0;
        // });
        if (question[INDEX]['QuestionItem']['type'] == "ESSAY") {
          var q1 = $("#title").attr("question_id");
          my_ans_g.question_id = q1;
          my_ans_g.load().then(function(resp) {
            var data = JSON.parse(resp);
            var data_len = data.length;
            var es = '<div>';
            es += '<textarea  placeholder="Write your answer" class="form-control es-input" style="min-height:150px;resize:none;">';
            if (data_len > 0)
              es += atob(data[0].text_ans);
            es += '</textarea>';
            es += '<button type="submit" class="submit btn btn-primary es-btn"><i class="fa fa-save"></i> Submit</button>';
            es += '</div>';
            $('.items').html(es);
          });
        }
      }
      $(".items").on('click', '.es-btn', function() {
        var es_input = $(".es-input").val();
        $('#file-loader').show();
        q.cid = '';
        q.qid = qid;
        q.quiz_id = quiz_id;
        q.question_id = $('#title').attr('question_id');
        q.type = 'essay';
        q.ctxt = es_input;
        q.submitAns().then(function() {
          $('#file-loader').hide();  
          alert('Submitted!');
        }, function() {
          alert('Something went wrong!');
          $('#file-loader').hide();
        });
      });
      $(".items").on("click", ".enum-remove", function() {
        $('#file-loader').show();
        var id = $(this).attr("data_id");
        var elem = $(this).parents(".list-group-item");
        $.ajax({
          url: ROOT_PATH + "Quiz/u.php?route=rem", 
          type: "post",
          data: {id: id}
        }).then(function(resp) {
          elem.remove();
          $('#file-loader').hide();
        }, function() {
          alert("Something went wrong!");
          $('#file-loader').hide();
        });
      });
      $(".items").on("keyup", ".my-enum-ans", function(evt) {
        // Once the user hit the enter button submit the enumeration 
        var list = $(".my-enum-list");
        if (evt.keyCode == 13) {
          var ans = $(this).val();
          var t = '<div class="list-group-item" style="position:relative;">';
          t += ans;
          t += '<button style="position:absolute;top:8px;right:10px;" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
          t += '</div>';
          // submit
          $('#file-loader').show();
          q.cid = '';
          q.qid = qid;
          q.quiz_id = quiz_id;
          q.question_id = $('#title').attr('question_id');
          q.type = 'enum';
          q.ctxt = ans;
          q.submitAns().then(function() {
            loadEnum();
            $('#file-loader').hide();  
          }, function() {
            alert('Something went wrong!');
            $('#file-loader').hide();
          });
          // end submit
          $(this).val("");
        }
      });
      $('.items').on('click', '.choice', function() {
        var me = $(this);
        // choice id where the user selected the answer
        $('#file-loader').show();
        var cid = me.parents('.mul').attr('rel');
        q.cid = cid;
        q.qid = qid;
        q.quiz_id = quiz_id;
        q.question_id = $('#title').attr('question_id');
        q.type = me.parent('div').attr('class');
        q.ctxt = me.parent('div').find('.ctxt').html();
        q.submitAns().then(function() {
          $('#file-loader').hide();
          $('.choice').removeClass('mul-active');
          me.addClass('mul-active');
          var index = me.parents('.mul').attr('index');
          var itemIndex = me.parents('.mul').attr('item_index');
          my_ans[index] = [itemIndex];  
        }, function() {
          alert('Something went wrong!');
          $('#file-loader').hide();
        });
        // submit the individual answer
      });
      /* review button */
      $('.review-btn').on('click', function() {
        $('.done').hide();
        $('.right').removeClass('bactive');
        $('.right').addClass('bactive');
        $('.right').removeAttr('disabled');
      });
      /* end review button */
      /*done btn*/
      $('.done-btn').on('click', function() {
        if (confirm('Are you sure?')) {
          var q = new QUIZ();
          q.quiz_id  = '<?php print $qid; ?>';
          $("#file-loader").show();
          q.done().then(function() {
            alert('Your done! Thank you!');
            window.location.reload();
            $("#file-loader").hide();
          }, function() {
            $("#file-loader").hide();
          });
        } 
      });
      /* end done btn */
      $('.startnow').on('click', function() {
          q.start().then(function(resp) {
            var o = JSON.parse(resp);
            if (o.ok == 1) {
              $('.intro').hide();
            }
            window.location.reload();
          }, function() {
            alert('Something went wrong!');
          });
      });
      $('.left').on('click', function() {
        win_open(-1);
      });
      $('.right').on('click', function() {
        win_open(+1);
      });
    });
    </script>
  <?php else: // else  $is_done ?>
    <div id="result" style="padding:20px;background-color:white;border-radius:10px;border:1px solid #eee;margin:auto;">
      <div id="myscore" style="font-weight:bold;font-size:20pt;"></div>
      <div id="percent"></div
>    </div>
    <script>
      var WIDTH = 0;
      var HEIGHT = 0;
      window.addEventListener("load", function() {
        WIDTH = window.screen.availWidth;
        HEIGHT = window.screen.availHeight;
        result = $("#result");
        result.css("width", (WIDTH * 0.5) + "px");
        result.css("height", (HEIGHT * 0.6) + "px");
        result.css("margin-top", (HEIGHT * 0.05) + "px");
        $('#myscore').html("Quiz Results<hr />");
        $("#percent").html("My Score: <span style=\"color:black;font-weight:bolder;\">" + score.correct.toFixed(2) + "</span>");
        $("#percent").append("<br />Total: <span style=\"color:black;font-weight:bolder;\">" + score.total + "</span>");
        $("#percent").append("<br />Percentage: <span style=\"color:red;font-weight:bolder;\">" + (parseFloat((score.correct / score.total) * 100)).toFixed(2) + "%</span>");
        $("#percent").append("<br /><br /><a href=\"../topics/menu.php\" class=\"btn btn-primary\">Goto Topics</a>");
      });
    </script>
  <?php endif; //$is_done  ?>
  </body>
</html>