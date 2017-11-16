/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER, currentPage */

//<!-- //for old browsers

function dynamic_css() {

  //local functions to dynamic_css function
  function responsive_padding_horizontal(selector, lr, responsive, min, max) {
    if (typeof max === "undefined" || max === null) {
      max = 1000;
    }
    var attr = 'padding-';
    if (lr === 'left') {
      attr = attr + 'left';
    } else if (lr === 'right') {
      attr = attr + 'right';
    } else {
      return 0;
    }
    //this is the size it would be set to if set to 'responsive'
    var size = ($(window).width()) * responsive / 100;
    if (size < min) {
      selector.css(attr, min.toString() + 'px');
    } else if (size > max) {
      selector.css(attr, max.toString() + 'px');
    } else {
      selector.css(attr, responsive.toString() + 'vw');
    }
  }

  /*function responsive_padding_vertical(selector, tb, responsive, min, max) {
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
   selector.css(attr,min.toString() + 'px');
   } else if(size > max) {
   selector.css(attr,max.toString() + 'px');
   } else {
   selector.css(attr,responsive.toString() + 'vh');
   }
   }*/

  function responsive_font(selector, relativeto, responsive, min, max) {
    if (typeof max === "undefined" || max === null) {
      max = 1000;
    }
    if (typeof min === "undefined" || min === null) {
      min = 0;
    }
    //this is the size it would be set to if set to 'responsive'
    if (relativeto === 'window') {
      var size = ($(window).height()) * responsive / 100;
    } else if (relativeto === 'parent-div') {
      var size = selector.parent().height() * responsive / 100;
    } else {
      return -1;
    }
    if (size < min) {
      selector.css('font-size', min.toString() + 'px');
      return min;
    } else if (size > max) {
      selector.css('font-size', max.toString() + 'px');
      return max;
    } else {
      selector.css('font-size', size + 'px');
      return size;
    }
  }

  var nWid = null;
  var nHght = null;
  var font = null;

  var page = $('#page');

  //DYNAMIC CSS FOR INDEX GOES HERE
  //VVVVVVVVVVVVVVVVVVVVVVVVV
  var search_container = $('#search-container');
  search_container.width(search_container.height()*6.0256);
  var search_text = $('#search-text');
  nHght = responsive_font(search_text, 'parent-div', 78.9473, 15);//22);
  search_text.css({'line-height':nHght+'px'});
  {
    var header_left_container = $('#header-left-container');
    nWid = header_left_container.height() * 4.4298;
    header_left_container.width(nWid + 1);
  }
  {
    var logo = $('#logo');
    nWid = logo.height() * 4.0168;
    logo.width(nWid);
  }
  {
    var logo_verbose_container = $('#logo-verbose-container');
    nWid = logo_verbose_container.height() * 3.0253;
    logo_verbose_container.width(nWid);
  }
  if (currentPage !== 'map') {
    page.css('padding-top', 20);
    page.css('padding-bottom', 8);
    responsive_padding_horizontal(page, 'left', 4.2789, 54);
    responsive_padding_horizontal(page, 'right', 5.1506, 65);
  }
  var headerproportion = $('#header-container').width() / $('#header-container').height();
  var pullout_positioner = $('#pullout-positioner');
  var nav_bar_container = $('#nav-bar-container');
  var menu_container = $('#menu-container');
  if (headerproportion > 7) {// && currentPage !== 'documentation') {
    menu_container.css({display: 'none'});
    nav_bar_container.css({display: 'block'});
    nWid = nav_bar_container.height() * 13.9565;
    nav_bar_container.width(nWid);
    var cssval = pullout_positioner.css('right');
    cssval = cssval.substring(0, 1);
    if (cssval === '0') {
      pullout_positioner.animate({right: '-100%'}, 'fast');
    }
    responsive_font($('#tabs'), 'parent-div', 46.1538, 12);//16);
  } else {// if(currentPage !== 'documentation') {
    responsive_font($('li.pullout-list-el'), 'window', 1.9544, 12, 26);//16,30);
    pullout_positioner.css({top: $('#header-container').height() + 'px'});
    menu_container.width(menu_container.height() * 2.5435);
    nav_bar_container.css({display: 'none'});
    menu_container.css({display: 'block'});
    responsive_font($('#menu-text'), 'parent-div', 50);
  }
  //^^^^^^^^^^^^^^^^^^^^^^^^

  switch (currentPage) {
    case 'home':

      //HOME CSS GOES HERE
      //VVVVVVVVVVVVVVVVVVVVVVVV
      page.css('height', 'auto');
      {
        var featured = page.find('#featured');
        responsive_font(featured, 'window', 1.6287, 10);//12);
        $(featured).css('margin-bottom', featured.height());
      }
      /*{
        var home_card_container = page.find('div.home-card-container');
        nWid = home_card_container.parent().width() * 0.245; //keep in mind this is not taking into account minimum width
        home_card_container.width(nWid);
        home_card_container.height(home_card_container.width() * 1.2907);
      }*/
      responsive_font(page.find('div.card-title'), 'parent-div', 33.3333, 11);//14);
      {
        var read_more = page.find('.card-read-more').children('div');
        nHght = responsive_font(read_more, 'parent-div', 95.6522, 11);//15);
        read_more.css('line-height', nHght.toString() + 'px');
      }
      TITLE_DEFAULT: {
        var default_title_cont = page.find('.preview-default #preview-title-container');
        if (!default_title_cont.length) {
          break TITLE_DEFAULT;
        }
        var divWidth = default_title_cont.width();
        nHght = divWidth * 0.09929;
        font = (nHght).toString() + 'px';
        var prev_title = default_title_cont.children('#preview-title');
        prev_title.css({'font-size': font});
        prev_title.css({'line-height': (nHght * 1.2619).toString() + 'px'});
        nHght = divWidth * 0.047281;
        font = (nHght).toString() + 'px';
        var prev_title_sec = default_title_cont.children('#preview-title-secondary');
        prev_title_sec.css({'font-size': font});
        prev_title_sec.css({'line-height': (nHght * 3.25).toString() + 'px'});
      }
      LAYOUT_WIDE: {
        var layout_wide = page.find('div.preview-wide');
        if (!layout_wide.length) {
          break LAYOUT_WIDE;
        }
        TITLE_WIDE: {
          //title css for wide images
          var wide_title_cont = layout_wide.children('#preview-title-container');
          nHght = wide_title_cont.width() * 0.22222;
          font = (nHght * 36.1702 / 100).toString() + 'px';
          var prev_title = wide_title_cont.children('#preview-title');
          prev_title.css({'font-size': font});
          prev_title.css({'line-height': (nHght / 2).toString() + 'px'});
          wide_title_cont.height(nHght);
        }
        //image sizing
        MEDIA_WIDE: {
          var wide_media_cont = layout_wide.children('#preview-media-container');
          wide_media_cont.height(wide_media_cont.width() * 0.4563);
        }
      }
      LAYOUT_TALL: {
        var layout_tall = page.find('div.preview-tall');
        if (!layout_tall.length) {
          break LAYOUT_TALL;
        }
        TITLE_TALL: {
          //title css for tall images
          var tall_title_cont = layout_tall.children('#preview-title-container');
          nHght = tall_title_cont.width() * 0.4433;
          font = (nHght * 0.3778).toString() + 'px';
          var prev_title = tall_title_cont.children('#preview-title');
          prev_title.css({'font-size': font});
          prev_title.css({'line-height': (nHght / 2).toString() + 'px'});
          tall_title_cont.height(nHght);
        }
        //image sizing
        MEDIA_TALL: {
          var tall_media_cont = layout_tall.children('#preview-media-container');
          tall_media_cont.height(tall_media_cont.width() * 1.7725);
        }
      }
      LAYOUT_LETTER: {
        var layout_letter = page.find('div.preview-letter');
        if (!layout_letter.length) {
          break LAYOUT_LETTER;
        }
        TITLE_LETTER: {
          //title css for letters
          var letter_title_cont = layout_letter.children('#preview-title-container');
          nHght = letter_title_cont.width() * 0.1158;
          font = (nHght * 0.6531).toString() + 'px';
          var prev_title = letter_title_cont.children('#preview-title');
          prev_title.css({'font-size': font});
          prev_title.css({'line-height': (nHght).toString() + 'px'});
          letter_title_cont.height(nHght);
        }
        //letter sizing
        MEDIA_LETTER: {
          var letter_media_cont = layout_letter.children('#preview-media-container');
          letter_media_cont.height(letter_media_cont.width() * 1.7725);
          //description styling
          layout_letter.children('#preview-desc-container').height(letter_media_cont.height());
        }
      }
      //lower view all css
      var prev_lower = page.find('#preview-lower');
      nHght = prev_lower.width() * 0.05437;
      prev_lower.height(nHght);
      var border = (nHght / 6) + 'px solid #930707';
      page.find('#view-all-underline').css({'border-top': border});
      font = nHght * 0.3913;
      page.find('#view-all').css({'font-size': font + 'px'});
      //lower view padding for wide layout
      page.find('.preview-wide #preview-lower').css('margin-top', (nHght * 0.8261) + 'px');
      //lower view padding for tall layout
      page.find('.preview-tall #preview-lower').css('margin-top', (nHght * 1.5217) + 'px');
      //lower view padding for letter layout
      page.find('.preview-letter #preview-lower').css('margin-top', (nHght * 0.6957) + 'px');
      //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

      break;
    case 'interviews':

      //INTERVIEWS CSS GOES HERE
      //VVVVVVVVVVVVVVVVVVVVVVVV
      page.css('height', 'auto');
      //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

      break;
    case 'images':

      //IMAGES CSS GOES HERE
      //VVVVVVVVVVVVVVVVVVVVVVVV
      page.css('height', 'auto');
      //set margins
      /*var newMargin = $(window).width() * 0.009509;
      if (newMargin < 12) {
        newMargin = 12;
      }
      newMargin = '-' + newMargin.toString() + 'px';
      var cards_flex = page.find('#image-cards-flex');
      cards_flex.css({'margin-left': newMargin, 'margin-right': newMargin});*/
      {
        var select = page.find('#select-container');
        nHght = select.width()*0.1;
        select.height(nHght);
        var child = select.children('#select');
        child.height(nHght);
        select.css('margin-bottom', select.height());
        responsive_font(child, 'parent-div', 70);
      }
      var images_modal = $('#images-modal');
      if (images_modal.length) {
        images_modal.css({'left':'-'+images_modal.parent().offset().left+'px'});
        var images_modal_title = images_modal.find('#images-modal-title');
        nHght = responsive_font(images_modal_title,'parent-div',93);
        images_modal_title.css({'line-height':nHght+'px'});
        var images_modal_clicknote = images_modal.find('#images-modal-left-clicknote');
        nHght = responsive_font(images_modal_clicknote,'parent-div',93);
        images_modal_clicknote.css({'line-height':nHght+'px'});
      }
      /*nWid = cards_flex.width() * 0.165; //keep in mind this is not taking into account minimum width
      image_card_cont.width(nWid).height(image_card_cont.width() * 1.2907);*/
      var image_card_cont = page.find('div.image-card-container');
      responsive_font(image_card_cont.find('div.card-title'), 'parent-div', 33.3333);
      modifyMargins(false);
      /*{
        var read_more = image_card_cont.find('div.card-read-more').children('div');
        nHght = responsive_font(read_more, 'parent-div', 95.6522);
        read_more.css('line-height', nHght.toString() + 'px');
      }*/
      //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

      break;
    case 'video':

      //VIDEO CSS GOES HERE
      //VVVVVVVVVVVVVVVVVVVVVVVV
      page.css('height', 'auto');
      //set margins
      var newMargin = $(window).width() * 0.02617;
      if (newMargin < 33) {
        newMargin = 33;
      }
      newMargin = '-' + newMargin.toString() + 'px';
      $('#video-list-container').css({'margin-left': newMargin, 'margin-right': newMargin});
      //set other things
      $('.video-single-container').height($('.video-single-container').width() * 0.6424);
      var newsize = responsive_font($('.video-single-title'), 'parent-div', 72.4138, 42);
      $('.video-single-title').css('line-height', (newsize * 1.381).toString() + 'px');
      //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

      break;
    case 'map':

      //MAP CSS GOES HERE
      //VVVVVVVVVVVVVVVVVVVVVVVV
      page.css({padding: 0});
      nHght = $(window).height() - $('#header-positioner-height-offset').height() - $('#footer-container').height();
      page.height(nHght);
      $('#map-container').height(nHght);
      //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

      break;
    case 'timeline':

      //TIMELINE CSS GOES HERE
      //VVVVVVVVVVVVVVVVVVVVVVVV
      page.css('height', 'auto');
      //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

      break;
    case 'letters':

      //LETTERS CSS GOES HERE
      //VVVVVVVVVVVVVVVVVVVVVVVV
      page.css('height', 'auto');
      
      {
        var featured = page.find('#featured-container');
        nHght = responsive_font(featured, 'window', 1.6287, 10);//12);
        $(featured.children()).css('margin-bottom', nHght+'px');
      }
      //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

      break;
    case 'tribes':

      //TRIBES CSS GOES HERE
      //VVVVVVVVVVVVVVVVVVVVVVVV
      page.css('height', 'auto');
      //set margins
      var newMargin = $(window).width() * 0.01507;
      if (newMargin < 19) {
        newMargin = 19;
      }
      newMargin = '-' + newMargin.toString() + 'px';
      $('#tribes-list-container').css({'margin-left': newMargin, 'margin-right': newMargin});
      $('.tribe-single-container').height($('.tribe-single-container').width() * 0.6373);
      responsive_font($('.tribe-single-title'), 'parent-div', 61.6667);
      var custom_fancybox = $('.tribes-history-container');
      custom_fancybox.height(custom_fancybox.width() * 0.41096);
      var newsize = responsive_font($('.tribes-history-nav div'), 'parent-div', 45.74);
      $('.tribes-history-nav div').css('line-height', newsize.toString() + 'px');
      //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

      break;
      /*case 'documentation':
       $('#page').css('height','auto');
       
       break;
       default:
       //nada*/
  }

  init_shadows();

  nHght = $(window).height() - $('#header-positioner-height-offset').height() - $('#footer-container').height();
  $('#page-container').css('min-height', nHght);
}

var activate_dynamic_css;
var last_css_firing = 0;
var css_timeout_reaction = 12; //in hertz
var css_frequency = 48; //in hertz

if (window.attachEvent) {
  window.attachEvent('onresize', function () {
    clearTimeout(activate_dynamic_css);
    if(css_frequency !== 0) {
      if ((Date.now() - last_css_firing) > (1000 / css_frequency)) {
        dynamic_css();
        last_css_firing = Date.now();
        return 0;
      }
    }
    activate_dynamic_css = setTimeout(function () {
      dynamic_css();
      last_css_firing = Date.now();
    }, (1000 / css_timeout_reaction));
  });
} else if (window.addEventListener) {
  window.addEventListener('resize', function () {
    clearTimeout(activate_dynamic_css);
    if(css_frequency !== 0) {
      if ((Date.now() - last_css_firing) > (1000 / css_frequency)) {
        dynamic_css();
        last_css_firing = Date.now();
        return 0;
      }
    }
    activate_dynamic_css = setTimeout(function () {
      dynamic_css();
      last_css_firing = Date.now();
    }, (1000 / css_timeout_reaction));
  }, true);
} else {
  //The browser does not support Javascript event binding
}


//for old browsers -->