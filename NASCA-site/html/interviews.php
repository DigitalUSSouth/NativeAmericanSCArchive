<?php
require_once "../api/configuration.php";

$jsonTabData = file_get_contents(SITE_ROOT.DB_ROOT."/interviews/tabs.json");
$tabData = json_decode($jsonTabData,true);

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


<ul id="interviews-nav">
<?php
  $counter=1;
  foreach ($tabData as $data):?>
  <li<?php print ($counter++==1)?" class=\"custom-active\"":"";?>><a data-toggle="tab" href="#<?php print $data['href'];?>" class="text-dark-grey source-serif"><strong><big><?php print $data['tribe'];?></strong></big></a></li>
<?php endforeach;?>
</ul>
<div class="tab-content">
  <?php
    $counter=1;
    foreach($tabData as $tab):?>
      <div id="<?php print $tab['href']; ?>" class="tab-pane fade<?php print ($counter++==1)?' in active':'' ?>">
        <?php print $tab['href']; ?>
      </div>
    <?php endforeach; ?>
</div>