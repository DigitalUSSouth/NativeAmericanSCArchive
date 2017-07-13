<div class="row source-serif text-dark-grey" id="featured">
  Featured
</div>
<div class="book" id="home-book">
  <div id="home-left">
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
  $height = intval($pointers->data[$numbers[$i-1]]->height);
  $width = intval($pointers->data[$numbers[$i-1]]->width);
  $size = 'wide';
  if($height > $width) {
    $size = 'tall';
  }
  echo '<div class="home-card-container">';
  echo '<div class="home-card background-black" id="home-card-' . $i . '">';// . indexValue
  echo '  <div class="additional">';
  echo '    <p id="errors">';
  $trimmed = $title;
  if(strlen($trimmed) > 20) {
    $trimmed = substr($trimmed,0,20) . '...';
  }
  $small_ref = getImageReference($id, 'small');
  if($small_ref < 0) {
    echo 'small_ref = ' . $small_ref;
    $small_ref = SITE_ROOT . '/img/error.png';
  }
  echo '    </p>';
  echo '    <p id="title">' . $trimmed . '</p>';
  echo '    <p id="type">' . $type . '</p>';
  echo '    <p id="ref-small">' . $small_ref . '</p>';
  echo '    <p id="size">' . $size . '</p>';
  echo '    <p id="index">' . $id . '</p>';
  echo '    <p id="toggle">0</p>';
  echo '  </div>';
  echo '  <img class="card-image" src="' . $small_ref . '" />';
  echo '  <div class="card-title-container background-red">';
  $type_formatted = $type;
  if(substr($type,-1,1) === 's') {
    $type_formatted = substr($type,0,strlen($type)-1);
  }
  echo '    <div class="card-title text-white source-serif">' . ucfirst($type_formatted) . '</div>';
  echo '  </div>';
  echo '  <div class="card-read-more background-red">';
  echo '    <div class="text-white source-serif">Read More</div>';
  echo '  </div>'; //readMoreToggle(homePtr, cdmPtr, type, card
  echo '  <div class="card-point background-red">';
  echo '    <img src="img/cardPoint.svg" />';
  echo '  </div>';
  echo '  <div class="card-hover" onclick="readMoreToggle(' . $numbers[$i-1] . ',\'#home-card-' . $i . '\')"></div>';
  echo '</div>';
  echo '<div class="shadow"></div>';
  echo '</div>';
}
?>
  </div>
  <div id="home-middle"></div>
  <div id="home-right">
    <?php
      include ('home-more.php');
    ?>
  </div>
</div>