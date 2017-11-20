var detailShown = false;
var shownId = -1;
var currentTabLetters = "";
var initialLoad = true;
function init_letters() {
  toggleSearch('on');
  var custom_about_click = $('#custom-about-section-click');
  var custom_about_content = custom_about_click.siblings('#custom-about-section-content');
  custom_about_click.click(function() {
    if(custom_about_content.css('display') === 'none') {
      custom_about_content.animate({'opacity':1,'letter-spacing':'0ex'},{duration:200,queue:false}).css({'display':'inline'});
      custom_about_click.html('Collapse');
    } else {
      custom_about_content.animate({'opacity':0,'letter-spacing':'-0.5ex'},{duration:200,queue:false}).css({'display':'none'});
      custom_about_click.html('About this page');
    }
  });
  
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


  if (currentUrl.length >= 2){//we might have a sub uri
    if ($.inArray(currentUrl[1],tabHrefs) !== -1){
      currentTabLetters = currentUrl[1];      
    }
    else {
      changePage("404","tabs-home");
      return;
    }
    $('.nav-tabs a[href="#'+currentTabLetters+'"]').tab('show')
  }
  else {//no sub uri, but we set the history to point to catawba
    replaceCurrentState("letters",tabHrefs[0]);
  }
  if (currentUrl.length ==3){//we have a letter uri
    var letterUri = currentUrl[2];
    //console.log(letterUri);
    tribe = currentUrl[1];
    //console.log(tribe)
    $('.carousel[data-tribe=\"'+tribe+'\"] .letter-toggle[data-letter=\"'+letterUri+'\"]').click();
  }

  //register for tab changes, so we can update uri
  $('.nav-tabs.letter-tab a').on('shown.bs.tab', function(event){
    if (!initialLoad)hideLetter();
    //console.log(event)
    var hash = event.target.hash; // active tab
    var tab = hash.substring(1); //remove leading '#'
    if(!initialLoad)setNewState("letters",tab);
    //console.log('newstate');
    currentTabLetters = tab;
    initialLoad=false;
  });
}

function showLetter(letterId,tribe){
  //console.log('show')
  $("#letterDetail").collapse('show');
  url = SITE_ROOT + '/html/letter-detail.php?id='+letterId+'&tribe='+tribe;
  //console.log(url);
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
  //update uri
  setNewState("letters",currentTabLetters,letterId);
}

function hideLetter(){
  //console.log('hide')
  $("#letterDetail").html("<div class=\"text-center\"><h1>Loading...</h1><i class=\"fa fa-spinner fa-spin\" style=\"font-size:76px\"></i></h1></div>")
  $("#letterDetail").collapse("hide");
  detailShown = false;
  setNewState("letters",currentTabLetters)
}