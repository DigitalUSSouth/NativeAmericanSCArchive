<div class="row">
  <div class="featured">FEATURED</div>
</div>
<div class="book" id="home_book">
  <div class="col" id="home_left">
<?php
$api_dir = preg_replace('/html.home\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
include_once ($api_dir . 'configuration.php');
$count = intval($config->frontend->home->card_count);
if($count <= 0) {
  $count = 6;
}
$imagePointers = json_decode(file_get_contents(SITE_ROOT . '/db/data/images/imagePointers.json'));
$numbers = range(0,intval($imagePointers->total)-1);
shuffle($numbers);
$numbers = array_slice($numbers, 0, $count);
for($i = 1; $i <= $count; $i++) {
  $id = $imagePointers->pointers[$numbers[$i-1]]->pointer;
  echo '<div class="home_card" id="home_card_' . $i . '">';// . indexValue
  echo '  <div class="additional">';
  echo '    <p id="index">' . $id . '</p>';
  echo '    <p id="toggle">0</p>';
  echo '  </div>';
  echo '  <a href="img/native_' . $i . '.jpg" data-lightbox="featured" data-title="native ' . $i . '" onclick="">';
  echo '    <img src="img/native_' . $i . '.jpg">';
  echo '  </a>';
  echo '  <h2>Title From CDM ID ' . $id . '</h2>';
  echo '  <div class="readmore">';
  echo '    <a href="#" onclick="readMoreToggle(\'images\',' . $id . ',\'#home_card_' . $i . '\')">READ MORE</a>';
  echo '  </div>';
  echo '  <div id="point">';
  echo '    <object data="img/card-point.svg" type="image/svg+xml">';
  echo '      <img src="img/card-point.jpg" />';
  echo '    </object>';
  echo '  </div>';
  echo '</div>';
}
?>
  </div>
  <div class="col" id="home_right">
    <div class="preview">
      <div id="details">
        <?php
          include ('home-more.php');
        ?>
      </div>
      <div class="preview_lower">
        <div class="viewmore">
          <a href="#" onclick="">VIEW MORE</a>
        </div>
      </div>
    </div>
  </div>
</div>