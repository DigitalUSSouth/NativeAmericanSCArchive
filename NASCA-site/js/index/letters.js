var detailShown = false;
var shownId = -1;

function init_letters() {
  toggleSearch('on');
  $(".letter-toggle").click(function (e){
    //hideLetter();//hide old letter
    var letterId = parseInt($(this).attr("data-letter"));
    var tribe = $(this).parent().parent().parent().attr("data-tribe");
    //console.log(letterId+' '+shownId+' click '+detailShown);
    if (detailShown && letterId==shownId) {
      shownId = -1;
      detailShown = false;
      hideLetter();
    }
    else if (detailShown && letterId != shownId) {
      showLetter(letterId,tribe);
      shownId = letterId;
    }
    else {
      showLetter(letterId,tribe);
      shownId = letterId;
    }
  });
  

  //register for tab changes, so we can update uri
  $('.nav-tabs.letter-tab a').on('shown.bs.tab', function(event){
    hideLetter();
    //console.log(event)
    var hash = event.target.hash; // active tab
    var tab = hash.substring(1); //remove leading '#'
    //setNewState("interviews",tab);
    //console.trace();
    //currentTabInterviews = tab;
  });
}

function showLetter(letterId,tribe){
  //console.log('show')
  $("#letterDetail").collapse('show');
  url = SITE_ROOT + '/html/letter-detail.php?id='+letterId+'&tribe='+tribe;
  $.ajax({
		type:'GET',
    url: url,
    async: true,
    dataType: 'html',
    success: function(data) {
      $('#letterDetail').html(data);
    }
  });
  detailShown = true;
}

function hideLetter(){
  //console.log('hide')
  $("#letterDetail").html("<div class=\"text-center\"><h1>Loading...</h1><i class=\"fa fa-spinner fa-spin\" style=\"font-size:76px\"></i></h1></div>")
  $("#letterDetail").collapse("hide");
  detailShown = false;
}