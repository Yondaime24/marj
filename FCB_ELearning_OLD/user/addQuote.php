<?php 
  use classes\auth;
  use classes\access;

  require_once '../___autoload.php';
  $auth = new auth();
  $auth::isLogin();
  $ac = new access();

?>
<!DOCTYPE html>
<html>
<head>
	  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="apple-touch-icon" sizes="76x76" href="../assets/images/feed-logo.png">
	  <link rel="icon" type="image/png" href="../assets/images/feed-logo.png">
	  <title>F.E.E.D. | Add Motivational Tips</title>

    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap3.min.css">
	  <link rel="stylesheet" type="text/css" href="../assets/css/editabout.css">
	  <link rel="stylesheet" href="../assets/css/all.min.css" />
	  <link rel="stylesheet" type="text/css" href="../assets/sweetalert/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../assets/css/loading_gif.css" />

    <script src="<?php print ROOT_PATH; ?>config/config.js"></script>

    <style>
        .red::placeholder{
            color:red;
            opacity: .5;
            font-style:italic;
        }
    </style>

</head>
<body>
<?php if ($ac->check()): ?>
  <main>
    <section class="sec-left">
    <a style="margin-left:5px;" href="../index.php" class="btn btn-success" title="Return to Home"><i class="fas fa-home"></i></a>
    </section>
    <section class="sec-mid">
      <div class="editor">
      <form method="post" id="add_motivational_tips">
          <div style="display:flex;">
            <div style="flex:1 1 0;padding:0px 10px 0px 10px;">
                <input type="hidden" name="quote_id" id="quote_id">
                <input type="text" id="title" name="title" class="form-control" placeholder="Please Add Title">
            </div>
            <div style="flex:1 1 0;padding:0px 10px 10px 10px;">
                <button type="submit" id="save" style="float:right;" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                <button id="view_motivational_tips" style="float:right;margin-right:5px;" class="btn btn-info"><i class="fa fa-eye"></i>View All Motivational Tips</button>
            </div>
          </div>
          <textarea id="editor1"></textarea>
      </form>
      </div>
    </section>
    <section class="sec-right">
    </section>
  </main>

<?php else: ?>
    Unauthorized Access!
<?php endif; ?>

<div class="modal fade" id="loaderModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <div class="loading_bar">
            <img src="../assets/images/loader.gif" width="50px">
          </div>
        </div>
          <div class="footer">
            <span  style="margin-top: 20px;">Loading...</span>
          </div>
      </div>
    </div>
</div>

<div id="modal-motivational-tips">
  <div style="padding:10px;overflow:auto;height:500px;">
  <div style="display:flex;margin-bottom:10px;">
        <button class="btn btn-success btn-sm" id="search_btn"><i class="fa fa-search"></i></button>
        <input type="text" id="searchbar" autocomplete="off" class="form-control" style="width:250px;" placeholder="Search...">
  </div>
    <table class="table table-bordered">
      <thead style="background-color:#eee;">
        <th scope="col" class="text-center">Date Added</th>
        <th scope="col" class="text-center">Title</th>
        <th scope="col" class="text-center">Status</th>
        <th scope="col" class="text-center">Option</th>
      </thead>
        <tbody class="motivational_tips_content" style="position:relative;">
         
        </tbody>
    </table>
  </div>
</div>

	<script src="<?php print ROOT_PATH; ?>assets/js/jquery.min.js"></script>
	<script src="<?php print ROOT_PATH; ?>assets/ckeditor/ckeditor.js"></script>
  <script src="<?php print ROOT_PATH; ?>assets/js/editor.js"></script>
	<script src="<?php print ROOT_PATH; ?>assets/sweetalert/dist/sweetalert2.min.js"></script>
  <script src="<?php print ROOT_PATH; ?>assets/js/bootstrap3.min.js"></script>
  <script src="<?php print ROOT_PATH; ?>assets/cbox/cbox.js"></script>

  <script>
     function CKupdate(){
          for(instance in CKEDITOR.instances){
              CKEDITOR.instances[instance].updateElement();
              CKEDITOR.instances['editor1'].setData('')
          }
      }

        //CBOX MODAL
        var motivationaltips = new cbox("#modal-motivational-tips");
        motivationaltips.title = "Motivational Tips";
        motivationaltips.logo = '<i class="fas fa-lightbulb"></i>';
        motivationaltips.height = 290;
        motivationaltips.width = 750;
        motivationaltips.index = 1;
        motivationaltips.init();

       //INSERT DATA TO DATABASE
       $("#save").on("click", function(e) {
            e.preventDefault();
            var quote_id = $("#quote_id");
            var title = $("#title");
            var editor1 = CKEDITOR.instances['editor1'].getData();
            if (title.val().trim() == "") {
                title.css('border', '1px solid red');
                title.attr('placeholder', 'Title Cannot Be Empty!')
                title.addClass('red');
                return;
            } else {
                title.css('border', '1px solid green');
                title.attr('placeholder', 'Please Add Title')
                title.removeClass('red');
            }
            if (editor1 == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Content cannot be empty.'
                })
                return;
            }else{
                // not empty
            }
            var data = new FormData();
            data.append("quote_id", quote_id.val());
            data.append("title", title.val());
            data.append('editor1', editor1);
            var xml = new XMLHttpRequest();
            xml.onload = function() {
            var resp = JSON.parse(xml.responseText);
              if (resp.ok == 1) {
              Swal.fire({
                  heightAuto: false,
                  icon: 'success',
                  title: 'Success!',
                  text: 'Data has been successfully saved to motivational tips!'
              });
              } else if (resp.ok == 0) {
                  title.css('border', '1px solid red');
                  Swal.fire({
                  heightAuto: false,
                  icon: 'error',
                  title: 'Oops!',
                  text: 'Title Already Exist!'
                  });
              }else{
                  $('#loaderModal').modal('hide');
                  title.css('border', '1px solid lightgray');
                  title.val('');
                  title.removeClass('red');
                  title.attr('placeholder', 'Please add title')
                  CKupdate();
              }
            }
            xml.open("POST", ROOT_PATH + "user/route.php?r=saveTips");
            xml.send(data);
        });

        //DISPLAY ON CBOX
        $("#view_motivational_tips").on("click", function(e) {
          e.preventDefault();
          motivationaltips.show();
          $.ajax({url: ROOT_PATH + 'user/route.php?r=getAllMotivationalTips', type: 'post', data:{}}).then(function(resp) {
            var motivationaltips = JSON.parse(resp);
            var motivationaltips_len = motivationaltips.length; 
            if (motivationaltips_len == 0){
                        var t = '';
                            t += '<div style="display:flex;justify-content:center;align-items:center;height:50vh;position:absolute;width:100%;">'; 
                            t += '<h4 style="color:red;font-weight:bold;">No Added Motivational Tips Yet!</h4>'; 
                            t += '</div>';
                        $(".motivational_tips_content").html(t);  
                    }else{
                        var t = '';
                        for (var i = 0; i < motivationaltips_len; i++) {

                            t += '<tr>';
                            t += '<td class="text-center" width="190">'+ motivationaltips[i]["dt"] +'</td>';
                            t += '<td class="text-center">'+ motivationaltips[i]["title"] +'</td>';

                            if(motivationaltips[i]["status"]=="Not Shown"){
                            t += '<td class="text-center" width="120" style="color:red;">'+ motivationaltips[i]["status"] +'</td>';
                            }else{
                            t += '<td class="text-center" width="120" style="color:#2ecc71;">'+ motivationaltips[i]["status"] +'</td>'; 
                            }

                            t += '<td class="text-center" width="100">';
                            t += '<a href="editQuote.php?quote_id='+ motivationaltips[i]["quote_id"] +'" class="btn btn-sm btn-primary" style="width:100%; margin-bottom:5px;"><i class="fas fa-edit"></i> Edit</a>';
                            
                            if(motivationaltips[i]["status"]=="Not Shown"){
                                t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-info display" index="' + motivationaltips[i]["quote_id"] + '"  rel="' + motivationaltips[i]["quote_id"] + '"><i class="fa fa-eye" style="color:white;"></i> Display</button>';
                            }else{
                                t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-warning undisplay" index="' + motivationaltips[i]["quote_id"] + '"  rel="' + motivationaltips[i]["quote_id"] + '"><i class="fa fa-eye" style="color:white;"></i> Undisplay</button>';
                            }
                            
                            t += '<button style="width:100%;" class="btn btn-sm btn-danger trash" index="' + motivationaltips[i]["quote_id"] + '"  rel="' + motivationaltips[i]["quote_id"] + '"><i class="fa fa-trash" style="color:white;"></i> Delete</button>';
                            t += '</td>';
                            t += '</tr>';

                        }
                        $(".motivational_tips_content").html(t);
                    }
              });
          });

          //SEARCH DISPLAY ON CBOX
        $("#searchbar").on("keyup", function(evt) {
            if(evt.keyCode == 13) {
                load_grid($("#searchbar").val(), true);
            }
        });
        $('#search_btn').on('click', function() {
            load_grid($("#searchbar").val(), true);
        });
          //LOAD SEARCH AND DISPLAY
          function load_grid(search = "", load = false) {
            if (load) {
                $(".motivational_tips_content").html("Searching...");
            }
            $.ajax({
                url: ROOT_PATH + "user/route.php?r=getSearchTips",
                type: "post",
                data: {search: search}
            }).then(function(resp) {
                var global_data = JSON.parse(resp);
                var len = global_data.length;
                var t = "";
                if(len == 0){
                 $(".motivational_tips_content").html("No Data Found!");
                }else{

                for (var i = 0; i < len; i++) {

                    t += '<tr>';
                    t += '<td class="text-center" width="190">' + global_data[i]["dt"] + '</td>';
                    t += '<td class="text-center">' + global_data[i].title + '</td>';

                    if(global_data[i].status=="Not Shown"){
                    t += '<td class="text-center" width="120" style="color:red;">' + global_data[i].status + '</td>';
                    }else{
                    t += '<td class="text-center" width="120" style="color:#2ecc71;">' + global_data[i].status + '</td>'; 
                    }

                    t += '<td class="text-center" width="100">';
                    t += '<a href="editQuote.php?quote_id='+ global_data[i].quote_id +'" class="btn btn-sm btn-primary" style="width:100%; margin-bottom:5px;"><i class="fas fa-edit"></i> Edit</a>';
                            
                    if(global_data[i].status=="Not Shown"){
                        t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-info display" index="' + global_data[i].quote_id + '"  rel="' + global_data[i].quote_id + '"><i class="fa fa-eye" style="color:white;"></i> Display</button>';
                    }else{
                        t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-warning undisplay" index="' + global_data[i].quote_id + '"  rel="' + global_data[i].quote_id + '"><i class="fa fa-eye" style="color:white;"></i> Undisplay</button>';
                    }
                            
                    t += '<button style="width:100%;" class="btn btn-sm btn-danger trash" index="' + global_data[i].quote_id + '"  rel="' + global_data[i].quote_id + '"><i class="fa fa-trash" style="color:white;"></i> Delete</button>';
                    t += '</td>';
                    t += '</tr>';
                    
                }
                $(".motivational_tips_content").html(t);
            }
            }, function() {
                alert("Something went wrong!");
            });
        }
           //DELETE
     $('.motivational_tips_content').on("click", ".trash", function(evt) {
      var i = $(this).attr("index");
        Swal.fire({
                heightAuto: false,
                title: 'Are you sure?',
                text: 'You wont be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                    url: ROOT_PATH + "user/route.php?r=deleteMotivationalTips",
                    type: "post",
                    data: {id: i},
                     beforeSend:function(){
                        $('#loaderModal').modal('show');
                    },
                    success: function(data) {
                         $('#loaderModal').modal('hide');
                         load_grid("", false);
                        Swal.fire({
                            heightAuto: false,
                            icon: 'success',
                            title: 'Success!',
                            text: 'Content Successfully Deleted!'
                        });
                    }
                  });
                }
              });
      evt.stopPropagation();
    });
       //UPDATED TO DISPLAYED
       $('.motivational_tips_content').on("click", ".display", function(evt) {
      var i = $(this).attr("index");
        Swal.fire({
                heightAuto: false,
                title: 'Are you sure?',
                text: 'Previous content will be undisplayed!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, display it!'
            }).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                    url: ROOT_PATH + "user/route.php?r=displayMotivationalTips",
                    type: "post",
                    data: {id: i},
                     beforeSend:function(){
                        $('#loaderModal').modal('show');
                    },
                    success: function(data) {
                         $('#loaderModal').modal('hide');
                         load_grid("", false);
                        Swal.fire({
                            heightAuto: false,
                            icon: 'success',
                            title: 'Success!',
                            text: 'Content Successfully Displayed!'
                        });
                    }
                  });
                }
              });
      evt.stopPropagation();
    });
        //UPDATED TO NOT SHOWN
    $('.motivational_tips_content').on("click", ".undisplay", function(evt) {
      var i = $(this).attr("index");
        Swal.fire({
                heightAuto: false,
                title: 'Are you sure?',
                text: 'Content will be undisplayed!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, undisplay it!'
            }).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                    url: ROOT_PATH + "user/route.php?r=undisplayMotivationalTips",
                    type: "post",
                    data: {id: i},
                     beforeSend:function(){
                        $('#loaderModal').modal('show');
                    },
                    success: function(data) {
                         $('#loaderModal').modal('hide');
                         load_grid("", false);
                        Swal.fire({
                            heightAuto: false,
                            icon: 'success',
                            title: 'Success!',
                            text: 'Content Successfully Undisplayed!'
                        });
                    }
                  });
                }
              });
      evt.stopPropagation();
    });



  </script>
  
</body>
</html>