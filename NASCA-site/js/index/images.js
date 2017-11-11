/* global card_anim_details, SITE_ROOT, IMAGES_START, IMAGES_CONT */

function init_images() {
  toggleSearch('on');
  //load init cards
  var loadbar = $('#images-loading img');
  loadbar.css('opacity','1');
  $.ajax({
    url: SITE_ROOT + '/html/images-card.php?si=0&cc='+IMAGES_START+'&srt=indexical',
    dataType: 'html',
    success: function(data) {
      //check for error code at end
      var end = data.substring(data.length-2,data.length);
      if($.isNumeric(end) && end < 0) {
        data = data.substring(0,data.length-2);
      }
      $('#image-cards-flex').html(data);
      init_images_cards();
      dynamic_css();
      loadbar.css('opacity','0');
    }
  });
  
  init_infinite_scroll();
  
  //add functionality for dropdown
  $('#select').change(function() {
    var flexbox = $('#image-cards-flex');
    flexbox.empty();
    var sort = $(this).val();
    var loadbar = $('#images-loading img');
    loadbar.css('opacity','1');
    $.ajax({
      url: SITE_ROOT + '/html/images-card.php?si=0&cc='+IMAGES_START+'&srt='+sort,
      dataType: 'html',
      success: function(data) {
        //check for error code at end
        var end = data.substring(data.length-2,data.length);
        if($.isNumeric(end) && end < 0) {
          data = data.substring(0,data.length-2);
        }
        flexbox.html(data);
        init_images_cards();
        dynamic_css();
        loadbar.css('opacity','0');
        init_infinite_scroll();
      }
    });
  });
}

var isLoading = false;

function init_infinite_scroll() {
  var win = $(window);
  win.scroll(function() {
    //end of document reached
    if($(document).height() - win.height() === win.scrollTop() && isLoading === false) {
      var sort = $('#select').val();
      var loadbar = $('#images-loading img');
      loadbar.css('opacity','1');
      //find start index to load
      var si = $('.image-card-container').last().children('.image-card').filter(':first').attr('id');
      si = parseInt(si.substring(11,si.length))+1;
      //load extra block of images
      $.ajax({
        url: SITE_ROOT + '/html/images-card.php?si='+si+'&cc='+IMAGES_CONT+'&srt='+sort,
        dataType: 'html',
        beforeSend: function() {
          isLoading= true;
        },
        success: function(data) {
          if($.isNumeric(data) && parseInt(data) < 0) {
            win.off("scroll");
          } else {
            //check for error code at end
            var end = data.substring(data.length-2,data.length);
            if($.isNumeric(end) && end < 0) {
              data = data.substring(0,data.length-2);
            }
            $('#image-cards-flex').append(data);
            init_images_cards();
            dynamic_css();
          }
          loadbar.css('opacity','0');
          isLoading = false;
        }
      });
    }
  });
}

function init_images_cards() {
  //init_shadows();
  
  //set card hover animations
  //set hover for cards
  $('.card-hover').hover(function() {
    //enter
    var jthis = $(this);
    if(jthis.siblings('div.additional').children('#toggle').html() === '0') {
      jthis.siblings('img.card-image').animate({opacity:1.0},card_anim_details);
      jthis.parent().css({top: "5px"});
      //jthis.siblings('div.card-read-more').animate({left:0},card_anim_details);
      //var cardpoint = jthis.siblings('div.card-point');
      //cardpoint.animate({left:'100%'},card_anim_details);
      //cardpoint.css({'-webkit-transform':'translateX(-100%)'});
    }
  }, function() {
    //exit
    var jthis = $(this);
    if(jthis.siblings('div.additional').children('#toggle').html() === '0') {
      jthis.siblings('img.card-image').animate({opacity:0.9},card_anim_details);
      jthis.parent().css({top: "0"});
      //jthis.siblings('div.card-read-more').animate({left:'-50%'},card_anim_details);
      //var cardpoint = jthis.siblings('div.card-point');
      //cardpoint.animate({left:0},card_anim_details);
      //cardpoint.css({'-webkit-transform':'translateX(0)'});
    }
  });
}

function getElementsPerRow() {
  var firstCard = $('#image-card-0').parent();
  var cardWidth = firstCard.width();
  var flexWidth = firstCard.parent().width();
  //how many times does card width fit into flex width?
  return Math.floor(flexWidth/cardWidth);
}

function getColumnOfElement(id) {
  var cardNum = id.replace('#image-card-','');
  return (parseInt(cardNum)%getElementsPerRow())+1;
}

function imagesReadMoreToggle(imagePtr, card) {
  
  function animateOff(_card) {
    _card.children('.card-image').animate({opacity:0.9},card_anim_details);
    _card.css({top: '0'});
    _card.find('.additional #toggle').html('0');
    var parent = _card.parent();
    var detailDiv = parent.children('#card-details');
    detailDiv.animate({'height':'0%'},{duration:200,queue:false,complete:function(){
      parent.css({'z-index':2});
      detailDiv.remove();
    }});
  }

  function animateOn(_card) {
    _card.children('.card-image').animate({'opacity': 1.0},card_anim_details);
    _card.css({top: '5px'});
    _card.find('.additional #toggle').html('1');
    var parent = _card.parent();
    parent.css({'z-index':3});
    var detailDiv = '<div id="card-details"><div id="details-loading" class="custom-row"><img src="'+SITE_ROOT+'/img/loadingBar.gif" alt="Loading..."></div></div>';
    parent.append(detailDiv);
    detailDiv = parent.children('#card-details');
    detailDiv.css({'left':'-'+parent.offset().left+'px'});
    detailDiv.animate({'height':'150%'},{duration: 200, queue: false});
  }
  
  var jcard = $(card);
  var state = parseInt(jcard.find('.additional #toggle').text());
  //var url = SITE_ROOT + '/html/home-more.php';
  if(state === 0) {
    //get cards that are 'on'
    var ons = $('div.image-card').filter(function() {
      if($(this).find('.additional #toggle').html() == 0) {
        return false;
      } else {
        return true;
      }
    });
    //turn every on card off
    animateOff(ons);
    //turn current card on
    animateOn(jcard);
    //var size = $(card + ' .additional #size').text();
    //add relevant info to url
    //url += '?homeptr=' + homePtr + '&size=' + size;
  } else {
    //Then the card is already on. Turn it off.
    animateOff(jcard);
  }
  /*$.ajax({
    type:'POST',
    url: url,
    async: true,
    dataType: 'html',
    success: function(data) {
      var details = '#home-right';
      $(details).fadeOut('fast', function() {
        $(details).html(data).promise().done(function() {
          $(details).fadeIn('fast');
          dynamic_css();
        });
      });
    }
  });
  dynamic_css();*/
}