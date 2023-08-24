window.addEventListener('load', function() {
  var url = {
    topic: '../topics/topic.php?route='
  };
  var pptx = {
    id: null,
    filename: null,
    load: function() {
      $('.content-right').show();
      $(".contents .pptx-viewer").html('');
      $.post(url.topic + 'powerpoint', {topic_id: this.id}, function(resp) {
        try { 
          var o = JSON.parse(resp);
          var len = o.length;
          
          for (var i = 0; i < len; i++) {
            pptx.filename = o[i].name;
          }
          pptx.viewer();
        } catch(e) {console.log(e.toString());}
      });
    },
    viewer: function() {
      $(".pptx-viewer").pptxToHtml({
        pptxFileUrl: "../data/" + pptx.filename,
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
  }
  var topics = {
    load: function() {
      $.post(url.topic + "ListTopics", function(resp) {
        var t = "";
        try { 
          var o = JSON.parse(resp);
          var len = o.length;
          for (var i = 0; i < len; i++) {
            t += '<div class="menu-item" rel="' + o[i].id + '">';
            t += '  <div class="menu-title">' + o[i].title  + '</div>';
            t += '  <div class="menu-desc">' + o[i].des + '</div>';
            t += '</div>';
          }
          t += '<div class="loadmore"><i class="fa fa-table"></i> Load More</div>';
          $('.menu-content').html(t);
        } catch(e) {console.log(e.toString());}
      });
    }
  };
  //topics.load(); to load all topic in database

  /* Dropdown */
  $('.main-topic').on('click', function() {
    $('.main-topic .sub-topic').slideUp();
    // $('.main-topic .sub-topic').attr('');
    var e = $(this).find('.sub-topic');
    var s = e.attr('go');
    if (typeof(s) == 'undefined' || s == 'false') {
      e.slideDown();
      e.attr('go', 'true');
    } else {
      e.slideUp()
      e.attr('go', 'false');
    }
  });
  $('.main-topic .sub-topic .stt').on('click', function(e) {
    e.stopPropagation();
  });
  /* end Dropdown */ 

  /* Topics Panel */ 

  $('#addTopic').on('click', function() {
    $('#topic-panel').slideDown();
  });
  /* End Topics Panel  */ 

  $('#startquiz').on('click', function() {
    if (confirm('Are you sure you want to start the quizz?'))
      window.location.href='q_start.php';
  });

  $(".menu-content").on("click", ".menu-item", function() {
    var id = $(this).attr('rel');
    curindex = 0;
    pptx.id = id;
    pptx.load();
  });
 

  $(function () {
    var oldWidth,
      oldMargin,
      isFullscreenMode = false;
    $("#fullscreen").on("click", function () {
      // if (!isFullscreenMode) {
      //   oldWidth = $(".pptx-viewer .slide").css("width");
      //   oldMargin = $(".pptx-viewer .slide").css("margin");
      //   $(".pptx-viewer .slide").css({
      //     width: "99%",
      //     margin: "0 auto",
      //   });
      //   $(".pptx-viewer").toggleFullScreen();
      //   isFullscreenMode = true;
      // } else {
      //   $(".pptx-viewer .slide").css({
      //     width: oldWidth,
      //     margin: oldMargin,
      //   });
      //   $(".pptx-viewer").toggleFullScreen();
      //   isFullscreenMode = false;
      // }
      $(".pptx-viewer").toggleFullScreen();
    });
    $(document).bind("fullscreenchange", function () {
      if (!$(document).fullScreen()) {
        $(".pptx-viewer .slide").css({
          width: oldWidth,
          margin: oldMargin,
        });
      }
    });
  });

  

  
  $('#pptx-prev').on('click', function() {present(-1);});
  $('#pptx-next').on('click', function() {present(+1);});
  $('#opentopics').on('click', function() {
    present(0);
  });
  function loadCatForm() {
    var e = $('#panel-topic-form select[name=catid]');
    var len = catobj.length;
    var t = '';
    for (var i = 0; i < len; i++) {
      t += '<option value="' + catobj[i].id + '">' + catobj[i].title + '</option>';
    }
    e.html(t);
  } 
  $('#addTopic').on('click', function() {
    loadCatForm();    
    panelTopic.table();
  });
  
});