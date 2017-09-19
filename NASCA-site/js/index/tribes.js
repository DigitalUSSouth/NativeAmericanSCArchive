function init_tribes() {
  toggleSearch("off");
  init_shadows();
  
  /*$('.card-hover').each(function () {
    $(this).attr('href',SITE_ROOT + '/html/tribes.php');
  });*/
  $('.card-hover').fancybox({ 
    loop : true,
    //protect: true,
    //fitToView: false,
    autoSize: false,
    autoScale: false,
    scrolling: false,
    autoDimensions: false,
    //padding: 20,
    //width: 900,
    //height: 315,
    beforeShow: function (){
      $('.fancybox-inner').addClass('tribes-inner');
      $('.fancybox-slide').addClass('tribes-slide');
      
    },
    afterLoad: function (){
      var custom_fancybox = $('.tribes-history-container');
      custom_fancybox.height(custom_fancybox.width()*0.41096);
      $('.tribes-history-nav').hover(function() {
        //enter
        $(this).switchClass('background-grey','background-red',0);
      }, function() {
        //exit
        $(this).switchClass('background-red','background-grey',0);
      });
      $('.tribes-history-prev-container .tribes-history-nav').css('display','none');
      dynamic_css();
    },
    afterShow: function(){
      //load text for page 1
      tribes_history_page_load();
    }
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

  if (window.location.hash){//we might have a sub uri
    hash = window.location.hash
    if (hash.startsWith("#Tribes")){
      var tribeId = hash.substr(8);
      tribeId--;
      $('.card-hover[href="'+SITE_ROOT + '/html/tribes_history.php?tribe_id='+tribeId+'"').click();
    }
  }
}

function tribes_history_page_load() {
  var tribe_id = parseInt($('.tribes-history-text-container .additional #current-tribe').html());
  var page_num = parseInt($('.tribes-history-text-container .additional #current-page').html());
  var url = SITE_ROOT + '/html/tribes_history_page.php';
  url += '?tribe_id='+tribe_id+'&page_num='+page_num;
  $.ajax({
    type:'POST',
    url: url,
    async: true,
    dataType: 'html',
    success: function(data) {
      $('.tribes-history-text-container .tribes-history-text').html(data);
    }
  });
}

/*
 * direction - 'prev' or 'next'
 */
function tribes_history_page_change(direction,jtag) {
  var info = jtag.parent().siblings('.tribes-history-body').find('.tribes-history-text-container .additional');
  var tribe_id = parseInt(info.find('#current-tribe').html());
  //console.log(tribe_id);
  var current_page = parseInt(info.find('#current-page').html());
  //console.log(current_page);
  var tribes_data = getJsonObject(SITE_ROOT+'/db/data/tribes/data.json');
  var pages = tribes_data.data[tribe_id].pages;
  if(pages === 'undefined' || pages === null || pages <= 1) {
    pages = 1;
    $('.tribes-history-prev-container .tribes-history-nav').css('display','none');
    $('.tribes-history-next-container .tribes-history-nav').css('display','none');
    info.find('#current-page').html('1');
    tribes_history_page_load();
    return -1;
  }
  if(direction === 'prev') {
    if(current_page > 1) {
      current_page -= 1;
    }
  } else if(direction === 'next') {
    if(current_page < pages) {
      current_page += 1;
    }
  } else {
    return -2;
  }
  if(current_page <= 1) {
    $('.tribes-history-prev-container .tribes-history-nav').css('display','none');
    $('.tribes-history-next-container .tribes-history-nav').css('display','block');
  } else if(current_page >= pages) {
    $('.tribes-history-prev-container .tribes-history-nav').css('display','block');
    $('.tribes-history-next-container .tribes-history-nav').css('display','none');
  } else {
    $('.tribes-history-prev-container .tribes-history-nav').css('display','block');
    $('.tribes-history-next-container .tribes-history-nav').css('display','block');
  }
  info.find('#current-page').html(current_page.toString());
  tribes_history_page_load();
}