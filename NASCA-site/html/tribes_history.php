<?php
  if(! isset($_GET['tribe_id'])) {
    error_log('Tribal history fancybox did not receive tribe id from tribes.php',0);
    die('Something went wrong.');
  }
  $id = $_GET['tribe_id'];
  $tab_id = $_GET['tab_id'];
  $api_dir = preg_replace('/html.tribes_history\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  include_once ($api_dir . 'configuration.php');
  
  // grab current tab's json database
  if ($tab_id == 'federally-recognized') {
    $details = json_decode(file_get_contents(SITE_ROOT . '/db/data/tribes/data-federally-recognized.json'));
    $tab_int = 1;
  } elseif ($tab_id == 'state-recognized-tribes') {
    $details = json_decode(file_get_contents(SITE_ROOT . '/db/data/tribes/data-state-recognized-tribes.json'));
    $tab_int = 2;
  } elseif ($tab_id == 'state-recognized-groups') {
    $details = json_decode(file_get_contents(SITE_ROOT . '/db/data/tribes/data-state-recognized-groups.json'));
    $tab_int = 3;
  } elseif ($tab_id == 'unrecognized') {
    $details = json_decode(file_get_contents(SITE_ROOT . '/db/data/tribes/data-unrecognized.json'));
    $tab_int = 4;
  }
  
  $image_dir = $details->directories->image_directory;
  $desc_dir = $details->directories->description_directory;
  $tribe = $details->data[$id];
?>
<div class="tribes-history-container background-off-white">
  <div class="tribes-history-nav-container tribes-history-prev-container custom-column">
    <div class="tribes-history-nav clickable custom-row text-center background-grey text-white" onclick="tribes_history_page_change('prev',$(this));">
      <div>Previous Page</div>
    </div>
  </div>
  <div class="tribes-history-body custom-column">
    <div class="tribes-history-logo-container custom-row">
      <img class="custom-column" src="<?php echo SITE_ROOT . $image_dir . '/' . $tribe->logo; ?>" alt="Tribal Logo" onerror="this.onerror=null;this.src=SITE_ROOT+'/img/error/error.png';"/>
    </div>
    <div class="tribes-history-text-container custom-row">
      <div class="additional">
        <div id="current-tribe"><?php echo $id; ?></div>
        <div id="current-tab"><?php echo $tab_int; ?></div>
        <div id="current-page">1</div>
      </div>
      <div class="tribes-history-text custom-row source-serif">
        
      </div>
    </div>
  </div>
  <div class="tribes-history-nav-container tribes-history-next-container custom-column">
    <div class="tribes-history-nav clickable custom-row text-center background-grey text-white" onclick="tribes_history_page_change('next',$(this));">
      <div>Next Page</div>
    </div>
  </div>
</div>
