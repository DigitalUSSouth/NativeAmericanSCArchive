<?php
  if(! isset($_GET['tribe_id'])) {
    error_log('Tribal history fancybox did not receive tribe id from tribes.php',0);
    die('Something went wrong.');
  }
  if(! isset($_GET['page_num'])) {
    error_log('Did not receive page number.',0);
    die('Something went wrong.');
  }
  $id = $_GET['tribe_id'];
  $page_num = $_GET['page_num'];
  $api_dir = preg_replace('/html.tribes_history_page\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  include_once ($api_dir . 'configuration.php');
  $details = json_decode(file_get_contents(SITE_ROOT . '/db/data/tribes/data.json'));
  $desc_dir = $details->directories->description_directory;
  $tribe = $details->data[$id];
  include_once ($api_dir . 'cdm.php');
  include_once ($api_dir . 'simple_html_dom.php');
  $history = curl(SITE_ROOT . $desc_dir . '/' . (string)$tribe->description);
  $history = str_get_html((string)$history);
  echo $history->find('.tribes-history-block',$page_num-1);
?>