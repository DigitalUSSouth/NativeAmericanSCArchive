/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

var currentPage = 'home';

//init stuff for index.html
function init_index() {
  //populate global variables with info from configuration file
  setGlobals();
  
  //set invisible EVERYTHING that will fade in
  var fadeIns = ['.header', '.content', '.copyright'];
  for(var i = 0; i < fadeIns.length; i++) {
    $(fadeIns[i]).css('display','none');
  }
  
  //set date of copyright
  var d = new Date();
  document.getElementById('copyright').innerHTML = "NASCA &copy; " + d.getFullYear();
  
  //get home page content
  $.get({
    url: SITE_ROOT + '/html/home.html',
    success: function(data) {
      $('.content').html(data);
    }
  });
  
  //set thumbnails of home content
  //var image = "";
  //for(var i = 1; i <= 1; i++) {
  //  image = "#thumb" + i.toString();
  //  url = "url("+window.url_home+"/img/native_"+i.toString()+".jpg)";
  //  $(image).css("background",url);//http://digital.tcl.sc.edu/utils/getthumbnail/collection/nasca/id/" + pointers[i] + ")");
  //}
  
  //dynamic css function
  //handles content top padding when nav bar resizes
  var intervalId = setInterval(function(){
    var height = $('.header').height();
    $('.body-container').css('padding-top', height+'px');
  },250);
  
  //fade in
  intervalFade(fadeIns,500);

};

function changePage(page) {
  //check if page is already up
  if(!(page === currentPage)) {
    //fade out content
    $('.content').fadeOut(750,function(){
      //callback when fadeOut complete
      //set html content
      $.get({
        url: SITE_ROOT + '/html/' + page + '.html',
        success: function(data) {
          //callback when html retrieved
          $('.content').html(data);
          switch(page) {
            case 'home':
              break;
            case 'interviews-list':
              //launch_interview('Catawba_Earl-Robbins-May-1987-minified.json');
              break;
            case 'images':
              break;
            case 'video':
              break;
            case 'timeline':
              break;
            case 'map':
              break;
            case 'census':
              break;
            case 'tribes':
              break;
            default:
              //code
          }
          $('.content').fadeIn(750);
        }
      });
    });
    currentPage = page;
  }
}

//for old browsers -->