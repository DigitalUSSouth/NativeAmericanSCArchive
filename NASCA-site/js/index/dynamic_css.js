/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER, currentPage */

//<!-- //for old browsers

if(window.attachEvent) {
  window.attachEvent('onresize', function() {
    dynamic_css();
  });
}
else if(window.addEventListener) {
  window.addEventListener('resize', function() {
    dynamic_css();
  }, true);
}
else {
    //The browser does not support Javascript event binding
}

/*
 * Whenever the window is loaded, resized, 
 */
function dynamic_css() {
  //set anything in index
  responsive_font('#search-text','window',2.273,22);
  var newWid = ($('#header-left').height()+48)*2.726;
  var newWid2 = $(window).width()*0.2411;
  if(newWid < newWid2) {
    $('#header-left').css('min-width',newWid2+2);
  } else {
    $('#header-left').css('min-width',newWid+2);
  }
  newWid = $('#search-container').height()*6.2143;
  $('#search-container').width(newWid);
  $('#search-text').css({'line-height':$('#search-text').height()+'px'});
  newWid = $('#logo a img').width()*0.605;
  newWid2 = $('#logo-verbose').width();
  if(newWid < newWid2) {
    $('#logo-verbose img').css('width',newWid);
  } else {
    $('#logo-verbose img').css('width',newWid2);
  }
  responsive_padding_horizontal('#header-left', 'left', 1.25, 24);
  //responsive_padding_horizontal('#header-left', 'right', 1.25, 24);
  responsive_padding_vertical('#header-left', 'top', 4.959, 48);
  $('#logo').css('width',$('#logo').height()*3.967);
  responsive_padding_horizontal('#page', 'left', 2.5, 48);
  responsive_padding_horizontal('#page', 'right', 2.5, 48);
  responsive_padding_vertical('#page', 'top', 1.6, 16);
  responsive_padding_vertical('#page', 'bottom', 1.6, 16);
  $('#page-container').css('min-height',$(window).height()-$('#header-positioner-height-offset').height()-$('#footer-container').height());
  var headerproportion = $('#header-container').width()/$('#header-container').height();
  if(headerproportion > 7) {
    var cssval = $('#pullout-positioner').css('right');
    cssval = cssval.substring(0,1);
    if(cssval === '0') {
      $('#pullout-positioner').animate({right:'-100%'},'fast');
    }
    responsive_font('#tabs','window',1.756,17);
    newWid = $('#nav-bar-container').height()*14.8387;
    $('#nav-bar-container').width(newWid);
    $('#menu-container').css({display:'none'});
    $('#nav-bar-container').css({display:'block'});
  } else {
    responsive_font('.pullout-list-el','window',1.756,17,30);
    $('#pullout-positioner').css({top:$('#header-container').height()+'px'});
    $('#menu-container').width($('#menu-container').height()*2.1282);
    $('#nav-bar-container').css({display:'none'});
    $('#menu-container').css({display:'block'});
  }
  //then set other bits depending on what page it's on
  switch(currentPage) {
    case 'home':
      //responsive_padding_horizontal('#home_right', 'left', 3.75, 72);
      //responsive_padding_horizontal('#home_right', 'right', 3.75, 72);
      $('.home-card-container').height($('.home-card-container').width()*1.3538);
      responsive_font('#featured','window',1.446,14);
      responsive_font('.card-title','parent-div',32.14,18);
      break;
    case 'interviews':
      
      break;
    case 'images':
      
      break;
    case 'video':
      
      break;
    case 'map':
      
      break;
    case 'timeline':
      
      break;
    case 'letters':
      
      break;
    case 'tribes':
      
      break;
  }
}

/*
 * lr is left or right
 */
function responsive_padding_horizontal(selector, lr, responsive, min, max) {
  if(typeof max === "undefined" || max === null) {
    max = 1000;
  }
  var attr = 'padding-';
  if(lr === 'left') {
    attr = attr + 'left';
  } else if(lr === 'right') {
    attr = attr + 'right';
  } else {
    return 0;
  }
  //this is the size it would be set to if set to 'responsive'
  var size = ($(window).width())*responsive/100;
  if(size < min) {
    $(selector).css(attr,min.toString() + 'px');
  } else if(size > max) {
    $(selector).css(attr,max.toString() + 'px');
  } else {
    $(selector).css(attr,responsive.toString() + 'vw');
  }
}

/*
 * tb is top or bottom
 */
function responsive_padding_vertical(selector, tb, responsive, min, max) {
  if(typeof max === "undefined" || max === null) {
    max = 1000;
  }
  var attr = 'padding-';
  if(tb === 'top') {
    attr = attr + 'top';
  } else if(tb === 'bottom') {
    attr = attr + 'bottom';
  } else {
    return 0;
  }
  //this is the size it would be set to if set to 'responsive'
  var size = ($(window).height())*responsive/100;
  if(size < min) {
    $(selector).css(attr,min.toString() + 'px');
  } else if(size > max) {
    $(selector).css(attr,max.toString() + 'px');
  } else {
    $(selector).css(attr,responsive.toString() + 'vh');
  }
}

function responsive_font(selector, relativeto, responsive, min, max) {
  if(typeof max === "undefined" || max === null) {
    max = 1000;
  }
  //this is the size it would be set to if set to 'responsive'
  if(relativeto === 'window') {
    var size = ($(window).height())*responsive/100;
  } else if(relativeto === 'parent-div') {
    var size = $(selector).parent().height()*responsive/100;
  } else {
    return 0;
  }
  if(size < min) {
    $(selector).css('font-size',min.toString() + 'px');
  } else if(size > max) {
    $(selector).css('font-size',max.toString() + 'px');
  } else {
    $(selector).css('font-size',size+'px');
  }
}

//for old browsers -->