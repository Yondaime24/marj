	window.addEventListener('load', function() {
        document.querySelector('.top-loader').style.display = 'none';
      });
      $("#menu-toggle").click(function(e){
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
      });
    $(document).ready(function(){
            fetchFolder();
              var folderModal = new cbox("#folderModal");
              var imgModal = new cbox("#imgModal");
              folderModal.init();
              imgModal.init();

		    //DISPLAY FOLDER MODAL
		    $('#add_folder_btn').click(function(){
		        $('#folder_form')[0].reset();
		        $('#action').val("Add");
		        $('#operation').val("Add");
		        document.getElementById("folder_name").style.border = "1px solid lightgray";
		        document.getElementById("help-text").style.display = "none";
                folderModal.title = "Add New Album ";
                folderModal.backgroundcolor_header = "#48b02b";
                folderModal.logo = '<i class="fa fa-plus"></i>';
                folderModal.update();
                folderModal.show();
		    });

        //GET FOLDER ID
        $(document).on('click', '.add', function() {
            var id = $(this).attr("id"); 
            $('#folder_to_img_id').val(id);
            $('#img_action').val("Upload");
            $('#operation2').val("Upload");
            $('#user_uploaded_image').html('');
            document.getElementById("img_action").style.display = "none";
            document.getElementById("b-img").style.display = "block";
            $('#img_title').val('');
            $('#img_desc').val('');
            $('#fileuploadurl').val('');
            $('#img_name').val('');
            imgModal.title = "Add Image ";
            imgModal.backgroundcolor_header = "#48b02b";
            imgModal.logo = '<i class="fa fa-plus"></i>';
            imgModal.update();
            imgModal.show();
        });

        //FETCH FOLDER
        function fetchFolder()
        {
            var action = "Load";
            $.ajax({
                url: "../classes/gallery_action.php",
                method: "POST",
                data: {
                    operation: action
                },
                success: function(data) {
                    $('#folder_result').html(data);
                }
            });
        }

        //FETCH IMAGE
        function fetchImage()
        {
            var id = $('#folder_to_img_id').val();
            var action = "Fetch";
            $.ajax({
                url: "../classes/gallery_action.php",
                method: "POST",
                data: {
                    operation2: action,
                    folder_to_img_id: id
                },
                beforeSend:function(){
                    $("#result").html("<div style='height:100%;width:100%;display:flex;justify-content:center;align-items:center;'><div class='loader_t'></div></div>");
                },
                success: function(data) {
                    $('#result').html(data);
                }
            });
        }

		    //INSERT
    		$(document).on('submit', '#folder_form', function(e){
    			e.preventDefault();
    			var folderName = $('#folder_name').val();
                var folderModal = new cbox("#folderModal");

    			if (folderName != '') {
    				$.ajax({
		                url:"../classes/gallery_action.php",
		                method:'POST',
		                data:new FormData(this),
		                contentType:false,
		                processData:false,
                        beforeSend:function(){
                            $('#folderModal').modal('hide');
                            $('#loaderModal').modal('show');
                        },
		                success:function(data)
		                {
                            fetchFolder();
                            folderModal.close();
                            $('#loaderModal').modal('hide');
		                    $('#folder_form')[0].reset();
		                    $('#folderModal').modal('hide');
		                    $('#action').val("Add");
                            $('#operation').val("Add");
		                     Swal.fire(
                                'Success!',
                                '',
                                'success'
                                )
		                }
		            });
    			}else{
                    document.getElementById("folder_name").style.border = "1px solid red";
		            document.getElementById("help-text").style.display = "block";
		    		}
    		});

    	//UPDATE AND FETCH SINGLE DATA
        $(document).on('click', '.edit', function() {
            var id = $(this).attr("id"); 

            var action = "Select";
            $.ajax({
                url: "../classes/gallery_action.php", 
                method: "POST", 
                data: {
                    folder_id: id,
                    operation: action
                }, 
                dataType: "json",
                beforeSend:function(){
                     $('#loaderModal').modal('show');
                },
                success: function(data) {
                    fetchFolder();
                    folderModal.title = "Update Album Name";
                      folderModal.backgroundcolor_header = "48b02b";
                      folderModal.logo = '<i class="fa fa-edit"></i>';
                      folderModal.update();
                      folderModal.show();
                    $('#loaderModal').modal('hide');
                    $('#operation').val("Update");
                    $('#action').val("Update");
                    $('#folder_id').val(id);
                    $('#folder_name').val(data.folder_name); 
                    document.getElementById("folder_name").style.border = "1px solid lightgray";
                    document.getElementById("help-text").style.display = "none";
                }
            });
        });

       //DELETE
        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id"); 
            Swal.fire({
                title: 'Delete Folder?',
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
                    url: "../classes/gallery_action.php",
                    method: "POST",
                    data: {
                        folder_id: id,
                        operation: action
                    },
                    beforeSend:function(){
                     $('#loaderModal').modal('show');
                    },
                    success: function(data) {
                        fetchFolder();
                        $('#loaderModal').modal('hide');
                        Swal.fire(
                        'Deleted!',
                        'Folder has been deleted.',
                        'success'
                        )
                    }
                })
                   
                }
            })
        });


        //GET IMAGE DETAILS AND UPDATE SINGLE DATA (EDITING)
        $(document).on('click', '.img_edit', function() {
            var id = $(this).attr("id"); 
            var action = "Select";
            $.ajax({
                url: "../classes/gallery_action.php", 
                method: "POST", 
                data: {
                    folder_to_img_id: id,
                    operation2: action
                }, 
                dataType: "json",
                beforeSend:function(){
                     $('#loaderModal').modal('show');
                },
                success: function(data) {
                    $('#loaderModal').modal('hide');
                    $('#folder_to_img_id').val(data.folder_id);
                    $('#operation2').val("Update");
                    $('#img_action').val("Update");
                    $('#img_title').val(data.img_title); 
                    $('#img_desc').val(data.img_desc);
                    $('#img_id').val(id);
                    $('#user_uploaded_image').html(data.img_name);
                    document.getElementById("img_action").style.display = "block";
                    document.getElementById("b-img").style.display = "none";
                    imgModal.title = "Edit Image Details";
                    imgModal.backgroundcolor_header = "#48b02b";
                    imgModal.logo = '<i class="fa fa-edit"></i>';
                    imgModal.update();
                    imgModal.show();
                   
                }
            });
        });
 
        //UPLOAD IMAGE
        $(document).on('submit', '#img_form', function(event){
        event.preventDefault();
            var imgTitle = $('#img_title').val();
            // var imgName = $('#img_name').val();
            var folderID = $('#folder_to_img_id').val(); 
            var imgAction = $('#img_action').val();
            var extension = $('#img_name').val().split('.').pop().toLowerCase();
        if(extension != '')
        {
            if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
            {

                // Swal.fire({
                //     icon: 'error',
                //     title: 'Oops...',
                //     text: 'Only JPG, JPEG, PNG & GIF files are allowed.'
                // })
                $('#img_name').val('');
                $('#fileuploadurl').val('Only JPG, JPEG, PNG & GIF files are allowed.');
                document.getElementById("fileuploadurl").style.color = "red";
                document.getElementById("img_action").style.display = "none";
                return false;
            }
        }   
        if(folderID != '')
        {
            $.ajax({
                url:"../classes/gallery_action.php",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                beforeSend:function(){
                     imgModal.close();
                     $('#loaderModal').modal('show');
                },
                success:function(data)
                {
                    // alert(data);
                    fetchImage();
                    fetchFolder();
                    $('#loaderModal').modal('hide');
                    $('#img_title').val('');
                    $('#img_desc').val('');
                    $('#fileuploadurl').val('');
                    $('#img_name').val('');
                    imgModal.close();
                    document.getElementById("img_action").style.display = "none";
                     Swal.fire(
                                'Success!',
                                '',
                                'success'
                                )
                     // setTimeout("location.reload(true);",1000);
                }
            });
        }
        else
        {
            Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'All fields required!'
                })
            document.getElementById("image_title").style.border = "1px solid red";
            document.getElementById("help-text").style.display = "block";
        }
    });

       // SHOW IMG
        $(document).on('click', '.a-folder', function() {
        	var id = $(this).attr("id"); 
            
            $("#clear_all").css("display", "block");
            $('.del_all_img').attr('id', id);

            $('#folder_to_img_id').val(id);
        	var action = "View";
            $.ajax({
                url: "../classes/gallery_action.php",
                method: "POST",
                data: {
                    operation2: action,
                    folder_to_img_id: id
                },
                beforeSend:function(){
                     $('#loaderModal').modal('show');
                },
                success: function(data) {
                    $('#result').html(data);
                    $('#loaderModal').modal('hide');
                    let len = $('.uploaded').length;
                    if (len == 0) {
                      $("#clear_all").css("display", "none");
                      $('#result').prepend('<div style="color: red; font-size: 28px; position: absolute; right: 0; left: 0; bottom:0; top:50px;font-weight:bold;">\
                        <strong style="display: flex;justify-content:center;align-items:center;margin-top:200px;"><u>No Image Available Yet!</u></strong>\
                      </div>');
                    }

                        $(".a-folder").click(function(){

                            var navItem = $(".a-folder");

                            for(let i = 0; i <navItem.length; i++) {
                                navItem[i].classList.remove("active");
                            }

                            this.classList.add("active");

                        });
                }
            });

         });

        //DELETE IMAGE
        $(document).on('click', '.img_delete', function() {
            var id = $(this).attr("id"); 
            var folderId = $('#folder_to_img_id').val();
            // alert(id);
            // alert(folderId);
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
                    url: "../classes/gallery_action.php",
                    method: "POST",
                    data: {
                        img_id_popup: id,
                        popup_operation: action,
                        img_folder_id: folderId
                    },
                    beforeSend:function(){
                     $('#loaderModal').modal('show');
                    },
                    success: function(data) {
                        fetchImage()
                        fetchFolder();
                        $('#loaderModal').modal('hide');
                        Swal.fire({
                              icon: 'success',
                              title: 'Deleted!',
                              text: 'Image has been deleted',
                              showConfirmButton: false,
                              timer: 1500
                          })
                        // setTimeout("location.reload(true);",1500);
                    }
                })
                   
                }
            })
        });



        //DELETE IMAGE
        $(document).on('click', '.del_all_img', function() {
            var id = $(this).attr("id"); 
            Swal.fire({
                title: 'Are you sure to delete all images?',
                text: 'This will also delete the album folder!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {

                var action = "Delete"; 
                $.ajax({
                    url: "../classes/gallery_action.php",
                    method: "POST",
                    data: {
                        folder_id: id,
                        del_operation: action,
                    },
                    beforeSend:function(){
                     $('#loaderModal').modal('show');
                    },
                    success: function(data) {
                        fetchImage()
                        fetchFolder();
                        $('#loaderModal').modal('hide');
                        Swal.fire({
                              icon: 'success',
                              title: 'Deleted!',
                              text: 'Image has been deleted',
                              showConfirmButton: false,
                              timer: 1500
                          })
                        // setTimeout("location.reload(true);",1500);
                        $("#clear_all").css("display", "none");
                    }
                })
                   
                }
            })
        });



    	});


        document.getElementById("img_name").onchange = function () {
            document.getElementById("fileuploadurl").value = this.value.replace(/C:\\fakepath\\/i, '');
            document.getElementById("fileuploadurl").style.color = "black";
            document.getElementById("img_action").style.display = "block";

            var fileSize = this.files[0];
            var sizeLimit = 20000000;

            if (fileSize.size > sizeLimit){
                $('#img_name').val('');
                $('#fileuploadurl').val('ERROR: Image size exceeds 20MB!');
                document.getElementById("fileuploadurl").style.color = "red";
                document.getElementById("img_action").style.display = "none";
                return false;
            }

        };