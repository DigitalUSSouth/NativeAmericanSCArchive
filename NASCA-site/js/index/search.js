function init_search() {
  toggleSearch('on');
  console.log (currentUrl);
  var page = currentUrl[0];
  var sP = null;
  var sP2 = null;
  if (currentUrl.length>=2){
    sP = currentUrl[1];
    if (currentUrl.length=3){
      sP2 = currentUrl[2];
    }
  }
  replaceCurrentState(page,sP,sP2)
}