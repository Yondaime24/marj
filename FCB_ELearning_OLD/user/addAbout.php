<?php 
  use classes\auth;
  use classes\access;
  require_once "../___autoload.php";
  auth::isLogin();
  $ac = new access();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php print ROOT_PATH; ?>assets/images/feed-logo.png">
	<link rel="icon" type="image/png" href="<?php print ROOT_PATH; ?>assets/images/feed-logo.png">
	<title>F.E.E.D. | Add Daily Reads</title>

    <link rel="stylesheet" type="text/css" href="<?php print ROOT_PATH; ?>assets/css/bootstrap3.min.css">
	<link rel="stylesheet" type="text/css" href="<?php print ROOT_PATH; ?>assets/css/editabout.css">
	<link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/all.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php print ROOT_PATH; ?>assets/sweetalert/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?php print ROOT_PATH; ?>assets/css/loading_gif.css" />

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
      <form method="post" id="add_daily_reads">
          <div style="display:flex;">
            <div style="flex:1 1 0;padding:0px 10px 0px 10px;">
                <input type="hidden" name="about_id" id="about_id" />
                <input type="text" id="title" name="title" class="form-control" placeholder="Please Add Title">
            </div>
            <div style="flex:1 1 0;padding:0px 10px 10px 10px;">
                <!-- <input type="submit" class="save_btn" id="save" name="save" value="Save"> -->
                <button type="submit" id="save" style="float:right;" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                <button id="view_daily_reads" style="float:right;margin-right:5px;" class="btn btn-info"><i class="fa fa-eye"></i>View All Daily Reads</button>
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
            <img src="<?php print ROOT_PATH; ?>assets/images/loader.gif" width="50px">
          </div>
        </div>
          <div class="footer">
            <span  style="margin-top: 20px;">Loading...</span>
          </div>
      </div>
    </div>
</div>

<div id="modal-daily-reads">
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
        <tbody class="daily_reads_content" style="position:relative;">
         
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
        var dailyReads = new cbox("#modal-daily-reads");
        dailyReads.title = "Daily Reads";
        dailyReads.logo = '<i class="fa fa-book-open"></i>';
        dailyReads.height = 290;
        dailyReads.width = 750;
        dailyReads.index = 1;
        dailyReads.init();

        //INSERT DATA TO DATABASE
        $("#save").on("click", function(e) {
            e.preventDefault();
            var about_id = $("#about_id");
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
            data.append("about_id", about_id.val());
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
                    text: 'Data has been successfully saved to daily reads!'
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
            xml.open("POST", ROOT_PATH + "user/route.php?r=Save");
            xml.send(data);
        });

        //DISPLAY ON CBOX
        $("#view_daily_reads").on("click", function(e) {
            e.preventDefault();
            dailyReads.show();

            $.ajax({url: ROOT_PATH + 'user/route.php?r=getAllDailyReads', type: 'post', data:{}}).then(function(resp) {
                var dailyReads = JSON.parse(resp);
                var dailyReads_len = dailyReads.length; 
                    if (dailyReads_len == 0){
                        var t = '';
                            t += '<div style="display:flex;justify-content:center;align-items:center;height:50vh;position:absolute;width:100%;">'; 
                            t += '<h4 style="color:red;font-weight:bold;">No Added Daily Reads Yet!</h4>'; 
                            t += '</div>';
                        $(".daily_reads_content").html(t);  
                    }else{
                        var t = '';
                        for (var i = 0; i < dailyReads_len; i++) {

                            t += '<tr>';
                            t += '<td class="text-center" width="190">'+ dailyReads[i]["dt"] +'</td>';
                            t += '<td class="text-center">'+ dailyReads[i]["title"] +'</td>';

                            if(dailyReads[i]["status"]=="Not Shown"){
                            t += '<td class="text-center" width="120" style="color:red;">'+ dailyReads[i]["status"] +'</td>';
                            }else{
                            t += '<td class="text-center" width="120" style="color:#2ecc71;">'+ dailyReads[i]["status"] +'</td>'; 
                            }

                            t += '<td class="text-center" width="100">';
                            t += '<a href="editAbout.php?about_id='+ dailyReads[i]["about_id"] +'" class="btn btn-sm btn-primary" style="width:100%; margin-bottom:5px;"><i class="fas fa-edit"></i> Edit</a>';
                            
                            if(dailyReads[i]["status"]=="Not Shown"){
                                t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-info display" index="' + dailyReads[i]["about_id"] + '"  rel="' + dailyReads[i]["about_id"] + '"><i class="fa fa-eye" style="color:white;"></i> Display</button>';
                            }else{
                                t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-warning undisplay" index="' + dailyReads[i]["about_id"] + '"  rel="' + dailyReads[i]["about_id"] + '"><i class="fa fa-eye" style="color:white;"></i> Undisplay</button>';
                            }
                            
                            t += '<button style="width:100%;" class="btn btn-sm btn-danger trash" index="' + dailyReads[i]["about_id"] + '"  rel="' + dailyReads[i]["about_id"] + '"><i class="fa fa-trash" style="color:white;"></i> Delete</button>';
                            t += '</td>';
                            t += '</tr>';

                        }
                        $(".daily_reads_content").html(t);
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
                $(".daily_reads_content").html("Searching...");
            }
            $.ajax({
                url: ROOT_PATH + "user/route.php?r=getSearchReads",
                type: "post",
                data: {search: search}
            }).then(function(resp) {
                var global_data = JSON.parse(resp);
                var len = global_data.length;
                var t = "";
                if(len == 0){
                 $(".daily_reads_content").html("No Data Found!");
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
                    t += '<a href="editAbout.php?about_id='+ global_data[i].about_id +'" class="btn btn-sm btn-primary" style="width:100%; margin-bottom:5px;"><i class="fas fa-edit"></i> Edit</a>';
                            
                    if(global_data[i].status=="Not Shown"){
                        t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-info display" index="' + global_data[i].about_id + '"  rel="' + global_data[i].about_id + '"><i class="fa fa-eye" style="color:white;"></i> Display</button>';
                    }else{
                        t += '<button style="width:100%;margin-bottom:5px;" class="btn btn-sm btn-warning undisplay" index="' + global_data[i].about_id + '"  rel="' + global_data[i].about_id + '"><i class="fa fa-eye" style="color:white;"></i> Undisplay</button>';
                    }
                            
                    t += '<button style="width:100%;" class="btn btn-sm btn-danger trash" index="' + global_data[i].about_id + '"  rel="' + global_data[i].about_id + '"><i class="fa fa-trash" style="color:white;"></i> Delete</button>';
                    t += '</td>';
                    t += '</tr>';
                    
                }
                $(".daily_reads_content").html(t);
            }
            }, function() {
                alert("Something went wrong!");
            });
        }
    //DELETE
     $('.daily_reads_content').on("click", ".trash", function(evt) {
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
                    url: ROOT_PATH + "user/route.php?r=deleteDailyReads",
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
    $('.daily_reads_content').on("click", ".display", function(evt) {
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
                    url: ROOT_PATH + "user/route.php?r=displayDailyReads",
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
    $('.daily_reads_content').on("click", ".undisplay", function(evt) {
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
                    url: ROOT_PATH + "user/route.php?r=undisplayDailyReads",
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