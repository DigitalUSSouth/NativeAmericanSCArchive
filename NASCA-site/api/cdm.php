<?php
  $api_dir = str_replace('cdm.php','',__FILE__);
  require_once($api_dir . 'configuration.php');
  
  ini_set('allow_url_fopen', '1');
  ini_set('allow_url_include', '1');
  
  function curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
  }
  
  function contains($str, $sub) {
    if(strpos($str, $sub) === FALSE) {
      return FALSE;
    } else {
      return TRUE;
    }
  }
  
  /*
   * SHOULD return an xml document with information on the image, from cdm
   * Error codes:
   * 0 - "Record does not exist"
   * -1 - "Error looking up collection /nasca
   *       No permission to access this collection"
   * -2 - Simple XML couldn't parse response
   * -3 - Image info is not formatted properly
   */
  function getImageInfo($pointer) {
    $query = CDM_API_WEBSERVICE . 'dmGetImageInfo' . CDM_COLLECTION . '/' . $pointer . '/xml';
    $response = (string)curl($query);
    $xml = simplexml_load_string($response);
    
    if(contains($response, 'Record does not exist')) {
      error_log('getImageInfo: Error -1: Record does not exist.',0);
      return -1;
    } else if(contains($response,'Error looking up collection')) {
      error_log('getImageInfo: Error -2: No permission to access cdm collection.',0);
      return -2;
    } else if($xml === FALSE) {
      error_log('getImageInfo: Error -3: Simple XML couldn\'t parse response.',0);
      return -3;
    } else if(!isset($xml->width) || !isset($xml->height)) {
      error_log('getImageInfo: Error -4: Image info is not formatted properly.',0);
      return -4;
    }
    
    return $xml;
  }
  
  /*
   * SHOULD get dimensions of any image by pointer from cdm
   * Error codes:
   * -1 - Couldn't get image info
   * -2 - Width and/or height are reading 0
   */
  function getImageDimensions($pointer) {
    $info = getImageInfo($pointer);
    $arr['width'] = 0;
    $arr['height'] = 0;
    if($info < 0) {
      return -1;
    } else {
      $arr['width'] = $info->width;
      $arr['height'] = $info->height;
    }
    if($arr['width'] == 0 || $arr['height'] == 0) {
      return -2;
    }
    return $arr;
  }
  
  /*
   * SHOULD get the image's title from cdm (could also just get it from data.json)
   * Error codes:
   * -1 - Couldn't get image info
   * -2 - Title doesn't exist
   */
  function getImageTitle($pointer) {
    $info = getImageInfo($pointer);
    if($info < 0) {
      return -1;
    } else {
      if($info->title === FALSE) {
        return -2;
      } else {
        return $info->title;
      }
    }
  }
  
  /*
   * Tries to compile an appropriate query for the image with relevant sizing
   * Error codes:
   * -1 - Error getting image dimensions
   * -2 - Either scale, new_width, or new_height have not been set properly or at all
   */
  function getImageReference($pointer, $size) {
    $query = CDM_API_UTILS . 'CISOROOT=' . substr(CDM_COLLECTION, 1) . '&CISOPTR=' . $pointer . '&action=2&DMSCALE=';
    $arr = getImageDimensions($pointer);
    if($arr < 0) {
      return -1;
    }
    $width = $arr['width'];
    $height = $arr['height'];
    $scale = -1;
    $new_width = -1;
    $new_height = -1;
    if($size === 'full') {
      $query .= '100' . '&DMWIDTH=' . $width . '&DMHEIGHT=' . $height;
      return $query;
    } else if($size === 'large') {
      if($width >= $height) { //if width is larger
        if($width > 1280) {
          $new_width = 1280;
        } else {
          $new_width = $width;
        }
      } else { //if height is larger
        if($height > 1280) {
          $new_height = 1280;
        } else {
          $new_height = $height;
        }
      }
    } else if($size === 'small') {
      if($width >= $height) { //if width is larger
        if($width > 640) {
          $new_width = 640;
        } else {
          $new_width = $width;
        }
      } else { //if height is larger
        if($height > 640) {
          $new_height = 640;
        } else {
          $new_height = $height;
        }
      }
    }
    if($new_height === -1) {
      $new_height = $new_width * $height / $width;
    } else if($new_width === -1) {
      $new_width = $new_height * $width / $height;
    }
    $scale = 100 * $new_width / $width;
    if($scale <= 0 || $new_width <= 0 || $new_height <= 0) {
      return -2;
    }
    $query .= $scale . '&DMWIDTH=' . $new_width . '&DMHEIGHT=' . $new_height;
    return $query;
  }
  
  /*
   * input - $pointer - cdm pointer to the item
   *       - $attrib - php array of all attributes that the function should return
   * returns - json formatted STRING with a value assigned to each attribute requested
   */
  function getItemInfo($pointer, $attrib) {
    $response = array();
    $query = CDM_API_WEBSERVICE . 'dmGetItemInfo' . CDM_COLLECTION . '/' . $pointer . '/json';
    $json = json_decode((string)curl($query), true);
    for($i = 0; $i < count($attrib); $i++) {
      switch($attrib[$i]) {
        case 'relati':
          $response['relati'] = $json['relati'];
          break;
        case 'publis':
          $response['publis'] = $json['publis'];
          break;
        case 'title':
          $response['title'] = $json['title'];
          break;
        case 'descri':
          $response['descri'] = $json['descri'];
          break;
        case 'type':
          $response['type'] = $json['type'];
          break;
        case 'media':
          $response['media'] = $json['media'];
          break;
        case 'creato':
          $response['creato'] = $json['creato'];
          break;
        case 'dateb':
          $response['dateb'] = $json['dateb'];
          break;
        case 'datea':
          $response['datea'] = $json['datea'];
          break;
        case 'geogra':
          $response['geogra'] = $json['geogra'];
          break;
        case 'source':
          $response['source'] = $json['source'];
          break;
        case 'extent':
          $response['extent'] = $json['extent'];
          break;
        case 'rights':
          $response['rights'] = $json['rights'];
          break;
        case 'tribe':
          if(gettype($json['tribe']) === 'string') {
            $response['tribe'] = $json['tribe'];
          } else {
            $response['tribe'] = '';
          }
          break;
        default:
          //nothing
      }
    }
    return $response;
  }

?>