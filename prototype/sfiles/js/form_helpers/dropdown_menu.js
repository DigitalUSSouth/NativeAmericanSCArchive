$('.dropdown-menu').on('click', 'li a', function() {
  var btn = $(this).parent().parent().parent().find('.btn:first-child');
  btn.text($(this).text());
  btn.val($(this).text());
});
