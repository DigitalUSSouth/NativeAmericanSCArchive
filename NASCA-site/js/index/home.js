/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

var card_anim_details = {duration: 350, queue: false};

function init_home() {
  toggleSearch('on');
  init_shadows();
  //set hover for cards
  $('.card-hover').hover(function() {
    //enter
    var jthis = $(this);
    var additional = jthis.siblings('div.additional');
    if(additional.children('#toggle').html() === '0') {
      jthis.siblings('img.card-image').animate({opacity:1.0},card_anim_details);
      jthis.parent().css({top: "5px"});//, left: "1px"});//,{duration: 100, queue: false});
      jthis.siblings('div.card-read-more').animate({left:0},card_anim_details);
      var cardpoint = jthis.siblings('div.card-point');
      cardpoint.animate({left:'100%'},card_anim_details);
      cardpoint.css({'-webkit-transform':'translateX(-100%)'});
      //$(this).parent().siblings('div.shadow').animate({top:'-3px'},card_anim_details);
    }
    var title = additional.children('#title').html();
    jthis.siblings('div.card-title-container').children('div.card-title').html(title);
  }, function() {
    //exit
    var jthis = $(this);
    var additional = jthis.siblings('div.additional');
    if(additional.children('#toggle').html() === '0') {
      jthis.siblings('img.card-image').animate({opacity:0.9},card_anim_details);
      jthis.parent().css({top: "0"});//, left: "0"});//,{duration: 100, queue: false});
      jthis.siblings('div.card-read-more').animate({left:'-50%'},card_anim_details);
      var cardpoint = jthis.siblings('div.card-point');
      cardpoint.animate({left:0},card_anim_details);
      cardpoint.css({'-webkit-transform':'translateX(0)'});
      //$(this).parent().siblings('div.shadow').animate({top:'0'},card_anim_details);
    }
    var type = additional.children('#type').html();
    jthis.siblings('div.card-title-container').children('div.card-title').html(type);
  });
}

/*
 * @param {string} card - id of card to display on the right
 * @param {number/int} homePtr - index of card information in home/data.json
 */
function homeReadMoreToggle(homePtr, card) {
  
  function animateOff(_card) {
    _card.children('.card-read-more').animate({left:'-50%'},card_anim_details);
    var cardpoint = _card.children('.card-point');
    cardpoint.animate({left:0},card_anim_details);
    cardpoint.css({'-webkit-transform':'translateX(0)'});
    _card.children('.card-image').animate({opacity:0.9},card_anim_details);
    _card.css({top: '0'});
    //$(_card).siblings('div.shadow').animate({top:'0'},card_anim_details);
    _card.find('.additional #toggle').html('0');
  }

  function animateOn(_card) {
    _card.children('.card-read-more').animate({'left': 0},card_anim_details);
    var cardpoint = _card.children('.card-point');
    cardpoint.animate({'left': '100%'},card_anim_details);
    cardpoint.css({'-webkit-transform':'translateX(-100%)'});
    _card.children('.card-image').animate({'opacity': 1.0},card_anim_details);
    _card.css({top: '5px'});
    //$(_card).siblings('div.shadow').animate({top:'-3px'},card_anim_details);
    _card.find('.additional #toggle').html('1');
  }
  
  var additional = $(card + ' .additional');
  var state = parseInt(additional.children('#toggle').text());
  var url = SITE_ROOT + '/html/home-more.php';
  if(state === 0) {
    //get cards that are 'on'
    var ons = $('.home-card').filter(function() {
      if($(this).find('.additional #toggle').html() == 0) {
        return false;
      } else {
        return true;
      }
    });
    //turn every on card off
    animateOff(ons);
    //turn current card on
    animateOn($(card));
    var size = additional.children('#size').text();
    //add relevant info to url
    url += '?homeptr=' + homePtr + '&size=' + size;
  } else {
    //Then the card is already on. Turn it off.
    animateOff($(card));
  }
  $.ajax({
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
  //dynamic_css();
}

//for old browsers -->