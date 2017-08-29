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


  //iterate through timeline wrapper elements and add to array
  var elements = [];
  $(".timeline-embed").each(function(i,obj){
    elements.push($(this).attr('id'))
  });
  console.log(elements);

  //now we take array from above and run timeline initialization
  var counter = 1;
  for (var i in elements){
      console.log(elements[i])
      wrapperElement = elements[i]
      var embed = document.getElementById(wrapperElement);
      embed.style.height = "400px";//getComputedStyle(document.body).height;
      //embed.style.width = "100%"
      var options =  { 
        hash_bookmark: false
      }

      var dataPath = "html/ht/data/data"+counter+".json"
      counter++;
      console.log(counter)
      console.log(dataPath)
      window.timeline = new TL.Timeline(wrapperElement,dataPath,options);
      window.addEventListener('resize', function() {
        var embed = document.getElementById(wrapperElement);
        //embed.style.height = getComputedStyle(document.body).height;
        //timeline.updateDisplay();
      })
  }




  $('#testBtn').click(function(){
    $('#overlay').fadeIn('fast',function(){
        $('#box1').animate({'top':'10%'},250);
    });
});
$('#boxclose').click(function(){
    $('#box1').animate({'top':'-500px'},250,function(){
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