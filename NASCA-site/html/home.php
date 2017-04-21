<div class="book" id="home_book">
  <div class="col" id="home_left">
<?php
$api_dir = preg_replace('/html.home\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
include_once ($api_dir . 'configuration.php');
$count = intval($config->frontend->home->card_count);
if($count <= 0) {
  $count = 6;
}
for($i = 1; $i <= $count; $i++) {
  echo '<div class="home_card" id="home_card_' . $i . '">';
  echo '  <img src="img/native_' . $i . '.jpg">';
  echo '  <h1>Sample Title ' . $i . '</h1>';
  echo '  <div class="readmore">';
  echo '    <a href="img/native_' . $i . '.jpg" data-lightbox="featured" data-title="native ' . $i . '" onclick="">READ MORE</a>';
  echo '  </div>';
  echo '</div>';
}
?>
  </div>
  <div class="col" id="home_right">
    <div class="preview">
      
    </div>
  </div>
</div>