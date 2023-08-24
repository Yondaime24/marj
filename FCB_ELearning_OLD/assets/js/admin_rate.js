window.addEventListener('load', function() {
    document.querySelector('.top-loader').style.display = 'none';
  });

  $(document).ready(function(){
    
  $(".cmt_res").on("click", ".prev-a", function() {
    var id = $(this).attr("id");
    $('#user_id').val(id);
    fetchPrevCmt();
  });

   //FETCH PREVIOUS COMMENT
   function fetchPrevCmt()
    {
        var id = $('#user_id').val();
        var action = "Fetch";
        $.ajax({
            url: "action.php",
            method: "POST",
            data: {
                operation2: action,
                user_id: id
            },
            success: function(data) {
                $('#result').html(data);
            }
        });
    }

    $(document).on('click', '#filter_5star', function() {
      $('#filter_rating').html("5 stars <i class=\"fas fa-chevron-down\"></i>");
              $.ajax({url: ROOT_PATH + 'Rate/r.php?route=recentCmt5', type: 'post', data:{}}).then(function(resp) {
                var data = JSON.parse(resp);
                var recentCmt5 = data.recentCmt5;
                var recentCmt5_len = recentCmt5.length; 
              if(recentCmt5_len == 0) {
                var t = '';
                  t += '<div class="cmt_div" style="display:flex;justify-content:center;align-items:center;height:30vh;">'; 
                  t += '<span style="font-size:18px;font-weight:bold;color:red;">No Related Reviews Yet!</span>'; 
                  t += '</div>';
                $(".cmt_res").html(t);
              }else{
                var t = '';
                for (var i = 0; i < recentCmt5_len; i++) {
                  t += '<div class="cmt_div">'; 
                  t += '<span class="fnum">'+ recentCmt5[i]["rate_value"] +'<small class="snum">/5</small></span>'; 
                  t += '<br>';
                  t += '<div>'; 
                  t += '<div class="cmt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ recentCmt5[i]["rate_cmt"] +'</div>'; 
                  t += '<small class="date_rate">' + recentCmt5[i]["dt"] + '</small>';
                  t += '<span class="ago"><i class="fas fa-clock"></i> ' + recentCmt5[i].ago + ' ago</span>';
                  t += '<a class="prev-a" href="#" id="'+ recentCmt5[i]["user_id"] +'" data-toggle="modal" data-target="#prev_modal"><i class="fas fa-comments"></i> Previous Reviews</a>';
                  t += '</div>'; 
                  t += '</div>';
                }
                $(".cmt_res").html(t);
              }
            });
    });
    $(document).on('click', '#filter_4star', function() {
      $('#filter_rating').html("4 stars <i class=\"fas fa-chevron-down\"></i>");
        $.ajax({url: ROOT_PATH + 'Rate/r.php?route=recentCmt4', type: 'post', data:{}}).then(function(resp) {
          var data = JSON.parse(resp);
          var recentCmt4 = data.recentCmt4;
          var recentCmt4_len = recentCmt4.length; 
            if(recentCmt4_len == 0) {
                var t = '';
                  t += '<div class="cmt_div" style="display:flex;justify-content:center;align-items:center;height:30vh;">'; 
                  t += '<span style="font-size:18px;font-weight:bold;color:red;">No Related Reviews Yet!</span>'; 
                  t += '</div>';
                $(".cmt_res").html(t);
              }else{
                var t = '';
                for (var i = 0; i < recentCmt4_len; i++) {
                  t += '<div class="cmt_div">'; 
                  t += '<span class="fnum">'+ recentCmt4[i]["rate_value"] +'<small class="snum">/5</small></span>'; 
                  t += '<br>';
                  t += '<div>'; 
                  t += '<div class="cmt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ recentCmt4[i]["rate_cmt"] +'</div>'; 
                  t += '<small class="date_rate">' + recentCmt4[i]["dt"] + '</small>';
                  t += '<span class="ago"><i class="fas fa-clock"></i> ' + recentCmt4[i].ago + ' ago</span>';
                  t += '<a class="prev-a" href="#" id="'+ recentCmt4[i]["user_id"] +'" data-toggle="modal" data-target="#prev_modal"><i class="fas fa-comments"></i> Previous Reviews</a>';
                  t += '</div>'; 
                  t += '</div>';
                }
                $(".cmt_res").html(t);
            }
        });
    });
    $(document).on('click', '#filter_3star', function() {
      $('#filter_rating').html("3 stars <i class=\"fas fa-chevron-down\"></i>");
      $.ajax({url: ROOT_PATH + 'Rate/r.php?route=recentCmt3', type: 'post', data:{}}).then(function(resp) {
        var data = JSON.parse(resp);
        var recentCmt3 = data.recentCmt3;
        var recentCmt3_len = recentCmt3.length; 
        if(recentCmt3_len == 0) {
          var t = '';
            t += '<div class="cmt_div" style="display:flex;justify-content:center;align-items:center;height:30vh;">'; 
            t += '<span style="font-size:18px;font-weight:bold;color:red;">No Related Reviews Yet!</span>'; 
            t += '</div>';
          $(".cmt_res").html(t);
        }else{
          var t = '';
          for (var i = 0; i < recentCmt3_len; i++) {
            t += '<div class="cmt_div">'; 
            t += '<span class="fnum">'+ recentCmt3[i]["rate_value"] +'<small class="snum">/5</small></span>'; 
            t += '<br>';
            t += '<div>'; 
            t += '<div class="cmt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ recentCmt3[i]["rate_cmt"] +'</div>'; 
            t += '<small class="date_rate">' + recentCmt3[i]["dt"] + '</small>';
            t += '<span class="ago"><i class="fas fa-clock"></i> ' + recentCmt3[i].ago + ' ago</span>';
            t += '<a class="prev-a" href="#" id="'+ recentCmt3[i]["user_id"] +'" data-toggle="modal" data-target="#prev_modal"><i class="fas fa-comments"></i> Previous Reviews</a>';
            t += '</div>'; 
            t += '</div>';
          }
          $(".cmt_res").html(t);
        }
        
      });
    });
  $(document).on('click', '#filter_2star', function() {
    $('#filter_rating').html("2 stars <i class=\"fas fa-chevron-down\"></i>");
      $.ajax({url: ROOT_PATH + 'Rate/r.php?route=recentCmt2', type: 'post', data:{}}).then(function(resp) {
        var data = JSON.parse(resp);
        var recentCmt2 = data.recentCmt2;
        var recentCmt2_len = recentCmt2.length; 
        if(recentCmt2_len == 0) {
          var t = '';
            t += '<div class="cmt_div" style="display:flex;justify-content:center;align-items:center;height:30vh;">'; 
            t += '<span style="font-size:18px;font-weight:bold;color:red;">No Related Reviews Yet!</span>'; 
            t += '</div>';
          $(".cmt_res").html(t);
        }else{
          var t = '';
          for (var i = 0; i < recentCmt2_len; i++) {
            t += '<div class="cmt_div">'; 
            t += '<span class="fnum">'+ recentCmt2[i]["rate_value"] +'<small class="snum">/5</small></span>'; 
            t += '<br>';
            t += '<div>'; 
            t += '<div class="cmt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ recentCmt2[i]["rate_cmt"] +'</div>'; 
            t += '<small class="date_rate">' + recentCmt2[i]["dt"] + '</small>';
            t += '<span class="ago"><i class="fas fa-clock"></i> ' + recentCmt2[i].ago + ' ago</span>';
            t += '<a class="prev-a" href="#" id="'+ recentCmt2[i]["user_id"] +'" data-toggle="modal" data-target="#prev_modal"><i class="fas fa-comments"></i> Previous Reviews</a>';
            t += '</div>'; 
            t += '</div>';
          }
          $(".cmt_res").html(t);
        }
      });
    });
    $(document).on('click', '#filter_1star', function() {
      $('#filter_rating').html("1 stars <i class=\"fas fa-chevron-down\"></i>");
      $.ajax({url: ROOT_PATH + 'Rate/r.php?route=recentCmt1', type: 'post', data:{}}).then(function(resp) {
        var data = JSON.parse(resp);
        var recentCmt1 = data.recentCmt1;
        var recentCmt1_len = recentCmt1.length; 
        if(recentCmt1_len == 0) {
          var t = '';
            t += '<div class="cmt_div" style="display:flex;justify-content:center;align-items:center;height:30vh;">'; 
            t += '<span style="font-size:18px;font-weight:bold;color:red;">No Related Reviews Yet!</span>'; 
            t += '</div>';
          $(".cmt_res").html(t);
        }else{
          var t = '';
          for (var i = 0; i < recentCmt1_len; i++) {
            t += '<div class="cmt_div">'; 
            t += '<span class="fnum">'+ recentCmt1[i]["rate_value"] +'<small class="snum">/5</small></span>'; 
            t += '<br>';
            t += '<div>'; 
            t += '<div class="cmt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ recentCmt1[i]["rate_cmt"] +'</div>'; 
            t += '<small class="date_rate">' + recentCmt1[i]["dt"] + '</small>';
            t += '<span class="ago"><i class="fas fa-clock"></i> ' + recentCmt1[i].ago + ' ago</span>';
            t += '<a class="prev-a" href="#" id="'+ recentCmt1[i]["user_id"] +'" data-toggle="modal" data-target="#prev_modal"><i class="fas fa-comments"></i> Previous Reviews</a>';
            t += '</div>'; 
            t += '</div>';
          }
          $(".cmt_res").html(t);
        }
      });
    });
          
              $.ajax({url: ROOT_PATH + 'Rate/r.php?route=recentCmt', type: 'post', data:{}}).then(function(resp) {
                var data = JSON.parse(resp);
                var recentCmt = data.recentCmt;
                var recentCmt_len = recentCmt.length; 
                if(recentCmt_len == 0) {
                  var t = '';
                    t += '<div class="cmt_div" style="display:flex;justify-content:center;align-items:center;height:30vh;">'; 
                    t += '<span style="font-size:18px;font-weight:bold;color:red;">No Reviews Available Yet!</span>'; 
                    t += '</div>';
                  $(".cmt_res").html(t);
                }else{
                  var t = '';
                  for (var i = 0; i < recentCmt_len; i++) {
                    t += '<div class="cmt_div">'; 
                    t += '<span class="fnum">'+ recentCmt[i]["rate_value"] +'<small class="snum">/5</small></span>'; 
                    t += '<br>';
                    t += '<div>'; 
                    t += '<div class="cmt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ recentCmt[i]["rate_cmt"] +'</div>'; 
                    t += '<small class="date_rate">' + recentCmt[i]["dt"] + '</small>';
                    t += '<span class="ago"><i class="fas fa-clock"></i> ' + recentCmt[i].ago + ' ago</span>';
                    t += '<a class="prev-a" href="#" id="'+ recentCmt[i]["user_id"] +'" data-toggle="modal" data-target="#prev_modal"><i class="fas fa-comments"></i> Previous Reviews</a>';
                    t += '</div>'; 
                    t += '</div>';
                  }
                  $(".cmt_res").html(t);
                }
                
              });

    $.ajax({url: ROOT_PATH + 'Rate/r.php?route=average', type: 'post', data:{}}).then(function(resp) {
      var average = JSON.parse(resp);
      var result = parseFloat(average[0]['average']).toFixed(1);
      if(isNaN(result)){
        $("#rate01").html("No Reviews Yet!");
      }else{
        $("#rate01").html(result + " average based on ");
      }
      var n = (100 * result) / 5;
      $(".cornerimage").css({'width': n + '%'});
    });
    $.ajax({url: ROOT_PATH + 'Rate/r.php?route=count', type: 'post', data:{}}).then(function(resp) {
      var user_rate_count = JSON.parse(resp);
      var c_res = user_rate_count[0]['rate_count'];
      if(c_res == 0){
        $("#count01").html("");
      }else{
        $("#count01").html(c_res + " reviews.");
      }
     $.ajax({url: ROOT_PATH + 'Rate/r.php?route=5star', type: 'post', data:{}}).then(function(resp) {
      var count = JSON.parse(resp);
      var result = parseFloat(count[0]['count']).toFixed(1);
       // total of 5 star / overall rate total x 100
        var percent = (result / user_rate_count[0]['rate_count']) * 100;
        var percent2 = parseFloat(percent).toFixed(0);
        $("#bar5").css({'height': percent2 + '%'});
        if(isNaN(percent2)){
          $("#star5avg").text('0%');
        }else{
          $("#star5avg").text(percent2 + '%');
        }
     });
     $.ajax({url: ROOT_PATH + 'Rate/r.php?route=4star', type: 'post', data:{}}).then(function(resp) {
      var count = JSON.parse(resp);
      var result = parseFloat(count[0]['count']).toFixed(1);
        // total of 4 star / overall rate total x 100
        var percent = (result / user_rate_count[0]['rate_count']) * 100;
        var percent2 = parseFloat(percent).toFixed(0);
        $("#bar4").css({'height': percent2 + '%'});
        if(isNaN(percent2)){
          $("#star4avg").text('0%');
        }else{
          $("#star4avg").text(percent2 + '%');
        }
     });
     $.ajax({url: ROOT_PATH + 'Rate/r.php?route=3star', type: 'post', data:{}}).then(function(resp) {
      var count = JSON.parse(resp);
      var result = parseFloat(count[0]['count']).toFixed(1);
       // total of 3 star / overall rate total x 100
        var percent = (result / user_rate_count[0]['rate_count']) * 100;
        var percent2 = parseFloat(percent).toFixed(0);
        $("#bar3").css({'height': percent2 + '%'});
        if(isNaN(percent2)){
          $("#star3avg").text('0%');
        }else{
          $("#star3avg").text(percent2 + '%');
        }
     });
     $.ajax({url: ROOT_PATH + 'Rate/r.php?route=2star', type: 'post', data:{}}).then(function(resp) {
      var count = JSON.parse(resp);
      var result = parseFloat(count[0]['count']).toFixed(1);
        // total of 2 star / overall rate total x 100
        var percent = (result / user_rate_count[0]['rate_count']) * 100;
        var percent2 = parseFloat(percent).toFixed(0);
        $("#bar2").css({'height': percent2 + '%'});
        if(isNaN(percent2)){
          $("#star2avg").text('0%');
        }else{
          $("#star2avg").text(percent2 + '%');
        }
     });
     $.ajax({url: ROOT_PATH + 'Rate/r.php?route=1star', type: 'post', data:{}}).then(function(resp) {
      var count = JSON.parse(resp);
      var result = parseFloat(count[0]['count']).toFixed(1);
        // total of 1 star / overall rate total x 100
        var percent = (result / user_rate_count[0]['rate_count']) * 100;
        var percent2 = parseFloat(percent).toFixed(0);
        $("#bar1").css({'height': percent2 + '%'});
        if(isNaN(percent2)){
          $("#star1avg").text('0%');
        }else{
          $("#star1avg").text(percent2 + '%');
        }
     });
});

$("a[href='#top']").click(function() {
  $("html, body").animate({scrollTop: 0}, "slow");
  return false;
});

});

var mybutton = document.getElementById("myBtn");
window.onscroll = function() {scrollFunction()};
function scrollFunction() {
  if(document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  }else{
    mybutton.style.display = "none";
  }
}