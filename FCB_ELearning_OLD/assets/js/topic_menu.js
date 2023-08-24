var SLIDE = null;
var SLIDE_LEN = 0;
var SLIDE_HEIGHT = 0;
  var gwin = {
    pptx: {
      width:0,
      height:0
    }
  };
  var GINDEX = 0;
  function loadSlide(index = 0) {
    if (SLIDE == null) return;
    $('.pptx-file .slide').hide();
     GINDEX += index;
    if (GINDEX >= SLIDE_LEN) GINDEX = SLIDE_LEN - 1;
    if (GINDEX <= 0) GINDEX = 0;
    $('#pageno').html((GINDEX + 1) + ' of ' + SLIDE_LEN);
    $('.pptx-file .slide').removeClass('gtrans');;
    SLIDE[GINDEX].style.display = 'block';
    SLIDE[GINDEX].classList.add('gtrans');                
  }
  function TopicViewer() {
    this.id = '';
    this.title = '';
    this.description = '';
    this.type = '';
    this.load = function() {
      return $.ajax({url: '../topics/r.php?r=get-topic', type: 'post', data:{id: this.id, type: this.type}});
    }
    this.pptx = function() {
      return $.ajax({url: '../topics/r.php?r=pptx', data: {id:this.id, type: this.type}, type: 'post'});
    }
  }
  function UpdateWindow() {
    var lw = window.screen.availWidth - 340;
    var lh = window.screen.availHeight - 140;
    $('.bl').css('width', (lw - 40) * 0.30 + 'px');
    $('.br').css('width', (lw - 40) * 0.70 + 'px');
    // right size window size
    gwin['pptx']['width'] = lw - 28;
    gwin['pptx']['height'] = lh - 70;
    try {
      var t = 10;
      var r = gwin['pptx']['height'] > 100 ? gwin['pptx']['height'] / SLIDE_HEIGHT  :  1 ;
      $('.slide').css('transform-origin', '0px 25px');
      $('.slide').css('transform', 'scale(' + r  + ')');
      if (gwin['pptx']['width'] > gwin['pptx']['height']) {
        // console.log(gwin['pptx']['width'] - 900);
        t = 125;
        t = gwin['pptx']['width'] * 0.1300;
      }
      $('.slide').css('left',  t + 'px');
      // $('.slide').css('left',  '0px');
      // $('.slide').css('right',  '0px');
      // $('.slide').css('margin',  'auto');
      //$('.slide').css('display', 'block');
    } catch(err) {
      console.log(err);
    }
    $('#box-content').css('width',  lw  + 'px');
    $('#box-content').css('height', lh  + 'px');
    $('.pptx-file').css('width', gwin['pptx']['width'] + 'px');
    $('.pptx-file').css('height', gwin['pptx']['height'] + 'px');     
    //sidebar width and height
    $('.sidebar').css('height', (window.screen.availHeight - 71) + 'px');
  }
  function QUIZ() {
    this.objid = '';
    this.type = '';
    this.load = function() {
      return $.ajax({url: '../topics/r.php?r=Quiz/List', type: 'post', data:{objid:this.objid, type: this.type}});
    }
  }
  window.addEventListener('load', function() {
    /// re open all sider if get is avallabel
    if (g_cat_id != '') {
      var cat_elem = $(".cat-item");
      var cat_elem_len = cat_elem.length;
      for (var m = 0; m < cat_elem_len; m++) {
        var c_elem = cat_elem.eq(m);
        if(c_elem.attr("rel") == g_cat_id) {
          c_elem.find(".cat-content").slideDown();
          c_elem.find(".cat-plus-btn").html('<i class="fa fa-circle-chevron-down"></i>');
          if (g_main_id != '') {
            // open the subtopics
            ////////////////////////////////////////
            var topicId = g_main_id;
            var topic_btn = c_elem.find(".topic-plus-btn");
            var topic_btn_len = topic_btn.length;
            for (var m1 = 0; m1 < topic_btn_len; m1++) {
              var topic_id = topic_btn.eq(m1).attr("topicid");
              if (topic_id == g_main_id) {
                var btn = topic_btn.eq(m1);
                /////////////////////////////
                 btn.html('<i class="fa fa-refresh rotate"></i>');
                $.ajax({url: '../topics/topic.php?route=getSubTopics', type: 'post', data: {topicId: topicId}}).then(function(resp) {
                  var data = JSON.parse(resp);
                  var len = data.length;
                  var t = '';
                  var elem = btn.parents('.topic-main-item').find('.topic-sub');
                  var pptx = '';
                  for (var i = 0; i < len; i++) {
                    var ilen = data[i][1].length;
                    pptx = '';
                    for (var j = 0; j < ilen; j++) {
                      pptx += data[i][1][j]['name'] + ';';
                    }
                    t += '<div class="topic-sub-item" pptx="' + pptx + '" rel="' + data[i][0]['id'] + '">';
                    t += '<div class="topic-sub-content">' + data[i][0]['title'] + '</div>';
                    t += '</div>';
                  }
                  elem.html(t);
                  btn.html('<i class="fa fa-minus-circle"></i>');
                  btn.parents('.topic-main-item').find('.topic-main-content').slideDown(function() {
                    //////////////////////////////// open subtopic /////////////////////////////
                    var sub_item = cat_elem.find(".topic-sub-item");
                    var sub_item_len = sub_item.length;
                    for (var m2 = 0; m2 < sub_item_len; m2++) {
                      var sub_elem = sub_item.eq(m2);
                      var sub_id = sub_elem.attr("rel");
                      if (sub_id == g_sub_id) {
                        // open the topics
                        loadSide(g_sub_id);
                        // end open the topics
                        break;
                      }
                    }
                    //////////////////////////////// end subtopic open/////////////////////////
                  });
                }, function(resp) {
                  btn.html('<i class="fa fa-plus-circle"></i>');
                });
                /////////////////////////////
                break;
              }
            }
            ////////////////////////////////////////
          }
          break;
        }
      }
    }
    /// end 
    /*
     * Starting a quiz
     * 
     */
    $('#start-quiz-btn').on('click', function() {
      var quiz = new QUIZ();
      var t = "<div class=\"list-grou main-drop\">";
      var sub_t_id = $(this).attr('href');
      quiz.objid = sub_t_id;
      quiz.type = 'SUBTOPIC';
      $("#file-loader").show();
      quiz.load().then(function(resp) {
        $("#file-loader").hide();
        var o = JSON.parse(resp);
        var len = o.group.length;
        for (var a = 0; a < len; a++) {
          t += '<div class="list-group-item">';
          t += '<div class="list-group-item" style="margin-bottom:2px;"><i class="fa fa-plus-circle"></i> '+ o['group'][a]['title'] + ' - ' + o['group'][a]['dt'] + "</div>";
          var itemlen = o['group'][a]['item'].length;
          t += '<div class="list-group drop">';
          for (var b = 0; b < itemlen; b++) {
            t +=  '<div class="list-group-item drop-item" style="padding:5px;padding-left:20px;position:relative;" rel="' + o['group'][a]['item'][b]['id'] + '">';
            t += o['group'][a]['item'][b]['title']; // value
            t += '<button class="startqb" style="position:absolute;top:1px;right:5px;border:none;box-shadow:0 0 3px rgba(0,0,0,0.5);padding:3px;background-color:#1dad6e;color:white;border-radius:5px;"><i class="fa fa-pencil"></i> Start</button>';
            t +=  '</div>'; // drop-item
          }
          t += '</div>'; // drop
          t += '</div>'; // list-group-item
        }
        t += '</div>'; // list-group
        $('#list-q-modal').show();
        $('#list-q-modal #q-list-gen').html(t);
      }, function() {
        alert('Something went wrong!/ Server is Offline!');
        $("#file-loader").hide();
      });
      // retrieve the data  quizezz from the database
    });
    $('#list-q-modal #q-list-gen').on('click', '.main-drop', function() {
      $(this).find('.drop').slideDown();
      if ($(this).find('.drop').attr('style') == 'display: block;') {
        $(this).find('.drop').slideUp();   
      }
    });
    $('#list-q-modal #q-list-gen').on('click', '.drop', function(evt) {
      console.dir(evt);
      evt.stopPropagation();
    });
    $('#list-q-modal #q-list-gen').on('click', '.startqb', function(evt) {
      var id = $(this).parents('.drop-item').attr('rel');
      window.location.href = "../Quiz/start.php?qid=" + btoa(id);
      evt.stopPropagation();
    });
    /*
    ** Uploading the powerpoint presentation
    */  
    var isUploaded = true;
    $('#uploadnow-ppsx').on('click', function() {
      var input = document.getElementById('ppsx-input');
      var id = $(this).attr('rel');
      var data = new FormData();
      data.append('file', input.files[0]);
      data.append('id', id);
      //formdata.append('file', input.files);
      $('#msg-ppsx').html('');
      /*
      ** Validating the upload input
      ** Basic validation
      **
      */
      if (typeof(input.files[0]) == 'undefined') {
         $('#msg-ppsx').html('<div class="alert alert-danger">Please Select a file</div>');
      }
      var type = input.files[0].type;
      var name = input.files[0].name;
      if (name == '') {
        $('#msg-ppsx').html('<div class="alert alert-danger">Select a PPSX File</div>');
        return;
      }
      if (type != 'application/vnd.openxmlformats-officedocument.presentationml.slideshow') {
         $('#msg-ppsx').html('<div class="alert alert-danger">The file is not a slide show, please select a file with the ppsx file extensoin</div>');          
         return;
      }
      if (isUploaded == false) return;
      isUploaded = false;
      var http = new XMLHttpRequest();
      http.addEventListener('readystatechange', function() {
        if (http.readyState == 4) {
          /*
          ** after the upload success
          */
          isUploaded = true;
          $('#msg-ppsx').html('<div class="alert alert-success">Uploaded</div>');
        }
      });
      http.upload.addEventListener('progress', function(evt) {
        /*
        ** This area is for the progress bar of the uploads
        */
        var total = evt.total;
        var loaded = evt.loaded;
        var per = 0;
        if (loaded > 0)
          per = parseInt((loaded / total) * 100);
        $('#msg-ppsx').html('<div style="border:1px solid grey;width:100%;height:20px;"><div style="background-color:green;height:100%;width:' + per + '%"></div></div><div style="margin-top:5px;">' + per + ' %</div>');
      });
      //http.setRequestHeader('Content-type', 'formdata/x-www-formdata');
      http.open('POST','../topics/r.php?r=upload-ppsx');
      http.send(data);
    });
    /*
    ** Click the open file
    ** The Client computer must have installed the fcb ctrl
    ** to view the ppts file directly open in
    */
    $('.llg').on('click', function() {
      loadSlide(-1);
    });
    $('.rrg').on('click', function() {
      loadSlide(+1);
    });
    UpdateWindow();
    window.addEventListener('resize', function() {
      UpdateWindow();
    });
     $('#box-content').show();
    $('.cat-plus-btn').on('click', function() {
      var btn = $(this);
      if (btn.html() == '<i class="fa fa-circle-chevron-right"></i>') {
        btn.parents('.cat-item').find('.cat-content').slideDown();
        btn.html('<i class="fa fa-circle-chevron-down"></i>');
      } else {
        btn.html('<i class="fa fa-circle-chevron-right"></i>');
        btn.parents('.cat-item').find('.cat-content').slideUp();
      }
    });
    $('.topic-plus-btn').on('click', function() {
      var btn = $(this);
      if (btn.html() == '<i class="fa fa-plus-circle"></i>') {
        //get the subtopics and add it to the subtopic
        var topicId = btn.attr('topicId');
        btn.html('<i class="fa fa-refresh rotate"></i>');
        $.ajax({url: '../topics/topic.php?route=getSubTopics', type: 'post', data: {topicId: topicId}}).then(function(resp) {
          var data = JSON.parse(resp);
          var len = data.length;
          var t = '';
          var elem = btn.parents('.topic-main-item').find('.topic-sub');
          var pptx = '';
          for (var i = 0; i < len; i++) {
            var ilen = data[i][1].length;
            pptx = '';
            for (var j = 0; j < ilen; j++) {
              pptx += data[i][1][j]['name'] + ';';
            }
            t += '<div class="topic-sub-item" pptx="' + pptx + '" rel="' + data[i][0]['id'] + '">';
            t += '<div class="topic-sub-content">' + data[i][0]['title'] + '</div>';
            t += '</div>';
          }
          elem.html(t);
          btn.html('<i class="fa fa-minus-circle"></i>');
          btn.parents('.topic-main-item').find('.topic-main-content').slideDown();
        }, function(resp) {
          btn.html('<i class="fa fa-plus-circle"></i>');
        });
      } else {
        btn.parents('.topic-main-item').find('.topic-main-content').slideUp();
        btn.html('<i class="fa fa-plus-circle"></i>');
      }
    });
    // var t_mousemove = null;
    // $('.pptx-file').on('mousemove', function() {
    //   if (t_mousemove != null) { clearTimeout(t_mousemove); t_mousemove = null; }
    //   if (SLIDE != null)
    //   $('.lflf').show();
    //   if (t_mousemove == null) {
    //     t_mousemove = setTimeout(function() {
    //       $('.lflf').hide();
    //     }, 1000);
    //   }
    // });
    /*
     * Toggle the menu in the topics/menu.php
     * 
     */
    // $('.llg').on('mouseover', function() {
    //   $('.lflf').show();
    //   clearTimeout(t_mousemove);
    // });
    // $('.rrg').on('mouseover', function() {
    //   $('.lflf').show();
    //   clearTimeout(t_mousemove);
    // });
    // $('.btn-menu01').on('mouseover', function() {
    //   $('.lflf').show();
    //   clearTimeout(t_mousemove);
    // });
    $('#create-quiz').on('click', function() {
      var id = $(this).attr('href');
      var link = '../Quiz/CreateQuiz.php?obj=' + id + '&type=SUBTOPIC&group_id=';
      if (confirm('Create Quiz for this topics')) window.open(link);
    });
     $('#openfile').on('click', function() {
      var id = $(this).attr('href');
      $('#file-loader').show();
      $.post('../api.php?route=ppsx/open', {id:id}).then(function(resp) {
        var data = JSON.parse(resp);
        $('#file-loader').hide();
        if (data['ok'] == 1) {
          // open the third party application
          $('#file-loader').show();
          $.ajax({
            url: 'http://localhost:8558/feed/' + SERVER_HOST  + SERVER_ROOT, 
            type: 'GET', 
            crossDomain: true, 
            headers: {
              "Access-Control-Allow-Origin": "*"
            },
            contentType: 'application/javascript',
            dataType: 'jsonp'
          }).then(function() {}, function(resp) {
            $('#file-loader').hide();
          });
        } else {
          alert("No slide show PPSX");
        }
      });
    });
    $('#upload-ppsx').on('click', function() {
      var id = $(this).attr('href');
      $('.upload-pptx-wrap').show();
    });
    // click on the items
    function PPTXLoader(elem, file) {
      $(elem).html('');
      $(elem).pptxToHtml({
        pptxFileUrl: "../data/" + file,
        //fileInputId: "uploadFileInput",
        slideMode: false,
        //keyBoardShortCut: false
        slideModeConfig: {
          //on slide mode (slideMode: true)x
          //first: 1,
          nav: false /** true,false : show or not nav buttons*/,
          // navTxtColor: "white" /** color */,
          // navNextTxt: "&#8250;", //">"
          // navPrevTxt: "&#8249;", //"<"
          showPlayPauseBtn: false /** true,false */,
          keyBoardShortCut: false /** true,false */,
          showSlideNum: false /** true,false */,
          autoSlide: false,
          loop: false
          // background: "white" /** false or color*/,
          // transition:
          //   "default" /** transition type: "slid","fade","default","random" , to show transition efects :transitionTime > 0.5 */,
          // transitionTime: 0.5 /** transition time in seconds */,
        },
      });
    }
    $('.topic-sub').on('click', '.topic-sub-item', function() {
      loadSide($(this).attr('rel'));
    });
  });
function loadSide(idd) {
      var id = idd; 
      var tv = new TopicViewer();
      tv.id = id;
      tv.type = 'SUBTOPIC';
      var elem = '.pptx-file';
      $(elem).html('Please wait...');
      $('.lflf').hide();
      SLIDE = null; /* Set null to avoid displaying the button menu if there no powerpoint is displayed */
      tv.load().then(function(resp) {
        $(elem).html('');
        $('.lflf').show();
        var output = JSON.parse(resp);
        var data = output.head;
        var res = output.res;
        var reslen = res.length;
        if (reslen > 0) {
          var img = '../_res/pptcover/' + res[0]['name'];
          var fimg = '<img style="border-radius:20px;box-shadow:0 0 6px rgba(0,0,0,0.3);width:' +  gwin['pptx']['width']  + 'px;height:' +  gwin['pptx']['height']  + 'px;object-fit:cover;" src="' + img + '" />';
          $('.pptx-file').html(fimg);
        }
        var len = data.length;
        var t = '<div class="breadcrumb">';
        for (var i = 0; i < len; i++) {
          t += '<div class="breadcrumb-item">' + data[i] + '</div>';
        }
        t += '</div>';
        $('.topic_title').html(t);
        $('#start-quiz-btn').attr('href', id);
        $('#create-quiz').attr('href', id);
        $('#openfile').attr('href', id);
        $('#upload-ppsx').attr('href', id);
        $('#uploadnow-ppsx').attr('rel', id);
        $('#upload-ppsx01').attr('rel', id);
        $('#uploadnow-ppsx-cover').attr('rel', id);
      }, function(err) {
        console.log(err);
      });
    }