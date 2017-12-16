<?php
require_once "../api/configuration.php";

$jsonTabData = file_get_contents(SITE_ROOT.DB_ROOT."/interviews/tabs.json");
$tabData = json_decode($jsonTabData,true);
//sort tabData alphabetically by tribe name
usort($tabData,function($a,$b) {
  return strcmp(strtolower(trim($a['tribe'])),strtolower(trim($b['tribe'])));
});

?>
<!--div class="custom-row text-dark-grey" id="featured-container">
  <div id="featured" class="source-serif">
    Interviews
    <div class="half-underline-black"></div>
  </div>
  <div id="custom-about-section-row" class="anton">
    <div id="custom-about-section-inner">
      <div id="custom-about-section-content" class="text-dark-grey">
        Interviews About Placeholder <a href="#" target="_blank" title="Go To Interviews Link" class="text-red">link here</a>
      </div>
      <div id="custom-about-section-click" class="clickable text-red">About this page</div>
    </div>
  </div>
</div-->


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
      <div id="<?php print $tab['href']; ?>" class="book tab-pane fade<?php print ($counter++==1)?' in active':'' ?>">
        <div class="interviews-book book">
          <div class="interviews-left">
            <div class="interviews-left-logo">
              <img src="<?php print SITE_ROOT.$tab['logo']; ?>" alt="Tribal Logo">
            </div>
          </div>
          <div class="interviews-right">
            <?php
            $interviews = $tab['interviews'];
            asort($interviews);
            $count = count($interviews);
            $row1 = floor($count/2)+($count%2);
            ?>
            <div class="interviews-right-column">
              <?php
              foreach(array_slice($interviews,0,$row1) as $dataFile=>$name):
                //$interview = json_decode(file_get_contents(SITE_ROOT.DB_ROOT.'/interviews/transcripts/json/minified/'.$dataFile),true);
                //$interviewTitle = $interview['title'];
                ?>
                <div class="interviews-button-container">
                  <div class="interviews-button background-red box-shadow">
                    <div class="card-hover clickable" data-transcript="<?php print $dataFile; ?>" onclick="launch_interview_modal(this);"></div>
                    <div class="interviews-button-text source-serif text-white text-center custom-row"><?php print $name; ?></div>
                  </div>
                </div> 
              <?php endforeach;?>
            </div>
            <div class="interviews-right-column">
              <?php
              foreach(array_slice($interviews,$row1,$count) as $dataFile=>$name):
                //$interview = json_decode(file_get_contents(SITE_ROOT.DB_ROOT.'/interviews/transcripts/json/minified/'.$dataFile),true);
                //$interviewTitle = $interview['title'];
                ?>
                <div class="interviews-button-container">
                  <div class="interviews-button background-red box-shadow">
                    <div class="card-hover clickable" data-transcript="<?php print $dataFile; ?>" onclick="launch_interview_modal(this);"></div>
                    <div class="interviews-button-text source-serif text-white text-center custom-row"><?php print $name; ?></div>
                  </div>
                </div> 
              <?php endforeach;?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
</div>