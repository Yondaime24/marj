var chatStyle = '<link rel="stylesheet" href="' + ROOT_PATH + 'assets/chatbox/chat.css" / >';
$("head").append(chatStyle);
var fcbChat = new cbox("#chatbox001");
$("body").prepend('<div id="chatbox001"></div>');
fcbChat.width = 700;
fcbChat.title = "FEED Messenger";
fcbChat.backgroundcolor = "";
fcbChat.backgroundcolor_header = "rgb(128 128 128 / 26%)";
fcbChat.logo = '<i class="fa fa-message"></i>';
fcbChat.top = 20;
fcbChat.left = 100;
fcbChat.init();
var d = '<div class="chat-wrap">';
   //start sider
   d += '<div class="chat-sider">\
      <div class="user-wrap">\
        <div class="chat-title-wrap">\
          <div class="chat-title">FEED Chat</div>\
          <input type="search" class="user-search" placeholder="Search User" />\
        </div>\
        <div class="user-gen">\
        </div>\
      </div>\
    </div>';
   // end sider
   // start message
   d += '<div class="chat-msg">\
      <div class="chat-msg-header">\
        <div class="chat-user-name"></div>\
      </div>\
      <div class="chat-msg-wrap" id="chat-mgs" style="">\
      </div>\
      <div class="chat-msg-footer">\
        <div class="chat-box-wrap">\
          <textarea class="chat-input" placeholder="Enter Message"></textarea>\
        </div>\
      <div class="chat-button-send">\
        <i class="fa fa-paper-plane"></i>\
      </div>\
      </div>\
    </div>';
   // end ,message
   d += '</div>';
fcbChat.document.append(d);
$(fcbChat.elem).on("keyup", ".user-search", function(evt) {
  if (evt.keyCode == 13)
    ChatBox.show($(this).val(), true);
});
// 
var is_recv_loaded = true;
var msg_offset = 0;
var client_id = "";
var client_loaded = false;
var client_chat_opend = false;
var client_current_data = [];
// 
// sending a message

// to open a chat
/*
  client_chat_opend = client_chat_opend;
  client_id = client_id;
  ChatBox.to = to;
*/
// end open a chat
var get_chat_loaded = true;
setInterval(function() {
  // checking if there's a new message
  if (ChatBox.role == "admin")
    if (get_chat_loaded) {
      //get_chat_loaded = false;
      $.post(ROOT_PATH + 'chats?route=getLatestChat').then(function(resp) {
        var data = JSON.parse(resp);
        if (data.length > 0) {
          client_chat_opend = 1;
          client_id = data[0]["ffrom"];
          ChatBox.to = data[0]["ffrom"];
          get_chat_loaded = true;
          $('.chat-user-name').html(data[0]["name"]);
        }
      });
    }
}, 2000);

$(fcbChat.elem).on("click", ".chat-button-send", function() {
  var msg = $(".chat-input").val();
  var uid = '';
  var chat = $("#chat-mgs");
  //chat.append('<div style="width:100%;padding:10px;height:50px;border:1px solid #eee;border-radius:10px;" class="mssg01">Sending...</div>');
  if (ChatBox.to != null)
    uid = client_id;
  msg = msg.trim();
  $(".chat-input").val("");
  if (msg != '') {
    d = '';
    chat.append('<div class="loader01" style="margin-top:100px;width:100%;padding:10px;height:50px;background-color:#eee;border:grey;border-radius:10px;">Sending...</div>');
    $.ajax({url: ROOT_PATH + "chats/?route=send", type: "post", data: {msg: msg, uid: uid}}).then(function() {
      $(fcbChat.elem).find(".loader01").remove();
      var c = $(fcbChat.elem).find(".chat-msg-wrap")[0];
      c.scrollTop = c.scrollHeight + c.scrollTop;
    }, function(resp) {
      console.log("Problem Connecting to the server");
    });
  }
});
// end sending a message

function isChange(key) {
  var data = client_current_data;
  var l = data.length;
  for (var i = 0; i < l; i++) { 
    if (data[i].id == key)
      return false;
  }
  return true;
}
var client_chat_realtime = setInterval(function() {
  if (client_chat_opend) {
    // real time checking if there's a new message
    var chat = $("#chat-mgs");
    var myid = ChatBox.myid;
    var myrole = ChatBox.role;
    ChatBox.recv(0).then(function(resp) {
      var data = JSON.parse(resp);
      var data_len = data.length;
      var d = '';
      for (var i = 0; i < data_len; i++) {
        d = '';
        if (isChange(data[i].id)) {
          if (data[i].ffrom == myid || data[i].ffrom == myrole) {
            d += '<div class="right-chat-wrap">\
            <div class="right-chat"><p>\
              ' + data[i].msg  + '\
            </p></div>\
          </div>';
            } else {
              // sender 
            d += '<div class="left-chat-wrap">\
            <div class="left-chat"><p>\
              ' + data[i].msg + '\
            </p></div>\
          </div>';
          }
          client_current_data = data;
          fcbChat.show();
          chat.append(d);
          var c = $(fcbChat.elem).find(".chat-msg-wrap")[0];
          c.scrollTop = c.scrollHeight + c.scrollTop;
        }
      }
    });
  }
}, 2000);
$(fcbChat.elem).find(".cclose").on("click", function() {
  // overide the click event for closing the chat box
  // client_chat_opend = false;
  client_loaded = false;
  msg_offset = 0;
  // set the variable to default state
});
$(fcbChat.elem).find(".chat-msg-wrap").on("scroll", function(evt) {
  var chat = this;
  if (!client_loaded)
  if (chat.scrollTop == 0) {
    if (is_recv_loaded) {
      msg_offset++;
      chatLoader(msg_offset, chat);
    } else {
      var c = $(fcbChat.elem).find(".chat-msg-wrap")[0];
      c.scrollTop = c.scrollHeight + c.scrollTop + 100; 
    }
  }
  // getting the end of scroll 
  // console.dir(chat.offsetHeight + chat.scrollTop >= chat.scrollHeight);
});
function chatLoader(offset = 0, chatObj = null) {
  var chat = $("#chat-mgs");
  var idno = client_id;
  ChatBox.to = idno;
  var myid = ChatBox.myid;
  var myrole = ChatBox.role;
  is_recv_loaded = false;
  chat.prepend('<div class="loader" style="margin-bottom:10px;width:100%;padding:10px;height:50px;background-color:#eee;border:grey;border-radius:10px;">Loading...</div>');
  if (!client_loaded)
  ChatBox.recv(offset).then(function(resp) {
    $(fcbChat.elem).find(".loader").remove();
    is_recv_loaded = true;
    var data = JSON.parse(resp);
    var data_len  = data.length;
    var d = "";
    for (var i = data_len - 1; i >= 0; i--) { 
      if (data[i].ffrom == myid || data[i].ffrom == myrole) {
        // receiver
        d += '<div class="right-chat-wrap">\
        <div class="right-chat"><p>\
          ' + data[i].msg  + '\
        </p></div>\
      </div>';
        } else {
          // sender 
        d += '<div class="left-chat-wrap">\
        <div class="left-chat"><p>\
          ' + data[i].msg + '\
        </p></div>\
      </div>';
      }
      client_chat_opend = true;
    }
    if (offset == 0)
      client_current_data = data;
    chat.prepend(d); 
    if (chatObj != null)
      chatObj.scrollTop = 1;
    else {
      var c = $(fcbChat.elem).find(".chat-msg-wrap")[0];
      c.scrollTop = c.scrollHeight + c.scrollTop;
    }
    if (data_len == 0)
      // no data is response then of the loaded is off
      client_loaded = true;
  });
}
$(fcbChat.elem).on("click", ".user-list", function(evt) {
  $(fcbChat.elem).find(".user-list").removeClass("chat-user-active");
  $(this).addClass("chat-user-active");
  var chat = $(fcbChat.elem).find(".chat-msg-wrap");  
  msg_offset = 0;
  client_id = $(this).attr("rel");
  chat_name = $(this).find(".user-name").html();
  $(fcbChat.elem).find(".chat-user-name").html("<i class=\"fa fa-user\"></i> " + chat_name);
  client_loaded = false;
  chat.html("");
  chatLoader(0);
});
var ChatBox = {
  is_loaded_at_start: false,
  myid: null,
  myrole: null,
  init: function() {  
    return $.ajax({url: ROOT_PATH + 'chats/?route=me', type: 'post'}).then(function(resp) {
      var data = JSON.parse(resp);
      ChatBox.myid = data.idno;
      ChatBox.role = data.role;
    });
  },
  to: null,
  recv: function(offset = 0) {
    return $.ajax({url: ROOT_PATH + 'chats/?route=recv', type: 'post', data: {uid: this.to, offset: offset}});
  },
  loadUser: function(search = "") {
    return $.ajax({url: ROOT_PATH + 'chats/?route=list', type: 'post', data: {search: search}});
  },
  show: function(search = "", is_search = false, is_clicked = true) {
    if (!this.is_loaded_at_start || is_search)  
      if (this.role == 'admin') {
        if (search != "")
          fcbChat.document.find(".user-gen").html("<div style=\"color:white;\">Loading...</div>");
        this.loadUser(search).then(function(resp) {
          var data = JSON.parse(resp);
          var data_len = data.length;
          var d = '';
          var unread = '';
          if (data_len  > 0) {
            for (var i = 0; i < data_len; i++) {
              var name = data[i].name;
                  name = name.toUpperCase();
              unread = (data[i].total_unread > 0) ? data[i].total_unread : '';
              d += '<div class="user-list" rel="' + data[i].uid + '">';
                d += '\
                <div class="user-pic-wrap">\
                  <div class="user-pic">\
                    <div class="online-status">\
                    \
                    </div>\
                    <div class="pic">\
                      <div style="padding:7px;color:#dfaa80;"><i class="fa fa-user" style="font-size:18pt;"></i></div>\
                    </div>\
                  </div>\
                  <div class="user-name">' + name + '</div>\
                </div>';
              d += '</div>';
            }
          } else {
            //fcbChat.document.find();
          }
          fcbChat.document.find(".user-gen").html(d);
        });
      } else {
        // execute if user
        // then start the conversation
        client_chat_opend = true;
        $(fcbChat.elem).find(".user-search").hide();
        chatLoader(0);
        $(fcbChat.elem).find(".chat-user-name").html("<i class=\"fa fa-user\"></i> Administrator");
      } // end this.role
    this.is_loaded_at_start = true;
    if (is_clicked)
      fcbChat.show();
  },
  close: function() {
    fcbChat.close();
  }
};
ChatBox.init().then(function() {
  if (ChatBox.role == '') {
    ChatBox.show("", false, false);
  }
});