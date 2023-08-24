function include(path) {
  var script = $("script");
  var script_len = script.length;
  var mod = ROOT_PATH + "assets/c/" + path;
  for (var i = 0; i < script_len; i++) {
    if (script.eq(i).attr("src") == mod) {
      console.log("Warning: Duplicate module " + mod);
      return;
    }
  } 
  $("body").prepend('<script src="' + mod  + '"></script>');
}