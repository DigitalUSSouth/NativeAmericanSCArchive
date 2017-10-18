/* global card_anim_details, SITE_ROOT */

function init_images() {
  toggleSearch('on');
  init_images_cards();
  
  var win = $(window);
  win.scroll(function() {
    //end of document reached
    if($(document).height() - win.height() === win.scrollTop()) {
      $('#images-loading img').css('opacity','1');
      //find start local index to load
      var sli = $('.image-card-container').last().children('.image-card').filter(':first').attr('id');
      sli = parseInt(sli.substring(11,sli.length))+1;
      //load extra block of images
      $.ajax({
        url: SITE_ROOT + '/html/images-card.php?sli=' + sli,
        dataType: 'html',
        success: function(data) {
          $('#image-cards-flex').append(data);
          init_images_cards();
          dynamic_css();
          $('#images-loading img').css('opacity','0');
        }
      });
    }
  });
}

function init_images_cards() {
  init_shadows();
  
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

function imagesReadMoreToggle(imagePtr, card) {
  
  function animateOff(_card) {
    //_card.children('.card-read-more').animate({left:'-50%'},card_anim_details);
    //var cardpoint = _card.children('.card-point');
    //cardpoint.animate({left:0},card_anim_details);
    //cardpoint.css({'-webkit-transform':'translateX(0)'});
    _card.children('.card-image').animate({opacity:0.9},card_anim_details);
    _card.css({top: '0'});//, left: '0'});//,{duration: 100, queue: false});
    //_card.siblings('div.shadow').animate({top:'0'},card_anim_details);
    _card.find('.additional #toggle').html('0');
  }

  function animateOn(_card) {
    //_card.children(' .card-read-more').animate({'left': 0},card_anim_details);
    //var cardpoint = _card.children('.card-point');
    //cardpoint.animate({'left': '100%'},card_anim_details);
    //cardpoint.css({'-webkit-transform':'translateX(-100%)'});
    _card.children('.card-image').animate({'opacity': 1.0},card_anim_details);
    _card.css({top: '5px'});//, left: '1px'});//,{duration: 100, queue: false});
    //_card.siblings('div.shadow').animate({top:'-3px'},card_anim_details);
    _card.find('.additional #toggle').html('1');
  }
  
  var jcard = $(card);
  var state = parseInt(jcard.find('.additional #toggle').text());
  //var url = SITE_ROOT + '/html/home-more.php';
  if(state === 0) {
    //get cards that are 'on'
    var ons = $('.image-card').filter(function() {
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