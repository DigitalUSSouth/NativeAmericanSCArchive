<div class="book" id="home_book">
  <div class="col" id="home_left">
<?php
$api_dir = preg_replace('/html.home\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
include_once ($api_dir . 'configuration.php');
include_once ($api_dir . 'cdm.php');
$count = intval($config->frontend->home->card_count);
if($count <= 0) {
  $count = 6;
}
$pointers = json_decode(file_get_contents(SITE_ROOT . '/db/data/home/data.json'));
$numbers = range(0,intval($pointers->count)-1);
shuffle($numbers);
//$numbers = array_slice($numbers, 0, $count);
for($i = 1; $i <= $count; $i++) {
  $id = $pointers->data[$numbers[$i-1]]->pointer;
  $title = $pointers->data[$numbers[$i-1]]->title;
  $type = $pointers->data[$numbers[$i-1]]->type;
  echo '<div class="home_card" id="home_card_' . $i . '">';// . indexValue
  echo '  <div class="additional">';
  echo '    <p id="errors">';
  $trimmed = $title;
  if(strlen($trimmed) > 20) {
    $trimmed = substr($trimmed,0,20) . '...';
  }
  $large_ref = getImageReference($id, 'large');
  $small_ref = getImageReference($id, 'small');
  if($large_ref < 0) {
    echo 'large_ref = ' . $large_ref . '<br>';
    $large_ref = SITE_ROOT . '/img/imgfallback.png';
  }
  if($small_ref < 0) {
    echo 'small_ref = ' . $small_ref;
    $small_ref = SITE_ROOT . '/img/imgfallback.png';
  }
  echo '    </p>';
  echo '    <p id="index">' . $id . '</p>';
  echo '    <p id="toggle">0</p>';
  echo '  </div>';
  echo '  <a href="' . $large_ref . '" data-lightbox="featured" data-title="' . $trimmed . '" onclick="">';
  echo '    <img src="' . $small_ref . '">';
  echo '  </a>';
  echo '  <h2>' . $trimmed . '</h2>';
  echo '  <div class="readmore">';
  echo '    <a href="#" onclick="readMoreToggle(' . $numbers[$i-1] . ',' . $id . ',\'' . $type . '\',\'#home_card_' . $i . '\')">READ MORE</a>';
  echo '  </div>'; //readMoreToggle(homePtr, cdmPtr, type, card
  echo '  <div id="point">';
  echo '    <object data="img/cardPoint/new/cardPoint.svg" type="image/svg+xml">';
  echo '      <img src="img/cardPoint/new/cardPoint.png" />';
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