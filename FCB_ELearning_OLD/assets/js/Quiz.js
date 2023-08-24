///////////////////////////////////
//////////////////////////////////
/////////////////////////////////
// simple loader
var designer = {
  CreateQuestion: function(data = {}) {
    return $.ajax({url: '../Quiz/qdesigner.php?q=CreateQuestion', type: 'post', data: data});
  }
};
$('#createqbtn').on('click', function() {
  let t = $('#createqbtn').attr('rel');
  if (t == 'off') {
    $('#createqbtn').attr('rel', 'on');
    $('.listtype').slideDown();
  } else {
    $('.listtype').slideUp();
    $('#createqbtn').attr('rel', 'off');
  }
});
$('.typebtn').on('click', function() {
  let code = $(this).attr('rel');
  $('.listtype').hide();
  $('#createqbtn').attr('rel', 'off');
  gForms.multiple.clear();
  qchoice.clear();
  if (code == 'MUL') {
    gForms.multiple.id = '';
    gForms.multiple.type = code;
    // creating a question with the type of MUL
    $('#gen').append(gForms.multiple.draw());
  } else if (code == 'BL') {
    $('#gen').append(bl.draw());
  } else if (code == 'ENUM') {
    var en = new Qenum();
    $('#gen').append(en.draw());
    en = undefined;
  } else if (code == 'ESSAY') {
    var essay = new Essay();
    $('#gen').append(essay.draw());
    essay = null;
  }
});

// Question loader
var Q = {
  group_id: '',
  load: function() {
    return $.ajax({url: '../Quiz/r.php?route=GetQuiz', type: 'POST', data: {
      GroupId: Q.group_id
    }});
  }
};

// items
var qchoice = {
  id: '',
  QIId: '',
  Question: '',
  Type: '',
  save: function() {
    return $.ajax({url: '../Quiz/r.php?route=AddItemtoQuestion', type: 'post', data: {
        Id: qchoice.id,
        QIId: qchoice.QIId,
        Question: qchoice.Question,
        Type: qchoice.Type
    }});
  },
  clear: function() {
    qchoice.id = '';
    qchoice.QIId = '';
    qchoice.Type = '';
    qchoice.Question = '';
  }, 
  trash: function() {
    return $.ajax({url: '../Quiz/r.php?route=ChoiceItemTrash', type: 'post', data: {
        id: qchoice.id 
    }});
  },
  key: function() {
    var data = {q_id: this.QIId, c_id: this.id};
    return $.ajax({url: '../Quiz/r.php?route=key-answer', type: 'post', data: data});
  }
};
// Question objects
var question = {
  //quesion id
  id: '',
  Question: '',
  AnsKey: '',
  QCCode: '',
  Type: '',
  QGId: '',
  points: '',
  save: function() {
    // saving the question items 
    // use for saving and updating the data
    return $.ajax({url: '../Quiz/r.php?route=AddQuestionItem', type: 'post', data: {
      QIId: this.id,
      Question: this.Question,
      AnsKey: this.AnsKey,
      QCCode: this.QCCode,
      QGId: this.QGId,
      Type: this.Type,
      points: this.points
    }});
  },
  trash: function() {
    return $.ajax({url: '../Quiz/r.php?route=trashQ', type: 'post', data: {id: question.id}});
  },
  clear: function() {
    this.id = '';
    this.Question = '';
    this.AnsKey = '';
    this.QCCode = '';
    this.Type = '';
    this.QGId = '';
  }
};

var gForms = {
  multiple: {
    current_item_id: null,
    id: '',
    type: '',
    choice_id: '',
    qtext: '',
    // choices list for the multiple choices  
    clist: '',
    active: '',
    choice: function(v) {
      return '<li i_id="' + this.choice_id + '" class="choicelist-0101" style="min-height:30px;cursor:pointer;' + this.active + '"> ' + v + '</li>';
    },
    draw: function() {
      return '\
        <div class="qmultiple" q_id="' + this.id + '" q_type="' + this.type + '" style="position:relative;">\
          <div class="qcon" style="border-bottom:1px solid grey;padding-bottom:5px;min-height:50px;">\
            <div class="question-txt" style="font-weight:bold;">' + this.qtext + '</div>\
            <ul class="question-choice" style="list-style-type:circle;">\
            ' + this.clist + '\
            </ul>\
            <div class="choices-wrap" style="display:none;">\
              <textarea class="tf" placeholder="Enter Choices" style="width:270px;padding:3px;height:50px;border:2px solid #2da179;"></textarea>\
              <br />\
              <div class="btn-group">\
                <button class="save btn btn-primary btn-sm">Save</button>\
                <button class="close btn btn-primary btn-sm">Close</button>\
                <button class="edit btn btn-primary btn-sm">Edit the question</button>\
              </div>\
            </div>\
          </div>\
          <div class="qform" style="display:none;">\
            <div class="form-group">\
              <textarea placeholder="Enter the question" class="tf" name="" style="border:3px solid #2da179;border-radius:10px;padding:5px;width:100%;margin-top:5px;">' + this.qtext  + '</textarea>\
            </div>\
            <div class="btn-group" style="margin-top:10px;">\
              <button class="save btn btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>\
              <button class="cancel btn btn-primary btn-sm"><i class="fa fa-recycle"></i> Remove</button>\
              <button class="btn btn-primary btn-sm close"><i class="fa fa-close"></i> Close</button>\
            </div>\
            <div style="border-bottom:1px solid grey;padding-bottom:10px;"></div>\
          </div>\
          <button class="main-edit btn btn-primary btn-sm" style="position:absolute;top:2px;right:5px;"><i class="fa fa-edit"></i> Edit</button>\
          <div style="width:250px;height:60px;position:absolute;background-color:#0be50b;z-index:50;top:-25px;left:0px;right:0px;bottom:0px;margin:auto;border-bottom:2px solid white;display:none;;" class="menu-choice">\
            <div class="list-group">\
              <div class="list-group-item" style="color:white;background-color:#0fbf4a !important;">\
                <i class="fa fa-cog"></i> Option\
              </div>\
              <div class="list-group-item choice-key">\
                <i class="fa fa-check"></i> Answer Key\
              </div>\
              <div class="list-group-item choice-edit">\
                <i class="fa fa-edit"></i> Edit\
              </div>\
              <div class="list-group-item choice-remove">\
                <i class="fa fa-trash"></i> Remove\
              </div>\
              <div class="list-group-item choice-close">\
                <i class="fa fa-close"></i> Close\
              </div>\
            </div>\
          </div>\
        </div>\
        ';
    },
    clear: function() {
      this.current_item_id = null;
      this.id = '';
      this.type = '';
      this.choice_id = '';
      this.qtext = '';
      this.clist = '';
      this.active = '';
    }
  }
}
// Functionalities 

/* Multiple Choices */
//save button
$('#gen').on('click', '.qmultiple .save', function() {
  let parent = $(this).parents('.qmultiple');
  let val = parent.find('.qform .tf').val();
  if (val.trim() == '') return alert('Provide a question please!');

  parent.find('.qcon').find('.question-txt').html(val);
  parent.find('.qform').hide();
  parent.find('.choices-wrap').show();
  // saving the data to database
  //retrieve the id,, it is useful especialy in the update fo the question items
  question.id =  parent.attr('q_id');
  question.Question = val;
  question.AnsKey = '';
  question.QCCode = quiz.type;
  question.Type = 'MUL';
  question.QGId = quiz.group_id;
  question.save().then(function(resp) {
    let data = JSON.parse(resp);
    let lastId = data.LastId;
    parent.attr('q_id', lastId);
    // save sucess
  }, function() {
    console.log('Problem Connecting to the server!');
  });
  // Working
  // end saving to database
});

// Cancel
$('#gen').on('click', '.qmultiple .cancel', function() {
  let parent = $(this).parents('.qmultiple');
  // remove q in the table
  let id = parent.attr('q_id');

  if (confirm('Remove this Question?'))  {
    question.id = id;
    question.trash().then(function(resp) {
       parent.remove();
       question.clear();
    });
  }
});
// pressing the edit the question

$('#gen').on('click', '.qmultiple .choices-wrap .edit', function() {
  let parent = $(this).parents('.qmultiple');
  parent.find('.choices-wrap').hide();
  parent.find('.qform').show();
});
// presing the saving choices
$('#gen').on('click', '.qmultiple .choices-wrap .save', function(evt) {
  let parent = $(this).parents('.qmultiple');
  let v = parent.find('.choices-wrap').find('.tf').val();
  if (v.trim() == '') return alert('Please fill up the field!');
  parent.find('.choices-wrap').find('.tf').val('');
  
  /*
  **  id: '',
  **  QIId: '',
  **  Question: '',
  **  Type: '',
  **  qchoic.save();
  */

  // Saving to the database
  let q_id = parent.attr('q_id');
  let q_type = parent.attr('q_type');
  
  if (gForms.multiple.current_item_id != null)
    // Getting the id of the choice for update operation
    qchoice.id = gForms.multiple.current_item_id[0].attributes.i_id.nodeValue;
  
  qchoice.QIId = q_id;
  qchoice.Type = q_type;
  qchoice.Question = v;
  qchoice.save().then(function(resp) {
    let data = JSON.parse(resp);
    
    let last_id = data.LastId;
    // adding the design to the html document
    if (gForms.multiple.current_item_id == null) {
      // insert operation
      gForms.multiple.choice_id = last_id;
      parent.find('.question-choice').append(gForms.multiple.choice(v));
    } else {
      // update operation
      gForms.multiple.current_item_id.html(v);
     
    }
    gForms.multiple.current_item_id = null;
    qchoice.clear();
    
    //parent.find('.choices-wrap').hide();
    parent.find('.main-edit').show();
  });
  // database saving end

 
  

});
// pressing the close button 
$('#gen').on('click', '.qmultiple .choices-wrap .close', function() {
  let parent = $(this).parents('.qmultiple');  
  parent.find('.choices-wrap').hide();
  parent.find('.main-edit').show();
  gForms.multiple.current_item_id = null;  
});
// pressing the main edit button
$('#gen').on('click', '.qmultiple .main-edit', function() {
  let parent = $(this).parents('.qmultiple');
  $(this).hide();
  parent.find('.qform').show();
}); 
// Double click to delete the choices items
$('#gen').on('click', '.qmultiple .choicelist-0101', function(evt) {
  $('.menu-choice').hide();
  let id = $(this).attr('i_id');
  let elem = $(this)
  let parent = $(this).parents('.qmultiple');
  var menu = parent.find('.menu-choice');
  // console.log(evt);
  // menu.css('left', '0px');
  // console.log(menu);
  menu.show();
  gForms.multiple.current_item_id = elem;

  //if (!confirm('Remove Item?')) return;
 
  // qchoice.id = id;
  // qchoice.trash().then(function(resp) {
  //   elem.remove();
  //   qchoice.clear();
  // }, function() {
  //   console.log('Problem Connecting to the server!');
  // });

  // Removing the items in the database
  
})

// choice menu edit
$('#gen').on('click', '.qmultiple .menu-choice .choice-key', function() {
  var el = gForms.multiple.current_item_id;
  var c_id = el.attr('i_id');
  var q_id = el.parents('.qmultiple').attr('q_id');
  // free the variable
  gForms.multiple.current_item_id = null;
  if (!confirm('Are you sure? this is the answer key?')) return;
  // add the key answer to the question
  qchoice.id = c_id;
  qchoice.QIId = q_id;
  qchoice.key().then(function(resp) {
    el.parents('.qmultiple').find('ul').find('li').attr('style', 'min-height:30px;cursor:pointer;');
    el.attr('style', 'min-height:30px;cursor:pointer;color:green;font-weight:bold;');
  });
});

$('#gen').on('click', '.qmultiple .menu-choice .choice-edit', function() {
  let id = $(this).attr('i_id');
  let elem = $(this)
  let parent = $(this).parents('.qmultiple');
  parent.find('.menu-choice').hide();
  
  $('.qform').hide();
  $('.choices-wrap').hide();
  parent.find('.qform').hide();
  parent.find('.choices-wrap').show();

  parent.find('.choices-wrap').find('.tf').val(gForms.multiple.current_item_id[0].innerText);

  // Removing the items in the database
  //let data = $(this).find('').html().trim();
  //parent.find('.choices-wrap').find('.tf').val(data);
  //gForms.multiple.current_item_id = $(this);
})
// choice menu close
$('#gen').on('click', '.qmultiple  .menu-choice .choice-close', function() {
  let parent = $(this).parent('.qmultiple');
  $('.menu-choice').hide();
  gForms.multiple.current_item_id = null;
});
$('#gen').on('click', '.qmultiple  .menu-choice .choice-remove', function() {
  let parent = $(this).parents('.qmultiple');
  
  parent.find('.menu-choice').show();

  

  if (!confirm('Remove Item?')) return;
  gForms.multiple.current_item_id.remove();

  qchoice.id = gForms.multiple.current_item_id.attr('i_id');
  qchoice.trash().then(function(resp) {
    qchoice.clear();
    $('.menu-choice').hide();
  }, function() {
    console.log('Problem Connecting to the server!');
  });
  gForms.multiple.current_item_id = null;
});
$('#gen').on('click', '.qmultiple .menu-choice .choice-key', function() {
  $('.menu-choice').hide();
  gForms.multiple.current_item_id = null;  
});
// $('#gen').on('click', '.qmultiple .choicelist-0101', function() {
//   let parent = $(this).parents('.qmultiple');
//   let id = $(this).attr('i_id');
//   // Removing the items in the database
//   let data = $(this).html().trim();
//   parent.find('.choices-wrap').find('.tf').val(data);
//   gForms.multiple.current_item_id = $(this);
// });
// click the cancel button
$('#gen').on('click', '.qmultiple .qform .close', function() {
  let parent = $(this).parents('.qmultiple');
  parent.find('.qform').hide();
  parent.find('.main-edit').show();
});
/* End Multiple choies */
///////////////////////////////////////////
///////////////////////////////////////////
///////////////////////////////////////////
///////////////////////////////////////////





/* For True and false question  */
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\//
var bl = {
  q_id: '',
  q_true: '',
  q_false: '',
  q_txt: '',
  activeTxt: 'background-color:#00b500;',
  // set the true or false
  keyTrue: null,
  keyFalse: null,
  draw: function() {
    var _true = '', _false = '';
    if (this.keyTrue == true)
      _true = this.activeTxt;
    if (this.keyFalse == true)
      _false = this.activeTxt;
    var data =  '\
      <div type="BL" q_id="' + this.q_id  + '" class="bl-q" style="position:relative;padding-top:10px;border-bottom:1px solid grey;padding-bottom:10px;">\
        <div class="bl-con" style="">\
          <div class="bl-q-txt" style="font-weight:bold;">\
          ' + this.q_txt + '\
          </div>\
          <div class="bl-q-choice">\
            <div class="btn-group">\
              <button class="btn btn-primary btn-sm q_true qtf" c_id="' + this.q_true + '" style="' + _true + '">True</button>\
              <button class="btn btn-primary btn-sm q_false qtf" c_id="' + this.q_false  + '" style="' + _false  + '">False</button>\
            </div>\
          </div>\
        </div>\
        \
        <div class="btn-group bl-edit-btn" style="position:absolute;right:5px;top:10px;">\
          <button class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>\
        </div>\
        <div class="bl-q-input" style="position:relative;margin-top:20px;display:none;">\
          <textarea placeholder="Enter Question" class="form-control tf">' + this.q_txt  + '</textarea>\
          <div class="btn-group" style="margin-top:10px;">\
            <button class="btn btn-primary btn-sm submit"><i class="fa fa-save"></i> Submit</button>\
            <button class="btn btn-primary btn-sm delete"><i class="fa fa-recycle"></i> Remove</button>\
            <button class="btn btn-primary btn-sm close"><i class="fa fa-close"></i> Close</button>\
          </div>\
        </div>\
      </div>\
    ';
    this.clear();
    return data;
  },
  clear: function() {
    this.keyTrue = null;
    this.keyFalse = null;
    this.q_id = '';
    this.q_true = '';
    this.q_fales = '';
    this.q_txt = '';
  }
};

// Submit the 
$('#gen').on('click', '.bl-q .bl-q-input .btn-group .submit', function() {
  var parent = $(this).parents('.bl-q');
  var id = parent.attr('q_id');
  var tf = parent.find('.tf');
  var type = parent.attr('type');
  var tf_view = parent.find('.bl-q-txt');
  if (tf.val().trim() == '')
    return alert('Please Provide a Question');


  question.id =  parent.attr('q_id');
  question.Question = tf.val();
  question.AnsKey = '';
  question.QCCode = quiz.type;
  question.Type = 'BL';
  question.QGId = quiz.group_id;
  question.save().then(function(resp) {
    let data = JSON.parse(resp);
    let lastId = data.LastId;
    parent.attr('q_id', lastId);
    // save sucess
    // Once Successful save to database
    // get the true id
    var _true = JSON.parse(data.t);
    // get the false id
    var _false = JSON.parse(data.f);
    // plot all the id in the save operation
    parent.find('.q_true').attr('c_id', _true.LastId);
    parent.find('.q_false').attr('c_id', _false.LastId);
    // end of plotting the id

    tf_view.html(tf.val());
    parent.find('.bl-con').show();
    parent.find('.bl-edit-btn').show();
    parent.find('.bl-q-input').hide();
  }, function() {
    console.log('Problem Connecting to the server!');
  });
});

// pressing the edit button
$('#gen').on('click', '.bl-edit-btn', function() {
  var parent = $(this).parents('.bl-q');
    parent.find('.bl-con').hide();
    parent.find('.bl-edit-btn').hide();
    parent.find('.bl-q-input').show();
});

// close
$('#gen').on('click', '.bl-q .bl-q-input .btn-group .close', function() {
  var parent = $(this).parents('.bl-q');
  parent.find('.bl-con').show();
  parent.find('.bl-edit-btn').show();
  parent.find('.bl-q-input').hide();
});

// delete
$('#gen').on('click', '.bl-q .bl-q-input .btn-group .delete', function() {
  var parent = $(this).parents('.bl-q');
  var q_id = parent.attr('q_id');
  /*
  **   trash: function() {
  **  return $.ajax({url: '../Quiz/r.php?route=trashQ', type: 'post', data: {id: question.id}});
  **},
  **
  */
  if (confirm('Delete this question?')) {
    question.id = q_id;
    question.trash().then(function() {
      parent.remove();
      alert('removed!');
    }, function() {
      alert('Internet Connection is bad!');
    });
  }
});
// Setting the answer key of the true or false
// for true
function setBlAnsKey(id, q_id, m) {
  // var qchoice = {
  // id: '',
  // QIId: '',
  // Question: '',
  // Type: '',
  qchoice.id = id;
  qchoice.QIId = q_id;
  qchoice.key().then(function(resp) {
    m.attr('style', bl.activeTxt);
  });
}
$('#gen').on('click', '.q_true', function() {
  var parent = $(this).parents('.bl-q');
  var q_id = parent.attr('q_id');
  var c_id = $(this).attr('c_id');
  if (!confirm('Set as answer key?'))return;
  parent.find('.qtf').attr('style', '');
  setBlAnsKey(c_id, q_id, $(this));
});
// for the false
$('#gen').on('click', '.q_false', function() {
  var parent = $(this).parents('.bl-q');
  var q_id = parent.attr('q_id');
  var c_id = $(this).attr('c_id'); 
 if (!confirm('Set as answer key?'))return;
  parent.find('.qtf').attr('style', '');
  setBlAnsKey(c_id, q_id,  $(this));
});
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\//
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/* end for the true and false question*/


///////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
/////////////////////// Start for enumeration ////////////////////////
// enumeration class
function Qenum() {
  this.q_id = '';
  this.qtxt = '';
  this.tf = 'display:none;';
  this.list = {
    html: '',
    c_id: '',
    txt: '',
    draw: function() {
      var html = '<div class="list-group-item items01" style="cursor:pointer;" c_id="' + this.c_id  + '">' + this.txt  + '</div>';
      this.html += html;
      return html;
    }
  };

  this.draw = function() {
    return '<div class="enum" q_id="' + this.q_id  + '" style="border-bottom:1px solid grey;padding-bottom:10px;position:relative;min-height:50px;">\
      <div class="key" style="margin-bottom:10px;min-height:50px;'+ this.tf +'">\
        <div class="q-text" style="min-height:50px;font-weight:bold;">\
          ' + this.qtxt + '\
        </div>\
        <div class="lbl">List of Answer:</div>\
        <div class="list-group list-item list-item00001" style="position:relative;">\
          ' + this.list.html  + '\
        </div>\
        <div class="q-tf" style="display:none;position:relative;">\
          <input type="text" class="form-control cc" placeholder="Enter to submit"/>\
          <button class="btn btn-danger btn-sm" style="position:absolute;right:5px;top:5px;"><i class="fa fa-close"></i></button>\
        </div>\
        <button class="btn btn-primary btn-sm add-ans-btn" style="margin-top:10px;"><i class="fa fa-plus"></i> Add Answer</button>\
      </div>\
      <div class="input" style="display:none;">\
        <textarea class="form-control tf" placeholder="Enter the question">' + this.qtxt  + '</textarea>\
        <div class="btn-group" style="margin-top:5px;">\
          <button class="btn btn-primary btn-sm save"><i class="fa fa-save"></i> Save</button>\
          <button class="btn btn-primary btn-sm trash"><i class="fa fa-recycle"></i> Remove</button>\
          <button class="btn btn-primary btn-sm close"><i class="fa fa-close"></i> Close</button>\
        </div>\
      </div>\
      <button class="btn btn-primary btn-sm editq" style="position:absolute;top:10px;right:5px;"><i class="fa fa-edit"></i> Edit</button>\
    </div>';
  }
}
// save the question

$('#gen').on('click', '.enum .input .save', function() {
  /*
      id: '',
      Question: '',
      AnsKey: '',
      QCCode: '',
      Type: '',
      QGId: '',
      save: function() {
        // saving the question items 
        // use for saving and updating the data
        return $.ajax({url: '../Quiz/r.php?route=AddQuestionItem', type: 'post', data: {
          QIId: this.id,
          Question: this.Question,
          AnsKey: this.AnsKey,
          QCCode: this.QCCode,
          QGId: this.QGId,
          Type: this.Type
        }});
      },
  */
  var parent = $(this).parents('.enum');
  var tf = parent.find('.input').find('.tf');
  var id = parent.attr('q_id');

  question.id = id;
  question.Question = tf.val();
  question.QGId = quiz.group_id;
  question.QCCode = quiz.type;
  question.Type = 'ENUM';

  question.save().then(function(resp) {
    var o = JSON.parse(resp);
    parent.attr('q_id', o.LastId);
    parent.find('.q-text').html(tf.val());
    parent.find('.input').hide();
    parent.find('.editq').show();
    parent.find('.key').show();
    parent.find('.add-ans-btn').show(); 
  }, function() {
    alert('Something went wrong!');
  });
});
// clicking the main edit
$('#gen').on('click', '.enum .editq', function() {
  var parent = $(this).parents('.enum');
  parent.find('.input').show();
  parent.find('.key').hide();
  $(this).hide();
});
// clicking the close
$('#gen').on('click', '.enum .input .close', function() {
  var parent = $(this).parents('.enum');
  parent.find('.input').hide();
  parent.find('.editq').show();
});

$('#gen').on('click', '.enum .input .trash', function() {
  var parent = $(this).parents('.enum');
  var q_id = parent.attr('q_id');
  // if the question id in dom is empty then directly delete the dom elements
  if (q_id == '') {
    parent.remove();
    return ;
  }
  // confirmation before deleting the question
  if (confirm('Do you want to remove this question?')) {
    question.id = q_id;
    question.trash().then(function() {
      parent.remove();
    });
  }
});

// adding answer key
$('#gen').on('click', '.enum .key .add-ans-btn', function() {
  var parent = $(this).parents('.enum');
  parent.find('.add-ans-btn').hide();
  parent.find('.q-tf').show();
});
// closing the add textfield for list of answer
$('#gen').on('click', '.enum .key .q-tf button', function() {
  var parent = $(this).parents('.enum');
  parent.find('.q-tf').hide();
  //parent.find('.add-ans-btn').show();
});
// adding the answer list
$('#gen').on('keyup', '.key .cc', function(evt) {
  var qenum = new Qenum();
  var parent = $(this).parents('.enum');
  var group = parent.find('.list-item');
  var v = $(this);
  var q_id = parent.attr('q_id');

  if (q_id == '') return;

  if (evt.keyCode == 13) {
      
    qchoice.id = '';
    qchoice.QIId = q_id;
    qchoice.Question = v.val();
    qchoice.Type = 'ENUM';
    qchoice.save().then(function(resp) {
      var data = JSON.parse(resp);
      qenum.list.c_id = data.LastId;
      qenum.list.txt = v.val();
      group.append(qenum.list.draw());
      v.val('');
      qenum = undefined;
    }, function() {
      alert('Something went wrong!');
    });
  }
  /*
    // items
    var qchoice = {
      id: '',
      QIId: '',
      Question: '',
      Type: '',
      save: function() {
        return $.ajax({url: '../Quiz/r.php?route=AddItemtoQuestion', type: 'post', data: {
            Id: qchoice.id,
            QIId: qchoice.QIId,
            Question: qchoice.Question,
            Type: qchoice.Type
        }});
      },
      clear: function() {
        qchoice.id = '';
        qchoice.QIId = '';
        qchoice.Type = '';
        qchoice.Question = '';
      }, 
      trash: function() {
        return $.ajax({url: '../Quiz/r.php?route=ChoiceItemTrash', type: 'post', data: {
            id: qchoice.id 
  */

  
});
// removing the items
$('#gen').on('dblclick', '.items01', function() {
  var parent = $(this).parents('.enum');
  var item = $(this);
  if (confirm('Removed?')) {
    var c_id = item.attr('c_id');
    qchoice.id = c_id;
    qchoice.trash().then(function() {
      alert('Removed!');
       item.remove();
    });
  }
});

//////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
////////////////////// End for enumeration /////////////////////////////////


 ///////////////////////////////////////////////////////
//////////////////////////////////////////////////////
// start for the essay
function Essay() {
  this.q_txt = '';
  this.q_id = '';
  this.q_type = '';
  this.points = '';
  this.ht = '<br />Points:';
  this.draw = function() {
    var tfpoint = this.ht + this.points;
    return '<div q_id="' + this.q_id + '" class="q_essay" style="margin-top:10px;border-bottom:1px solid grey;min-height:60px;position:relative;" q_type="">\
      <div class="q_txt"><span class="q" style="font-weight:bolder;">' + this.q_txt + '</span><span class="s" style="font-weight:normal;">' + tfpoint + '</span></div>\
      <div class="w_input" style="display:none;">\
        <div class="q_input"><textarea class="form-control tf" placeholder="Enter Question">' + this.q_txt  + '</textarea></div>\
        <div class="point" style="margin-top:10px;"><input type="number" class="form-control sf" value="' + this.points + '" placeholder="Enter Point/Score" /></div>\
      </div>\
      <div class="btn-group btn-menu" style="margin-top:10px;display:none;">\
        <button class="btn btn-primary btn-sm save"><i class="fa fa-save"></i> Submit</button>\
        <button class="btn btn-primary btn-sm trash"><i class="fa fa-recycle"></i> Remove</i></button>\
        <button class="btn btn-primary btn-sm close"><i class="fa fa-close"></i> Close</button>\
      </div>\
      <button class="btn btn-primary btn-sm edit" style="position:absolute;top:10px;right:5px;"><i class="fa fa-edit"></i> Edit</button>\
    </div>\
    ';
  }
}
// close
$('#gen').on('click', '.close', function() {
  var parent = $(this).parents('.q_essay');
  parent.find('.w_input').hide();
  parent.find('.btn-menu').hide();
}); 
// edit
$('#gen').on('click', '.edit', function() {
  var parent = $(this).parents('.q_essay');
  parent.find('.w_input').show();
  parent.find('.btn-menu').show();
  
});
// removing the question

$('#gen').on('click', '.trash', function() {
  var parent = $(this).parents('.q_essay');
  question.id = parent.attr('q_id');
  if (confirm('Are you sure?')) {
    question.trash().then(function() {
      parent.remove();
      alert('Removed Succesfully!');
    });
  }
});
$('#gen').on('click', '.save', function() {
  var es = new Essay();
  var parent = $(this).parents('.q_essay');
  var q_txt = parent.find('.tf').val();
  var q_score = parent.find('.sf').val();;
  var q_id = parent.attr('q_id');
  var q_type = 'ESSAY';
  question.id = q_id;
  question.Type = q_type;
  question.QGId = quiz.group_id;
  question.QCCode = quiz.type;
  question.Question = q_txt;
  question.points = q_score;
  question.save().then(function(resp) {
    var o = JSON.parse(resp);
    parent.find('.q_txt').find('.q').html(q_txt);
    parent.find('.q_txt').find('.s').html(es.ht + q_score);
    parent.attr('q_id', o.LastId);
    parent.attr('q_type', q_type);
    es = undefined;
    parent.find('.w_input').hide();
    parent.find('.btn-menu').hide();
  });
});
// end of essay
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
////////////////////////////////////////////////////



/* Retrieving the questionaier  */
function Q_plot(o) {
  let group = JSON.parse(atob(o));
  // retrieve the question list
  let q = group.Data;
  // length of the question items
  let glen = q.length;

  let qtext =  '';
  let qtype = '';
  let id = '';
  // variables use int the loop b
  let clen = 0;
  let cid = '';
  let cdes = '';
  let ctxt = '';
  var points = '';
  // fill the list if it is the key answer
  
  let gactive = 'color:green;font-weight:bold;';
  $('#group_txt').html(group.Group.Title + ' <div style="font-weight:normal;font-size:12pt;margin-top:10px;">INFO: Created ' + group.Group.age + ' ago' + '</div>');
  for (let a = 0; a < glen; a++) {
    // rendered all the question
    qtext = q[a].Question.Question;
    // Question type (Multiple choice, essay )
    qtype = q[a].Question.type;
    // Id of each question
    id = q[a].Question.QIId;
    // points
    points = q[a].Question.points;
    if (qtype == 'MUL') {
      // displaying the design to the html
      gForms.multiple.qtext = qtext;
      gForms.multiple.id = id;
      gForms.multiple.type = qtype;
      
      // retrieving all the list of the Choices 
      clen = q[a].Items.length;
      ctxt = '';

      for (let b = 0; b < clen; b++) {
        cdes = q[a].Items[b].Des;
        cid = q[a].Items[b].QICId;
        gForms.multiple.choice_id = cid;

        //means that it is the answer key
        if (q[a].Items[b].IsAnsKey == '1')
          gForms.multiple.active = gactive;
          //console.log(q[a].Items[b].IsAnsKey);
        ctxt += gForms.multiple.choice(cdes);
        gForms.multiple.active = '';
      }
      gForms.multiple.clist = ctxt;
      $('#gen').append(gForms.multiple.draw());
      // clear field
      gForms.multiple.qtext = "";
      gForms.multiple.id = "";
      gForms.multiple.type = "";
    } // endif the type mul
    // this condescion is for the true or false items only
    // TRUE or FALSE
    //////////////////////////////////////////////////////
    //////////////////////////////////////////////////////
    if (qtype == 'BL') {
      // true or false
      // get the true and false id
      clen = q[a].Items.length;
      for (var b = 0; b < clen; b++) {
        if (q[a].Items[b].Des == 'True') {
          if (q[a].Items[b].IsAnsKey == '1') {
            bl.keyTrue = true;
          }
          bl.q_true = q[a].Items[b].QICId;
        }
        if (q[a].Items[b].Des == 'False') {
          if (q[a].Items[b].IsAnsKey == '1'){
            bl.keyFalse = true;
          }
          bl.q_false = q[a].Items[b].QICId;
        }
      }  
      //bl.q_true = (q[a].Items[b].)
      //bl.q_false = 
      bl.q_id = id;
      bl.q_txt = qtext;

      $('#gen').append(bl.draw());
    }
    //////////////////////////////////////////////////////
    //////////////////////////////////////////////////////

    //////////////////////////////////////////////////////
    /////////////////////////////////////////////////////
    // Enumeration 
    if (qtype == 'ENUM') {
       clen = q[a].Items.length;

      var n1 = new Qenum();
      n1.qtxt = qtext;
      n1.q_id = id;
      n1.tf = '';
      for (var b = 0; b < clen; b++) {
        n1.list.c_id = q[a].Items[b].QICId;
        n1.list.txt = q[a].Items[b].Des;
        n1.list.draw();
      }
      $('#gen').append(n1.draw());
      n1 = undefined;
    } 
    // end enumeration
    /////////////////////////////////////////////////////
    /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////
    // Essay
    if (qtype == 'ESSAY') {
      var es = new Essay();
      es.q_id = id;
      es.q_txt = qtext;
      es.q_type = qtype;
      es.points = points;
      $('#gen').append(es.draw());
    }
    // End Essay
    ///////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////
  }
}



Q.group_id = quiz.group_id;
Q.load().then(function(resp) {
  // plot all the respons from the server
  Q_plot(resp);
  document.querySelector('.top-loader').style.display = 'none';
});
/* End Retrieving the questionaier */