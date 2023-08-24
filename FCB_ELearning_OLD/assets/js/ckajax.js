    
    $(document).ready(function(){
       fetchImage(); 

    //DISPLAY MODAL
    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('.modal-title').text("Add Image");
        $('#action').val("Add");
        $('#operation').val("Add");
        $('#user_uploaded_image').html('');
    });

    //INSERT
        $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
        var imgContent = $('#user_image').val();
        var extension = $('#user_image').val().split('.').pop().toLowerCase();
        if(extension != '')
        {
            if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Only JPG, JPEG, PNG & GIF files are allowed.'
                })
                $('#user_image').val('');
                $('#fileuploadurl').val('Select image to upload');
                document.getElementById("fileuploadurl").style.color = "gray";
                document.getElementById("action").style.display = "none";
                return false;
            }
        }   
        if(imgContent != '')
        {
            $.ajax({
                url:"../../classes/ckimage_insert.php",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                beforeSend:function(){
                   $('#loaderModal').modal('show');
                    $('#userModal').modal('hide');
                 },
                success: function(data) {
                    fetchImage();
                    $('#loaderModal').modal('hide');
                    // alert(data);
                    $('#user_form')[0].reset();
                    $('#userModal').modal('hide');
                    document.getElementById("action").style.display = "none";
                     Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Image successfully uploaded!',
                            showConfirmButton: false,
                            timer: 1800
                        })
                }
            });
        }
        else
        {
            Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please browse for image.'
                })
        }
    });

    //VIEW
        function fetchImage()
        {
            var action = "Load";
            $.ajax({
                url: "../../classes/ckimage_insert.php",
                method: "POST",
                data: {
                    operation: action
                },
                 beforeSend:function(){
                   $('#loaderModal').modal('show');
                 },
                success: function(data) {
                    $('#loaderModal').modal('hide');
                    $('#result').html(data);
                }
            });
        }

        //DELETE
        $(document).on('click', '.delete_btn', function() {
            var user_id = $(this).attr("id"); 
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
                    url: "../../classes/ckimage_insert.php",
                    method: "POST",
                    data: {
                        user_id: user_id,
                        operation: action
                    },
                    beforeSend:function(){
                        $('#loaderModal').modal('show');
                    },
                    success: function(data) {
                        fetchImage();
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


    });

    document.getElementById("user_image").onchange = function () {
    document.getElementById("fileuploadurl").value = this.value.replace(/C:\\fakepath\\/i, '');
    document.getElementById("action").style.display = "block";
    document.getElementById("fileuploadurl").style.color = "black";


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