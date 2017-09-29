/* global interval, SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

var items;
var el;

var currentTimeline

function init_timeline() {
  toggleSearch('on');  
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

  //iterate through timeline wrapper elements and add to array
  //TODO remove .each() loop below. we don't need it anymore
  var elements = [];
  $(".timeline-embed").each(function(i,obj){
    elements.push($(this).attr('id'))
  });
  console.log(elements);


  for(var i=1; i<=12; i++){
    $("#timelineModal"+i).on('shown.bs.modal', function(e){
      var i = e.relatedTarget.dataset.boxId //have to redeclare because of scope
      var indexNum = i
      wrapperElement = $("#timeline-embed-"+i).attr('id');
      var embed = document.getElementById(wrapperElement);
      embed.style.height = "500px";//getComputedStyle(document.body).height;
      $("#"+i).css('z-index','101');
      var options =  { 
        hash_bookmark: false
      }
      var dataPath = SITE_ROOT+"/html/ht/data/data"+indexNum+".json"
      window.timeline = new TL.Timeline(wrapperElement,dataPath,options);

      //update uri
      setNewState("timeline",i);
    });
    $("#timelineModal"+i).on('hidden.bs.modal',function(e){
      setNewState("timeline")      
    });      
  }

  if (currentUrl.length >= 2){//we might have a sub uri
    nUrl = parseInt(currentUrl[1]);
    if (nUrl>=1 && nUrl<=12){
      currentTimeline = currentUrl[1];      
    }
    else {
      changePage("404","tabs-home");
      return;
    }
    $('#openTimeline'+currentTimeline).click()
  }
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