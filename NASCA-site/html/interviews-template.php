<?php
  $api_dir = preg_replace('/html.interviews-template\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  include_once ($api_dir . 'configuration.php');
  include_once ($api_dir . 'cdm.php');
  $filename = $_SERVER['DOCUMENT_ROOT'].REL_HOME.DB_ROOT.'/interviews/transcripts/json/minified/'.$_GET['f'].'-minified.json';
  //print $filename;
  $interviewData = getJsonLocal($filename);
  if(gettype($interviewData) === 'integer' && $interviewData < 0) {
    die('Could not retrieve the interview.');
  }
  $title = $interviewData->title;
  $audio = $interviewData->audio_file;
  $transcript = $interviewData->text;
  $description = $interviewData->description;
?>

<div class="interview-template-container background-off-white">
  <script>
    var lastStepJson = {"lastTime": 0.0,"lastBruteForce": 0.0,"lastUpdate": 0.0,"lastHighlightedId": -1,"additionalHighlightedIds": 0};
    var transcriptobject = getJsonObject(SITE_ROOT+DB_ROOT+'/interviews/transcripts/json/minified/<?php print $_GET['f']; ?>-minified.json');
    //set up jplayer with appropriate media
    $('#jquery_jplayer_1').jPlayer({
      ready: function() {
        $(this).jPlayer('setMedia', {
          title: '<?php print $title; ?>',
          mp3: '<?php print SITE_ROOT.DB_ROOT.'/interviews/compressed/'.$audio; ?>'
        });
      },
      timeupdate: function(event) {
        lastStepJson = updateTranscript(event, transcriptobject, '#ts', lastStepJson);
      },
      cssSelectorAncestor: '#jp_container_1',
      preload: 'metadata',
      volume: 0.75,
      muted: false,
      backgroundColor: '#323a3b',
      swfPath: '../js',
      supplied: 'mp3',
      useStateClassSkin: true,
      autoBlur: false,
      smoothPlayBar: true,
      keyEnabled: true,
      remainingDuration: true,
      toggleDuration: true,
      errorAlerts: false,
      warningAlerts: false,
      cssSelector: {
        videoPlay: '.jp-video-play',
        play: '.jp-play',
        pause: '.jp-pause',
        stop: '.jp-stop',
        seekBar: '.jp-seek-bar',
        playBar: '.jp-play-bar',
        mute: '.jp-mute',
        unmute: '.jp-unmute',
        volumeBar: '.jp-volume-bar',
        volumeBarValue: '.jp-volume-bar-value',
        volumeMax: '.jp-volume-max',
        playbackRateBar: '.jp-playback-rate-bar',
        playbackRateBarValue: '.jp-playback-rate-bar-value',
        currentTime: '.jp-current-time',
        duration: '.jp-duration',
        title: '.jp-title',
        fullScreen: '.jp-full-screen',
        restoreScreen: '.jp-restore-screen',
        repeat: '.jp-repeat',
        repeatOff: '.jp-repeat-off',
        gui: '.jp-gui',
        noSolution: '.jp-no-solution'
       }
    });
  </script>
  <div id="interview-book" class="book custom-column">
    <div id="interview-template-left">
      <div id="interview-template-title" class="anton text-dark-grey"><?php print $title; ?></div>
      <div id="interview-template-description" class="source-serif text-dark-grey"><i><?php print $description; ?></i></div>
        
      <div id="jquery_jplayer_1" class="jp-jplayer"></div>
      <div id="jp_container_1" class="jp-audio" role="application" aria-label="media player">
        <div class="jp-type-single">
          <div class="jp-gui jp-interface border-red text-white source-serif">
            <div class="jp-volume-controls">
              <button class="jp-mute" role="button" tabindex="0">mute</button>
              <button class="jp-volume-max" role="button" tabindex="0">max volume</button>
              <div class="jp-volume-bar">
                <div class="jp-volume-bar-value"></div>
              </div>
            </div>
            <div class="jp-controls-holder">
              <div class="jp-controls">
                <button class="jp-play" role="button" tabindex="0">play</button>
                <button class="jp-stop" role="button" tabindex="0">stop</button>
              </div>
              <div class="jp-progress-container">
                <div class="jp-progress">
                  <div class="jp-seek-bar">
                    <div class="jp-play-bar"></div>
                  </div>
                </div>
                <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
              </div>
              
              <!--div class="jp-toggles">
                <button class="jp-repeat" role="button" tabindex="0">repeat</button>
              </div-->
            </div>
          </div>
          <!--div class="jp-details">
            <div class="jp-title" aria-label="title">&nbsp;</div>
          </div-->
          <div class="jp-no-solution">
            <span>Update Required</span>
            To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
          </div>
        </div>
      </div>
      
    </div>
    <div class="transcript source-serif" id="transcript">
      <?php
      foreach($transcript as $i=>$ts) {
        ?>
        <p class="ts-bit" id="ts<?php print $i; ?>"><?php print $ts->speaker; ?>:<br><?php print $ts->text_bit; ?><br></p>
        <?php
      }
      ?>
    </div>
    <button id="transcript-scroll-button" class="anton text-white background-red">
      Scroll to Current Text
    </button>
  </div>
</div>