<?php
  $api_dir = preg_replace('/html.images-card\.php/','api/',__FILE__);
  include_once ($api_dir . 'configuration.php');
  include_once ($api_dir . 'cdm.php');
  
  /*
   * Prints a certain number of cards from images/data.json,
   * in a given sort order, starting with a certain index in the sorted array.
   * If the $cardCount is greater than the remainder of cards in the list after
   * the $startIndex, $cardCount will be disregarded and only the remainder
   * will be printed.
   * 
   * Inputs:
   * (int) $startIndex - starting index to search from images/data.json (after sort)
   *                        keep in mind that this is NOT the cdm pointer, but the array index
   * (int) $cardCount - number of cards to print in order after the given index
   * (string) $sortMethod - method of sorting and/or filtering images in images/data.json
   * 
   * Sorting options:
   * indexical - by local index in images/data.json
   * alphabetical - alphabetical by title of image
   * tribal - alphabetical by tribe name (omit those without tribe)
   * catawba
   * chicora
   * ecsiut
   * edisto
   * peedee
   * santee
   * waccamaw
   * wassamasaw
   * 
   * Returns negative number if error, sends error codes to php error log.
   */
  function cardDriver($startIndex, $cardCount, $sortMethod) {
    $data_loc = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_IMAGE;
    $data = getJsonLocal($data_loc);
    $data = $data->data;
    switch($sortMethod) {
      case 'indexical':
        //do nothing
        break;
      case 'alphabetical':
        usort($data,function($a,$b) {
          return strcmp(strtolower(trim($a->title)),strtolower(trim($b->title)));
        });
        break;
      case 'tribal':
        $data = array_filter($data,function($obj) {
          return (trim($obj->tribe) !== '');
        });
        usort($data,function($a,$b) {
          return strcmp(strtolower(trim($a->tribe)),strtolower(trim($b->tribe)));
        });
        break;
      case 'catawba':
      case 'chicora':
      case 'ecsiut':
      case 'edisto':
      case 'peedee':
      case 'santee':
      case 'waccamaw':
      case 'wassamasaw':
        $data = array_filter($data,function($obj) use ($sortMethod) {
          return (strtolower(str_replace(' ','',$obj->tribe)) === $sortMethod);
        });
        $data = array_values($data);
        break;
      default:
        return -1;
    }
    
    $count = count($data);
    
    if($startIndex >= $count) {
      return -2;
    }
    
    $limit = $startIndex + $cardCount;
    for($ind = $startIndex; $ind < $limit; $ind++) {
      //check if index asked for is valid in data
      if($ind >= $count) {
        return -3;
      }
      //print data
      $card = $data[$ind];
      $pntr = $card->pointer;
      $title = $card->title;
      $size = 'wide';
      $height = (int)$card->height;
      $width = (int)$card->width;
      if($height > $width) {
        $size = 'tall';
      }
      $ref = getImageReference($pntr,'thumbnail',1);
      $title_s = $title;
      if(strlen($title_s) > 18) {
        $title_s = substr($title_s,0,18) . '...';
      }
      ?>
      <div class="image-card-container">
        <div class="image-card card-natural background-black shadow-caster" id="image-card-<?php print (string)$ind; ?>">
          <div class="additional">
            <p id="size"><?php print $size; ?></p>
            <p id="pointer"><?php print $pntr; ?></p>
            <p id="index"><?php print $ind; ?></p>
            <p id="toggle">0</p>
          </div>
          <img class="card-image" src="<?php print $ref; ?>" />
          <div class="card-title-container background-red">
            <div class="card-title text-white source-serif"><?php print $title_s; ?></div>
          </div>
          <div class="card-hover" onclick="imagesReadMoreToggle('<?php print $ind; ?>','#image-card-<?php print $ind; ?>')"></div>
        </div>
        <div class="shadow"></div>
      </div>
      <?php
    }
  }
  
  //check if $startIndex, $cardCount, and $sortMethod are set in url arguments.
  //If so, run cardDriver().
  if(isset($_GET['si']) && isset($_GET['cc']) && isset($_GET['srt'])) {
    $si = $_GET['si'];
    $cc = $_GET['cc'];
    $srt = $_GET['srt'];
    if(is_numeric($si) && is_numeric($cc) && (!is_numeric($srt))) {
      //ensure the variables are correct types
      $si = (int)$si;
      $cc = (int)$cc;
      $srt = (string)$srt;
      $err = cardDriver($si,$cc,$srt);
      if($err < 0) {
        echo $err;
      }
    } else {
      error_log('images-card.php: Error: php file was accessed with necessary arguments but they were not correct type.',0);
    }
  } else {
    error_log('images-card.php: Error: php file was accessed without necessary arguments to print cards.',0);
  }
?>