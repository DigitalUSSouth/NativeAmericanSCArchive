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
  
  function curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
  }
  
  function getImageInfo($pointer) {
    $query = CDM_API_WEBSERVICE . 'dmGetImageInfo' . CDM_COLLECTION . '/' . $pointer . '/xml';
    $response = simplexml_load_file($query);
    if ($response === false) {
      $err = '';
      foreach(libxml_get_errors() as $error) {
        $err .= '<br>' . $error->message;
      }
      return 'F' . $err;
    }
    return $response;
  }
  
  function getImageDimensions($pointer) {
    $info = getImageInfo($pointer);
    if($info[0] === 'F') {
      $arr['width'] = 100;
      $arr['height'] = 100;
    } else {
      $arr['width'] = $info->width;
      $arr['height'] = $info->height;
    }
    return $arr;
  }
  
  function getImageTitle($pointer) {
    $info = getImageInfo($pointer);
    if($info[0] === 'F') {
      return $info;
    } else {
      return $info->title;
    }
  }
  
  function getImageReference($pointer, $size) {
    $query = CDM_API_UTILS . 'CISOROOT=' . substr(CDM_COLLECTION, 1) . '&CISOPTR=' . $pointer . '&action=2&DMSCALE=';
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