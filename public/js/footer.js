$(document).ready(function(){
    if($('body').height() < Math.max(document.documentElement.clientHeight, window.innerHeight || 0)){
        $('.footer').css('height','100vh');
    }
});