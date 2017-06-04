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
  responsive_font('#tabs',1.653,16);
  responsive_font('#search-text',2.273,22);
  var newWid = ($('#header-left').height()+48)*2.726;
  var newWid2 = $(window).width()*0.2411;
  if(newWid < newWid2) {
    $('#header-left').css('min-width',newWid2+2);
  } else {
    $('#header-left').css('min-width',newWid+2);
  }
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
  responsive_padding_vertical('#page', 'top', 3.5, 34);
  responsive_padding_vertical('#page', 'bottom', 3.5, 34);
  
  //then set other bits depending on what page it's on
  switch(currentPage) {
    case 'home':
      
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

function responsive_font(selector, responsive, min, max) {
  if(typeof max === "undefined" || max === null) {
    max = 1000;
  }
  //this is the size it would be set to if set to 'responsive'
  var size = ($(window).height())*responsive/100;
  if(size < min) {
    $(selector).css('font-size',min.toString() + 'px');
  } else if(size > max) {
    $(selector).css('font-size',max.toString() + 'px');
  } else {
    $(selector).css('font-size',responsive.toString() + 'vh');
  }
}

//for old browsers -->