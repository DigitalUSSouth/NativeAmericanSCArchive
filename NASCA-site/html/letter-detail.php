<?php
require_once "../api/configuration.php";

$jsonTabData = file_get_contents(SITE_ROOT."/db/data/letters/tabs.json");
$tabData = json_decode($jsonTabData,true);

if (isset($_GET['tribe']) && isset($_GET['id'])){
  if (isset($tabData[$_GET['tribe']])){
    $tribe = $tabData[$_GET['tribe']];
    if (isset($tribe['letters'][$_GET['id']])):
      $letter = $tribe['letters'][$_GET['id']];?>
        <div class="panel row">
          <h3 class="text-red text-center"><?php print $letter['description'];?></h3>
          <?php foreach($letter['pages'] as $page):?>
            <div class="col-xs-2 col-md-5">
              <div class="panel">
                <img class="img-responsive" src="<?php print $page['image']?>">
              </div>
            </div>
            <div class="col-xs-10 col-md-7">
              <div>
                <p class="letter-transcript"><?php print preg_replace('/\\n/','<br>',$page['transcript']);?></p>
              </div>
            </div>
            <div class="clearfix"></div>
          <?php endforeach;?>
        </div>
    <?php
    else:
      die("id not set");
    endif;
  }
  else{
    dieNicely("tribe not set");
  }
}
else {
  dieNicely("parameters not set");
}

function dieNicely($msg){
  print $msg;
  die();
}
