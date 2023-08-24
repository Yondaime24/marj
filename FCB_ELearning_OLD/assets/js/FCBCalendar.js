/* FCB Calendar */
var FCBCalendar = {
  elem: null,
  sel: null,
  event: false,
  calarr: [],
  monshort: ["JAN", "FEB", "MAR", "APR", "MAY", "JUNE", "JUL", "AUG", "SEPT", "OCT", "NOV", "DEC"],
  monlong: ["JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"],
  month: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
  curyr: null,
  curmon: null,
  week: [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday"
  ],
  updatefeb: function(y) {
    if (y % 4 == 0)
      /* Leap year  */
      this.month[1] = 29;
    else this.month[1] = 28;
  },
  prevnext: function(month) {
    this.curmon += month;
    if (this.curmon > 12) {this.curyr++;this.curmon = 1;} else if (this.curmon < 1) { this.curmon = 12; this.curyr--; }
    this.start(this.sel, this.curyr + "-" + this.curmon + "-" + "01");
  },
  start: function(elem, cusdt = "") {
    this.elem = document.querySelector(elem);
    this.sel = elem;
    var curdt = null;
    var fcbobj = this;
    var dt;
    if (cusdt == "") {
      dt = new Date();
      curdt = dt;
    }
    else {
      dt = new Date(cusdt);
      curdt = new Date();
    }
    
    var curmon = curdt.getMonth() + 1;
    var curday = curdt.getDate()
    var curyear = curdt.getFullYear();

    var month = dt.getMonth();
    var year = dt.getFullYear();
    this.curyr = year;
    this.curmon = month + 1;
    var d1 = new Date(year + "-" + (month + 1) + "-01");
    var d1name = d1.getDay();
    //console.dir(dt);
    this.updatefeb(year);
    var getLastDay = this.month[month];
    var w = 1;
    var j = 1;
    var flag = false;
    var t = '<style>';
    t += '.cbox{color:#6e6e6e;font-weight:bolder;background-color:white;width:50px;height:40px;border:2px solid #beffc3;display:inline-block;padding:2px;}.cboxh{color:rgba(0,0,0,0);}.cl{display:inline-block;}';
    t += '.cprev{float:left;}';
    t += '.cnext{float:right;}';
    t += '.cboxcolor{background-color:#04e8ff;color:white;}';
    t += '.cpbutton{font-weight:bolder;border:none;border-radius:10px;padding:2px;width:40px;background-color:#6ebadf;color:white;}';
    t += '</style>';
    t +='<div style="padding:10px;">';
    t += '<div style="padding:20px;position:relative;width:390px;border-radius:10px;">';
    
    t += '<table style="width:100%;">';
    t += '<tr>';
    t += '<td>';
    t += '<button class="cpbutton cprev"><</button>'
    t += '</td>';
    
    t += '<td style="text-align:center;">';
    t += this.monlong[month] + " " + year;
    t += '</td>';
    
    t += '<td style="text-align:right;">';
    t += '<button class="cpbutton cnext">></button>';
    t += '</td>';
    
    t += '</tr>';
    t +=' </table>';
    while (true) {  
      if (j <= d1name) {
        t += '<div class="cbox cboxh" value="0">&nbsp;</div>';
      } else {
        if (curmon == this.curmon && curday == w && curyear == this.curyr) {
          t += '<div class="cbox cboxcolor" value="' + w + '">' + w + '</div>';
        } else {
          t += '<div class="cbox" value="' + w + '">' + w + '</div>';
        }
        flag = true;
      }
      if (j % 7 == 0) {
        t+= '<br />';
      }
      if (w == getLastDay) break;
      if (flag) w++;
      j++;
    }
    t += '</div>';
    t += '</div>';
    this.elem.innerHTML = t;

    /* Add event in prev and next*/
    if (!this.event) {
      $(this.elem).on("click", ".cprev", function() {
        fcbobj.prevnext(-1);
      });

      $(this.elem).on("click", ".cnext", function() {
        fcbobj.prevnext(+1);
      });
      this.event = true;
    }
  }
};