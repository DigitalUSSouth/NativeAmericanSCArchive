<?php
  //$api_dir = preg_replace('/html.images_card\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  $html_dir = preg_replace('images_card\.php/','',__FILE__);// 'html\home.php','',__FILE__);
  //include_once ($api_dir . 'configuration.php');
  //include_once ($api_dir . 'cdm.php');
  include_once ($html_dir . 'images-base.php');
  
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
   * Returns nothing, sends error codes to php error log if necessary.
   */
  function cardDriver($startLocalIndex, $cardCount) {
    //adsf
  }
  
  //check if $startLocalIndex and $cardCount are set in url arguments
  //if so, run cardDriver(). Otherwise, including this file would only
  //access whatever may be added at the top of the file
  if(isset($_GET['sli']) && isset($_GET['cc'])) {
    $sli = $_GET['sli'];
    $cc = $_GET['cc'];
    if(isnumeric($sli) && isnumeric($cc)) {
      //ensure the variables are ints and not strings
      $sli = (int)$sli;
      $cc = (int)$cc;
      cardDriver($sli,$cc);
    } else {
      error_log('images-card.php: Error: php file was accessed with necessary arguments but they were not numeric.',0);
    }
  } else {
    error_log('images-card.php: Notice: php file was accessed without necessary arguments to print cards.',0);
  }
?>