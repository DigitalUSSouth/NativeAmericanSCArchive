$(function(){
    $('.hold1').data('size','big');
});

$(window).scroll(function(){
    if($(document).scrollTop() > 0)
    {
        if($('.hold1').data('size') == 'big')
        {
            $('.hold1').data('size','small');
            $('.hold1').stop().animate({
                height:'40px'
            },600);
        }
    }
    else
    {
        if($('.hold1').data('size') == 'small')
        {
            $('.hold1').data('size','big');
            $('.hold1').stop().animate({
                height:'128px'
            },600);
        }  
    }
});