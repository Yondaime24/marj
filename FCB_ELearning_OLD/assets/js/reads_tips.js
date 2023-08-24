 ////////DAILY READS//////////
 load_dailyreads();
 load_dailyreads_onModal();
 load_motivationaltips();
 load_motivationaltips_onModal();

 $('.left-div-card').on("click", "#view_daily_reads", function() {
   getAllDailyReads();
 });
  $('#modal_daily_reads').on("click", "#view_daily_reads", function() {
   getAllDailyReads();
   $('#modal-about').modal('hide');
 });

 $('.left-div-card').on("click", "#see_more", function() {
   load_dailyreads_onModal();
 });

   $("#searchbar").on("keyup", function(evt) {
       if(evt.keyCode == 13) {
           load_grid($("#searchbar").val(), true);
       }
   });
   $('#search_btn').on('click', function() {
       load_grid($("#searchbar").val(), true);
   });

 function load_dailyreads() {
   $.ajax({url: ROOT_PATH + 'user/indexRoute.php?route=getDisplayedReads', type: 'post', data:{}}).then(function(resp) {
     var dailyReads = JSON.parse(resp);
     var dailyReads_len = dailyReads.length; 

     if(dailyReads_len==0){
       var t = '';
         t += '<div class="content">'; 
         t += '<div class="img-container">'; 
         t += ' <img id="empty_content1" src="'+ ROOT_PATH +'assets/images/feed-logo.png" >'; 
         t += '</div>'; 
         t += '</div>'; 
         t += '<div class="footer">';
         if (admin_access){
         t += '<button data-toggle="modal" data-target="#modal-daily-reads" id="view_daily_reads" style="float:right;margin-right:30px;border-radius:50px;" class="btn-success"><i class="fa fa-eye"></i> View Daily Reads</button>';
         }else{
           t += '<div style="display:flex;justify-content:center;align-items:center;"><strong style="color:red;">Daily Reads Not Yet Available!</strong></div>';
         }
         t += '</div>';
       $(".left-div-card").html(t);
     }else{
         var t = '';
         for (var i = 0; i < dailyReads_len; i++) {
           t += '<div class="content">'; 
           t += ''+ dailyReads[i]["content"] +''; 
           t += '</div>'; 
           t += '<div class="footer">';
           t += ' <a id="see_more" href="#" data-toggle="modal" data-target="#modal-about">See More>></a>';
           t += '</div>';
         $(".left-div-card").html(t);
         }
     }
   });
 }
 function load_dailyreads_onModal() {
   $.ajax({url: ROOT_PATH + 'user/indexRoute.php?route=getDisplayedReads', type: 'post', data:{}}).then(function(resp) {
     var dailyReads = JSON.parse(resp);
     var dailyReads_len = dailyReads.length; 
         var t = '';
         for (var i = 0; i < dailyReads_len; i++) {
           t += '<div class="modal-header" style="position:relative;padding-bottom:0px;">';
           t += '<span style="font-size:18px;font-weight:bold;"> '+ dailyReads[i]["title"] +'</span>';
           if (admin_access){
           t += '<div style="position:absolute;top:0px;width:100%;">';
           t += '<button  data-toggle="modal" data-target="#modal-daily-reads" id="view_daily_reads" style="margin-right:50px;float:right;margin-top:5px;" class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View Daily Reads</button>';
           t += '<a  style="margin-right:5px;float:right;margin-top:5px;" href="'+ROOT_PATH+'user/editAbout.php?about_id='+ dailyReads[i]["about_id"] +'" class="editBtn btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
           t += '</div>';
           }
           t += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
           t += '</div>';
           
           t += '<div class="modal-body">';
           t += ''+ dailyReads[i]["content"] +''; 
           t += '</div>';
          
         $("#modal_daily_reads").html(t);
         }
   });
 }

     function getAllDailyReads() {
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
                       t += '<a href="'+ ROOT_PATH +'user/editAbout.php?about_id='+ dailyReads[i]["about_id"] +'" class="btn btn-sm btn-primary" style="width:100%; margin-bottom:5px;"><i class="fas fa-edit"></i> Edit</a>';
                       
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
     }
     function load_grid(search = "", load = false) {
       load_dailyreads();
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
               t += '<a href="'+ ROOT_PATH +'user/editAbout.php?about_id='+ global_data[i].about_id +'" class="btn btn-sm btn-primary" style="width:100%; margin-bottom:5px;"><i class="fas fa-edit"></i> Edit</a>';
                       
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
                    getNotShownReads();
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
                    getNotShownReads();
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
                    getNotShownReads();
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
////////MOTIVATIONAL TIPS//////////
$('.center-quote-card').on("click", "#view_motivational_tips", function() {
 getAllMotivationalTips();
});
$('#modal_motivational_tips').on("click", "#view_motivational_tips", function() {
  getAllMotivationalTips();
  $('#modal-quote').modal('hide');
});

$("#searchbar2").on("keyup", function(evt) {
 if(evt.keyCode == 13) {
   load_grid2($("#searchbar2").val(), true);
 }
});
$('#search_btn2').on('click', function() {
   load_grid2($("#searchbar2").val(), true);
});
$('.center-quote-card').on("click", "#see_more", function() {
 load_motivationaltips_onModal();
});

function load_motivationaltips() {
   $.ajax({url: ROOT_PATH + 'user/indexRoute.php?route=getDisplayedTips', type: 'post', data:{}}).then(function(resp) {
     var motivationaltips = JSON.parse(resp);
     var motivationaltips_len = motivationaltips.length; 

     if(motivationaltips_len==0){
       var t = '';
         t += '<div class="content">'; 
         t += '<div style="display:flex;width:100%;justify-content:center;align-items:center;margin-top:20px;">'; 
         t += ' <img id="empty_content1" src="'+ ROOT_PATH +'assets/images/feed-logo.png">'; 
         t += '</div>'; 
         t += '</div>'; 
         t += '<div class="content-footer">';
         if (admin_access){
         t += '<button data-toggle="modal" data-target="#modal-motivational-tips" id="view_motivational_tips" style="float:right;margin-right:30px;border-radius:50px;" class="btn-success"><i class="fa fa-eye"></i> View Motivational Tips</button>';
         }else{
           t += '<div style="display:flex;justify-content:center;align-items:center;"><strong style="color:red;">Motivational Tips Not Yet Available!</strong></div>';
         }
         t += '</div>';
       $(".center-quote-card").html(t);
     }else{
         var t = '';
         for (var i = 0; i < motivationaltips_len; i++) {
           t += '<div class="content">'; 
           t += ''+ motivationaltips[i]["content"] +''; 
           t += '</div>'; 
           t += '<div class="content-footer">';
           t += ' <a id="see_more" href="#" data-toggle="modal" data-target="#modal-quote">See More>></a>';
           t += '</div>';
         $(".center-quote-card").html(t);
         }
     }
   });
 }
 function getAllMotivationalTips() {
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
                       t += '<a href="'+ ROOT_PATH +'user/editQuote.php?quote_id='+ motivationaltips[i]["quote_id"] +'" class="btn btn-sm btn-primary" style="width:100%; margin-bottom:5px;"><i class="fas fa-edit"></i> Edit</a>';
                       
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
     }
     function load_grid2(search = "", load = false) {
       load_motivationaltips();
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
               t += '<a href="'+ ROOT_PATH +'user/editQuote.php?quote_id='+ global_data[i].quote_id +'" class="btn btn-sm btn-primary" style="width:100%; margin-bottom:5px;"><i class="fas fa-edit"></i> Edit</a>';
                       
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
   function load_motivationaltips_onModal() {
   $.ajax({url: ROOT_PATH + 'user/indexRoute.php?route=getDisplayedTips', type: 'post', data:{}}).then(function(resp) {
     var motivationalTips = JSON.parse(resp);
     var motivationalTips_len = motivationalTips.length; 
         var t = '';
         for (var i = 0; i < motivationalTips_len; i++) {
           t += '<div class="modal-header" style="position:relative;padding-bottom:0px;">';
           t += '<span style="font-size:18px;font-weight:bold;"> '+ motivationalTips[i]["title"] +'</span>';
           if (admin_access){
           t += '<div style="position:absolute;top:0px;width:100%;">';
           t += '<button data-toggle="modal" data-target="#modal-motivational-tips" id="view_motivational_tips" style="margin-right:50px;float:right;margin-top:5px;" class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View Motivational Tips</button>';
           t += '<a  style="margin-right:5px;float:right;margin-top:5px;" href="'+ROOT_PATH+'user/editQuote.php?quote_id='+ motivationalTips[i]["quote_id"] +'" class="editBtn btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
           t += '</div>';
           }
           t += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
           t += '</div>';
           
           t += '<div class="modal-body">';
           t += ''+ motivationalTips[i]["content"] +''; 
           t += '</div>';
          
         $("#modal_motivational_tips").html(t);
         }
   });
 }
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
                    load_grid2("", false);
                    getNotShownTips();
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
                    load_grid2("", false);
                    getNotShownTips();
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
                    load_grid2("", false);
                    getNotShownTips();
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

$("#dr-a").on("click", function(){
 $("#arch-dr").slideUp();
 $("#arch-mt").slideDown();
 $("#arch-mt").css("display", "block");
 $("#mt-a").css("display", "block");
 $("#dr-a").css("display", "none");
 getNotShownTips();
});
$("#mt-a").on("click", function(){
 $("#arch-mt").slideUp();
 $("#arch-dr").slideDown();
 $("#arch-dr").css("display", "block");
 $("#dr-a").css("display", "block");
 $("#mt-a").css("display", "none");
 getNotShownReads();
});
 getNotShownReads();
 var global_daily_reads = null;
 function getNotShownReads() {
   $.ajax({url: ROOT_PATH + 'user/indexRoute.php?route=geNotDisplayedReads', type: 'post', data:{}}).then(function(resp) {
     var dailyReads = JSON.parse(resp);
     var dailyReads_len = dailyReads.length; 
     global_daily_reads = dailyReads;

     $("#arch-mt").css("display", "none");
     $("#arch-dr").css("display", "block");
     $("#dr-a").css("display", "block");
     $("#mt-a").css("display", "none");

     if(dailyReads_len==0){
       var t = '';
         t += '<div style="height:20vh;display:flex;justify-content:center;align-items:center;">'; 
         t += '<span style="color:red;font-weight:bold;">No Contents Added Yet!</span>'; 
         t += '</div>';
       $("#archive-content").html(t);
     }
     else{
         var t = '';
         for (var i = 0; i < dailyReads_len; i++) {
           t +=  '<li style="position:relative;list-style:none;">'; 
           t +=  '<a  maxlength="2" id="daily_reads_archive" href="#" index="'+ i +'" data-toggle="modal" data-target="#modal-archive">'+ dailyReads[i]["title"] +'</a>'; 
           t +=  '<small style="position:absolute;color:gray;font-style:italic;right:0px;bottom:0px;font-size:10px;">Created On: '+ dailyReads[i]["dt"] +'</small>'; 
           t +=  '</li>'; 
         $("#archive-content").html(t);
         }
     }
   });
 }
 var global_motivational_tips = null;
 function getNotShownTips() {
   $.ajax({url: ROOT_PATH + 'user/indexRoute.php?route=geNotDisplayedTips', type: 'post', data:{}}).then(function(resp) {
     var motivationaltips = JSON.parse(resp);
     var motivationaltips_len = motivationaltips.length; 
     global_motivational_tips = motivationaltips;

     $("#arch-mt").css("display", "block");
     $("#arch-dr").css("display", "none");
     $("#dr-a").css("display", "none");
     $("#mt-a").css("display", "block");

     if(motivationaltips_len==0){
       var t = '';
         t += '<div style="height:20vh;display:flex;justify-content:center;align-items:center;">'; 
         t += '<span style="color:red;font-weight:bold;">No Contents Added Yet!</span>'; 
         t += '</div>';
       $("#archive-content").html(t);
     }
     else{
         var t = '';
         for (var i = 0; i < motivationaltips_len; i++) {
           t +=  '<li style="position:relative;list-style:none;">'; 
           t +=  '<a id="motivational_tips_archive" href="#" index="'+ i +'" data-toggle="modal" data-target="#modal-archive">'+ motivationaltips[i]["title"] +'</a>'; 
           t +=  '<small style="position:absolute;color:gray;font-style:italic;right:0px;bottom:0px;font-size:10px;">Created On: '+ motivationaltips[i]["dt"] +'</small>'; 
           t +=  '</li>'; 
         $("#archive-content").html(t);
         }
     }
   });
 }

 $('#archive-content').on("click", "#daily_reads_archive", function(evt) {
   var id = $(this).attr("index");
   var data = global_daily_reads[id];
   var t = '';
   t += '<div class="modal-header" style="position:relative;padding-bottom:0px;">';
   t += '<span style="font-size:18px;font-weight:bold;"> '+ data.title +'</span>';
   if (admin_access){
     t += '<div style="position:absolute;top:0px;width:100%;">';
           t += '<a  style="margin-right:50px;float:right;margin-top:5px;" href="'+ROOT_PATH+'user/editAbout.php?about_id='+ data.about_id +'" class="editBtn btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
           t += '</div>';
    }
   t += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
   t += '</div>';
           
   t += '<div class="modal-body">';
   t += ''+ data.content +''; 
   t += '</div>';
   $("#modal_archive").html(t);
 });

 $('#archive-content').on("click", "#motivational_tips_archive", function(evt) {
   var id = $(this).attr("index");
   var data2 = global_motivational_tips[id];
   var t = '';
   t += '<div class="modal-header" style="position:relative;padding-bottom:0px;">';
   t += '<span style="font-size:18px;font-weight:bold;"> '+ data2.title +'</span>';
   if (admin_access){
     t += '<div style="position:absolute;top:0px;width:100%;">';
           t += '<a  style="margin-right:50px;float:right;margin-top:5px;" href="'+ROOT_PATH+'user/editQuote.php?quote_id='+ data2.quote_id +'" class="editBtn btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
           t += '</div>';
    }
   t += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
   t += '</div>';
           
   t += '<div class="modal-body">';
   t += ''+ data2.content +''; 
   t += '</div>';
   $("#modal_archive").html(t);
 });