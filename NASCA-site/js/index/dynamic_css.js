/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER, currentPage */

//<!-- //for old browsers

if(window.attachEvent) {
  window.attachEvent('onresize', function() {
    dynamic_css();
    dynamic_css();
  });
}
else if(window.addEventListener) {
  window.addEventListener('resize', function() {
    dynamic_css();
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
  responsive_font('#search-text','parent-div',78.9473,15);//22);
  newWid = $('#search-container').height()*6.0256;
  $('#search-container').width(newWid);
  $('#search-text').css({'line-height':$('#search-text').height()+'px'});
  //correct width for header-left-container, based on height
  newWid = $('#header-left-container').height()*4.4298;
  $('#header-left-container').width(newWid+1);
  //correct width for logo, based on height
  newWid = $('#logo').height()*4.0168;
  $('#logo').width(newWid);
  //correct width for logo TYPE container, based on height
  newWid = $('#logo-verbose-container').height()*3.0253;
  $('#logo-verbose-container').width(newWid);
  if(currentPage !== 'map') {
    responsive_padding_horizontal('#page', 'left', 4.2789, 54);
    responsive_padding_horizontal('#page', 'right', 5.1506, 65);
  }
  var headerproportion = $('#header-container').width()/$('#header-container').height();
  if(headerproportion > 7 && currentPage !== 'documentation') {
    var cssval = $('#pullout-positioner').css('right');
    cssval = cssval.substring(0,1);
    if(cssval === '0') {
      $('#pullout-positioner').animate({right:'-100%'},'fast');
    }
    newWid = $('#nav-bar-container').height()*13.9565;
    $('#nav-bar-container').width(newWid);
    responsive_font('#tabs','parent-div',46.1538,12);//16);
    $('#menu-container').css({display:'none'});
    $('#nav-bar-container').css({display:'block'});
  } else if(currentPage !== 'documentation') {
    responsive_font('.pullout-list-el','window',1.9544,12,26);//16,30);
    $('#pullout-positioner').css({top:$('#header-container').height()+'px'});
    $('#menu-container').width($('#menu-container').height()*2.5435);
    $('#nav-bar-container').css({display:'none'});
    $('#menu-container').css({display:'block'});
    responsive_font('#menu-text','parent-div',50);
  }
  //then set other bits depending on what page it's on
  switch(currentPage) {
    case 'home':
      responsive_font('#featured','window',1.6287,10);//12);
      $('#featured').css('margin-bottom',$('#featured').height());
      $('.home-card-container').height($('.home-card-container').width()*1.2907);
      responsive_font('.card-title','parent-div',33.3333,11);//14);
      var newheight = responsive_font('.card-read-more div','parent-div',95.6522,11);//15);
      $('.card-read-more div').css('line-height',newheight.toString()+'px');
      //title css for default
      var divwidth = $('.preview-default #preview-title-container').width();
      newheight = divwidth*0.09929;
      var font = (newheight).toString()+'px';
      $('.preview-default #preview-title-container #preview-title').css({'font-size':font});
      $('.preview-default #preview-title-container #preview-title').css({'line-height':(newheight*1.2619).toString()+'px'});
      newheight = divwidth*0.047281;
      font = (newheight).toString()+'px';
      $('.preview-default #preview-title-container #preview-title-secondary').css({'font-size':font});
      $('.preview-default #preview-title-container #preview-title-secondary').css({'line-height':(newheight*3.25).toString()+'px'});
      //title css for wide images
      newheight = $('.preview-wide #preview-title-container').width()*0.22222;
      font = (newheight*36.1702/100).toString()+'px';
      $('.preview-wide #preview-title-container #preview-title').css({'font-size':font});
      $('.preview-wide #preview-title-container #preview-title').css({'line-height':(newheight/2).toString()+'px'});
      $('.preview-wide #preview-title-container').height(newheight);
      //title css for tall images
      newheight = $('.preview-tall #preview-title-container').width()*0.4433;
      font = (newheight*0.3778).toString()+'px';
      $('.preview-tall #preview-title-container #preview-title').css({'font-size':font});
      $('.preview-tall #preview-title-container #preview-title').css({'line-height':(newheight/2).toString()+'px'});
      $('.preview-tall #preview-title-container').height(newheight);
      //title css for letters
      newheight = $('.preview-letter #preview-title-container').width()*0.1158;
      font = (newheight*0.6531).toString()+'px';
      $('.preview-letter #preview-title-container #preview-title').css({'font-size':font});
      $('.preview-letter #preview-title-container #preview-title').css({'line-height':(newheight).toString()+'px'});
      $('.preview-letter #preview-title-container').height(newheight);
      //media
      //image sizing
      $('.preview-wide #preview-media-container').height($('.preview-wide #preview-media-container').width()*0.4563);
      $('.preview-tall #preview-media-container').height($('.preview-tall #preview-media-container').width()*1.7725);
      //letter sizing
      $('.preview-letter #preview-media-container').height($('.preview-letter #preview-media-container').width()*1.7725);
      //description
      //image wide layout
      
      //image tall layout
      
      //letter layout
      $('.preview-letter #preview-desc-container').height($('#preview-media-container').height());
      //lower view all css
      newheight = $('#preview-lower').width()*0.05437;
      $('#preview-lower').height(newheight);
      var border = (newheight/6) + 'px solid #930707';
      $('#view-all-underline').css({'border-top':border});
      font = newheight*0.3913;
      $('#view-all').css({'font-size':font+'px'});
      //lower view padding for wide layout
      $('.preview-wide #preview-lower').css('margin-top',(newheight*0.8261)+'px');
      //lower view padding for tall layout
      $('.preview-tall #preview-lower').css('margin-top',(newheight*1.5217)+'px');
      //lower view padding for letter layout
      $('.preview-letter #preview-lower').css('margin-top',(newheight*0.6957)+'px');
      break;
    case 'interviews':
      
      break;
    case 'images':
      
      break;
    case 'video':
      $('.video-single-container').height($('.video-single-container').width()*0.6424);
      var newsize = responsive_font('.video-single-title','parent-div',72.4138,42);
      $('.video-single-title').css('line-height',(newsize*1.381).toString()+'px');
      break;
    case 'map':
      $('#page').css({padding:0});
      var newHeight = $(window).height() - $('#header-positioner-height-offset').height() - $('#footer-container').height();
      $('#page').height(newHeight);
      $('#map-container').height(newHeight);
      break;
    case 'timeline':
      
      break;
    case 'letters':
      
      break;
    case 'tribes':
      
      break;
    case 'documentation':
      
      break;
    default:
      //nada
  }
  var newHeight = $(window).height()-$('#header-positioner-height-offset').height()-$('#footer-container').height();
  $('#page-container').css('min-height',newHeight);
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
  if(typeof min === "undefined" || min === null) {
    min = 0;
  }
  //this is the size it would be set to if set to 'responsive'
  if(relativeto === 'window') {
    var size = ($(window).height())*responsive/100;
  } else if(relativeto === 'parent-div') {
    var size = $(selector).parent().height()*responsive/100;
  } else {
    return -1;
  }
  if(size < min) {
    $(selector).css('font-size',min.toString() + 'px');
    return min;
  } else if(size > max) {
    $(selector).css('font-size',max.toString() + 'px');
    return max;
  } else {
    $(selector).css('font-size',size+'px');
    return size;
  }
}

//for old browsers -->