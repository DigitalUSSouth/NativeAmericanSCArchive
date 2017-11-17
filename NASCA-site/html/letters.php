<?php
require_once "../api/configuration.php";

$jsonTabData = file_get_contents(SITE_ROOT."/db/data/letters/tabs.json");
$rawTabData = json_decode($jsonTabData,true);
$tabData = array();
$tabHrefs = array();

//filter out bad data
//This is horribly inneficient but we have to do it
//because we keep getting bad data from ContentDM
foreach ($rawTabData as $rawItem){
  if ($rawItem['href']=="") continue;
  if (empty(array_filter($rawItem['letters']))) continue;
  $validLetter = true;
  foreach ($rawItem['letters'] as $letter){
    foreach($letter['pages'] as $page){
      $fullPath = $page['image'];
      $img = explode('/',$fullPath);
      $img = end($img);
      $imgPath = "../db/data/letters/".$img;
      if (!(file_exists($imgPath))){
        $validLetter = false;
        break;
      }
    }
    if (!$validLetter) break;
  }
  if (!$validLetter) continue;
  $tabData[] = $rawItem;
  $tabHrefs[] = $rawItem['href'];
}
usort($tabData, function($a,$b){
  if ($a['href']==$b['href']) return 0;
  return ((int)$a['href']<(int)$b['href']) ? -1 : 1;
});
sort($tabHrefs);
?>
<div class="custom-row text-dark-grey" id="featured-container">
  <div id="featured" class="source-serif">
    Letters to the Governor
    <div class="half-underline-black"></div>
  </div>
  <div id="letters-custom-about-section-row" class="anton">
    <div id="letters-custom-about-section-inner">
      <div id="letters-custom-about-section-content" class="text-black">
        The Letters section covers correspondence written by Catawba Indians, South Carolina governors, <a href="<?php print SITE_ROOT.DB_ROOT.'/letters/catawba-indian-agent.pdf'?>" target="_blank" title="About the Catawba Indian Agents" class="text-red">Catawba Indian agents</a> and other interested parties relative to financial affairs on the Catawba Indian Nation. Letters are taken from the South Carolina State Archives Governor's Correspondence files.
      </div>
      <div id="letters-custom-about-section-click" class="clickable text-red">About this page</div>
    </div>
  </div>
</div>
<script>var tabHrefs = <?php print json_encode($tabHrefs); ?>;</script>
<ul class="nav nav-tabs nav-justified letter-tab">
<?php
  $counter=1;
  foreach ($tabData as $data):?>
  <li<?php print ($counter++==1)?" class=\"active\"":"";?>><a data-toggle="tab" href="#<?php print $data['href'];?>" class="text-red"><strong><big><?php print $data['href'];?></strong></big></a></li>
<?php endforeach;?>
</ul>

<div class="tab-content">
<?php 
$counter=1;
foreach($tabData as $name=>$data):?>
  <div id="<?php print $data['href'];?>" class="tab-pane fade<?php print ($counter==1)?" in active":""?>">
    <div class="row letters-row">
      <div class="col-xs-12">

      <div id="lettersCarousel<?php print $counter;?>" class="carousel slide" data-ride="carousel" data-interval="false" data-tribe="<?php print $data['href'];?>">
        <!-- Indicators -->
        <ol class="carousel-indicators">
          <?php 
            $counter2 = 0;
            $len = sizeof($data['letters']);
            while($len>0):?>
          <li data-target="#lettersCarousel<?php print $counter;?>" data-slide-to="<?php print $counter2;?>" class="<?php print ($counter2++==0)?"active":""?>"></li>
            <?php
            $len = $len-4;
            endwhile;?>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
          <?php
          $counter2 = 0;
          foreach($data['letters'] as $letter):
            if ($counter2%4 == 0):?>
            <div class="col-xs-10 col-xs-offset-1 item<?php print ($counter2==0)?" active":""?>">
            <?php endif;?>
              <div class="letter-toggle btn text-center col-xs-6 col-md-3" data-toggle="collhapse" data-target="#lettehrDetail" data-letter="<?php print $letter['id']?>">
                <img class="img-responsive" src="<?php print $letter['thumb'];?>" alt="">
                <p class="letter-desc"><strong><?php print $letter['description'];?></strong></p>
              </div>
            <?php if($counter2%4 == 3):?>
            </div>
            <?php endif;
            $counter2++;
          endforeach;
          if (($counter2%4)>=1 && ($counter2%4)<=3){print '</div>';}?>
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#lettersCarousel<?php print $counter;?>" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#lettersCarousel<?php print $counter;?>" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>

      </div>
    </div>
  </div>
<?php 
$counter++;
endforeach;?>

<div id="letterDetail" class="collapse">
  <div class="text-center"><h1>Loading...</h1><i class="fa fa-spinner fa-spin" style="font-size:76px"></i></h1></div>
</div>
  