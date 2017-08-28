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
  console.log(items)
  // listen for events
  window.addEventListener("load", callbackFunc);
  window.addEventListener("resize", callbackFunc);
  window.addEventListener("scroll", callbackFunc);
  callbackFunc();
  //iframeChange();
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