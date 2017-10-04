function init_video() {
  toggleSearch('on');
  if (window.location.hash){//we might have a sub uri
    hash = window.location.hash
    if (hash.startsWith("#Videos")){
      var videoId = hash.substr(8);
      videoId--;
      $('.video-single-media-fancybox').eq(videoId).click();
    }
  }
}