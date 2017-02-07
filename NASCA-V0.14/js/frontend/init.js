<!-- //for old browsers

//fade in
$(document).ready(function() {
  //set EVERYTHING that will fade in to invisible
  $('header').css("display","none");
  $('section').css("display","none");
  $('footer').css("display","none");  

  //set thumbnails
  var image = "";
  for(var i = 1; i <= 9; i++) {
    image = "#thumb" + i.toString();
    url = "url(../images/native_"+i.toString()+".jpg)"
    $(image).css("background",url);//http://digital.tcl.sc.edu/utils/getthumbnail/collection/nasca/id/" + pointers[i] + ")");
  }

  //fade everything in
  $('header').fadeIn("slow", function() {
    $('section').fadeIn("slow", function() {
   	  $('footer').fadeIn("slow");
    });
  });

});

//for old browsers -->