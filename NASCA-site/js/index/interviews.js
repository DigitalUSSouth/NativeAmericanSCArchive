//<!-- //for old browsers

//init stuff for oral_histories.html
function launch_interview(filename) {
  //grab json
  var datalocation = url_home + '/db/data/interviews/';
  var jsonobject = getJsonObject(datalocation + filename);

  //set title
  document.getElementById('title').innerHTML = jsonobject.title;

  var lastStepJson = {"lastTime": 0.0,"lastBruteForce": 0.0,"lastUpdate": 0.0,"lastHighlightedId": -1,"additionalHighlightedIds": 0};

  //set up jplayer with appropriate media
  $('#jquery_jplayer_1').jPlayer({
    ready: function() {
      $(this).jPlayer('setMedia', {
        title: jsonobject.title,
        mp3: (datalocation + jsonobject.audio_file)
      });
    },
    timeupdate: function(event) {
      lastStepJson = updateTranscript(event, jsonobject, '#ts', lastStepJson);
    },
    cssSelectorAncestor: '#jp_container_1',
    swfPath: '../js',
    supplied: 'mp3',
    useStateClassSkin: true,
    autoBlur: false,
    smoothPlayBar: true,
    keyEnabled: true,
    remainingDuration: true,
    toggleDuration: true
  });
  
  //put all json in text field
  var htmloutput = '';
  for(var i = 0; i < jsonobject.text.length; i++) {
    htmloutput += '<p class="ts-bit" id="ts' + i + '">' + jsonobject.text[i].speaker + ':<br>';
    htmloutput += jsonobject.text[i].text_bit + '<br></p>';
  }
  document.getElementById('transcript').innerHTML = htmloutput;

};

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
 *    'lastHighlightedId': 0,
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
    //unhighlight lastStepJson.lastHighlightedId and
    //any lastStepJson.additionalHighlightedIds
    for(var i = 0; i <= lastStepJson.additionalHighlightedIds; i++) {
      highlight((divIdRoot + (lastStepJson.lastHighlightedId + i).toString()),'off');
    }
    
    currentStepJson.lastHighlightedId = -1; //temp
    currentStepJson.additionalHighlightedIds = 0 //temp

    var i = 0;
    while((timecodeToInt(transcriptAsJson.text[i].timecode) < (currentTime - 0.05)) &&
												i < transcriptAsJson.text.length) {
      i++;
    }
    
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
      currentStepJson.additionalHighlightedIds = 0 //temp

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
    //if the id IS NOT already highlighted
    if(rgb2hex($(id).css('color')) === '#f7f7f7') {
      //animate it on
      $(id).animate({
        backgroundColor: '#ffff60',
        color: '#787788'
      }, 250);
    }
  } else if(state === "off") {
    //if the id IS highlighted
    if(rgb2hex($(id).css('color')) === '#787788') {
      //animate it off
      $(id).animate({
        backgroundColor: '#840004',
        color: '#f7f7f7'
      }, 250);
    }
  } else {
    alert('oral_histories.js:highlight(id,state) BAD INPUT');
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