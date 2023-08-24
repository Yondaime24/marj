window.addEventListener('load', function() {
    document.querySelector('.top-loader').style.display = 'none';
  });

  $(document).ready(function (){

    $('#rateus_form').on('submit', function(e) {
          e.preventDefault();

          var starsValue = $("input[type='radio'][name='rate_value']:checked").val();
          var commentValue = $('#rate_cmt').val();
          if (!starsValue) {
               $('#rate_help').css('display', 'block');  
               $('#rate_error').show();
          }else if (commentValue == ''){
                $('#rate_cmt').css('border-bottom', '1px solid red');
                $('#comment_help').show();
                $('#rate_cmt').focus();
          }else if(commentValue.length <= 20){
                $('#rate_cmt').css('border-bottom', '1px solid red');
                $('#comment_help').show();
                $('#i').text('Your feedback matters! (Please leave a longer comment)');
          }else if(commentValue.length > 20){
            $.ajax({
                url:"action.php",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                    beforeSend:function(){
                        $('#loaderModal').modal('show');
                    },
                success:function(data)
                {
                    $('#loaderModal').modal('hide');
                    $('#rateus_form')[0].reset();
                    $('#submit_btn').val("Submit");
                    $('#operation').val("Submit");
                      Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Feedback has been submitted. Thank you!',
                        showConfirmButton: false,
                        timer: 1800
                    })
                }
            });

          }
        });


         $(document).on('click', '#star5', function() {
            document.getElementById("rate_help").style.visibility = "hidden";
            document.getElementById("rate_error").style.display = "none"; 
         });

         $(document).on('click', '#star4', function() {
            document.getElementById("rate_help").style.visibility = "hidden";
            document.getElementById("rate_error").style.display = "none"; 
         });

         $(document).on('click', '#star3', function() {
            document.getElementById("rate_help").style.visibility = "hidden";
            document.getElementById("rate_error").style.display = "none"; 
         });

         $(document).on('click', '#star2', function() {
            document.getElementById("rate_help").style.visibility = "hidden";
            document.getElementById("rate_error").style.display = "none"; 
         });

         $(document).on('click', '#star1', function() {
            document.getElementById("rate_help").style.visibility = "hidden";
            document.getElementById("rate_error").style.display = "none"; 
         });

         $(document).on('click', '#history', function() {
              $.ajax({url: ROOT_PATH + 'Rate/r.php?route=history', type: 'post', data:{}}).then(function(resp) {
                var data = JSON.parse(resp);
                if (data.current.length > 0) {
                  $("#rated_value").html(data.current[0]["rate_value"] + "<small>/5</small>");
                  var star = $(".ustar");
                  var star_len = star.length;
                  for (var star_index = 0; star_index < data.current[0]["rate_value"]; star_index++) {
                    star.eq(star_index).css("color", "orange");
                  }
                }
                var history = data.history;
                var history_len = history.length; 

                if(history_len == 0) {
                  var t = '';
                    t += '<div class="cmt_div" style="display:flex;justify-content:center;align-items:center;height:30vh;">'; 
                    t += '<span style="font-size:18px;font-weight:bold;color:red;">No Ratings Available Yet!</span>'; 
                    t += '</div>';
                  $(".comment-div").html(t);
                }else{
                  var t = '';
                  for (var i = 0; i < history_len; i++) {
                    t += '<div style="border-bottom:1px solid #b3b3b3;position:relative;">'; 
                    t += '<span style="font-weight:bold;float:left;">' + history[i]["rate_value"] + '<small style="font-weight:normal;">/5</small></span>';
                    t += '<br />';
                    t += '<div>';
                    t += '<div style="background-color: #f8f9fa;padding: 10px 0px 10px 0px;text-align:justify;"> &nbsp;&nbsp;' + history[i]['rate_cmt'] + '</div>';
                    t += '<small class="date_rate" style="font-style: italic;">' + history[i]["dt"] + '</small>';
                    t += ' <span style="position:absolute;right:0px;top:0px;"><i class="fas fa-clock"></i> ' + history[i].ago + ' ago</span>';
                    t += '</div>';
                    t += '</div>';
                  }
                  $(".comment-div").html(t);
                }
              });
         });
        
  });

          document.getElementById("rate_cmt").oninput = function () {
              document.getElementById("rate_cmt").style.borderBottom = "1px solid #d2d2d2";
              document.getElementById("comment_help").style.display = "none";
          };