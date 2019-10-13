<div class="custom-row top-padding-20"></div>
<div id="video-list-container">
  <?php
  $api_dir = preg_replace('/html.video\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  include_once ($api_dir . 'configuration.php');
  $details = json_decode(file_get_contents('../db/data/video/data.json'));
  $video_prefix = $details->urls->video_prefix;
  $thumb_prefix = $details->urls->thumbnail_prefix;
  $thumb_suffix = $details->urls->thumbnail_suffix;
  for($i = 0; $i < $details->count; $i++) {
    $el = $details->data[$i];?>
    <!--  START single video template  -->
    <div class="video-single-container">
      <div class="video-single">
        <div class="video-single-title-container custom-title-overflow custom-row overflow-off-white">
          <div class="video-single-title anton text-black">
    <?php echo $el->title; ?>
          </div>
        </div>
        <div class="video-single-padding custom-row">
          <hr class="red custom-row"/>
        </div>
    <!-- MEDIA HERE -->
        <div class="video-single-media-container border-red background-red box-shadow">
          <a class="video-single-media-fancybox" data-fancybox href="#video<?php echo $i;?>">
            <img class="video-single-media" src="<?php echo '../db/data/video/'. $el->key?>" />
          </a>
        </div>
        <video width="80%"  controls id="video<?php echo $i;?>" style="display:none;">
          <source src="<?php echo $el->url;?>" type="video/mp4">
          <!--<source src="" type="video/webm">
          <source src="" type="video/ogg">-->
          Your browser doesn't support HTML5 video tag.
        </video>
    <!-- DESCRIPTION HERE -->
        <div class="video-single-padding"></div>
        <div class="video-single-description-container">
          <div class="video-single-description source-serif text-black">
    <?php echo $el->description;?>
          </div>
        </div>
      </div>
    </div>
    <!--  END single video template  -->
  <?php }
  ?>
</div>