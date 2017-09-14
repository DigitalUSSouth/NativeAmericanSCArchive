<?php
require_once "../api/configuration.php";

$jsonTabData = file_get_contents(SITE_ROOT."/db/data/letters/tabs.json");
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
  <div id="<?php print $data['href'];?>" class="tab-pane fade<?php print ($counter==1)?" in active":""?>">
    <div class="row letters-row">
      <div class="col-xs-12">

      <div id="lettersCarousel<?php print $counter;?>" class="carousel slide" data-ride="carousel">
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
              <div class="btn text-center col-xs-6 col-md-3" data-toggle="collapse" data-target="#letterDetail">
                <img class="img-responsive" src="<?php print $letter['thumb'];?>" alt="">
                <p><?php print $letter['description'];?></p>
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
  