/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

var currentPage = 'home';

var isFirstLoad = true;



window.onpopstate = function(event) {
  var popstate = event.state;
  if ($.isEmptyObject(popstate)) return;
  console.log(popstate);
  currentUrl = [];
  currentUrl.push(popstate.page);
  if (popstate.subPage !== null){
    currentUrl.push(popstate.subPage);
    if (popstate.subPage2 !== null){
      currentUrl.push(popstate.subPage2);
    }
  }
  //hide all bootstrap modals
  $(".modal").modal('hide')
  init_index();
};

//init stuff for index.html
function init_index() {
  //populate global variables with info from configuration file
  setGlobals();
  
  toggleSearch('on');
  
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

  //refactor tab changes so we can set the correct history state
  $('.tab').click(function(e){
    var tab = $(e.currentTarget);
    var tabId = tab.attr('id');
    var target = tabId.substring(5);
    currentUrl = [target];
    changePage(target,tab);
    setNewState(target);
  });
  $('.pullout-list-el').click(function(e){
    var tab = $(e.currentTarget);
    var tabId = tab.attr('id');
    var target = tabId.substring(13);
    currentUrl = [target];
    changePage(target,tab);
    setNewState(target);
  });

  //register for search input
  $("#search-input").change(function(){
    var value = $(this).val();
    value = encodeURIComponent(value);
    var uri = SITE_ROOT+'/search/'+value;
    //console.log(uri);
    $(this).parent().attr('data-target',uri);
  });
  $('#search-input').keypress(function (e) {
    var value = $(this).val();
    value = encodeURIComponent(value);
    var uri = SITE_ROOT+'/search/'+value;
    if (e.which == 13) {
      window.location = uri;
    }
  });
  $("#search-submit").click(function(){
    window.location = $("#search-form").attr('data-target');    
  });


  //check which main page is being requested and set page content automatically
  if(typeof currentUrl != "undefined" && currentUrl != null && currentUrl.length > 0){
    //we have a page other than home
    var tabElem = document.getElementById("tabs-"+currentUrl[0]);
    //console.log(tabElem);
    var page;
    if ($.inArray(currentUrl[0],["interviews","letters", "images","video","map","timeline","tribes","search"]) == -1){
      page = "404";
      currentUrl = ["404"];
    }
    else {
      page = currentUrl[0];
    }
    changePage(page,tabElem);
    //replaceCurrentState(page);
  }
  else {//home page
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
  }
};

function updateActiveTab(tabElem){
  $(".tab").removeClass("tab-active");
  $(tabElem).addClass("tab-active");
}

function changePage(page,tabElem) {
  //check if page is already up
  if(true) {//disabling check to make sure page gets reloaded everytime
    updateActiveTab(tabElem);    
    //fade out content
    $('#page').fadeOut(650,function(){
      //callback when fadeOut complete
      //set html content
      var requestUrl = SITE_ROOT + '/html/' + page + '.php';
      if (page=="search"){//pass appropriate params to search script
        var params = [];
        for(var i=0; i<currentUrl.length; i++){
          params.push({ name: i, value: currentUrl[i] });
        }
        requestUrl = requestUrl+'?'+$.param(params);
      }
      $.ajax({
        type:'POST',
        url: requestUrl,
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
                init_interviews();
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
              case 'search':
                init_search();
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
  }
}

//the following code sets a new state for browser history
//this allows users to bookmark individual pages in the site
function setNewState(page,subPage=null,subPage2=null){
  currentUrl = [page];
  if (subPage!==null){
    currentUrl.push(subPage);
    if (subPage2!==null){
      currentUrl.push(subPage2);
    }
  }
  var stateObject = {
    page: page,
    subPage: subPage,
    subPage2 : subPage2
  }
  var sPage = (subPage===null)?"":subPage;
  var sPage = (subPage2===null)? sPage : sPage+"/"+subPage2;
  var newUrl
  if (page!="home"){
    newUrl = SITE_ROOT+'/'+page+'/'+ sPage;
  }
  else {
    newUrl = SITE_ROOT+'/';
  }
  history.pushState(stateObject,page,newUrl);
  //console.log(currentUrl);
  //console.trace();
}

function replaceCurrentState(page,subPage=null,subPage2=null){
  currentUrl = [page];
  if (subPage!==null){
    currentUrl.push(subPage);
    if (subPage2!==null){
      currentUrl.push(subPage2);
    }
  }
  var stateObject = {
    page: page,
    subPage: subPage,
    subPage2 : subPage2
  }
  var sPage = (subPage===null)?"":subPage;
  var sPage = (subPage2===null)? sPage : sPage+"/"+subPage2;
  var newUrl
  if (page!="home"){
    newUrl = SITE_ROOT+'/'+page+'/'+ sPage;
  }
  else {
    newUrl = SITE_ROOT+'/';
  }
  history.replaceState(stateObject,page,newUrl);
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