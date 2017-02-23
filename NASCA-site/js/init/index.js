//<!-- //for old browsers

//init stuff for index.html
$(document).ready(function() {
  //populate global variables with info from configuration file
  populateCdmGlobals();
  populateUrlGlobals();

  //set thumbnails
  var image = "";
  for(var i = 1; i <= 1; i++) {
    image = "#thumb" + i.toString();
    url = "url("+window.url_home+"/img/native_"+i.toString()+".jpg)";
    $(image).css("background",url);//http://digital.tcl.sc.edu/utils/getthumbnail/collection/nasca/id/" + pointers[i] + ")");
  }

});

//for old browsers -->