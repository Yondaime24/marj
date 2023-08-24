var t = '';
var catobj = null;
var cat = {
    get: function(id) {
      if (catobj == null)
        return null;
      var l = catobj.length;
      for (var i = 0; i < l; i++) {
        if (id == catobj[i].id)
          return catobj[i];
      }
      return null;
    },
    loaddom:function(o) {
      var len = o.length;
      t = '';
      t += '<div class="card">';
      t += '<div class="card-header">List of Category</div>';
      t += '<div class="card-body">';
      
      //start of table
      t += '<table class="table table-sm table-bordered table-striped">';
      t += '<thead>';
      t += '<tr>';
      t += '<td>Action</td>';
      t += '<th>Name</th>';
      t += '<th>Description</th>';
      t += '</tr>';
      t += '</thead>';
      t += '<tbody>';
      for (var i = 0; i < len; i++) {
        t += '<tr index="' + i + '">';
        t += '<td>';
        t += '<div class="btn-group">';
        t += '<button class="btn btn-info btn-sm edit"><i class="fa fa-edit"></i></button>';
        t += '<button class="btn btn-danger btn-sm trash"><i class="fa fa-trash"></i></button>';
        t += '<a href="../Quiz/CreateQuiz.php?obj=' + o[i].id  + '&type=CAT&group_id=" target="_new" class="btn btn-primary btn-sm">';
        t += 'Create Quiz';
        t += '</a>';
        t += '</div>';
        t += '</td>';
        t += '<td>' + o[i].title + '</td>';
        t += '<td>' + o[i].des + '</td>';
        t += '</tr>';
      }
      t += '</tbody>';
      t += '</table>';
      //end table

      t +='</div>'; /* end of card body */
      t += '</div>';
      $('#catgen').html(t);
    },
    load: function() {
      return $.post('../settings/index.php?route=getCat', function(resp) {
        var o = JSON.parse(resp);
        catobj = o;
        cat.loaddom(catobj);
      });
    }
  }
var data;

window.addEventListener('load', function() {
  $('#category-panel #close').on('click', function() {
    $('#category-panel').fadeOut();
  });
  $('#category-panel #cat-form').on('submit', function() {
    data = $(this).serialize();
    $.post('../settings/index.php?route=submitCat', data, function(resp) {
      var o = JSON.parse(resp);
      if (o.ok == 1) {
        $('#category-panel #cat-form input[name=id]').val('');
        $('#category-panel #cat-form input[name=title]').val('');
        $('#category-panel #cat-form textarea[name=des]').val('');
        alert(o.msg);
        cat.load();
      } else {
        alert(o.msg);
        
      }
    });
    return false;
  });
  $('#catgen').on('click', '.edit', function() {
    var i = $(this).parents('tr').attr('index');
    $('#category-panel #cat-form input[name=id]').val(catobj[i].id);
    $('#category-panel #cat-form input[name=title]').val(catobj[i].title);
    $('#category-panel #cat-form textarea[name=des]').val(catobj[i].des);
  });
  $('#catgen').on('click', '.trash', function() {
    var i = $(this).parents('tr').attr('index');
    var id = catobj[i].id;
    if (confirm('are you sure?'))
      $.post('../settings/index.php?route=trash', {id: id}, function(resp) {
        var o = JSON.parse(resp);
        if (o.ok) {
          cat.load();
          alert(o.msg);
        }
        else {
          alert(o.msg);
        }
      });
  });
});