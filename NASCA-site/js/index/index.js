/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

var currentPage = 'home';

var interval = 100; //length for all setInterval functions in milliseconds
var interval_index = null;
var interval_home = null;
var interval_interviews = null;
var interval_images = null;
var interval_video = null;
var interval_map = null;
var interval_timeline = null;
var interval_letters = null;

//init stuff for index.html
function init_index() {
  //populate global variables with info from configuration file
  setGlobals();
  
  //set invisible EVERYTHING that will fade in
  var fadeIns = ['#header', '#page', '#footer'];
  for(var i = 0; i < fadeIns.length; i++) {
    $(fadeIns[i]).css('display','none');
  }
  
  //set date of copyright
  var d = new Date();
  document.getElementById('copyright').innerHTML = "Native American South Carolina Archive (NASCA) &copy; " + d.getFullYear();
  
  //get home page content
  $.ajax({
    type:'POST',
    url: SITE_ROOT + '/html/home.php',
    async: true,
    dataType: 'html',
    success: function(data) {
      $('#page').html(data).promise().done(function() {
        init_home();
      });
    }
  });
  
  //dynamic css function
  //handles content top padding when nav bar resizes
  var interval_index = setInterval(function(){
    var navHeight = $('#header-positioner').height();
    $('#header-positioner-height-offset').css('height', navHeight+'px');
    
  },interval);
  
  //fade in
  intervalFade(fadeIns,500);

};

function clearPageIntervals() {
  var pages = [interval_home, interval_interviews, interval_images, interval_video, interval_map, interval_timeline, interval_letters];
  for(var i = 0; i < pages.length; i++) {
    if(pages[i] !== null) {
      clearInterval(pages[i]);
    }
    pages[i] = null;
  }
}

function clearPageInterval(pageInterVar) {
  clearInterval(pageInterVar);
  pageInterVar = null;
}

function changePage(page) {
  //check if page is already up
  if(!(page === currentPage)) {
    //fade out content
    $('#page').fadeOut(750,function(){
      //callback when fadeOut complete
      //clear all interval actions
      clearPageIntervals();
      //set html content
      $.ajax({
        type:'POST',
        url: SITE_ROOT + '/html/' + page + '.php',
        async: true,
        dataType: 'html',
        success: function(data) {
          //callback when html retrieved
          $('#page').html(data).promise().done(function() {
            switch(page) {
              case 'home':
                init_home();
                break;
              case 'interviews':
                //launch_interview('Catawba_Earl-Robbins-May-1987-minified.json');
                break;
              case 'images':
                break;
              case 'video':
                break;
              case 'map':
                break;
              case 'timeline':
                init_timeline();
                break;
              case 'letters':
                break;
              default:
                //code
            }
          });
          $('#page').fadeIn(750);
        }
      });
    });
      
    currentPage = page;
  }
}

//for old browsers -->