<?php
  $api_dir = preg_replace('/html.communities\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  include_once ($api_dir . 'configuration.php');
  $jsonTabData = file_get_contents("../db/data/communities/tab-data.json");
  $tabData = json_decode($jsonTabData,true);
?>

<div id="tribes-nav" class="custom-row">
<?php
  $counter=1;
  foreach ($tabData as $data):?>
  <div<?php print ($counter++==1)?' class="tribes-nav-button text-red tab-active"':' class="tribes-nav-button text-dark-grey"';?>>
    <a data-toggle="tab" href="#<?php print $data['href'];?>" class="source-serif clickable">
      <?php print $data['tab'];?>
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

        <div id="tribes-list-container">
          <?php

          if ($tab['href'] == 'federally-recognized') {
            $details = json_decode(file_get_contents(SITE_ROOT . '/db/data/communities/data-federally-recognized.json'));
          } elseif ($tab['href'] == 'state-recognized-groups') {
            $details = json_decode(file_get_contents(SITE_ROOT . '/db/data/communities/data-state-recognized-groups.json'));
          } elseif ($tab['href'] == 'state-recognized-tribes') {
            $details = json_decode(file_get_contents(SITE_ROOT . '/db/data/communities/data-state-recognized-tribes.json'));
          } elseif ($tab['href'] == 'unrecognized') {
            $details = json_decode(file_get_contents(SITE_ROOT . '/db/data/communities/data-unrecognized.json'));
          } else {
            echo "<p>Error: Could not locate tribe data!</p>";
          }

          $image_dir = $details->directories->image_directory;
          $desc_dir = $details->directories->description_directory;

          for($i = 0; $i < $details->count; $i++) {
            $el = $details->data[$i];
            //<!--  START single tribe card template  -->
            echo '<div class="tribe-single-container">';
            echo '  <div class="tribe-single shadow-caster">';
            //echo '    <div class="additional">';
            //echo '      <div id="href">html/tribes_history.php?tribe_id=' . $i . '</div>';
            //echo '    </div>';
            //<!--  TITLE HERE  -->
            echo '    <div class="tribe-single-title-container background-red custom-title-overflow overflow-red custom-row">';
            echo '      <div class="tribe-single-title anton text-white text-center">';
            echo $el->title;
            echo '      </div>';
            echo '    </div>';
            //<!-- LOGO HERE -->
            echo '    <div class="tribe-single-logo-container custom-row background-off-white">';
            //echo '      <a class="tribe-single-logo-fancybox custom-row" href="html/tribes_history.php?tribe_id=' . $i . '" data-fancybox="Tribes" data-type="iframe" data-width="560" data-height="315">';
            echo '        <img class="tribe-single-logo custom-row" src="' . SITE_ROOT . $image_dir . '/' . $el->logo . '" alt="Error fetching tribal logo" onerror="this.onerror=null;this.src=\'' . SITE_ROOT . '/img/error/error.png\';" />';
            //echo '      </a>';
            echo '    </div>';
            echo '    <a class="card-hover" href="' . SITE_ROOT . '/html/communities_history.php?tribe_id=' . $i . '&tab_id=' . $tab['href'] . '" .$tab[href] data-type="ajax" data-fancybox="Tribes">';// data-width="560" data-height="315">';
            //echo '      <a class="tribe-single-logo-fancybox custom-row" href="html/tribes_history.php?tribe_id=' . $i . '" data-fancybox="Tribes" data-type="iframe" data-width="560" data-height="315">';
            //echo '      </a>';
            echo '    </a>';
            echo '  </div>';
            echo '  <div class="shadow"></div>';
            echo '</div>';
            //<!--  END single tribe card template  -->
          }
          ?>
        </div>

    </div>
    <?php endforeach; ?>
</div>
