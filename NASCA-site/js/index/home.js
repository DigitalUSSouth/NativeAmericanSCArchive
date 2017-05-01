/* global interval, SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

function init_home() {
  //set thumbnails of home content
  //var image = "";
  //for(var i = 1; i <= 1; i++) {
  //  image = "#thumb" + i.toString();
  //  url = "url("+window.url_home+"/img/native_"+i.toString()+".jpg)";
  //  $(image).css("background",url);//http://digital.tcl.sc.edu/utils/getthumbnail/collection/nasca/id/" + pointers[i] + ")");
  //}
  
  //OLD CODE TO CENTER IMAGES HORIZONTALLY IN CARDS
  //PARTIALLY WORKS, BUT PROBLEMS WITH SYNCHRONICITY
  //  var card_count = $('#home_left > div').length;
  //  var scaledheight = parseInt(($('.home_card').css('height')).replace('px',''));
  //  var divwidth = parseInt(($('.home_card').css('width')).replace('px',''));
  //  
  //  for(var i = 1; i <= card_count; i++) {
  //    var query = '#home_card_' + i.toString() + ' a img';
  //    console.log(query);
  //    var url = $(query).attr('src');
  //    url = SITE_ROOT + '/' + url;
  //    console.log(url);
  //    
  //    var img = new Image();
  //    img.onload = function() {
  //      var fullwidth = this.width;
  //      var fullheight = this.height;
  //      var scaledwidth = (scaledheight * fullwidth) / fullheight;
  //      //finally, offset the card image
  //      var offset = (divwidth / 2) - (scaledwidth / 2);
  //      var newvalue = offset.toString().substring(0,6)+'px';
  //      console.log(newvalue);
  //      $(query).css('margin-left',newvalue);
  //    };
  //    img.src = url;
  //  }
  
  interval_home = setInterval(function() {
    var bookLeftHeight = $('#home_left').height();
    var bookMinHeight = parseInt(($('.book').css('min-height')).replace('px',''));
    if(bookLeftHeight >= bookMinHeight && bookLeftHeight <= 900) {
      $('#home_right').css('height',bookLeftHeight);
    }
  },interval);

}

/*
 * @param {string} type - may be 'image' 'video' or 'transcript'
 * @param {number/int} id - id on cdm or wherever of entry to load data from
 */
function readMore(type, id) {
  $.ajax({
    type:'POST',
    url: SITE_ROOT + '/html/home-more.php?type=' + type + '&id=' + id,
    async: true,
    dataType: 'html',
    success: function(data) {
      $('.preview #details').html(data);
    }
  });
  $('.viewmore a').attr({'onclick': 'changePage(\'' + type + '\')'});
}

/*
 * @param {string} card - id of card 
 */
function cardToggle(card) {
  var displacement = 20;
  var state = parseInt($(card+' .additional #toggle').text());
  if(state === 0) {
    //turn every other card off
    $('.home_card .readmore').css({'background': '#ffffff'});
    $('.home_card .readmore a').css({'color': '#1E1F1E'});
    /*$('.home_card').animate({
      'width': '215px',
      'margin-right': '0px'
    }, {duration: 200, queue: false});*/
    $('.home_card #point').animate({
      'right': '80px'
    }, {duration: 150, queue: false});
    $('.home_card .additional #toggle').html('0');
    
    //turn current card on
    $(card + ' .readmore').css({'background': '#840004'});
    $(card + ' .readmore a').css({'color': '#ffffff'});
    /*$(card).animate({
      'width': '-='+displacement+'px',
      'margin-right': displacement+'px'
    }, {duration: 200, queue: false});*/
    $(card + ' #point').animate({
      'right': '10px'
    }, {duration: 150, queue: false});
    $(card+' .additional #toggle').html('1');
  }
}

//for old browsers -->