<?php 
  use classes\Cert\FeedCertificate as cert;
  require_once '../___autoload.php';
  $c = new cert();
  $data = $c->getContent();
  $key = cert::getKey();
?>
<div style="padding:10px;">
  <h5 style="color:#626262;"><i class="fa fa-certificate"></i> Edit Certificate<h5>
  <hr />
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
        <div style="background-color:white;padding:10px;">
          <div class="list-group" style="font-size:12pt!important;">
            <div class="list-group-item" style="color:red;">
              *Pattern
            </div>
          <?php foreach ($key as $row => $value): ?>
            <div class="list-group-item">
              <?php print $row; ?> : <?php print $value; ?>
            </div>
          <?php endforeach; ?>
          </div>
          <br />
          <textarea class="form-control" id="content"><?php print isset($data[0]["cert_content"]) ? $data[0]["cert_content"] : ""; ?></textarea>
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
  var iurl = $('#if').attr('src');
  $('#sub').on('click', function() {
    var content = $('#content').val();
    $.post('<?php print ADMIN_PATH; ?>route.php?r=saveContent', {content: content}).then(function(resp) {
      alert('Saved');
      $('#if').attr('src', iurl);
    });
  });
</script>