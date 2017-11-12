<?php
  $api_dir = preg_replace('/html.images-details\.php/','api/',__FILE__);
  include_once ($api_dir . 'configuration.php');
  include_once ($api_dir . 'cdm.php');
  
  if(!isset($_GET['ptr'])) {
    die('ptr is not set');
  } else {
    if(!is_numeric($_GET['ptr'])) {
      die('ptr is not numeric');
    }
  }
  
  $ptr = $_GET['ptr'];
  $url = $_SERVER['DOCUMENT_ROOT'].REL_HOME.DB_ROOT.DB_IMAGE;
  $jsonImageData = getJsonLocal($url);
  $localId = getId($jsonImageData,$ptr);
  $info = $jsonImageData->data[$localId];
  //var_dump($info);
  $img_base = SITE_ROOT.DB_ROOT.'/images/'.$ptr;
  $title_s = $info->title;
  if(strlen($title_s) > 31) {
    $title_s = substr($title_s,0,31);
  }
    
?>
<div id="images-modal-padding">
  <div id="images-modal-left">
    <a id="images-modal-left-fancybox" href="<?php print $img_base.'_full.jpg'; ?>" data-fancybox="Images Modal" data-type="image" data-caption="<?php print $title_s; ?>" data-width="<?php print $info->width; ?>" data-height="<?php print $info->height; ?>">
      <div id="images-modal-left-clicknote-container" class="background-red text-white source-serif">
        <div id="images-modal-left-clicknote">Click To Expand</div>
      </div>
      <img id="images-modal-left-img" src="<?php print $img_base.'_small.jpg'; ?>">
    </a>
  </div>
  <div id="images-modal-right" class="text-black">
    <div id="images-modal-title-container" class="custom-title-overflow overflow-white">
      <div id="images-modal-title" class="anton"><?php print $info->title; ?></div>
    </div>
    <div id="images-modal-details" class="source-serif">
      test details
    </div>
  </div>
</div>