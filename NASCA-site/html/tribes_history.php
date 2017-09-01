<?php
  if(! isset($_GET['tribe_id'])) {
    error_log('Tribal history fancybox did not receive tribe id from tribes.php',0);
    die('Something went wrong.');
  }
  $id = $_GET['tribe_id'];
  $api_dir = preg_replace('/html.tribes_history\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  include_once ($api_dir . 'configuration.php');
  $details = json_decode(file_get_contents(SITE_ROOT . '/db/data/tribes/data.json'));
  $image_dir = $details->directories->image_directory;
  $desc_dir = $details->directories->description_directory;
  $tribe = $details->data[$id];
?>
<div class="tribes-history-container background-off-white">
  <div class="tribes-history-nav-container tribes-history-prev-container custom-column">
    <div class="tribes-history-nav clickable custom-row text-center background-red text-white">
      Previous Page
    </div>
  </div>
  <div class="tribes-history-body custom-column">
    <div class="tribes-history-logo-container custom-row">
      <img class="custom-column" src="<?php echo SITE_ROOT . $image_dir . '/' . $tribe->logo; ?>" alt="Tribal Logo" onerror="this.onerror=null;this.src=SITE_ROOT+'/img/error/error.png';"/>
    </div>
    <div class="tribes-history-text custom-row source-serif">
      <?php
      for($i = 0; $i<200; $i++){
        echo 'testing text ';
      }
      ?>
    </div>
  </div>
    <!--87.8% wide-->
  <div class="tribes-history-nav-container tribes-history-next-container custom-column">
    <div class="tribes-history-nav clickable custom-row text-center background-red text-white">
      Next Page
    </div>
  </div>
</div>