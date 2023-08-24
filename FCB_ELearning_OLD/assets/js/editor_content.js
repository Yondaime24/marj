    $(document).ready(function(){

      //UPDATE
      $(document).on('submit', '#edit_about', function(e){
            e.preventDefault();
            var id = $('#about_id').val();
            var title = $('#title').val();
            var ckEditor = $('#editor1').val();
            var action = "Save";

            if (title == "") {
                $('#title').css('border', '1px solid red');
                $('#title').attr('placeholder', 'Title Cannot Be Empty!')
                $('#title').addClass('red');
            }else if (ckEditor == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Content cannot be empty.'
                })
            }else{
                $('#title').css('border', '1px solid lightgray');
            $.ajax({
                    url: "../classes/editor_ajax.php",
                    method: "POST",
                    data: {
                        editor1: ckEditor,
                        about_id: id,
                        title: title,
                        save: action
                    }, 
                    beforeSend:function(){
                     $('#loaderModal').modal('show');
                    },
                    success: function(data) {
                        $('#loaderModal').modal('hide');
                        Swal.fire({
                            heightAuto: false,
                            icon: 'success',
                            title: 'Success!',
                            text: 'Content has been successfully updated!'
                        });
                    }
                });
            }

          });

      
    });