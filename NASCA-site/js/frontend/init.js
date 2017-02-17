//<!-- //for old browsers

//fade in
$(document).ready(function() {
  //set invisible EVERYTHING that will fade in
  var fadeIns = ['header', 'section'];
  for(var i = 0; i < fadeIns.length; i++) {
  	$(fadeIns[i]).css("display","none");
  }
  
  //set thumbnails
  var image = "";
  for(var i = 1; i <= 1; i++) {
    image = "#thumb" + i.toString();
    url = "url(../img/native_"+i.toString()+".jpg)"
    $(image).css("background",url);//http://digital.tcl.sc.edu/utils/getthumbnail/collection/nasca/id/" + pointers[i] + ")");
  }

  //fade everything in
  $(fadeIns[0]).fadeIn("slow", function() {
    $(fadeIns[1]).fadeIn("slow");
  });

});

//for old browsers -->