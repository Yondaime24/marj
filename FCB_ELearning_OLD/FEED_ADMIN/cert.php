<?php 
  require_once '../___autoload.php';
?>
<div style="padding:10px;">
  <h5 style="color:#626262;"><i class="fa fa-certificate"></i> Certificate Signatories<h5>
  <hr />
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
        <div style="background-color:white;padding:10px;">
          <select id='no' class="form-control" style="cursor:pointer;">
            <option value="1">Signatory 1</option>
            <option value="2">Signatory 2</option>
          </option>
          <input id="name" class="form-control" type="text" value="" placeholder="Display Name" style="margin-top:5px;" />
          <input id="pos" class="form-control" type="text" placeholder="Display Position" style="margin-top:5px;" />
          <button id="sub" class="btn btn-primary btn-sm" style="margin-top:5px;"><i class="fa fa-save"></i> Submit</button>
        </div>
      </div>
      <div class="col-md-9">
        <div style="background-color:white;width:100%;height:100%;">
          <iframe id="if" src="<?php print ADMIN_PATH; ?>route.php?r=topic_cert" style="width:100%;height:400px;"></iframe>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $('#no').on('change', function() {
    var no = $(this).val();
    loadSignatory(no);
  });
  function loadSignatory(no) {
    $.post('<?php print ADMIN_PATH; ?>route.php?r=getSig', {no: no}).then(function(resp) {
      var data = JSON.parse(resp);
      if (data.length > 0) {
        $('#name').val(data[0]['display_name']);
        $('#pos').val(data[0]['display_position']);
      }
    });
  }
  loadSignatory(1);
  var iurl = $('#if').attr('src');
  $('#sub').on('click', function() {
    var no = $('#no').val();
    var name = $('#name').val();
    var pos = $('#pos').val();
    $.post('<?php print ADMIN_PATH; ?>route.php?r=saveSign', {name: name, pos: pos, no: no}).then(function(resp) {
      var data = JSON.parse(resp);
      alert('Saved');
      $('#if').attr('src', iurl);
    });
  });
</script>