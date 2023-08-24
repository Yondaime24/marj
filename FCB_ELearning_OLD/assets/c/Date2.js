function Date2() {
  this.target_date = 0;
  this.current_date = 0;
  this.limit = 0;
  this.elapsed = function() {
    var diff = this.current_date - this.target_date;
    var limit = this.limit;
    var sec = limit - diff;
    if (sec <= 0) return '0';
    var t = '';
    var tmp = 0;
    var day = 0;
    day = parseInt(sec / 86400);
    tmp = sec % 86400;
    t += day + ":";
    sec = tmp;
    
    day = parseInt(sec / 3600);
    tmp = sec % 3600;
    t += day + ":";
    sec = tmp;
    
    day = parseInt(sec / 60);
    tmp = sec % 60;
    t += day + ":";
    sec = tmp;
    t += sec;
    return t;
  }
}