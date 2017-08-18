<?php
  $api_dir = preg_replace('/html.home-more\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  include_once ($api_dir . 'configuration.php');
  include_once ($api_dir . 'cdm.php');
  
  function driver() {
    if(isset($_GET['homeptr'])) {
      $homePointers = json_decode(file_get_contents(SITE_ROOT . '/db/data/home/data.json'));
      $homeptr = $_GET['homeptr'];
      $type = $homePointers->data[$homeptr]->type;
      $title = $homePointers->data[$homeptr]->title;
      $id = $homePointers->data[$homeptr]->pointer;
      $size = '';
      if(is_numeric($id)) {
        //if type isn't interview, images, video, then there's a problem as well 
        switch($type) {
          case 'interviews':
            echo 'This is an interview';
            break;
          case 'images':
            if(isset($_GET['size'])) {
              $size = $_GET['size'];
            } else {
              errorMessage(-1);
              return -1;
            }
            $err = printImageDetails($id,$title,$size);
            if($err < 0) {
              return -1;
            }
            break;
          case 'video':
            echo 'This is a video';
            break;
          case 'letters':
            /*if(isset($_GET['size'])) {
              $size = $_GET['size'];
            } else {
              errorMessage(-1);
              return -1;
            }*/
            $err = printLetterDetails($id, $title);
            if($err < 0) {
              return -1;
            }
            break;
          default:
            errorMessage(-2);
            return -1;
        }
      } else {
        errorMessage(-3);
        return -1;
      }

    }
    //if there aren't any arguments, load a default page that goes in the view
    //more box
    else {
      //default page
      printDefault();
    }
    return 0;
  }
  
  function errorMessage($code) {
    echo '<div class="source-serif">';
    echo 'I\'m afraid you have encountered a bug.<br/>';
    echo 'Error Code: ' . $code . '<br/>';
    
    if($code === -4) {
      echo '<br/>This means that the image, and probably the metadata with it, ';
      echo 'could not be found in University of South Carolina\'s collection ';
      echo 'hosted at ContentDM. It is most likely that the image is still there, ';
      echo 'but the \'pointer\' has changed due to a change to the collection. If ';
      echo 'this is the case, then the entry will be back up and running by 2 A.M., ';
      echo 'when this website will be automatically updated in response.<br/><br/>';
      echo 'Thank you for your patience!';
    }
    
    echo '<hr class="red"/>';
    echo '"What can I do about this?"<br/><br/>';
    echo 'If the problem has not solved itself within a day, please let the silly developers know that there\'s a problem in a file called \'home-more.php\'<br/>';
    echo '</div>';
  }
  
  function printDefault() {
    echo '<div id="preview-layout" class="preview-default">';
    echo '  <div id="preview-title-container">';
    echo '    <div id="preview-title" class="anton text-dark-grey">';
    echo '      <b>Welcome,</b>';
    echo '    </div>';
    echo '    <div id="preview-title-secondary" class="anton text-red">';
    echo '      to the Native American South Carolina Archive!';
    echo '    </div>';
    echo '  </div>';
    echo '  <hr class="red"/>';
    echo '  <div id="preview-desc-container" class="source-serif text-dark-grey">';
    echo '    <div id="preview-desc">';
    echo '      <p>Click any of the cards on the left to get more information in this window about featured pictures, interviews, and more from our archive.</p>';
    echo '      <br/>';
    echo '      <p><i>OR</i></p>';
    echo '      <br/>';
    echo '      <p>Click the tabs in the nav bar to see all the images, interviews, etcetera, in one place. You\'re also encouraged to visually learn about local Native American history under the Video, Map, and Timeline tabs!</p>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
  }
  
  function printImageDetails($id, $title, $size) {
    $trimmed = $title;
    if(strlen($trimmed) > 19) {
      $trimmed = substr($trimmed,0,19) . '...';
    }
    $ref_large = getImageReference($id, 'large');
    $ref_full = getImageReference($id, 'full');
    $dimensions = getImageDimensions($id);
    if(is_numeric($ref_large) && $ref_large < 0) {
      errorMessage(-4);
      return -1;
    }
    if(is_numeric($ref_full) && $ref_full < 0) {
      errorMessage(-4);
      return -1;
    }
    echo '<div id="preview-layout" class="preview-' . $size . '">';
    echo '  <div id="preview-title-container" class="custom-title-overflow">';
    echo '    <div id="preview-title" class="anton text-dark-grey">' . $title . '</div>';
    echo '  </div>';
    echo '  <div id="preview-media-container" class="border-red">';
    echo '    <a class="fancybox-home" href="' . $ref_full . '" data-fancybox="Featured" data-type="image" data-caption="' . $trimmed . '" data-width="' . $dimensions['width'] . '" data-height="' . $dimensions['height'] . '">';
    echo '      <img src="' . $ref_large . '" id="preview-media" />';
    echo '    </a>';
    echo '  </div>';
    echo '  <div id="preview-desc-container">';
    $attrib = array('descri','creato','tribe');
    $details = getItemInfo($id,$attrib);
    echo '    <div id="preview-desc" class="source-serif text-black">';
    echo (string)$details['descri'] . '<br/>Photographer: ' . (string)$details['creato'] . '<br/>Tribe: ' . (string)$details['tribe'];
    echo '    </div>';
    echo '  </div>';
    echo '  <div id="preview-lower" class="row">';
    echo '    <div id="view-all-container" onclick="changePage(\'images\');">';
    echo '      <div id="view-all" class=text-red>View All Images</div>';
    echo '      <div id="view-all-underline"></div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
    return 0;
  }
  
  function printLetterDetails($id, $title) {
    $trimmed = $title;
    if(strlen($trimmed) > 19) {
      $trimmed = substr($trimmed,0,19) . '...';
    }
    $ref_large = getImageReference($id, 'large');
    $ref_full = getImageReference($id, 'full');
    $dimensions = getImageDimensions($id);
    if(is_numeric($ref_large) && $ref_large < 0) {
      errorMessage(-4);
      return -1;
    }
    if(is_numeric($ref_full) && $ref_full < 0) {
      errorMessage(-4);
      return -1;
    }
    echo '<div id="preview-layout" class="preview-letter">';
    echo '  <div id="preview-title-container" class="custom-title-overflow">';
    echo '    <div id="preview-title" class="anton text-dark-grey">' . $title . '</div>';
    echo '  </div>';
    echo '  <div id="preview-media-container" class="border-red">';
    echo '    <a class="fancybox-home" href="' . $ref_full . '" data-fancybox="Featured" data-type="image" data-caption="' . $trimmed . '" data-width="' . $dimensions['width'] . '" data-height="' . $dimensions['height'] . '">';
    echo '      <img src="' . $ref_large . '" id="preview-media" />';
    echo '    </a>';
    echo '  </div>';
    echo '  <div id="preview-desc-container">';
    $attrib = array('descri','type','media','creato','dateb','datea');
    $details = getItemInfo($id,$attrib);
    echo '    <div id="preview-desc" class="source-serif text-black">';
    echo '      Description: ' . (string)$details['descri'] . '<br/>Type: ' . (string)$details['type'] . '<br/>Media: ' . (string)$details['media'] . '<br/>Creator: ' . (string)$details['creato'] . '<br/>dateb: ' . (string)$details['dateb'] . '<br/>datea: ' . (string)$details['datea'];
    echo '    </div>';
    echo '  </div>';
    echo '  <div id="preview-lower" class="row">';
    echo '    <div id="view-all-container" onclick="changePage(\'letters\');">';
    echo '      <div id="view-all" class=text-red>View All Letters</div>';
    echo '      <div id="view-all-underline"></div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
    return 0;
  }
  
  driver();
  
?>