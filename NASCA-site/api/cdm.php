<?php
  $current_dir = str_replace('cdm.php','',__FILE__);
  require_once($current_dir . 'configuration.php');

  function getApiVersion($format) {
    //grab information from
    $query = CDM_API_WEBSERVICE . 'wsAPIDescribe/' . $format;
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
  
  function getImageInfo($pointer) {
    $query = CDM_API_WEBSERVICE . 'dmGetImageInfo' . CDM_COLLECTION . '/' . $pointer . '/xml';
    $response = simplexml_load_file($query);
    return $response;
  }
  
  function getImageDimensions($pointer) {
    $info = getImageInfo($pointer);
    $arr['width'] = $info->width;
    $arr['height'] = $info->height;
    return $arr;
  }
  
  function getImageTitle($pointer) {
    $info = getImageInfo($pointer);
    return $info->title;
  }
  
  function getImageReference($pointer, $size) {
    $query = CDM_API_UTILS . 'CISOROOT=' . CDM_COLLECTION . '&CISOPTR=' . $pointer . '&action=2&DMSCALE=';
    $arr = getImageDimensions($pointer);
    $width = $arr['width'];
    $height = $arr['height'];
    $scale = 100;
    $proportion = 1;
    $new_width = 100;
    $new_height = 100;
    if($size === 'full') {
      $query .= $scale . '&DMWIDTH=' . $width . '&DMHEIGHT=' . $height;
      return $query;
    } else if($size === 'large') {
      if($width > 1500) {
        $new_width = 1500;
      } else {
        $new_width = $width;
      }
    } else if($size === 'small') {
      if($width > 500) {
        $new_width = 500;
      } else {
        $new_width = $width;
      }
    } else {
      //error size is wrong
    }
    $proportion = $height / $width;
    $new_height = $new_width * $proportion;
    $scale = 100 * $new_width / $width;
    $query .= $scale . '&DMWIDTH=' . $new_width . '&DMHEIGHT=' . $new_height;
    return $query;
  }
  
?>