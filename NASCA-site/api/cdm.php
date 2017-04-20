<?php
  $current_dir = str_replace('cdm.php','',__FILE__);
  require_once($current_dir . 'configuration.php');

  function getApiVersion($format) {
    //grab information from
    $query = CDM_BASE . 'wsAPIDescribe/' . $format;
    if($format === 'json') {
      $response = json_decode(file_get_contents($query));
      echo $response->version;
    } else if($format === 'xml') {
      $response = simplexml_load_file($query);
      echo $response->version;
    } else {
      $notice = 'bad argument: getCdmApiVersion (use \'xml\' or \'json\')';
      echo $notice . '\n' . $query;
    }
  }
?>