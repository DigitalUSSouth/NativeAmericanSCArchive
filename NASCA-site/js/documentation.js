var currentPage = 'documentation';

$(document).ready(function() {
  //set invisible EVERYTHING that will fade in
  var fadeIns = ['#header-positioner', '#page-container', '#footer-container'];
  for(var i = 0; i < fadeIns.length; i++) {
    $(fadeIns[i]).css('display','none');
  }
  
  //set onhover onclick stuff
  $('#logo').hover(function() {
    //enter
    $('#logo-verbose img').animate({opacity:1.0},'fast');
  }, function() {
    //exit
    $('#logo-verbose img').animate({opacity:0},'fast');
  });

  //set date of copyright
  var d = new Date();
  document.getElementById('copyright').innerHTML = "Native American South Carolina Archive (NASCA) &copy; " + d.getFullYear();


  //fade in
  intervalFade(fadeIns,500);
/*
  var i = 0;
  var cssinterval = setInterval(function(){
    dynamic_css();
    i++;
    //if it's been 4 seconds since load
    if(i > 19) {
      clearInterval(cssinterval);
    }
  },200);*/
});