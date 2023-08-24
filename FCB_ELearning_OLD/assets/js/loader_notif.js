window.addEventListener('load', function() {
  document.querySelector('.top-loader').style.display = 'none';
  initClock();
  $('#loaderModal').modal('hide');
});
$('#loaderModal').modal('show');
$('#notif-btn').on('click', function() {
  notification_get();
});
