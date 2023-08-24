
if (myrole != "admin") {
  $(".chatboxtoname").html("Administrator");
  $(".msg-ui").show();
  $(".users-list").hide();
  $("#m-head").hide();
  $("#msg-back").hide();
}
var chatBox = {
  prev: '<tr><td colspan="2"><center><button class="prevmsgchat" style="background-color:#898989;border:none;color:white;">Load More</button></center></td></tr>',
  list: null,
  prevList: null,
  to: null,
  listUser: null,
  nextCursor: null,
  prevCursor: null,
  prevCounter: 0,
  load: function(offset = 0) {
    var uid = this.to == null ? '' : this.to.uid;
    return $.ajax({url: 'chats/?route=recv', type: 'post', data: {uid: uid, offset: offset}}).then(function(resp) {
      chatBox.list = JSON.parse(resp);
    }, function(resp) {
      console.log("Problem connecting to the server!");
    });
  },
  loadUser: function(search = '') {
    return $.ajax({url: 'chats/?route=list', type: 'post', data: {search: search}}).then(function(resp) {
      chatBox.listUser = JSON.parse(resp);
    });
  }
}
var chatBoxHtml = {

}
var is_reloaded = true;
var g_offset = 0;
function reloadChatHTML(append_data = false) {
  var data = chatBox.list;
  var l = data.length;
  var gt1 = '';
  //if (l > 0)
    //gt1 += chatBox.prev;
  for (var i = l - 1; i >= 0; i--) {
    var time_ago = data[i].ago;
    //gt += '<tr>';
      if (data[i].ffrom == guid || data[i].ffrom == myrole) { 
          gt1 += '<div class="send-mess-wrap">';
          gt1 += '<span class="mess-time">' + time_ago + '</span>';
          gt1 += '<div class="send-mess_inner" style="">';
          gt1 += '<div class="send-mess-list">';
          gt1 += '<div class="send-mess">' + data[i].msg + '</div>';
          gt1 += '</div>';
          gt1 += '</div>';
          gt1 += '</div>';
          gt1 += '<div>';
          gt1 += '</div>';      
       } else {
          gt1 += '<div class="recei-mess-wrap">';
          gt1 += '<small class="mess-time">' + time_ago + '</small>';
          gt1 += '<div class="recei-mess_inner">';
          gt1 += '<div class="recei-mess-list">';
          gt1 += '<div class="recei-mess">';
          gt1 += '' + data[i].msg + '';
          gt1 += '</div>';
          gt1 += '</div>';
          gt1 += '</div>';
          gt1 += '</div>';
       }
    //gt += '</tr>';
  }
  if (data.length > 0) {
    if (append_data) {
      $('.chatBox').prepend(gt1);
    } else {
      $('.chatBox').html(gt1);
    }
  } else {
    if (append_data)
      g_offset--;
  }
}
$(".chatBox").on("scroll", function() {
  var top = $(this).scrollTop();
  //console.log($(this)[0].scrollHeight + top);
  //console.log($(this)[0].scrollWidth);
  if (top == 0) {
    if (is_reloaded) {
    g_offset++;
    is_reloaded = false;
      chatBox.load(g_offset).then(function() {
        is_reloaded = true;
        reloadChatHTML(true);
        $(".chatBox")[0].scrollTop = 1;
      });
    } 
  }
});
var gsu = '';
var gsu2 = '';
function chatLoadUser() {
  if (myrole != "admin") {
     $(".users-list").hide();
     return;
  }
  $('.users-list').html("Please Wait...");
  var data = chatBox.listUser;
  var l = data.length;
  var unread = '';
  var status = '';
  gsu  = '';
  gsu += '<div class="search-div" style="position:sticky;top:0px;z-index:100;margin-right:2vh;">';
  gsu += '<input type="text" placeholder="Search..." id="searchContact" class="form-control" style="width:100%;height:100%;border-radius:50px;font-size:2vh;background-color:whitesmoke" autocomplete="off" />';
  gsu += '</div>';
  for (var i = 0; i < l; i++) {
    unread = data[i].total_unread > 0 ? data[i].total_unread : "";
    if (data[i]["status"] == 1){
      gsu += '<div class="users-list-online" rel="' + i + '" id="'+ data[i].uid +'"> ';
      gsu += '<li>';
      gsu += '<span><i class="fas fa-2xs fa-circle"></i></span>';
      gsu += '<a id="ol-user" class="chatlistdiv" style="cursor:pointer;color:black;text-transform:uppercase;">' + data[i].name + '</a>';
      gsu += '<div class="msg-notif" >';
      gsu += '<i class="far fa-1x fa-message"></i><p class="cnt">' + unread + '</p>';
      gsu += '</div>';
      gsu += '</li>';                
      gsu += '</div>';
    }else{
      gsu += '<div class="users-list-offline" rel="' + i + '" id="'+ data[i].uid +'"> ';
      gsu += '<li>';
      gsu += '<span style="color:gray;"><i class="fas fa-2xs fa-circle"></i></span>';
      gsu += '<a id="ol-user" class="chatlistdiv" style="cursor:pointer;color:black;text-transform:uppercase;color:gray;">' + data[i].name + '</a>';
      gsu += '<div class="msg-notif">';
      gsu += '<i class="far fa-1x fa-message" style="color:gray;"></i><p class="cnt">' + unread + '</p>';
      gsu += '</div>';
      gsu += '</li>';                
      gsu += '</div>';
    }
  }
  $('.users-list').html(gsu);
}
if (myrole == "admin") {
  // setInterval(function() {
  //   chatBox.loadUser().then(function(resp) {
  //     var data = chatBox.listUser;
  //   });
  // }, 2000);
}
window.addEventListener("load", function() {
  $(".chatBoxBtn").on("click", function() {
    $(".chatBoxEditor").show();
  });
  $("#chatBoxSend").on("click", function() {
    var msg = $(".chatBoxTextField").val();
    var uid = '';
    if (chatBox.to != null)
      uid = chatBox.to.uid;
    msg = msg.trim();
    if (msg != '') {
      $.ajax({url: "chats/?route=send", type: "post", data: {msg: msg, uid: uid}}).then(function() {
        // var t = '<tr><td></td><td class="msg right"><div>' + msg + '</div></td>';
        //     t += '</tr>';
        // $('.chatBox').append(t);
      }, function(resp) {
        console.log("Problem Connecting to the server");
      });
    }
    $(".chatBoxEditor").hide();
    $(".chatBoxTextField").val("");
  });
  $(".chatBoxTextField").on("keyup", function(e) {
    // if (e.keyCode == 13) {
    //   var msg = $(this).val();
    //   $.ajax({url: "chats/?route=send", type: "post", data: {msg: msg}}).then(function() {
    //   }, function(resp) {
    //     console.log("Problem Connecting to the server");
    //   });
    //   $(".chatBoxEditor").hide();
    //   $(this).val("");
    // }
  });

  /*
  <tr>
    <td class="msg left"><div>Hi From Administrator! Good day we have a meeting today at 10pm in the evening</div></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td class="msg right"><div>Hellow from User</div></td>
  </tr>
  */
  $('.users-list').show();
  chatBox.loadUser().then(function() {
    chatLoadUser();
  }); 
  $('.chatBoxBtnContact').on('click', function() {
    $('.chatBoxEditor').hide();
    $('.users-list').show();
    chatBox.loadUser().then(function() {
      chatLoadUser();
    });  
  });
  $('.users-list').on('click', '.close', function() {
    $('.chatBoxEditor').hide();
    $('.users-list').hide();
  });


   $("#feed_message").focus(function(){
    $(this).attr("placeholder", "Type a message...");
   });
   $("#feed_message").on('input', function(){
    $(".foot-right").css("display", "flex");
    $(".send_btn").css("opacity", "1");
    $(".foot-left").css("padding-right", "0px");
   });


  $('.users-list').on('keyup', '#searchContact', function(evt) {
    var input = $("#searchContact").val();

    if (evt.keyCode == 13) {
      if(input == ""){
       $(this).attr("placeholder", "Please Enter A Name")
      }else{
      var a = $(this).val();
        chatBox.loadUser(a).then(function() {
          chatLoadUser();
        });
      }

    }

  });
  $('.users-list').on('click', '.users-list-online', function() {
    var i = $(this).attr('rel');
    var id = $(this).attr('id');
    chatBox.to = chatBox.listUser[i];
    $(".chatBox").html("<div style='width:100%;height:100%;display:flex;justify-content:center;align-items:center;'><div class='loader_t'></div></div>")
    chatBox.load().then(function() {
      $(".chatBox").html("");
      chatBox.nextCursor = chatBox.list;
      chatBox.prevCounter = 0;
      reloadChatHTML();
    });

    $('.chatboxtoname').html(chatBox.to.name);
    $('.chatBoxEditor').hide();
    $('.users-list').hide();
    $('#m-head').hide();
    // $('.msg-ui').show();
    $(".msg-ui").toggle("slide");

    chatBox.loadUser().then(function() {
      chatLoadUser();
      $(".chatBox")[0].scrollTop = $(".chatBox")[0].scrollTop + $(".chatBox")[0].scrollHeight;
    }); 

  });

  $('.users-list').on('click', '.users-list-offline', function() {
    var i = $(this).attr('rel');
    var id = $(this).attr('id');
    chatBox.to = chatBox.listUser[i];
    $(".chatBox").html("<div style='width:100%;height:100%;display:flex;justify-content:center;align-items:center;'><div class='loader_t'></div></div>")
    chatBox.load().then(function() {
      $(".chatBox").html("");
      chatBox.nextCursor = chatBox.list;
      chatBox.prevCounter = 0;
      reloadChatHTML();
    });
    $('.chatboxtoname').html(chatBox.to.name);
    $('.chatBoxEditor').hide();
    $('.users-list').hide();
    $('#m-head').hide();
    // $('.msg-ui').show();
    $(".msg-ui").toggle("slide");

    chatBox.loadUser().then(function() {
      chatLoadUser();
      $(".chatBox")[0].scrollTop = $(".chatBox")[0].scrollTop + $(".chatBox")[0].scrollHeight;
    }); 

  }); 

  $('#msg-back').on('click', function() {
    // $('.users-list').show();
    // $('#m-head').show();
    
    $(".users-list").toggle("slide");
    $("#m-head").toggle("slide");
    $('.msg-ui').hide();
    chatBox.to = null;
  });
   $("#b-info").on("click", function(){
      $("#b-res").slideToggle("fast");
      $("#b-res").text("Branch: " +chatBox.to.branch);
   });
  function isChange(key) {
    var data = chatBox.nextCursor;
    var l = data.length;
    for (var i = 0; i < l; i++) { 
      if (data[i].id == key)
        return false;
    }
    return true;
  }
  chatBox.load().then(function() {
    reloadChatHTML();
    chatBox.nextCursor = chatBox.list;

    setInterval(function() {
      chatBox.load().then(function() {
        var data = chatBox.list;
        var len = data.length;         
        var t = '';
        var change = false;
        var gt1 = "";
        for (var i = 0; i < len; i++) {
          var time_ago = data[i].ago;
          if (isChange(data[i].id)) {
            change = false;
            if (data[i].ffrom == guid || data[i].ffrom == myrole) { 
              gt1 += '<div class="send-mess-wrap">';
              gt1 += '<span class="mess-time">' + time_ago + '</span>';
              gt1 += '<div class="send-mess_inner" style="">';
              gt1 += '<div class="send-mess-list">';
              gt1 += '<div class="send-mess">' + data[i].msg + '</div>';
              gt1 += '</div>';
              gt1 += '</div>';
              gt1 += '</div>';
              gt1 += '<div>';
              gt1 += '</div>';      
           } else {
              gt1 += '<div class="recei-mess-wrap">';
              gt1 += '<small class="mess-time">' + time_ago + '</small>';
              gt1 += '<div class="recei-mess_inner">';
              gt1 += '<div class="recei-mess-list">';
              gt1 += '<div class="recei-mess">';
              gt1 += '' + data[i].msg + '';
              gt1 += '</div>';
              gt1 += '</div>';
              gt1 += '</div>';
              gt1 += '</div>';
           }
            $('.chatBox').append(gt1);
            chatBox.nextCursor = data;
            $(".chatBox")[0].scrollTop = $(".chatBox")[0].scrollTop + $(".chatBox")[0].scrollHeight;
          }  
        }
        
          // if (!match) {
          //   t += '<tr>';
            
          // }

        
      });
    }, 2000);
  });
  $('.chatBox').on('click', '.prevmsgchat', function() {
    var btn = $(this);
    btn.html('<i class="fa fa-refresh rotate"></i>');
    //$(this).parents('tr').remove();
    chatBox.load(++chatBox.prevCounter).then(function() {
      btn.parents('tr').remove();
      var t = chatBox.prev;
      var data = chatBox.prevCursor = chatBox.list;
      var l = data.length;
      if(l <= 0) $('..prevmsgchat').hide();
      for (var i = l - 1; i >= 0; i--) {
        var time_ago = data[i].ago;
        t += '<tr>';
          if (data[i].ffrom == guid || data[i].ffrom == myrole) { 
            // t += '<td></td>';
            gt += 'div class="recei-mess-wrap">';
            gt += '<small class="mess-time">' + time_ago + '</small>';
            gt += '<div class="recei-mess_inner">';
            gt += '<div class="recei-mess-list">';
            gt += '<div class="recei-mess">';
            gt += '' + data[i].msg + '';
            gt += '</div>';
            gt += '</div>';
            gt += '</div>';
            gt += '</div>';
          } else {
            gt += '<div class="send-mess-wrap">';
            gt += '<span class="mess-time">' + time_ago + '</span>';
            gt += '<div class="send-mess_inner" style="">';
            gt += '<div class="send-mess-list">';
            gt += '<div class="send-mess">' + data[i].msg + '</div>';
            gt += '</div>';
            gt += '</div>';
            gt += '</div>';
          }
        t += '</tr>';
      }
      $('.chatBox').prepend(t);
    });
  });


// AUTOMATICALLY UPDATES USER ONLINE AND MESSAGES
  function startLiveUpdate() {
    setInterval(function () {
      chatBox.loadUser().then(function() {
        chatLoadUser();
      }); 
    }, 2000);
  }
  startLiveUpdate();


});


