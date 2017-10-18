<div class="custom-row source-serif text-dark-grey" id="featured-container">
  <div id="featured">
    Featured
    <div class="half-underline-black"></div>
  </div>
</div>
<div class="book" id="home-book">
  <div id="home-left">
<?php
$api_dir = preg_replace('/html.home\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
include_once ($api_dir . 'configuration.php');
include_once ($api_dir . 'cdm.php');
$count = $config->frontend->home->card_count;
if($count === null || (int)$count <= 0 || (string)$count === '') {
  $count = 8;
} else {
  $count = intval($count);
}
$pointers = json_decode(file_get_contents(SITE_ROOT . DB_ROOT . DB_HOME));
$numbers = range(0,intval($pointers->count)-1);
shuffle($numbers);
//$numbers = array_slice($numbers, 0, $count);
$offset = 0;
for($i = 1; $i <= $count; $i++) {
  $card = $pointers->data[$numbers[$i-1]];
  $id = $card->pointer;
  $title = $card->title;
  $type = ucfirst($card->type);
  $size = 'wide';
  $ref = null;
  if($type === 'Image' || $type === 'Letter') {
    $height = (int)$card->height;
    $width = (int)$card->width;
    if($height > $width) {
      $size = 'tall';
    }
    $ref = getImageReference($id,'thumbnail',1);
    if(gettype($ref) === 'integer' && $ref < 0) {
      $count += 1;
      $offset += 1;
      error_log('home.php: Couldn\'t get image reference for pointer ' . $id . ' while generating home page cards. Trying another card.',0);
      continue;
    }
  } else if($type === 'Video') {
    //give the video a reference image
    $url = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_VIDEO;
    $vid_data = getJsonLocal($url);
    $index = getId($vid_data, $id);
    $obj = $vid_data->data[$index];
    $url_data = $vid_data->urls;
    $ref = (string)$url_data->thumbnail_prefix . (string)$obj->key . (string)$url_data->thumbnail_suffix;
    if(checkRemoteFile($ref) === FALSE) {
      //if the reference is invalid
      $count += 1;
      $offset += 1;
      error_log('home.php: Thumbnail reference for video pointer ' . $id . ' was not a valid link. Reference was ' . $ref . '. Trying another card.',0);
      continue;
    }
  } else {
    $count += 1;
    $offset += 1;
    error_log('home.php: Type is not recognized for pointer ' . $id . ' while generating home page cards. Trying another card.',0);
    continue;
  }
  if($ref === null) {
    $count += 1;
    $offset += 1;
    error_log('home.php: Something went wrong while printing card for pointer ' . $id . '. Trying another card.',0);
    continue;
  }
  $title_s = $title;
  if(strlen($title_s) > 18) {
    $title_s = substr($title_s,0,18) . '...';
  }
?>
<div class="home-card-container">
  <div class="home-card card-natural background-black shadow-caster" id="home-card-<?php print (string)($i-$offset); ?>">
    <div class="additional">
      <p id="title"><?php print $title_s; ?></p>
      <p id="type"><?php print $type; ?></p>
      <p id="ref"><?php print $ref; ?></p>
      <p id="size"><?php print $size; ?></p>
      <p id="index"><?php print $id; ?></p>
      <p id="toggle">0</p>
    </div>
    <img class="card-image" src="<?php print $ref; ?>" />
    <div class="card-title-container background-red">
      <div class="card-title text-white source-serif"><?php print $type; ?></div>
    </div>
    <div class="card-read-more background-red">
      <div class="text-white source-serif">Read More</div>
    </div>
    <div class="card-point background-red">
      <img src="<?php print SITE_ROOT; ?>/img/cardPoint.svg" />
    </div>
    <div class="card-hover" onclick="homeReadMoreToggle('<?php print $numbers[$i-1]; ?>','#home-card-<?php print (string)($i-$offset); ?>')"></div>
  </div>
  <div class="shadow"></div>
</div>
<?php
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