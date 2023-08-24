function clientTime() {
  var d = new Date();
  var t = "";
  t += FCBCalendar.week[d.getDay()] + ", " + FCBCalendar.monlong[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
  document.getElementById("profiletime").innerHTML = t;
}
clientTime();
setInterval(clientTime, 1000);
function searchlink(search) {

  window.location.href= "../search.php?search=" + search;
}
var search = $(".searchbar");
$(".sicon").on("click", function() {
  var s = search.val();
  searchlink(s);
});
search.on("keyup", function(evt) {
  if (evt.keyCode == 13) 
    searchlink($(this).val());
});
$(".user-menu button").on("click", function() {
  var link = $(this).attr("link");
  window.location.href=link;
});