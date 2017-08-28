function init_tribes() {
  toggleSearch("off");
  init_shadows();
  
  $('.card-hover').fancybox({
    'type': 'iframe',
    'width': 560,
    'height': 315,
    'href': $(this).siblings('.additional').find('#href').html()
  });
  //data-type\':\'iframe\',\'data-width\':560,\'data-fancybox\':\'Tribes\',\'data-height\':315})
  
  $('.card-hover').hover(function() {
    //enter
    $(this).parent().css({top: "5px"});
    var title_container = $(this).siblings('div.custom-title-overflow');
    title_container.css({'overflow-y': 'visible', 'z-index': 3});
    title_container.children('div').css({'background': 'rgba(147,7,7,0.75)'});
    $(this).siblings('div.tribe-single-logo-container').find('img').css({'opacity': 1.0});
  }, function() {
    //exit
    $(this).parent().css({top: "0"});
    var title_container = $(this).siblings('div.custom-title-overflow');
    title_container.css({'overflow-y': 'hidden', 'z-index': 'auto'});
    title_container.children('div').css({'background': 'rgba(147,7,7,0)'});
    $(this).siblings('div.tribe-single-logo-container').find('img').css({'opacity': 0.5});
  });
}