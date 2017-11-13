/* global card_anim_details, SITE_ROOT, IMAGES_START, IMAGES_CONT, currentUrl */

function init_images() {
  toggleSearch('on');
  
  var currentImagePtr = null;
  
  var _page = $('#page');
  
  if(currentUrl.length == 2) { //there's a sub uri
    if($.inArray(currentUrl[1],imagePointers) !== -1) {
      //pointer in url exists in images/data.json
      currentImagePtr = currentUrl[1];
      _page.css('opacity',0);
    } else {
      changePage('404','tabs-home');
      return;
    }
  }
  
  //load init cards
  var loadbar = $('#images-loading').children('img');
  loadbar.css('opacity','1');
  $.ajax({
    url: SITE_ROOT + '/html/images-card.php?si=0&cc='+IMAGES_START+'&srt=alphabetical',
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
      
      if(currentImagePtr !== null) {
        var win = $(window);
        var findCard = _page.find("div.image-card[data-pointer='"+currentImagePtr+"']");
        while(findCard.length === 0) {
          //load new block
          var sort = $('#select').val();
          //find start index to load
          var si = $('div.image-card-container').last().children('div.image-card').filter(':first').attr('id');
          si = parseInt(si.substring(11,si.length))+1;
          //load extra block of images
          $.ajax({
            url: SITE_ROOT + '/html/images-card.php?si='+si+'&cc='+IMAGES_CONT+'&srt='+sort,
            dataType: 'html',
            async: false,
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
              }
            }
          });
          findCard = _page.find("div.image-card[data-pointer='"+currentImagePtr+"']");
        }
        //by now a match should have been found
        if(findCard.length === 1) {
          findCard.children('div.card-hover').click();
          var modal = $('#images-modal');
          var modal_offset = modal.offset().top;
          var modal_height = modal.height();
          var win_height = win.height();
          var offset;
          if(modal_height < win_height) {
            offset = modal_offset - ((win_height / 2) - (modal_height / 2));
          } else {
            offset = modal_offset;
          }
          win.scrollTop(offset);
        }
        init_images_cards();
        dynamic_css();
        _page.animate({'opacity':1},{duration:200,queue:false});
      }
    }
  });
  
  init_infinite_scroll();
  
  //add functionality for sort select
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
var loadImageUrl = false;

function init_infinite_scroll() {
  var win = $(window);
  win.scroll(function() {
    //end of document reached
    if( ( ( $(document).height() - win.height() === win.scrollTop() ) || loadImageUrl ) && isLoading === false ) {
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
      jthis.parent().css({top: "7px"});
    }
  }, function() {
    //exit
    var jthis = $(this);
    if(jthis.siblings('div.additional').children('#toggle').html() === '0') {
      jthis.siblings('img.card-image').animate({opacity:0.9},card_anim_details);
      jthis.parent().css({top: "0"});
    }
  });
}

function init_images_details() {
  //TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO
  //TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO
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
  return parseInt(cardNum)%getElementsPerRow();
}

function getRowOfElement(id) {
  var cardNum = id.replace('#image-card-','');
  return Math.floor(parseInt(cardNum)/getElementsPerRow());
}

/*
 * mods bottom margins of row with details modal
 * 
 * @param {boolean} anim - true for animate margins, false for instant
 */
function modifyMargins(anim) {
  //first get cards of selected row
  var selectedId = $('#images-modal').siblings('div.image-card').attr('id');
  var allCards = $('div.image-card-container');
  //if there are no card details open
  if(!selectedId) {
    //set all bottom margins to 0
    if(anim) {
      allCards.animate({'margin-bottom':0},{duration:200,queue:false});
    } else {
      allCards.css({'margin-bottom':0});
    }
    return;
  }
  selectedId = parseInt(selectedId.replace('image-card-',''));
  var lowerId = selectedId-getColumnOfElement('#image-card-'+selectedId);
  var onCards = allCards.splice(lowerId,getElementsPerRow());
  //console.log(onCards.length);
  //console.log(allCards.length);
  onCards = $(onCards);
  allCards = $(allCards);
  var margin = onCards.first().height() * 1.55;
  if(anim) {
    $(onCards).animate({'margin-bottom':''+margin+'px'},{duration:200,queue:false});
    $(allCards).animate({'margin-bottom':0},{duration:200,queue:false});
  } else {
    $(onCards).css({'margin-bottom':''+margin+'px'});
    $(allCards).css({'margin-bottom':0});
  }
}

function imagesReadMoreToggle(card) {
  
  var loadingHtml = '<div id="images-modal-loading" class="custom-row"><img src="'+SITE_ROOT+'/img/loadingBar.gif" alt="Loading..."></div>';
  var jcard = $(card);
  var pointer = jcard.attr("data-pointer");
  
  function animateOff(_card) {
    var parent = _card.parent();
    var detailDiv = parent.children('#images-modal');
    detailDiv.empty();
    _card.children('.card-image').animate({opacity:0.9},card_anim_details);
    _card.css({top: '0'});
    _card.find('#toggle').html('0');
    detailDiv.attr('id','images-modal-exit');
    detailDiv.animate({'height':'0%'},{duration:200,queue:false,complete:function(){
      parent.css({'z-index':2});
      detailDiv.remove();
    }});
  }

  function animateOn(_card) {
    _card.children('.card-image').animate({'opacity': 1.0},card_anim_details);
    _card.css({top: '7px'});
    _card.find('#toggle').html('1');
    var parent = _card.parent();
    parent.css({'z-index':3});
    var detailDiv = '<div id="images-modal">'+loadingHtml+'</div>';
    parent.append(detailDiv);
    detailDiv = parent.children('#images-modal');
    detailDiv.css({'left':'-'+parent.offset().left+'px'});
    detailDiv.animate({'height':'150%'},{duration: 200, queue: false});
  }
  
  var state = parseInt(jcard.find('#toggle').text());
  if(state === 0) {
    //get cards that are 'on'
    var ons = $('div.image-card').filter(function() {
      if($(this).find('#toggle').html() == 0) {
        return false;
      } else {
        return true;
      }
    });
    //turn every on card off
    animateOff(ons);
    //turn current card on
    animateOn(jcard);
    $.ajax({
      url: SITE_ROOT + '/html/images-details.php?ptr='+pointer,
      dataType: 'html',
      success: function(data) {
        jcard.siblings('#images-modal').html(data).promise().done(function() {
          setNewState('images',pointer);
          init_images_details();
          dynamic_css();
          $('#images-modal-padding').animate({'opacity':1},{duration: 250, queue: false});
        });
      }
    });
  } else {
    //Then the card is already on. Turn it off.
    animateOff(jcard);
    setNewState('images');
  }
  modifyMargins(true);
}