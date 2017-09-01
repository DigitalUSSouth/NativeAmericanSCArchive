/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

var currentPage = 'home';

//init stuff for index.html
function init_index() {
  //populate global variables with info from configuration file
  setGlobals();
  
  toggleSearch('off');
  
  //set invisible EVERYTHING that will fade in
  //var fadeIns = ['#header-positioner', '#page-container', '#footer-container'];
  //for(var i = 0; i < fadeIns.length; i++) {
  //  $(fadeIns[i]).css('display','none');
  //}
  
  //set onhover onclick stuff
  $('#logo').hover(function() {
    //enter
    $('#logo-verbose img').animate({opacity:1.0},'fast');
  }, function() {
    //exit
    $('#logo-verbose img').animate({opacity:0},'fast');
  });
  
  $('#menu').click(function() {
    var cssval = $('#pullout-positioner').css('right');
    cssval = cssval.substring(0,cssval.length-2);
    cssval = parseInt(cssval);
    if(cssval < 0) {
      $('#pullout-positioner').animate({right:0},'fast');
    } else {
      $('#pullout-positioner').animate({right:'-100%'},'fast');
    }
  });
  
  //protect fancybox images from download
  $().fancybox({
    selector: '[data-fancybox="Featured"]',
    protect: true,
    autoSize: false,
    autoScale: false,
    autoDimensions: false
  });
  
  //set date of copyright
  var d = new Date();
  document.getElementById('copyright').innerHTML = "Native American South Carolina Archive (NASCA) &copy; " + d.getFullYear();
  
  dynamic_css();
  //fade in
  //intervalFade(fadeIns,500);
  
  //get home page content
  $.ajax({
    type:'POST',
    url: SITE_ROOT + '/html/home.php',
    async: true,
    dataType: 'html',
    success: function(data) {
      $('#page').html(data).promise().done(function() {
        dynamic_css();
        init_home();
        dynamic_css();
      });
    }
  });
};

function updateActiveTab(tabElem){
  $(".tab").removeClass("tab-active");
  $(tabElem).addClass("tab-active");
}

function changePage(page,tabElem) {
  //check if page is already up
  if(!(page === currentPage)) {
    updateActiveTab(tabElem);    
    //fade out content
    $('#page').fadeOut(650,function(){
      //callback when fadeOut complete
      //set html content
      $.ajax({
        type:'POST',
        url: SITE_ROOT + '/html/' + page + '.php',
        async: true,
        dataType: 'html',
        success: function(data) {
          //callback when html retrieved
          $('#page').html(data).promise().done(function() {
            dynamic_css();
            $('#page').fadeIn(650);
            switch(page) {
              case 'home':
                init_home();
                break;
              case 'interviews':
                init_interview();
                break;
              case 'images':
                init_images();
                break;
              case 'video':
                init_video();
                break;
              case 'map':
                init_map();
                break;
              case 'timeline':
                init_timeline();
                break;
              case 'letters':
                init_letters();
                break;
              case 'tribes':
                init_tribes();
                break;
              default:
                //code
            }
            dynamic_css();
          });
        }
      });
    });
    currentPage = page;
    setNewState(currentPage);
  }
}

//the following code sets a new state for browser history
//this allows users to bookmark individual pages in the site
function setNewState(page,subPage=null){
  var stateObject = {
    page: page,
    subPage: subPage
  }
  var sPage = (subPage===null)?"":subPage;
  var newUrl
  if (page!="home"){
    newUrl = SITE_ROOT+'/'+page+'/'+ sPage;
  }
  else {
    newUrl = SITE_ROOT+'/';
  }
  history.pushState(stateObject,page,newUrl);
  //console.log(newUrl);
}

function toggleSearch(val) {
  var right = $('#search-container').css('right');
  right = right.substring(0,1);
  var off = true;
  if(right === '0') {
    off = false;
  }
  if(val === 'off' && off === false) {
    $('#search-container').animate({'right':'-70%','opacity':0},350);
  } else if(val === 'on' && off) {
    $('#search-container').animate({'right':'0','opacity':1.0},350);
  }
}

//for old browsers -->