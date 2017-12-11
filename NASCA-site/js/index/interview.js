/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

var currentTabInterviews = "beaver-creek";

function init_interviews() {
  toggleSearch('on');
  
  if (currentUrl.length >= 2){//we might have a sub uri
    if ($.inArray(currentUrl[1],["catawba","beaver-creek","pee-dee","sumter-cheraw","wassamasaw"]) !== -1){
      currentTabInterviews = currentUrl[1];      
    }
    else {
      changePage("404","tabs-home");
      return;
    }
    $('#interviews-nav a[href="#'+currentTabInterviews+'"]').tab('show');
  }
  else {//no sub uri, but we set the history to point to catawba
    replaceCurrentState("interviews","beaver-creek");
  }
  
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

  //register for tab changes, so we can update uri
  $('#interviews-nav a').on('shown.bs.tab', function(event){
    //console.log(event)
    $(event.target).parent().addClass('tab-active').switchClass('text-dark-grey','text-red',{duration:60,queue:true}).siblings('.tab-active').removeClass('tab-active').switchClass('text-red','text-dark-grey',{duration:60,queue:true});
    //$('div.tab-active').removeClass('tab-active').switchClass('text-red','text-dark-grey');
    var hash = event.target.hash; // active tab
    var tab = hash.substring(1); //remove leading '#'
    if(currentUrl.length < 3) {
      setNewState("interviews",tab);
    }
    //console.trace();
    dynamic_css();
    currentTabInterviews = tab;
  });
  
  $('div.card-hover').hover(function(event) {
    //enter
    var jthis = $(event.target);
    jthis.parent().switchClass('background-red','background-white',{duration:60,queue:true});
    jthis.siblings().first().switchClass('text-white','text-dark-grey',{duration:60,queue:true});
  }, function(event) {
    //exit
    var jthis = $(event.target);
    jthis.parent().switchClass('background-white','background-red',{duration:60,queue:true});
    jthis.siblings().first().switchClass('text-dark-grey','text-white',{duration:60,queue:true});
  });

  if (currentUrl.length === 3){//we have a modal uri
    var modalUri = currentUrl[2];
    $('div.card-hover[data-transcript=\"'+modalUri+'-minified.json\"]').click();
  }
}

//init stuff for oral_histories.html
function launch_interview_modal(e) {
  var filename = e.dataset.transcript;
  var html = '';
  var match = filename.match(/.+?(?=-minified\.json)/);
  //append ajax results of interview template to html
  $.ajax({
		type:'POST',
    url: SITE_ROOT+'/html/interviews-template.php?f='+match[0],
    async: false,
    dataType: 'html',
    success: function(data) {
      html = data;
    }
  });
  //console.log(html);
  //html = '<div class="interview-template-container">'+html+'</div>';//temp line
  $.fancybox.open(
    html,
    {
      closeBtn: true,
      autoSize: false,
      autoScale: false,
      scrolling: false,
      autoDimensions: false,
      beforeShow: function (){
        if (match!==null){
          setNewState("interviews",currentTabInterviews,match[0]);
        }
      },
      afterLoad: function (){
        $('button.fancybox-close-small').addClass('custom-fancybox-close');
        $('#transcript-scroll-button').click(function() {
          var firstHighlight = $('p.transcript-highlight').first();
          if(firstHighlight.length) {
            console.log(firstHighlight.attr('id'));
            $('#transcript').animate({
              scrollTop: $('#transcript').scrollTop() + firstHighlight.position().top
            },1250);
          } else{
            console.log('nothing highlighted');
          }
        });
        dynamic_css();
      },
      beforeClose: function (){
        $('#jquery_jplayer_1').jPlayer("destroy");
        $('div.interview-template-container').html("<div class=\"text-center\"><h1>Loading...</h1><i class=\"fa fa-spinner fa-spin\" style=\"font-size:76px\"></i></h1></div>");
        setNewState("interviews",currentTabInterviews);
      }
    }
  );

}

/*
 * @param {event} event - the jPlayer timeUpdate event
 * @param {json} transcriptAsJson - the transcript to update in json format
 * @param {string} divIdRoot - root of id's that store text bits from transcript
 * @param {json} lastStepJson - information from last update
 * @returns {json} currentStepJson - information from current update to be used in next
 * 
 * SCHEMA for lastStepJson and currentStepJson
 * {
 *    'lastTime': 0.0,
 *    'lastBruteForce': 0.0,
 *    'lastUpdate': 0.0,
 *    'lastHighlightedId': -1,
 *    'additionalHighlightedIds': 0
 * }
 */
function updateTranscript(event, transcriptAsJson, divIdRoot, lastStepJson) {
  
  var currentTime = event.jPlayer.status.currentTime;
  
  var currentStepJson = jQuery.extend(true, {}, lastStepJson);
  currentStepJson.lastTime = currentTime;
  
  /*
   * If the user navigated the time forward or back
  */
  if(currentTime > (lastStepJson.lastTime + 0.75) || currentTime < (lastStepJson.lastTime - 0.75)) {
    //console.log('skip');
    //console.log('lastStep: ' + lastStepJson.lastTime + ', ' + lastStepJson.lastUpdate + ', ' + lastStepJson.lastHighlightedId + ', ' + lastStepJson.additionalHighlightedIds);
    //console.log('currentStep: ' + currentStepJson.lastTime + ', ' + currentStepJson.lastUpdate + ', ' + currentStepJson.lastHighlightedId + ', ' + currentStepJson.additionalHighlightedIds);
    //unhighlight lastStepJson.lastHighlightedId and
    //any lastStepJson.additionalHighlightedIds
    for(var i = 0; i <= lastStepJson.additionalHighlightedIds; i++) {
      if(lastStepJson.lastHighlightedId > -1) {
        highlight((divIdRoot + (lastStepJson.lastHighlightedId + i).toString()),'off');
      }
    }
    
    currentStepJson.lastHighlightedId = -1; //temp
    currentStepJson.additionalHighlightedIds = 0; //temp

    //console.log('lastStep: ' + lastStepJson.lastTime + ', ' + lastStepJson.lastUpdate + ', ' + lastStepJson.lastHighlightedId + ', ' + lastStepJson.additionalHighlightedIds);
    //console.log('currentStep: ' + currentStepJson.lastTime + ', ' + currentStepJson.lastUpdate + ', ' + currentStepJson.lastHighlightedId + ', ' + currentStepJson.additionalHighlightedIds);

    var i = 0;
    //console.log('init i');
    while((timecodeToInt(transcriptAsJson.text[i].timecode) < (currentTime - 0.05)) &&
												i < transcriptAsJson.text.length) {
      i++;
    }
    
    //console.log(i);
    
    /*
     * If i is still zero at this point, the player must have been stopped and restarted.
    */
    if(i === 0 && currentTime >= timecodeToInt(transcriptAsJson.text[i].timecode)) {
      highlight((divIdRoot + i.toString()),'on');
      currentStepJson.lastHighlightedId = i;
      
      //check if segments following i have same timecode,
      //highlight as necessary
      var count = 0;
      while(timecodeToInt(transcriptAsJson.text[i + count + 1].timecode)
                === timecodeToInt(transcriptAsJson.text[i].timecode)) {
        
        count++;
        highlight((divIdRoot + (i + count).toString()),'on');
      
      }
      
      currentStepJson.additionalHighlightedIds = count;
    
    }
    
    /*
     * At this point i should equal the first text bit that surpasses the current time.
    */ 
    else if(i > 0) {
      
      //find out if any before (i-1) have the same timecode
      var count = 0;
      while((i-1-count) >= 0) {
        if(typeof transcriptAsJson.text[i-2-count] === 'undefined') {
          break;
        }
        if(timecodeToInt(transcriptAsJson.text[i-2-count].timecode) ===
                timecodeToInt(transcriptAsJson.text[i-1].timecode)) {

          count++;
        
        } else {
          
          break;
				
        }
      }

      //highlight i-1-count and any additionals
      for(var j = 0; j <= count; j++) {
        
        highlight((divIdRoot + (i-1-count+j).toString()),'on');
      
      }
      
      currentStepJson.lastHighlightedId = i-1-count;
      currentStepJson.additionalHighlightedIds = count;

    }

    //currentStepJson.lastBruteForce = currentTime;
    currentStepJson.lastUpdate = currentTime;

  }


  /* If it's been more than 10 seconds since the last brute force reset
   *  (We do a brute force reset of transcript periodically as a failsafe,
   *  (for weird bugs I'm gonna attribute to solar flares)
  */
  //else if(currentTime > (lastStepJson.lastBruteForce + 10)) {

    //step through EVERY text bit
    //for(int i = 0; i < transcriptAsJson.text.length; i++) {

      //

    //}

    //currentStepJson.lastBruteForce = currentTime;
    //currentStepJson.lastUpdate = currentTime;

  //}


  /*
   * If none of the other conditions were met AND
   * it's been 0.75 seconds since the last update, update the transcript
  */
  else if(currentTime >= (lastStepJson.lastUpdate + 0.6)) {

    //Look for last highlighted id,
    // add additional if multiple with same time are highlighted,
    // then tack on one more.
    //You are left with the next upcoming time you should be searching for.
    
    var nextId = lastStepJson.lastHighlightedId + lastStepJson.additionalHighlightedIds + 1;
    var nextTime = timecodeToInt(transcriptAsJson.text[nextId].timecode);
    //console.log('update');
    /*
     * If the currentTime has surpassed the next time in the transcript
     *  (I added that little minus 0.05 just for a little padding,
     *   so the transition has less latency with the recording)
    */
    if(currentTime >= (nextTime - 0.05)) {
    
      //unhighlight lastStepJson.lastHighlightedId and
      //any lastStepJson.additionalHighlightedIds
      if(lastStepJson.lastHighlightedId >= 0) {
        for(var i = 0; i <= lastStepJson.additionalHighlightedIds; i++) {
          highlight((divIdRoot + (lastStepJson.lastHighlightedId + i).toString()),'off');
        }
      }

      currentStepJson.lastHighlightedId = -1; //temp
      currentStepJson.additionalHighlightedIds = 0; //temp

      //highlight nextId
      highlight((divIdRoot + (nextId).toString()),'on');
      currentStepJson.lastHighlightedId = nextId;

      //check if segments following nextId have same timecode,
      //highlight as necessary
      var count = 0;
      while(timecodeToInt(transcriptAsJson.text[nextId + count + 1].timecode)
                === timecodeToInt(transcriptAsJson.text[nextId].timecode)) {

        count++;
        highlight((divIdRoot + (nextId + count).toString()),'on');

      }

      currentStepJson.additionalHighlightedIds = count;

    }

    currentStepJson.lastUpdate = currentTime;

  }

  /* If NONE of these conditions were met, then:
   *	the user hasn't navigated
   *	a brute force reset is not yet necessary
   *	it's been less than 0.75 seconds since the last update
   *
   * In this case absolutely nothing happens, except that we update the 'lastTime'.
   * We do this to cut down on computation since it's not necessary to update the transcript
   * EVERY 0.25 seconds (the update function runs at 4hz, once every 0.25 seconds.)
  */

  return currentStepJson;

}

function timecodeToInt(timecode) {
  var hours = parseInt(timecode.substring(0,2));
  var minutes = parseInt(timecode.substring(3,5));
  var seconds = parseInt(timecode.substring(6,8));
  return ((hours * 360)+(minutes * 60)+(seconds));
}

function highlight(id,state) {
  if(state === 'on') {
    //console.log("on")
    $(id).toggleClass("transcript-highlight",250);
    //if the id IS NOT already highlighted
    //if(rgb2hex($(id).css('color')) === '#f7f7f7') {//default
      //animate it on
      /*$(id).animate({
        backgroundColor: '#ffff60',
        color: '#787788'//highlight
      }, 250);*/
    //}
  } else if(state === "off") {
    //console.log("off")
    $(id).toggleClass("transcript-highlight",250);
    
    //if the id IS highlighted
    //if(rgb2hex($(id).css('color')) === '#787788') {
      //animate it off
      /*$(id).animate({
        backgroundColor: '#840004',
        color: '#f7f7f7'
      }, 250);*/
    //}
  } else {
    console.log('oral_histories.js:highlight(id,state) BAD INPUT');
  }
}

function rgb2hex(rgb) {
  rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
  function hex(x) {
    return ('0' + parseInt(x).toString(16)).slice(-2);
  }
  return '#' + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

//for old browsers -->