<div class="custom-row top-padding-20"></div>
<div id="video-list-container">
  <?php
  $api_dir = preg_replace('/html.video\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  include_once ($api_dir . 'configuration.php');
  $details = json_decode(file_get_contents(SITE_ROOT . '/db/data/video/data.json'));
  $video_prefix = $details->urls->video_prefix;
  $thumb_prefix = $details->urls->thumbnail_prefix;
  $thumb_suffix = $details->urls->thumbnail_suffix;
  for($i = 0; $i < $details->count; $i++) {
    $el = $details->data[$i];
    //<!--  START single video template  -->
    echo '<div class="video-single-container">';
    echo '  <div class="video-single">';
    echo '    <div class="video-single-title-container custom-title-overflow custom-row overflow-off-white">';
    echo '      <div class="video-single-title anton text-black">';
    echo $el->title;
    echo '      </div>';
    echo '    </div>';
    echo '    <div class="video-single-padding custom-row">';
    echo '      <hr class="red custom-row"/>';
    echo '    </div>';
    //<!-- MEDIA HERE -->
    echo '    <div class="video-single-media-container border-red background-red box-shadow">';
    echo '      <a class="video-single-media-fancybox" href="' . $video_prefix . $el->key . '" data-fancybox="Videos" data-type="iframe" data-width="560" data-height="315">';
    echo '        <img class="video-single-media" src="' . $thumb_prefix . $el->key . $thumb_suffix . '" />';
    echo '      </a>';
    echo '    </div>';
    //<!-- DESCRIPTION HERE -->
    echo '    <div class="video-single-padding"></div>';
    echo '    <div class="video-single-description-container">';
    echo '      <div class="video-single-description source-serif text-black">';
    echo $el->description;
    echo '      </div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
    //<!--  END single video template  -->
  }
  ?>
</div>
<!--<iframe width="560" height="315" src="https://www.youtube.com/embed/0oR7e-WYJnQ" frameborder="0" allowfullscreen></iframe>-->