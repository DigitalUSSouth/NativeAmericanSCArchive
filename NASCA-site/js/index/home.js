/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

function init_home() {
  //set hover for cards
  $('.card-hover').hover(function() {
    //enter
    $(this).siblings('img.card-image').animate({opacity:1.0},150);
    $(this).parent().animate({top: "5px", left: "1px"},{duration: 100, queue: false});
    $(this).parent().css({"box-shadow":"2px 5px 14px -2px #323A3B"});
  }, function() {
    //exit
    $(this).siblings('img.card-image').animate({opacity:0.5},'fast');
    $(this).parent().animate({top: "0", left: "0"},{duration: 100, queue: false});
    $(this).parent().css({"box-shadow":"3px 10px 18px -2px #323A3B"});
  });
  var list = $('.home-card-container');
  var size = 'wide';
  for(var i = 0; i < list.length; i++) {
    size = list.eq(i).find('#size').html();
    if(size === 'tall') {
      var el = list.eq(i).children('.home-card').children('img');
      el.css({height: 'auto', width: '100%'});
    }
  }
}

/*
 * @param {type} card - jQuery selector for cards to animate off
 */
function animateOff(card) {
  /*$(card + ' .card-read-more').css({'background': '#ffffff'});
  $(card + ' .card-read-more div').css({'color': '#1E1F1E'});
  $(card + ' #point').animate({
    'right': '80px'
  }, {duration: 150, queue: false});*/
  $(card + ' .additional #toggle').html('0');
}

/*
 * @param {type} card - jQuery selector for cards to animate on
 */
function animateOn(card) {
  /*$(card + ' .readmore').css({'background': '#A80505'});
  $(card + ' .readmore a').css({'color': '#ffffff'});
  $(card + ' #point').animate({
    'right': '10px'
  }, {duration: 150, queue: false});*/
  $(card + ' .additional #toggle').html('1');
}

/*
 * @param {string} type - may be 'image' 'video' or 'transcript'
 * @param {number/int} id - id on cdm or wherever of entry to load data from
 */
function readMoreToggle(homePtr, cdmPtr, type, card) {
  var state = parseInt($(card + ' .additional #toggle').text());
  var url = SITE_ROOT + '/html/home-more.php';
  if(state === 0) {
    //turn every other card off
    animateOff('.home-card');
    //turn current card on
    animateOn(card);
    //add relevant info to url
    url += '?type=' + type + '&cdmptr=' + cdmPtr + '&homeptr=' + homePtr;
    //change what view more button does
    $('.preview-lower').fadeIn('fast');
    $('.preview-view-more').attr({'onclick': 'changePage(\'' + type + '\')'});
  } else {
    //then the card is already on. Turn it off and set readmore back to default
    animateOff(card);
    //leave url as is
    //change what view more button does
    $('.preview-lower').fadeOut('fast');
  }
  $.ajax({
    type:'POST',
    url: url,
    async: true,
    dataType: 'html',
    success: function(data) {
      var details = '#preview-details';
      $(details).fadeOut('fast', function() {
        $(details).html(data).promise().done(function() {
          $(details).fadeIn('fast');
        });
      });
      
    }
  });
}

//for old browsers -->