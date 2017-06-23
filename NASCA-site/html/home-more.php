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
            if(isset($_GET['size'])) {
              $size = $_GET['size'];
            } else {
              errorMessage(-1);
              return -1;
            }
            $err = printLetterDetails($id, $title, $size);
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
    echo 'I\'m afraid you have encountered a bug.<br/>';
    echo 'Error Code: ' . $code . '<br/>';
    echo '"What should I do about this?"<br/>';
    echo 'Please let the silly developers know that there\'s a problem in a file called \'home-more.php\'<br/>';
  }
  
  function printDefault() {
    echo '<h2><b>Welcome to the Native American South Carolina Archive!</b></h2>';
    echo '<hr class="red"/>';
    echo '<p>Click any of the cards on the left to get more information in this window about featured pictures, interviews, and videos in our archive.</p>';
    echo '<p><i>OR</i></p>';
    echo '<p>Click the tabs in the nav bar to see all the images, interviews, etcetera, in one place. You\'re also encouraged to visually learn about local Native American history under the Video, Map, and Timeline tabs!</p>';
  }
  
  function printImageDetails($id, $title, $size) {
    $trimmed = $title;
    if(strlen($trimmed) > 20) {
      $trimmed = substr($trimmed,0,20) . '...';
    }
    $ref_large = getImageReference($id, 'large');
    $ref_full = getImageReference($id, 'full');
    if(is_numeric($ref_large) && $ref_large < 0) {
      errorMessage(-4);
      return -1;
    }
    if(is_numeric($ref_full) && $ref_full < 0) {
      errorMessage(-4);
      return -1;
    }
    echo '<div id="preview-layout" class="preview-' . $size . '">';
    echo '  <div id="preview-title-container">';
    echo '    <div id="preview-title" class="anton text-dark-grey">' . $title . '</div>';
    echo '  </div>';
    echo '  <div id="preview-media-container">';
    echo '    <a href="' . $ref_full . '" data-lightbox="Featured" data-title="' . $trimmed . '">';
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
  
  function printLetterDetails($id, $title, $size) {
    echo '<p><b><i>From Letters...</i></b></p>';
    echo '<h2><b>' . $title . '</b></h2>';
    echo '<hr class="darkgrey"/>';
    echo '<p>...</p>';
    echo '<hr class="red"/>';
    echo '<p><b><i>Click \'View More\' to browse all of our archived letters.</i></b></p>';
  }
  
  driver();
  
?>