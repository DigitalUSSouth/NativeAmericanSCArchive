<?php
  $api_dir = preg_replace('/html.images-base\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  include_once ($api_dir . 'configuration.php');
  include_once ($api_dir . 'cdm.php');

  //pull images data, outside functions
  $data_loc = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_IMAGE;
  $data = getJsonLocal($data_loc);
  //$count = $data->count;
  $data = $data->data;
  $card_start = (int)$config->frontend->images->cards_start;
  
  /*
   * Prints a the base of the images page, with a certain number of starting
   * cards from images/data.json, in indexical order, starting from index 0.
   * 
   * Returns nothing, sends error codes to php error log if necessary.
   */
  function baseDriver() {
    //declare variables from globals
    global $card_start;
    global $data;
    
    ?>
    <div class="custom-row source-serif text-dark-grey" id="featured-container">
      <div id="featured">
        Indexical Order
        <div class="half-underline-black"></div>
      </div>
    </div>
    <div id="image-cards-flex">
      <?php
      for($ind = 0; $ind < $card_start; $ind++) {
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
          <div class="card background-black shadow-caster" id="image-card-<?php print (string)$ind; ?>">
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
            <div class="card-read-more background-red">
              <div class="text-white source-serif">Read More</div>
            </div>
            <div class="card-point background-red">
              <img src="img/cardPoint.svg" />
            </div>
            <div class="card-hover" onclick=""></div>
          </div>
          <div class="shadow"></div>
        </div>
        <?php
      }
      ?>
    </div>
    <?php
  }
  
  //check if $print is set in url argument
  //if so, run baseDriver(). Otherwise, including this file would only
  //access whatever may be added at the top of the file
  /*if(isset($_GET['print'])) {
    $print = $_GET['print'];
    //print needs to be a 1
    if(isnumeric($print)) {
      //ensure the variable is int and not string
      $print = (int)$print;
      if($print === 1) {
        baseDriver();
      }
    } else {
      error_log('images-base.php: Error: php file was accessed with necessary arguments but they were not correct value.',0);
    }
  } else {
    error_log('images-base.php: Notice: php file was accessed without necessary arguments to print base.',0);
  }*/
  baseDriver();
?>