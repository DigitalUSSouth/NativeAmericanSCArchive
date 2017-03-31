$(document).ready(function() {
  $('.modal-close').click(function() {
    $('.modal').css('display','none');
    $('.modal-body').html('');
  });
  $(window).click(function(e) {
    if (e.target.className === 'modal') {
      $('.modal').css('display','none');
      $('.modal-body').html('');
    }
  });
});