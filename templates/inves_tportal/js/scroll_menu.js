jQuery(document).ready(function($) {    
    $('#next').click(function(){
     var menu = $('#menu');
           var menuML = $('#menu').css('margin-left');
menuML = parseInt(menuML)  -480;
     result = menu.animate({'margin-left': menuML}, 500);
      return result;
    });
  
      $('#prev').click(function(){
     var menu = $('#menu');
           var menuML = $('#menu').css('margin-left');
menuML = parseInt(menuML)  +480;
     result = menu.animate({'margin-left': menuML}, 500);
      return result;
    });
});