//<!-- //for old browsers

//fade in
$(document).ready(function() {
  //set invisible EVERYTHING that will fade in
  var fadeIns = ['header', 'section'];
  for(var i = 0; i < fadeIns.length; i++) {
  	$(fadeIns[i]).css("display","none");
  }

  //fade everything in
  $(fadeIns[0]).fadeIn("slow", function() {
    $(fadeIns[1]).fadeIn("slow");
  });

});

//for old browsers -->