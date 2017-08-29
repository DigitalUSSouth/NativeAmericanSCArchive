/* global interval, SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

var items;

function init_timeline() {
  toggleSearch('off');
  
  //$("a").click(function(e) {
  //  e.preventDefault();
  //  $("#someFrame").attr("src", $(this).attr("href"));
  //});
  $('.closeall').click(function(){
    $('.panel-collapse.in').collapse('hide');
  });
  $('#collapseOne').on('show.bs.collapse', function () {    
  $('.panel-heading').animate({
      backgroundColor: "#515151"
    }, 500);   
  });

  $('#collapseOne').on('hide.bs.collapse', function () {    
    $('.panel-heading').animate({
      backgroundColor: "#00B4FF"
    }, 500);   
  });
  
  items = document.querySelectorAll(".timeline li");
  //console.log(items)
  // listen for events
  window.addEventListener("load", callbackFunc);
  window.addEventListener("resize", callbackFunc);
  window.addEventListener("scroll", callbackFunc);
  
  //we need to run this callback function the first time because we are loading the page via ajax
  callbackFunc();
  
  //iframeChange();

  var embed = document.getElementById('timeline-embed');
  embed.style.height = "400px";//getComputedStyle(document.body).height;
  //embed.style.width = "100%"
  var options =  { 
    hash_bookmark: false
  }
  window.timeline = new TL.Timeline('timeline-embed', 'html/ht/data/data.json',options);
  window.addEventListener('resize', function() {
    var embed = document.getElementById('timeline-embed');
    //embed.style.height = getComputedStyle(document.body).height;
    //timeline.updateDisplay();
  })




  $('#testBtn').click(function(){
    $('#overlay').fadeIn('fast',function(){
        $('#box').animate({'top':'10%'},250);
    });
});
$('#boxclose').click(function(){
    $('#box').animate({'top':'-500px'},250,function(){
        $('#overlay').fadeOut('fast');
    });
});


}
  
function isElementInViewport(el) {
  var rect = el.getBoundingClientRect();
  return (
    rect.top >= 0 &&
    rect.left >= 0 &&
    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
  );
}

function callbackFunc() {
  for (var i = 0; i < items.length; i++) {
    if (isElementInViewport(items[i])) {
      items[i].classList.add("in-view");
    }
  }
}

function iframeChange() {
  var buttons = document.querySelector(".loadiframe"), iframe = document.getElementById('frame');
  buttons.addEventListener("click", function (e) {
    iframe.src = e.target.dataset.src;
  });
}

//for old browsers --> 