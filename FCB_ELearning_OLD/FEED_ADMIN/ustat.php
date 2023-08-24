<div style="padding:10px;">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
        <label>Branch</label>
        <select class="form-control" id="branch">
        </select>
      </div>
      <div class="col-md-3">
        <label>User</label>
        <select class="form-control" id="user">
        </select>
      </div>
    </div>
  </div>
</div>
<script>
  $('#branch').select2();
  $('#user').select2();
</script>