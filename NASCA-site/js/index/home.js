/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

var card_anim_details = {duration: 350, queue: false};

function init_home() {
  toggleSearch('off');
  //set hover for cards
  $('.card-hover').hover(function() {
    //enter
    if($(this).siblings('div.additional').children('#toggle').html() === '0') {
      $(this).siblings('img.card-image').animate({opacity:1.0},card_anim_details);
      $(this).parent().css({top: "5px"});//, left: "1px"});//,{duration: 100, queue: false});
      //$(this).parent().css({"box-shadow":"1px 3px 12px -2px rgb(150,150,150)"});
      $(this).siblings('div.card-read-more').animate({left:0},card_anim_details);
      $(this).siblings('div.card-point').animate({left:'100%'},card_anim_details);
      $(this).siblings('div.card-point').css({'-webkit-transform':'translateX(-100%)'});
      $(this).parent().siblings('div.shadow').animate({top:'-3px'},card_anim_details);
    }
    var title = $(this).siblings('div.additional').children('#title').html();
    $(this).siblings('div.card-title-container').children('div.card-title').html(title);
  }, function() {
    //exit
    if($(this).siblings('div.additional').children('#toggle').html() === '0') {
      $(this).siblings('img.card-image').animate({opacity:0.9},card_anim_details);
      $(this).parent().css({top: "0"});//, left: "0"});//,{duration: 100, queue: false});
      //$(this).parent().css({"box-shadow":"3px 10px 18px -2px rgb(150,150,150)"});
      $(this).siblings('div.card-read-more').animate({left:'-50%'},card_anim_details);
      $(this).siblings('div.card-point').animate({left:0},card_anim_details);
      $(this).siblings('div.card-point').css({'-webkit-transform':'translateX(0)'});
      $(this).parent().siblings('div.shadow').animate({top:'0'},card_anim_details);
    }
    var type = $(this).siblings('div.additional').children('#type').html();
    if(type[type.length-1] === 's') {
      type = type.substring(0,type.length-1);
      type = jsUcfirst(type);
    }
    $(this).siblings('div.card-title-container').children('div.card-title').html(type);
  });
  /*var list = $('.home-card-container');
  var size = 'wide';
  for(var i = 0; i < list.length; i++) {
    size = list.eq(i).find('#size').html();
    if(size === 'tall') {
      var el = list.eq(i).children('.home-card').children('img');
      el.css({height: 'auto', width: '100%'});
    }
  }*/
}

/*
 * @param {type} card - jQuery selector for cards to animate off
 */
function animateOff(card) {
  $(card + ' .card-read-more').animate({left:'-50%'},card_anim_details);
  $(card + ' .card-point').animate({left:0},card_anim_details);
  $(card + ' .card-point').css({'-webkit-transform':'translateX(0)'});
  $(card + ' .card-image').animate({opacity:0.9},card_anim_details);
  $(card).css({top: '0'});//, left: '0'});//,{duration: 100, queue: false});
  //$(card).css({'box-shadow':'3px 10px 18px -2px rgb(150,150,150)'});
  $(card).siblings('div.shadow').animate({top:'0'},card_anim_details);
  $(card + ' .additional #toggle').html('0');
}

/*
 * @param {type} card - jQuery selector for cards to animate on
 */
function animateOn(card) {
  $(card + ' .card-read-more').animate({'left': 0},card_anim_details);
  $(card + ' .card-point').animate({'left': '100%'},card_anim_details);
  $(card + ' .card-point').css({'-webkit-transform':'translateX(-100%)'});
  $(card + ' .card-image').animate({'opacity': 1.0},card_anim_details);
  $(card).css({top: '5px'});//, left: '1px'});//,{duration: 100, queue: false});
  //$(card).css({'box-shadow':'1px 3px 12px -2px rgb(150,150,150)'});
  $(card).siblings('div.shadow').animate({top:'-3px'},card_anim_details);
  $(card + ' .additional #toggle').html('1');
}

/*
 * @param {string} card - id of card to display on the right
 * @param {number/int} homePtr - index of card information in home/data.json
 */
function readMoreToggle(homePtr, card) {
  var state = parseInt($(card + ' .additional #toggle').text());
  var url = SITE_ROOT + '/html/home-more.php';
  if(state === 0) {
    //turn every other card off
    animateOff('.home-card');
    //turn current card on
    animateOn(card);
    var cardType = $(card + ' .additional #type').text();
    var size = $(card + ' .additional #size').text();
    //add relevant info to url
    url += '?homeptr=' + homePtr + '&size=' + size;
  } else {
    //Then the card is already on. Turn it off.
    animateOff(card);
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
  dynamic_css();
}

//for old browsers -->