var prev = 0;

var PANEL_TOPIC_DATA = {
  topic_id: null, /* Cursor for current main topic data*/
  subtopic_id: null, /* Current Cursor Sub Topic  */

  data: null, /* Topics Data */
  subtopic: null /* SubTopic Data */
};
/* subtopic */
var subTopic = {
  pptx: {
    id: null,
    list: null,
    load: function() {
      return $.ajax({url: "../topics/topic.php?route=pptxList", type: "post", data: {subTopicId: PANEL_TOPIC_DATA.subtopic_id.id}}).then(function(resp) {
        subTopic.pptx.list = JSON.parse(resp);
      }, function(resp) {
        console.log("Problem Connecting to the server!");
      });
    }
  },
  open: function() {
    $("#sub-topic-panel #left").show();
    $("#sub-topic-panel #left").addClass("scale");
    $("#sub-topic-panel #right").removeClass("col-md-12");
    $("#sub-topic-panel #left").removeClass("scale-1");
    $("#sub-topic-panel #right").addClass("col-md-9");
  },
  close: function() {
    $("#sub-topic-panel #left").hide();
    $("#sub-topic-panel #right").addClass("col-md-12");
    $("#sub-topic-panel #right").removeClass("col-md-9"); 
    $("#sub-topic-panel #left").addClass("scale-1");
    $("#sub-topic-panel #left").removeClass("scale");
  },
  form_clear: function() {
    $("#sub-topic-panel #sub-topic-form input[name=id]").val("");
    $("#sub-topic-panel #sub-topic-form input[name=title]").val("");
    $("#sub-topic-panel #sub-topic-form textarea[name=des]").val("");
  },
  load: function() {
    $("#sub-topic-panel #table").html('<i class="fa fa-refresh rotate"></i> Please Wait...');
    return $.post("../topics/topic.php?route=subtopicdata",{topic_id: PANEL_TOPIC_DATA["topic_id"].id}, function(o) {
      PANEL_TOPIC_DATA["subtopic"] = JSON.parse(o);
      subTopic.table();
    });
  },
  table: function() {
    var data = PANEL_TOPIC_DATA["subtopic"];
    var t = '';
    var l = 0;
    if (data != null)
      l = data.length
    t += '<table class="table table-sm table-striped" style="margin-top:10px;">';
      t += '<tr>';
        t += '<th>Action</th>';
        t += '<th>Title</th>';
        t += '<th>Description</th>';
      t += '</tr>';
    for (var i = 0; i < l; i++) {
      t += '<tr i="' + i + '">';
        t += '<td style="">';
          t += '<div class="btn-group">';
            t += '<a href="../Quiz/CreateQuiz.php?obj=' + data[i].id  + '&type=SUBTOPIC&group_id=" target="_new" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Create Quiz</a>';
            t += '<button class="btn btn-default btn-sm view"><i class="fa fa-search"></i> Upload PPTX</button>';
            t += '<button class="btn btn-primary btn-sm edit"><i class="fa fa-edit"></i></button>';
            t += '<button class="btn btn-danger btn-sm trash"><i class="fa fa-trash"></i></button>';
          t += '</div>';
        t += '</td>';
        t += '<td>';
          t += '<div>' + data[i].title + '</div>';
        t += '</td>';
        t += '<td>';
          t += '<div>' + data[i].des + '</div>';
        t += '</td>';
      t += '</tr>';
    }
    t += '</table>';
    $("#sub-topic-panel #table").html(t);
  }
}

var panelTopic = {
  catid: null,
  loader: function() {
    $("#panel-topic-list").html("<span><i class=\"fa fa-refresh rotate\" style=\"color:red;\"></i> Loading...</span>");
  },
  table: function() {
    if (PANEL_TOPIC_DATA["data"] == null) this.load();

    var len = PANEL_TOPIC_DATA["data"].length;
    var t = '';
    t += '<table class="table table-sm table-striped">';
    t += '<thead>';
      t += '<tr>';
        t += '<th>';
          t += 'action';
        t += '</th>';
        t += '<th>';
          t += 'Title';
        t += '</th>';
        t += '<th>';
          t += 'Description';
        t += '</th>';
      t += '</tr>';
    t += '</thead>';
    t += '<tbody>';
    for (var i = 0; i < len; i++) {
      t += '<tr i="' + i + '">';
        t += '<td>';
          t += '<div class="btn-group">';
            t += '<a href="../Quiz/CreateQuiz.php?obj=' + PANEL_TOPIC_DATA["data"][i].id  + '&type=MAINTOPIC&group_id=" target="_new" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Create Quiz</a>';
            t += '<button class="btn btn-primary btn-sm sub"><i class="fa fa-pencil"></i> Sub Topic</button>';
            t += '<button class="btn btn-info btn-sm edit"><i class="fa fa-edit"></i></button>';
            t += '<button class="btn btn-danger btn-sm trash"><i class="fa fa-trash"></i></button>';
          t += '</div>';
        t += '</td>';
        t += '<td>';
          t += PANEL_TOPIC_DATA["data"][i].title;
        t += '</td>';
        t += '<td>';
          t += PANEL_TOPIC_DATA["data"][i].des;
        t += '</td>';
      t += '</tr>';
    }
    t += '</tbody>';
    $("#panel-topic-list").html(t);
  },
  load: function() {
    this.loader();
    $.post('../topics/topic.php?route=topicpanel', {catid: this.catid}, function(resp) {
      PANEL_TOPIC_DATA["data"] = JSON.parse(resp);
      panelTopic.table();    
    });
  }
};
window.addEventListener('load', function() {
  function panel_form_clear() {
    $("#panel-topic-form input[name=id]").val("");
    $("#panel-topic-form input[name=title]").val("");
    $("#panel-topic-form textarea[name=des]").val("");
  }
  $('#panel-topic-form').on('submit', function() {
    var data = $(this).serialize();
    $("#panel-topic-form .save").html('<i class="fa fa-refresh rotate" style="color:white;"></i> Wait...');
    $.post('../topics/topic.php?route=save', data, function(resp) {
      $("#panel-topic-form .save").html('<i class="fa fa-save"></i> Save Topics');  
      var o = JSON.parse(resp);
      if (o.ok == 1) {
        panel_form_clear();
        panelTopic.load();
        alert(o.msg);
        return;
      }
      alert(o.msg);
    });
    return false;
  });
  $('#topic-panel #close').on('click', function() {
    $('#topic-panel').slideUp();
  });
  $('#topic-panel #catbtn').on('click', function() {
    $('#category-panel').fadeIn();
  });
  $('#panel-topic-form select[name=catid]').on('click', function() {
    var e = $(this);
    var len = catobj.length;
    if (prev != len) {
      var t = '<option value="">[ Select Category ]</option>';
      for (var i = 0; i < len; i++) {
        t += '<option value="' + catobj[i].id + '">' + catobj[i].title + '</option>';
      }
      e.html(t);
      prev = len;
    }
  });
  $("#panel-topic-form select[name=catid]").on("change", function() {
    panel_form_clear();
    panelTopic.catid = $(this).val();
    panelTopic.load();
  });
  $("#panel-topic-list").on("click", ".edit", function() {
    var i = $(this).parents("tr").attr("i");
    panel_form_clear();
    $("#panel-topic-form input[name=id]").val(PANEL_TOPIC_DATA["data"][i].id);
    $("#panel-topic-form select[name=catid]").val(PANEL_TOPIC_DATA["data"][i].catid);
    $("#panel-topic-form input[name=title]").val(PANEL_TOPIC_DATA["data"][i].title);
    $("#panel-topic-form textarea[name=des]").val(PANEL_TOPIC_DATA["data"][i].des);
  });
  $("#panel-topic-list").on("click", ".trash", function() {
    var i = $(this).parents("tr").attr("i");
    var btn = $(this);
    var id = PANEL_TOPIC_DATA["data"][i].id;
    if (confirm("Are you sure?")) {
      btn.html('<i class="fa fa-refresh rotate"></i>');
      $.post("../topics/topic.php?route=trash", {id: id}, function(resp) {
        btn.html('<i class="fa fa-trash"></i>');
        var o = JSON.parse(resp);
        if (o.ok) {
          panel_form_clear();
          alert(o.msg);
          panelTopic.load();
          return;
        }
        alert(o.msg);
      });
    }
  });
  panelTopic.catid = $("#panel-topic-form select[name=catid]").val();
  panelTopic.load();
  cat.load();

/* ================================ Sub Topic Panel      =======================  */
  //open the sub topic window
  $("#panel-topic-list").on("click", ".sub" ,function() {
    PANEL_TOPIC_DATA["topic_id"] = PANEL_TOPIC_DATA["data"][$(this).parents("tr").attr("i")];
    if (PANEL_TOPIC_DATA["topic_id"] != null) {
      $('#sub-topic-panel').show();
      $('#sub-topic-panel #subtitle').html("[" + cat.get(PANEL_TOPIC_DATA["topic_id"].catid).title + "]" + " - " +PANEL_TOPIC_DATA["topic_id"].title);
      subTopic.load();
    }
  });
  //close the sub topic window
  $("#sub-topic-panel #close").on("click", function() {
    $('#sub-topic-panel').hide();
  });
  //open the form of sub topics
  $("#sub-topic-panel .new").on("click", function() {
    subTopic.open();
    subTopic.form_clear();
  });
  //closing the form
  $("#sub-topic-panel #left #close1").on("click", function() {
    subTopic.close();
  });
  //submit the form
  $("#sub-topic-panel #sub-topic-form").on("submit", function() {
    var data = $(this).serialize();
    data = data + "&topic_id=" + PANEL_TOPIC_DATA["topic_id"].id;
    $("#sub-topic-panel #sub-topic-form button[type=submit]").html('<i class="fa fa-refresh rotate"></i>');
    $.post("../topics/topic.php?route=sub_topic_save", data, function(resp) {
      var o = JSON.parse(resp);
      if (o.ok == 1) {
        $("#sub-topic-panel #sub-topic-form button[type=submit]").html('<i class="fa fa-save"></i>');
        alert(o.msg);
        subTopic.form_clear();
        subTopic.load();
        return;
      }
      alert(o.msg);
      return;
    });
    return false;
  });
  $("#sub-topic-panel #table").on("click", ".edit", function() {
    var i = $(this).parents("tr").attr('i');
    var data = PANEL_TOPIC_DATA["subtopic"][i];
    subTopic.open();
    subTopic.form_clear();
    $("#sub-topic-panel #sub-topic-form input[name=id]").val(data.id);
    $("#sub-topic-panel #sub-topic-form input[name=title]").val(data.title);
    $("#sub-topic-panel #sub-topic-form textarea[name=des]").val(data.des);
  });
  $("#sub-topic-panel #table").on("click", ".trash", function() {
    var i = $(this).parents("tr").attr('i');
    var data = PANEL_TOPIC_DATA["subtopic"][i];
    var el = $(this);
    if (!confirm("Are you sure?"))
      return;
    el.html('<i class="fa fa-refresh rotate"></i>');
    $.post("../topics/topic.php?route=sub_topic_trash", {id: data.id}, function(o) {
      el.html('<i class="fa fa-trash"></i>');
      var data = JSON.parse(o);
      if (data.ok == 1) {
        subTopic.form_clear();
        subTopic.load();
        alert(data.msg);
        return;    
      }
    });
  });
  $("#sub-topic-panel #table").on("click", ".view", function() {
    var i = $(this).parents("tr").attr('i');
    PANEL_TOPIC_DATA["subtopic_id"] = PANEL_TOPIC_DATA["subtopic"][i];
    $("#sub-topic-info").show();
    var id = PANEL_TOPIC_DATA["topic_id"].catid;
    var sub_id = PANEL_TOPIC_DATA["subtopic_id"];
    var co = cat.get(id);
    $("#cat_id").html(co.title);
    $("#topic_id").html(PANEL_TOPIC_DATA["topic_id"].title);
    $("#subtopic_id").html(sub_id.title);
    htmlPptxTable();
  });
/* ================================ End  Sub Topic Panel  =======================  */

/* ================================ Sub Topic Information ======================= */ 
  function htmlPptxTable() {
    //#sub-topic-info #gen
    $("#sub-topic-info #gen").html('Wait...');
    subTopic.pptx.load().then(function() {
      var data = subTopic.pptx.list;
      var len = data.length;
      var t = '';
      t += '<table class="table table-sm">';
        t += '<tr>';
          t += '<th>Action</th>';
          t += '<th>Name</th>';
          t += '<th>Date Uploaded</th>';
        t += '</tr>';
        for (var i = 0; i < len; i ++) {
          t += '<tr i="' + i + '">';
            t += '<td>';
              t += '<div class="btn-group">';
                t += '<button class="btn btn-sm btn-info view"><i class="fa fa-search"></i> View File</button>';
                t += '<button class="btn btn-sm btn-danger remove"><i class="fa fa-trash"></i> Remove</button>';
              t += '</div>';
            t += '</td>';
            t += '<td>' + data[i].original_name + '</td>';
            t += '<td>' + data[i].dt + '</td>';
          t += '</tr>';
        }
      t += '</table>';
      $("#sub-topic-info #gen").html(t);
    });
  }
  $("#sub-topic-info #close").on("click", function() {
    $("#sub-topic-info").hide();
    $(".progress-loader").hide();
  });

  /* Upload form for pptxt  */
  $('#sub-topic-form-upload').on("submit", function() {
    $(".progress-loader").hide();
    var f = $("#sub-topic-form-upload #pptx-file");
    if (typeof(f[0].files[0]) == "undefined") {
      alert("Please Select Powerpoint file");
      return false;
    }
    if (f[0].files[0].name.trim() == "") {
      alert("Please Select a file");
      return false;
    }
    if (f[0].files[0].type != "application/vnd.openxmlformats-officedocument.presentationml.presentation") {
      alert("Invalid powerpoint file");
      return false;
    }
    if (confirm("File is uploadable to server, proceed?")) {
      var data = new FormData();
      data.append("pptx", f[0].files[0]);
      data.append("subTopicId", PANEL_TOPIC_DATA.subtopic_id.id);
      $("#sub-topic-form-upload .upload").html('<i class="fa fa-refresh rotate"></i>');
      try {
        var total = 0;
        var loaded = 0;
        var w  = 0;
        var xml = new XMLHttpRequest();
        $(".progress-loader").show();
        xml.upload.addEventListener("progress", function(e) {
          if (e.lengthComputable) {
            loaded = e.loaded;
            total = e.total;
            w = parseInt((loaded/total) * 100);
            $(".progress-loader div").css("width", w + "%");
            $(".progress-loader div").html(w + "%");
          }
        });
        xml.addEventListener("load", function(res) {
          $("#sub-topic-form-upload .upload").html('<i class="fa fa-upload"></i> Upload');
          $(".progress-loader div").html('<span style="color:white;">Complete!</span>');
          htmlPptxTable();
        });
        xml.open("POST", "../topics/topic.php?route=uploadPPTX");
        //xml.setRequestHeader("Content-Type", "x-www-form-urlencoded");
        xml.send(data);
      }catch(e){console.log(e.toString());}
    }
    $(this).trigger("reset");
    return false;
  });
  $("#sub-topic-info #gen").on("click", '.remove', function() {
    var i = $(this).parents("tr").attr("i");
    subTopic.pptx.id = subTopic.pptx.list[i];
    var id = subTopic.pptx.id.id;
    if (!confirm("Are you sure?"))
      return;
    $.ajax({url: "../topics/topic.php?route=trashPptxFile", type: "post", data: {pptxId: id}}).then(function(res) {
      alert(res);
      htmlPptxTable();
    }, function() {
      console.log("Problem connecting to the Server!");
    });
  });

$("#sub-topic-info #gen").on("click", '.view', function() {
  var i = $(this).parents("tr").attr("i");
  subTopic.pptx.id = subTopic.pptx.list[i];
  $("#topic-power-point-viewer").show();
  pptxLoader();
});
/* ================================ End Sub Topic Info    ======================= */

/*================================= PPTX Viewer ==============================*/ 
var elem = null;
  var len = 0;
  var curindex = 0;

  function loadElem() {
    elem = document.getElementsByClassName("slide");
    len = elem.length;
  }

  function hideAll() {
    for (var i = 0; i < len; i++) {
      elem[i].style.display = "none";
    }
  }

  function present(c) {

    
    loadElem();
    hideAll();
    curindex += c;
    if (c == 0)
      curindex = 0;
    if (curindex >= len) {
      curindex = len - 1;
    } else if (curindex < 0) {
      curindex = 0;
    }
    elem[curindex].style.display = "block";
  }

function pptxLoader(v = "#topic-power-point-viewer .pptx-viewer") {
  $("#topic-power-point-viewer .pptx-viewer").html("");
  $(v).pptxToHtml({
    pptxFileUrl: "../data/" + subTopic.pptx.id.name,
    fileInputId: "uploadFileInput",
    slideMode: true,
    keyBoardShortCut: false,
    slideModeConfig: {
      //on slide mode (slideMode: true)x
      first: 1,
      nav: false /** true,false : show or not nav buttons*/,
      navTxtColor: "white" /** color */,
      navNextTxt: "&#8250;", //">"
      navPrevTxt: "&#8249;", //"<"
      showPlayPauseBtn: false /** true,false */,
      keyBoardShortCut: false /** true,false */,
      showSlideNum: false /** true,false */,
      showTotalSlideNum: false /** true,false */,
      autoSlide: false /** false or seconds (the pause time between slides) , F8 to active(keyBoardShortCut: true) */,
      randomAutoSlide: true /** true,false ,autoSlide:true */,
      loop: false /** true,false */,
      background: "black" /** false or color*/,
      transition:
        "default" /** transition type: "slid","fade","default","random" , to show transition efects :transitionTime > 0.5 */,
      transitionTime: 0.5 /** transition time in seconds */,
    },
  });
}
$(window).on("keyup", function (evt) {
    // var left = 37;f
    // var right = 39;
    // if (evt.keyCode == left) present(-1);
    // else if (evt.keyCode == right) present(+1);
    // if (evt.keyCode == 70) 
    // F key in keyboard
    //    $(".pptx-viewer").toggleFullScreen();
  });
$("#topic-power-point-viewer #close").on("click", function() {
  $("#topic-power-point-viewer").hide();
});
/*================================= End PPTX Viewer ===============================*/
});