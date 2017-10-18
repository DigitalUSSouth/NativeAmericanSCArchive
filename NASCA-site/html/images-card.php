<?php
  $api_dir = preg_replace('/html.images-card\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  $html_dir = preg_replace('/images-card\.php/','',__FILE__);// 'html\home.php','',__FILE__);
  include_once ($api_dir . 'configuration.php');
  include_once ($api_dir . 'cdm.php');
  //set noprint var for images.php
  $noprint = TRUE;
  include_once ($html_dir . 'images.php');
  
  $cards_per_block = (int)$config->frontend->images->cards_per_block;
  
  /*
   * Prints a certain number of cards from images/data.json,
   * in indexical order, starting with a certain index. If the
   * end of the list is met by this function, it will loop back
   * around to the beginning. This will not be an expected occurance
   * on the actual site. The implementation of this function should handle
   * the number of cards accurately so it does not loop around. Looping around
   * is simply a fallback.
   * 
   * Inputs:
   * (int) $startLocalIndex - starting index to search from images/data.json
   *                        keep in mind that this is NOT the cdm pointer
   * (int) $cardCount - number of cards to print in order after the given index
   * 
   * Returns negative number if error, sends error codes to php error log.
   */
  function cardDriver($startLocalIndex) {//, $cardCount) {
    //declare globals
    global $cards_per_block;
    global $count;
    global $data;
    
    $limit = $startLocalIndex + $cards_per_block;
    //$ind = $startLocalIndex;
    for($ind = $startLocalIndex; $ind < $limit; $ind++) {
      if($ind === $count) {
        $limit -= $ind;
        $ind = 0;
      } else if($ind > $count) {
        error_log('images-card.php: cardDriver(): ERROR: $ind is larger than $count for some reason. Variable output following - $limit = ' . $limit . ' - $startLocalIndex = ' . $startLocalIndex . ' - $ind = ' . $ind . ' - $count = ' . $count,0);
        return -1;
      }
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
  
  //check if $startLocalIndex and $cardCount are set in url arguments
  //if so, run cardDriver(). Otherwise, including this file would only
  //access whatever may be added at the top of the file
  if(isset($_GET['sli'])) {// && isset($_GET['cc'])) {
    $sli = $_GET['sli'];
    //$cc = $_GET['cc'];
    if(is_numeric($sli)) {// && isnumeric($cc)) {
      //ensure the variables are ints and not strings
      $sli = (int)$sli;
      //$cc = (int)$cc;
      cardDriver($sli);//,$cc);
    } else {
      error_log('images-card.php: Error: php file was accessed with necessary arguments but they were not numeric.',0);
    }
  } else {
    error_log('images-card.php: Notice: php file was accessed without necessary arguments to print cards.',0);
  }
?>