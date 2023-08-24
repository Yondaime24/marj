  $(document).ready(function(){
       fetchUploadedImage(); 

              var userModal = new cbox("#userModal");
              userModal.init();

    //DISPLAY MODAL
    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('#action').val("Add");
        $('#operation').val("Add");
        $('#user_uploaded_image').html('');
        document.getElementById("image_title").style.border = "1px solid lightgray";
        document.getElementById("help-text").style.display = "none";
        userModal.title = "Upload Image"
        userModal.backgroundcolor_header = "#48b02b";
        userModal.logo = '<i class="fa fa-plus"></i>';
        userModal.update();
        userModal.show();
         // $("#add_button").load("../index.php");
    });

    //INSERT
        $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
        var imgTitle = $('#image_title').val();
        var imgContent = $('#carousel_image').val();
        var extension = $('#carousel_image').val().split('.').pop().toLowerCase();
        if(extension != '')
        {
            if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
            {
                // Swal.fire({
                //     icon: 'error',
                //     title: 'Oops...',
                //     text: 'Only JPG, JPEG, PNG & GIF files are allowed.'
                // })
                $('#carousel_image').val('');
                $('#fileuploadurl').val('Only JPG, JPEG, PNG & GIF files are allowed.');
                document.getElementById("fileuploadurl").style.color = "red";
                document.getElementById("action").style.display = "none";
                return false;
            }
        }   
        // if(imgContent != '' && imgTitle != '')
        // {
            $.ajax({
                url:"../classes/carousel_action.php",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                 beforeSend:function(){
                   $('#loaderModal').modal('show');
                   userModal.close();
                 },
                success:function(data)
                {
                    // alert(data);
                    fetchUploadedImage(); 
                    $('#loaderModal').modal('hide');
                    $('#user_form')[0].reset();
                    // $('#userModal').modal('hide');
                    userModal.close();
                    document.getElementById("action").style.display = "none";
                     Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Image successfully uploaded!',
                            showConfirmButton: false,
                            timer: 1700
                        })
                }
            });
        // }
        // else
        // {
        //     // Swal.fire({
        //     //         icon: 'error',
        //     //         title: 'Oops...',
        //     //         text: 'All fields required!'
        //     //     })
        //     document.getElementById("image_title").style.border = "1px solid red";
        //     document.getElementById("help-text").style.display = "block";
        // }
    });

      //VIEW
        function fetchUploadedImage()
        {
            var action = "Load";
            $.ajax({
                url: "../classes/carousel_action.php",
                method: "POST",
                data: {
                    operation: action
                },
                beforeSend:function(){
                   $("#result").html("<div class='loader_t_float'></div>");
                 },
                success: function(data) {
                    $('#result').html(data);
                    $('#loaderModal').modal('hide');
                    let len = $('.uploaded').length;
                    if (len == 0) {
                      $('#result').prepend('<div class="uploaded" style="color: red; font-size: 18px;">\
                        <strong>No Image Uploaded Yet!</strong>\
                      </div>');
                    }
                    let len2 = $('.displayed').length;
                    if (len2 == 0) {
                      $('#result').prepend('<div class="displayed" style="color: red; font-size: 18px;">\
                        <strong>No Image Displayed Yet!</strong>\
                      </div>');
                    }
                }
            });
        }

        //DELETE
        $(document).on('click', '.delete_anchor', function() {
            var id = $(this).attr("id"); 
            Swal.fire({
                title: 'Are you sure?',
                text: 'You wont be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {

                var action = "Delete"; 
                $.ajax({
                    url: "../classes/carousel_action.php",
                    method: "POST",
                    data: {
                        carousel_id: id,
                        operation: action
                    },
                     beforeSend:function(){
                        $('#loaderModal').modal('show');
                    },
                    success: function(data) {
                        fetchUploadedImage();
                         $('#loaderModal').modal('hide');
                        // alert(data);
                        Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                        )
                    }
                })
                   
                }
            })
        });

      //UPDATE STATUS TO DISPLAY
      $(document).on('click', '.display_anchor', function() {
            var id = $(this).attr("id"); 
            Swal.fire({
                title: 'Are you sure?',
                text: 'Image will be displayed on users homepage!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, display it!'
            }).then((result) => {
                if (result.isConfirmed) {

                var action = "Update"; 
                $.ajax({
                    url: "../classes/carousel_action.php",
                    method: "POST",
                    data: {
                        carousel_id: id,
                        operation: action
                    },
                    beforeSend:function(){
                        $('#loaderModal').modal('show');
                    },
                    success: function(data) {
                        fetchUploadedImage();
                        $('#loaderModal').modal('hide');
                        // alert(data);
                        Swal.fire(
                        'Success!',
                        'Image has been displayed.',
                        'success'
                        )
                    }
                })
                   
                }
            })
        });

      //UPDATE STATUS TO NOT DISPLAY
      $(document).on('click', '.remove', function() {
            var id = $(this).attr("id"); 
            Swal.fire({
                title: 'Are you sure?',
                text: 'Image will not be displayed on users homepage!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, do not display!'
            }).then((result) => {
                if (result.isConfirmed) {

                var action = "Update2"; 
                $.ajax({
                    url: "../classes/carousel_action.php",
                    method: "POST",
                    data: {
                        carousel_id: id,
                        operation: action
                    },
                    beforeSend:function(){
                        $('#loaderModal').modal('show');
                    },
                    success: function(data) {
                        fetchUploadedImage();
                        $('#loaderModal').modal('hide');
                        // alert(data);
                        Swal.fire(
                        'Success!',
                        'Image has been not displayed.',
                        'success'
                        )
                    }
                })
                   
                }
            })
        });


        $("#image_title").on('input', function(){
            if($(this).val().length>100){
                $("#help-text").show();
                $("#action").hide();
                $("#help-text").text("Max length should not exceed 100 characters!");
                $("#help-text").css("font-style", "italic");
            }else{
                $("#help-text").hide();
                $("#help-text").text("Please enter image title!");
                $("#help-text").css("font-style", "italic");
                $("#action").show();
            }
        });


 });

    document.getElementById("carousel_image").onchange = function () {
    document.getElementById("fileuploadurl").value = this.value.replace(/C:\\fakepath\\/i, '');
    document.getElementById("fileuploadurl").style.color = "black";
    document.getElementById("action").style.display = "block";

            var fileSize = this.files[0];
            var sizeLimit = 20000000;

            if (fileSize.size > sizeLimit){
                $('#img_name').val('');
                $('#fileuploadurl').val('ERROR: Image size exceeds 20MB!');
                document.getElementById("fileuploadurl").style.color = "red";
                document.getElementById("action").style.display = "none";
                return false;
            }

  };

