function run() {
  $('.mul').on('click', function() {
    var o = this.classList;
    var i = 0;
    var checkflag = false;
    while (typeof(o[i]) == 'string') {
      if (o[i] == 'mul_check') {
        /* already  checked so uncheck it */
        checkflag = true;
        break;
      }
      i++;  
    }
    $(this).parents('.item-question-ans').find('.mul').removeClass('mul_check');
    if (!checkflag) $(this).addClass('mul_check');
    else $(this).removeClass('mul_check');
    
  });
}
window.addEventListener('load', run);