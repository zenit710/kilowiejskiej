$(document).ready(function() {
   $('#panels div').click(function() {
       $('#panels div').each(function() {
          $(this).attr('data-active','false');
          $(this).parent().find('ul').css('display','none');
       });
       $(this).attr('data-active','true');
       $(this).parent().find('ul').css('display','block');
   }) 
});