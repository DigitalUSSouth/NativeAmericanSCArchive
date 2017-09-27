<?php
require_once "../api/configuration.php";

$jsonTabData = file_get_contents(SITE_ROOT."/db/data/interviews/tabs.json");
$tabData = json_decode($jsonTabData,true);
?>
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
  