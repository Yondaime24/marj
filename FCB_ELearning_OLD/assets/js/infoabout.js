CKEDITOR.replace('feedabout_info', {
    filebrowserImageBrowseUrl: "assets/imageFolder/",
    height: 300
  });
  CKEDITOR.replace('fcbabout_info', {
    filebrowserImageBrowseUrl: "assets/imageFolder/",
    height: 300
  });
  CKEDITOR.on('dialogDefinition', function(e) {
          dialogName = e.data.name;
          dialogDefinition = e.data.definition;
          console.log(dialogName);
          if (dialogName == 'image') {
              dialogDefinition.removeContents('Link')
              dialogDefinition.removeContents('advanced')
              var tabContent = tabContent = dialogDefinition.getContents('info');
          }
  });
  $(document).ready(function(){
    var addQuote = new cbox("#modal-view-quote");
    addQuote.title = "Add Content ";
    addQuote.logo = '<i class="fa fa-plus"></i>';
    addQuote.height = 290;
    addQuote.width = 500;
    addQuote.init();
    $('#add_content2').click(function(){
      addQuote.show();
    });
    $('#view-contents').click(function(){
      addQuote.show();
    });
    $('.remove_btn').click(function(){
      addQuote.close();
    });
    var about_us = new cbox("#aboutus_modal");
    about_us.title = "About Us";
    about_us.logo = '<i class="fas fa-circle-info"></i>';
    about_us.height = 150;
    about_us.width = 300;
    about_us.init();
    $('#about_us').click(function(){
      about_us.show();
    });


    $('.aboutfeed').click(function(){
      var id = $(this).attr("id");
      about_us.close();
      $('#info_id').val(id);
      
    });
    $('.aboutfcb').click(function(){
      var id = $(this).attr("id");
      about_us.close();
      $('#info_id2').val(id);
    });
    $('.viewinfo_btn').on('click', function() {
      about_us.close();
    });

       //UPDATE
      $(document).on('submit', '#1', function(e){
        e.preventDefault();
        var info_id = $(this).attr("id"); 
        var feedabout_info = $('#feedabout_info').val();
        var save = "Save";
        $.ajax({
                url: "classes/feed_info.php",
                method: "POST",
                data: {
                    feedabout_info: feedabout_info,
                    info_id: info_id,
                    save_about: save
                }, 
                beforeSend:function(){
                 $('#loaderModal').modal('show');
                },
                success: function(data) {
                    $('#loaderModal').modal('hide');
                    Swal.fire({
                          icon: 'success',
                          title: 'Success!',
                          text: 'Content has been saved successfully',
                          showConfirmButton: false,
                          timer: 1500
                      })
                     setTimeout("location.href = 'index.php';",1500);
                }
            });
      });

       //UPDATE
       $(document).on('submit', '#2', function(e){
        e.preventDefault();
        var info_id2 = $(this).attr("id"); 
        var fcbabout_info = $('#fcbabout_info').val();
        var save = "Save";
        $.ajax({
                url: "classes/feed_info.php",
                method: "POST",
                data: {
                    fcbabout_info: fcbabout_info,
                    info_id2: info_id2,
                    save_about2: save
                }, 
                beforeSend:function(){
                 $('#loaderModal').modal('show');
                },
                success: function(data) {
                    $('#loaderModal').modal('hide');
                    Swal.fire({
                          icon: 'success',
                          title: 'Success!',
                          text: 'Content has been saved successfully',
                          showConfirmButton: false,
                          timer: 1500
                      })
                     setTimeout("location.href = 'index.php';",1500);
                }
            });
      });
 

  });