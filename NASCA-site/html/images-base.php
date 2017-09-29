<?php
  $api_dir = preg_replace('/html.images_base\.php/','api/',__FILE__);// 'html\home.php','',__FILE__);
  include_once ($api_dir . 'configuration.php');
  include_once ($api_dir . 'cdm.php');

  //pull images data, outside functions
  $data_loc = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_IMAGE;
  $data = getJsonLocal($data_loc);
  $count = $data->count;
  $data = $data->data;
  
  
  function baseDriver() {
    //declare variables from globals
    //ex: global $var
  }
  
  //check if $print is set in url argument
  //if so, run baseDriver(). Otherwise, including this file would only
  //access whatever may be added at the top of the file
  if(isset($_GET['print'])) {
    $print = $_GET['print'];
    //print needs to be a 1
    if(isnumeric($print)) {
      //ensure the variable is int and not string
      $print = (int)$print;
      if($print === 1) {
        baseDriver();
      }
    } else {
      error_log('images-base.php: Error: php file was accessed with necessary arguments but they were not correct value.',0);
    }
  } else {
    error_log('images-base.php: Notice: php file was accessed without necessary arguments to print base.',0);
  }
?>