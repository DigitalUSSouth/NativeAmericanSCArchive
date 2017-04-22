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
  
  var interval_home = setInterval(function() {
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
  var card_count = $('#home_left > div').length;
  console.log(card_count);
  var html = '';
  for(var i = 0; i < 100; i++) {
    html += 'Get ' + type + ' ' + id.toString() + ' from contentDM. ';
  }
  $('.preview #details').html(html);
}

//for old browsers -->