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
  $desc = $info->descri;
  //$inst = $info->relati;
  $publ = $info->publis; //contrib inst
  $creat = str_ireplace('(photographer)','',$info->creato);
  $creat_label = '';
  if(strtolower($creat) === strtolower($info->creato)) { //then nothing was replaced
    $creat_label = 'Creator';
  } else { //then photographer exists in title
    $creat_label = 'Photographer';
  }
  $creat = trim($creat);
  $date = $info->date;
  $date_orig = $info->dateb;
  $locat = $info->geogra;
  $tribe = $info->tribe;
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
      <?php
        if($tribe !== '') {
          //tribe is available
          ?>
          <p id="images-modal-details-tribe">From the <?php echo $tribe; ?> tribe.</p>
          <?php
        }
        if($locat !== '') {
          //location is available
          ?>
          <p id="images-modal-details-locat">Photographed in <?php echo $locat; ?>.</p>
          <?php
        }
      ?>
      <p id="images-modal-details-descr">
      <?php
        if(trim($desc) === '') {
          //desc not available
          ?>
          <i>A description for this image is not available</i>
          <?php
        } else {
          //desc available
          echo $desc;
        }
      ?>
      </p>
      <?php
        if(trim($publ) !== '') {
          //contributing institution is available
          ?>
          <p id="images-modal-details-contr"><b>Contributing Institution:</b> <?php echo $publ; ?></p>
          <?php
        }
        if($creat !== '') {
          //creator/photographer is available
          ?>
          <p id="images-modal-details-creat"><?php echo '<b>'.$creat_label.':</b> '.$creat; ?></p>
          <?php
        }
        if($date !== '') {
          //date is available
          ?>
          <p id="images-modal-details-date"><?php echo '<b>Date:</b> '.$date; ?></p>
          <?php
        }
        if($date_orig !== '') {
          //date of original artifact is available
          ?>
          <p id="images-modal-details-dateb"><?php echo '<b>Date of Original Artifact:</b> '.$date_orig; ?></p>
          <?php
        }
      ?>
          
    </div>
  </div>
</div>