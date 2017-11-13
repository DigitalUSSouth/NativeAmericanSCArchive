<div class="custom-row top-padding-20"></div>
<div id="tribes-list-container">
  <?php
  $api_dir = preg_replace('/html.tribes\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  include_once ($api_dir . 'configuration.php');
  $details = json_decode(file_get_contents(SITE_ROOT . '/db/data/tribes/data.json'));
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
    echo '    <a class="card-hover" href="' . SITE_ROOT . '/html/tribes_history.php?tribe_id=' . $i . '" data-type="ajax" data-fancybox="Tribes">';// data-width="560" data-height="315">';
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