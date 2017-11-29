<?php
require_once "../api/configuration.php";

$jsonTabData = file_get_contents(SITE_ROOT.DB_ROOT."/interviews/tabs.json");
$tabData = json_decode($jsonTabData,true);
//sort tabData alphabetically by tribe name
usort($tabData,function($a,$b) {
  return strcmp(strtolower(trim($a['tribe'])),strtolower(trim($b['tribe'])));
});

?>
<div class="custom-row text-dark-grey" id="featured-container">
  <div id="featured" class="source-serif">
    Interviews
    <div class="half-underline-black"></div>
  </div>
  <div id="custom-about-section-row" class="anton">
    <div id="custom-about-section-inner">
      <div id="custom-about-section-content" class="text-black">
        Interviews About Placeholder <a href="#" target="_blank" title="Go To Interviews Link" class="text-red">link here</a>
      </div>
      <div id="custom-about-section-click" class="clickable text-red">About this page</div>
    </div>
  </div>
</div>


<div id="interviews-nav" class="custom-row">
<?php
  $counter=1;
  foreach ($tabData as $data):?>
  <div<?php print ($counter++==1)?' class="interviews-nav-button text-red tab-active"':' class="interviews-nav-button text-dark-grey"';?>>
    <a data-toggle="tab" href="#<?php print $data['href'];?>" class="source-serif clickable">
      <?php print $data['tribe'];?>
    </a>
    <div class="half-underline-red"></div>
  </div>
<?php endforeach;?>
</div>
<div class="tab-content">
  <?php
    $counter=1;
    foreach($tabData as $tab):?>
      <div id="<?php print $tab['href']; ?>" class="tab-pane fade<?php print ($counter++==1)?' in active':'' ?>">
        <?php print $tab['href']; ?>
      </div>
    <?php endforeach; ?>
</div>