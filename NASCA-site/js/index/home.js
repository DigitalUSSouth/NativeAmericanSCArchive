/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

function init_home() {

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