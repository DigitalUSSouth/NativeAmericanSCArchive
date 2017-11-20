<?php
require_once "../api/configuration.php";

$jsonTabData = file_get_contents(SITE_ROOT."/db/data/interviews/tabs.json");
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
<ul class="nav nav-tabs nav-justified">
<?php
  $counter=1;
  foreach ($tabData as $data):?>
  <li<?php print ($counter++==1)?" class=\"active\"":"";?>><a data-toggle="tab" href="#<?php print $data['href'];?>" class="text-red"><strong><big><?php print $data['tribe'];?></strong></big></a></li>
<?php endforeach;?>
</ul>

<div class="tab-content">
<?php 
$counter=1;
foreach($tabData as $data):?>
  <div id="<?php print $data['href'];?>" class="tab-pane fade<?php print ($counter++==1)?" in active":""?>">
    <div class="row interview-row">
      <div class="col-xs-12">
        <img class="img-responsive interview-logo center-block" src="<?php print SITE_ROOT;?><?php print $data['logo'];?>">
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
      <?php foreach($data['interviews'] as $dataFile=>$name):
        $interview = json_decode(file_get_contents(SITE_ROOT.'/db/data/interviews/transcripts/json/minified/'.$dataFile),true);
        $interviewTitle = $interview['title'];
        ?>
        <div class="col-xs-6"><button class="btn btn-lg btn-default btn-interview col-xs-12" data-target="#interviewsModal" data-toggle="modal" data-filename="<?php print $dataFile;?>"><?php print $interviewTitle;?></button></div> 
      <?php endforeach;?>
      </div>
    </div>
  </div>
<?php endforeach;?>
  