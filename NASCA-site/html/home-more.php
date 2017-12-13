<?php
  $api_dir = preg_replace('/html.home-more\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  include_once ($api_dir . 'configuration.php');
  include_once ($api_dir . 'cdm.php');
  
  $errors = array();
  
  function printErrors($errors) {
    for($i = 0; $i < count($errors); $i++) {
      echo $errors[$i];
      echo '<br />';
    }
  }
  
  function printDefault(){ ?>
    <div id="preview-layout" class="preview-default">
      <div id="preview-title-container">
        <div id="preview-title" class="anton text-dark-grey">
          <b>Welcome</b>
        </div>
        <div id="preview-title-secondary" class="anton text-red">
          to the Native American South Carolina Archive!
        </div>
      </div>
      <hr class="red"/>
      <div id="preview-desc-container" class="source-serif text-dark-grey">
        <div id="preview-desc">
          <p>Click any of the cards on the left to get more information in this window about featured pictures, interviews, and more from our archive.</p>
          <br/>
          <p><i>OR</i></p>
          <br/>
          <p>Click the tabs in the nav bar to see all the images, interviews, etcetera, in one place. You're also encouraged to visually learn about local Native American history under the Video, Map, and Timeline tabs!</p>
        </div>
      </div>
    </div>
<?php } //printDefault()
  
  function printImageDetails($id, $title, $size) {
    global $errors;
    $trimmed = $title;
    if(strlen($trimmed) > 19) {
      $trimmed = substr($trimmed,0,19) . '...';
    }
    $ref_large = getImageReference($id,'large',1);
    $ref_full = getImageReference($id,'full',1);
    $dims = getImageDimensions($id,1);
    if(gettype($ref_large) === 'integer' && $ref_large < 0) {
      //couldn't get image reference
      $ref_large = SITE_ROOT . '/img/error/error.png';
      array_push($errors,'error in home-more.php line 50');
    }
    if(gettype($ref_full) === 'integer' && $ref_full < 0) {
      //couldn't get image reference
      $ref_full = SITE_ROOT . '/img/error/error.png';
      array_push($errors,'error in home-more.php line 55');
    }
    $attrib = array('descri','creato','tribe');
    $details = getItemInfo($id,$attrib,1);
?>
    <div id="preview-layout" class="<?php print 'preview-' . $size; ?>">
      <div id="preview-title-container" class="custom-title-overflow overflow-off-white">
        <div id="preview-title" class="anton text-dark-grey"><?php print $title; ?></div>
      </div>
      <div id="preview-media-container" class="border-red">
        <?php
          if(gettype($dims) === 'integer' && $dims < 0) {
            array_push($errors,'error in home-more.php line 67');
            //just print image, no link
        ?>
            <img src="<?php print $ref_large; ?>" id="preview-media" />
        <?php
          } else {
            //print full with link
        ?>
            <a class="fancybox-home" href="<?php print $ref_full; ?>" data-fancybox="Featured" data-type="image" data-caption="<?php print $trimmed; ?>" data-width="<?php print $dims['width']; ?>" data-height="<?php print $dims['height']; ?>">
              <img src="<?php print $ref_large; ?>" id="preview-media" />
            </a>
        <?php
          }
        ?>
      </div>
      <div id="preview-desc-container">
        <div id="preview-desc" class="source-serif text-black">
          <?php
            //TODO check if the details even returned
            $creato = (string)$details['creato'];
            $creato = str_ireplace('(photographer)','',$creato);
            $creato = trim($creato);
            print (string)$details['descri'] . '<br />Photographer: ' . $creato . '<br />Tribe: ' . (string)$details['tribe']
          ?>
        </div>
      </div>
      <div id="preview-lower" class="custom-row">
        <div id="view-all-container" onclick="setNewState('images','<?php print $id ?>'); changePage('images','#tabs-images');">
          <div id="view-all" class=text-red>Open In Images</div>
          <div id="view-all-underline"></div>
        </div>
      </div>
    </div>
<?php
  } //printImageDetails()
  
  function printLetterDetails($id, $title) {
    global $errors;
    $trimmed = $title;
    if(strlen($trimmed) > 19) {
      $trimmed = substr($trimmed,0,19) . '...';
    }
    $ref_large = getImageReference($id,'large',1);
    $ref_full = getImageReference($id,'full',1);
    $dims = getImageDimensions($id,1);
    if(gettype($ref_large) === 'integer' && $ref_large < 0) {
      //couldn't get image reference
      $ref_large = SITE_ROOT . '/img/error/error.png';
      array_push($errors,'error in home-more.php line 115');
    }
    if(gettype($ref_full) === 'integer' && $ref_full < 0) {
      //couldn't get image reference
      $ref_full = SITE_ROOT . '/img/error/error.png';
      array_push($errors,'error in home-more.php line 120');
    }
    $attrib = array('transc');
    $details = getItemInfo($id,$attrib,1);
    ?>
    <div id="preview-layout" class="preview-letter">
      <div id="preview-title-container" class="custom-title-overflow overflow-off-white">
        <div id="preview-title" class="anton text-dark-grey"><?php print $title; ?></div>
      </div>
      <div id="preview-media-container" class="border-red">
        <?php
          if(gettype($dims) === 'integer' && $dims < 0) {
            array_push($errors,'error in home-more.php line 132');
            //just print image, no link
        ?>
            <img src="<?php print $ref_large; ?>" id="preview-media" />
        <?php
          } else {
            //print full with link
        ?>
            <a class="fancybox-home" href="<?php print $ref_full; ?>" data-fancybox="Featured" data-type="image" data-caption="<?php print $trimmed; ?>" data-width="<?php print $dims['width']; ?>" data-height="<?php print $dims['height']; ?>">
              <img src="<?php print $ref_large; ?>" id="preview-media" />
            </a>
        <?php
          }
        ?>
      </div>
      <div id="preview-desc-container">
        <div id="preview-desc" class="source-serif text-black">
          <?php
            $description = '';
            if(gettype($details) === 'integer' && $details < 0) {
              array_push($errors,'error in home-more.php line 152');
              array_push($errors,'getItemInfo() returned error code ' . (string)$details);
              $description .= 'Something went wrong while getting the transcription for this letter. Please check back later.';
            } else {
              if(array_key_exists('transc',$details)) {
                $tran = trim((string)$details['transc']);
                if($tran !== '') {
                  $description .= $tran;
                } else {
                  $description .= 'A transcription is not yet available. Please check back later.';
                }
              } else {
                $description .= 'A transcription is not yet available. Please check back later.';
              }
            }
            print $description;
          ?>
        </div>
      </div>
      <div id="preview-lower" class="custom-row">
        <div id="view-all-container" onclick="changePage('letters','#tabs-letters');">
          <div id="view-all" class=text-red>View More Letters</div>
          <div id="view-all-underline"></div>
        </div>
      </div>
    </div>
<?php
  }
  
  function printVideoDetails($home_obj) {
    global $errors;
    $trimmed = $home_obj->title;
    if(strlen($trimmed) > 18) {
      $trimmed = substr($trimmed,0,18) . '...';
    }
    //get video info
    $url = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_VIDEO;
    $vid_data = getJsonLocal($url);
    $index = getId($vid_data, $home_obj->pointer);
    $obj = $vid_data->data[$index];
    $url_data = $vid_data->urls;
    $ref = (string)$url_data->thumbnail_prefix . (string)$obj->key . (string)$url_data->thumbnail_suffix;
    $vid = (string)$url_data->video_prefix . (string)$obj->key;
    ?>
    <div id="preview-layout" class="preview-wide">
      <div id="preview-title-container" class="custom-title-overflow overflow-off-white">
        <div id="preview-title" class="anton text-dark-grey"><?php print $home_obj->title; ?></div>
      </div>
      <div id="preview-media-container" class="border-red">
        <a class="fancybox-home" href="<?php print $vid; ?>" data-fancybox="Featured" data-type="iframe" data-caption="<?php print $trimmed; ?>" data-width="560" data-height="315">
          <img src="<?php print $ref; ?>" id="preview-media">
        </a>
      </div>
      <div id="preview-desc-container">
        <div id="preview-desc" class="source-serif text-black">
          <?php print $home_obj->description; ?>
        </div>
      </div>
      <div id="preview-lower" class="custom-row">
        <div id="view-all-container" onclick="setNewState('video','#Videos-'+<?php print substr("$home_obj->pointer",4); ?>); changePage('video');">
          <div id="view-all" class=text-red>Open in Videos</div>
          <div id="view-all-underline"></div>
        </div>
      </div>
    </div>
<?php
  }
  
  function printInterviewDetails($home_obj) {
    global $errors;
    $title = $home_obj->title;
    $ref = SITE_ROOT.$home_obj->ref;
    $script_file = $_SERVER['DOCUMENT_ROOT'].REL_HOME.DB_ROOT.'/interviews/transcripts/json/minified/'.$home_obj->script_file;
    $json = getJsonLocal($script_file,true);
    $transcript = $json['text'];
    ?>
    <div id="preview-layout" class="preview-letter">
      <div id="preview-title-container" class="custom-title-overflow overflow-off-white">
        <div id="preview-title" class="anton text-dark-grey"><?php print $title; ?></div>
      </div>
      <div id="preview-media-container">
        <img src="<?php print $ref; ?>" alt="Tribal Banner" id="preview-media-interview-custom">
      </div>
      <div id="preview-desc-container">
        <div id="preview-desc" class="source-serif text-black">
          <p><i>The following is a transcript sample from a recorded interview labeled, <b><?php print $title; ?></b></i></p><br/>
          <?php
            foreach($transcript as $i=>$ts) {
              if($i>10){break;}
              ?>
              <p class="ts-bit"><?php print $ts['speaker']; ?>: <?php print $ts['text_bit']; ?><br></p>
              <?php
            }
          ?>
        </div>
      </div>
      <div id="preview-lower" class="custom-row">
        <div id="view-all-container" onclick="setNewState('interviews','<?php print $home_obj->href; ?>','<?php print str_replace('-minified.json','',$home_obj->script_file); ?>');changePage('interviews','#tabs-interviews');">
          <div id="view-all" class=text-red>Listen To Interview</div>
          <div id="view-all-underline"></div>
        </div>
      </div>
    </div>
<?php
  }
  
  if(isset($_GET['homeptr'])) {
    $homePointers = json_decode(file_get_contents(SITE_ROOT . DB_ROOT . DB_HOME));
    $homeptr = $_GET['homeptr'];
    $home_obj = $homePointers->data[$homeptr];
    $type = strtolower($home_obj->type);
    $title = $home_obj->title;
    $id = $home_obj->pointer;
    $size = '';
    if(is_numeric($id)) {
      //if type isn't interview, images, letters, video, then there's a problem
      switch($type) {
        case 'interview':
          printInterviewDetails($home_obj);
          break;
        case 'image':
          if(isset($_GET['size'])) {
            $size = $_GET['size'];
          } else {
            array_push($errors,'size argument is unset for home-more.php. Displaying default page as fallback.');
            printDefault();
            break;
          }
          printImageDetails($id,$title,$size);
          break;
        case 'video':
          printVideoDetails($home_obj);
          break;
        case 'letter':
          printLetterDetails($id,$title);
          break;
        default:
          array_push($errors,'error in home-more.php line 212');
      }
    } else {
      array_push($errors,'error in home-more.php line 215');
    }

  }
  //if there aren't any arguments, load a default page that goes in the view
  //more box
  else {
    //default page
    printDefault();
  }
  
?>
<div class="additional">
  <p id="errors">
    <?php
      printErrors($errors);
    ?>
  </p>
</div>