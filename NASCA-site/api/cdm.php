<?php
  $api_dir = str_replace('cdm.php','',__FILE__);
  require_once($api_dir . 'configuration.php');
  
  ini_set('allow_url_fopen', '1');
  ini_set('allow_url_include', '1');
  
  /*
   * TODO TODO TODO
   * 
   * Refactor error codes so that repeated errors in different functions have the same code
   * -10 to -19: Input arg issues
   * -20 to -29: Output issues
   * -30 to -39: Json issues
   * -40 to -49: 'Doesn't exist' issues
   * -90 to -99: Unexpected results issues (Something being null when it shouldn't, resolutions being 0 or negative, etcetera)
   * 0 success
   * -1 failure
   * 
   * TODO TODO TODO
   */
  
  /*
   * Returns data from url using cURL
   */
  function curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
  }
  
  /*
   * Tries to save data from url in destination file using cURL
   * 
   * Returns:
   * 0 if success
   * -1 if failure
   */
  function curlSave($url, $dest) {
    $ch = curl_init($url);
    $fp = fopen($dest, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    
    if(curl_errno($ch)) {
      curl_close($ch);
      fclose($fp);
      return -1;
    } else {
      $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      if($resultStatus == 200) {
        curl_close($ch);
        fclose($fp);
        return 0;
      } else {
        curl_close($ch);
        fclose($fp);
        return -1;
      }
    }
  }
  
  function contains($str, $sub) {
    if(strpos($str, $sub) === FALSE) {
      return FALSE;
    } else {
      return TRUE;
    }
  }
  
  /*
   * Returns string representation of variable.
   * 
   * Input:
   * $var - may be a string or array. Anything else, and only the variable
   */
  function outputVar($var) {
    $var_type = (string)gettype($var);
    if($var_type === 'string') {
      return $var;
    } else if($var_type === 'array') {
      return implode(' ',$var);
    } else if($var_type === 'integer') {
      return (string)$var;
    } else if($var_type === 'boolean') {
      if($var === TRUE) {
        return 'True';
      } else if($var === FALSE) {
        return 'False';
      } else {
        return $var_type;
      }
    } else if($var_type === 'NULL' || $var_type === 'null') {
      return 'Null';
    } else {
      return $var_type;
    }
  }
  
  /*
   * Returns the contents of a json file at a given url.
   * 
   * Inputs:
   * $url - url of json file
   * 
   * Error codes:
   * -1 - Input is not a .json file
   * -2 - File does not exist
   * -3 - Json data is null
   * -4 - Error decoding json data
   * -5 - Innapropriate input type
   */
  function getJson($url) {
    if(gettype($url) !== 'string') {
      error_log('getJson: Error -5: Input must be a string (url or absolute path).',0);
      return -5;
    }
    if(contains($url,'.json')) {
      //it's a json file
    } else {
      error_log('getJson: Error -1: Input does not have a .json extension.',0);
      return -1;
    }
    $data = null;
    if(file_exists($url)) {
      $data = json_decode(file_get_contents($url));
      if($data === null && json_last_error() === JSON_ERROR_NONE) {
        error_log('getJson: Error -3: Json data at given location is null.',0);
        return -3;
      } else if($data === null) {
        error_log('getJson: Error -4: Error decoding json data. Probably malformed.',0);
        return -4;
      }
    } else {
      error_log('getJson: Error -2: File does not exist at ' . $url,0);
      return -2;
    }
    return $data;
  }
  
  /*
   * Returns the id at $json->data that has the given pointer.
   * 
   * Return:
   * either
   * (int) of index pointing to given pointer
   * or
   * (array) of series of indices pointing to given pointer
   * 
   * Inputs:
   * $json - given .json file to search
   * $pointer - given pointer to search for. String or Integer form will work.
   * 
   * Error codes:
   * -1 - Record of requested pointer does not exist
   */
  function getId($json, $pointer) {
    $pointer = (string)$pointer;
    $ind = null;
    for($i = 0; $i < $json->count; $i++) {
      if((string)gettype($json->data[$i]) === 'array') {
        for($j = 0; $j < count($json->data[$i],COUNT_NORMAL); $j++) {
          if((string)$json->data[$i][$j]->pointer === $pointer) {
            $ind = array($i,$j);
            break 2;
          }
        }
      }
      else if((string)$json->data[$i]->pointer === $pointer) {
        $ind = $i;
        break;
      }
    }
    if($ind === null) {
      error_log('getId: Error -1: Record of requested pointer does not exist in given json file',0);
      return -1;
    } else {
      return $ind;
    }
  }
  
  /*
   * Returns simpleXML object with information on the image, from cdm.
   * 
   * Inputs:
   * $pointer - cdm pointer to image
   * 
   * XML Schema:
   * <imageinfo>
   *  <filename>/usr/local/...</filename>
   *  <type>jp2</type>
   *  <width>1111</width>
   *  <height>1111</height>
   *  <title>title</title>
   * </imageinfo>
   * 
   * Error codes:
   * -1 - "Record does not exist"
   * -2 - "Error looking up collection /nasca
   *       No permission to access this collection"
   * -3 - Simple XML couldn't parse response
   * -4 - Image info is not formatted properly
   */
  function getCdmImageInfo($pointer) {
    $query = CDM_API_WEBSERVICE . 'dmGetImageInfo' . CDM_COLLECTION . '/' . (string)$pointer . '/xml';
    $response = (string)curl($query);
    $xml = simplexml_load_string($response);
    
    if(contains($response, 'Record does not exist')) {
      error_log('getCdmImageInfo: Error -1: Record does not exist.',0);
      return -1;
    } else if(contains($response,'Error looking up collection')) {
      error_log('getCdmImageInfo: Error -2: No permission to access cdm collection.',0);
      return -2;
    } else if($xml === FALSE) {
      error_log('getCdmImageInfo: Error -3: Simple XML couldn\'t parse response.',0);
      return -3;
    } else if(!isset($xml->width) || !isset($xml->height)) {
      error_log('getCdmImageInfo: Error -4: Image info is not formatted properly.',0);
      return -4;
    }
    
    return $xml;
  }
  
  /*
   * Gets dimensions of any image or letter by pointer.
   * 
   * Inputs:
   * $pointer - cdm pointer of image
   * $location - (int) type 0 to get straight from cdm, type 1 to get locally
   * 
   * Error codes:
   * -1 - Couldn't get image info
   * -2 - Width and/or height are invalid (less than or equal to zero)
   * -3 - Error getting json file
   * -4 - record of pointer does not exist
   * -5 - 'type' of pointer is undefined or does not exist
   * -6 - 'type' of pointer is invalid or is not a type that supports dimensions (must be image or letter)
   * -7 - Invalid input. $location argument was not a 0 or 1.
   * -8 - Object does not have width/height data.
   * -9 - Object does not have valid width and/or height.
   */
  function getImageDimensions($pointer, $location) {
    $arr['width'] = null; //declare array $arr with width and height
    $arr['height'] = null;
    if($location === 0) {
      $info = getCdmImageInfo($pointer); //get xml doc with dimensions from cdm
      if($info < 0) {
        error_log('getImageDimensions: Error -1: Couldn\'t get image info. (Check getCdmImageInfo for error.)',0);
        return -1;
      } else {
        $arr['width'] = $info->width;
        $arr['height'] = $info->height;
      }
      if($arr['width'] < 1 || $arr['height'] < 1) {
        error_log('getImageDimensions: Error -2: Received width and/or height are invalid. (less than or equal to zero)',0);
        return -2;
      }
    } else if($location === 1) {
      $types = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_TYPES;
      $types_content = getJson($types);
      if(gettype($types_content) === 'integer' && $types_content < 0) {
        error_log('getImageDimensions: Error -3: Error getting json file. Check getJson().',0);
        return -3;
      }
      //$types_content is populated with data from types.json
      //now find the type of $pointer
      $ind = getId($types_content, $pointer);
      if($ind < 0) {
        error_log('getImageDimensions: Error -4: Error getting id of pointer. Check getId().',0);
        return -4;
      }
      $pointer_type = $types_content->data[$ind]->type;
      $data_loc = null;
      if($pointer_type === null || $pointer_type === 'undefined' || $pointer_type === '') {
        error_log('getImageDimensions: Error -5: Type of pointer is undefined or does not exist at ' . $types,0);
        return -5;
      } else if($pointer_type === 'image') {
        $data_loc = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_IMAGE;
      } else if($pointer_type === 'letter') {
        $data_loc = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_LETTER;
      } else {
        error_log('getImageDimensions: Error -6: Type of pointer is invalid or is not a type that supports dimensions. (Must be image or letter)',0);
        return -6;
      }
      //$data_loc contains the path to the json file with the details on the pointer
      $data_content = getJson($data_loc);
      if(gettype($data_content) === 'integer' && $data_content < 0) {
        error_log('getImageDimensions: Error -3: Error getting json file. Check getJson().',0);
        return -3;
      }
      //$data_content is populated with json file with details on the pointer
      $ind = getId($data_content, $pointer);
      if(gettype($ind) === 'integer' && $ind < 0) {
        error_log('getImageDimensions: Error -4: Error getting id of pointer from ' . $data_loc . '. Check getId().',0);
        return -4;
      }
      //$ind should now have the index where 'pointer' is $pointer
      //return width and height into $arr
      //$ind may be an int or an array with two values
      if(gettype($ind) === 'integer') {
        $arr['width'] = $data_content->data[$ind]->width;
        $arr['height'] = $data_content->data[$ind]->height;
      } else if(gettype($ind) === 'array') {
        $arr['width'] = $data_content->data[$ind[0]][$ind[1]]->width;
        $arr['height'] = $data_content->data[$ind[0]][$ind[1]]->height;
      }
      //if the width and height are 0 or less, throw error
      if($arr['width'] === null || $arr['width'] === 'undefined' || $arr['width'] === '' || $arr['height'] === null || $arr['height'] === 'undefined' || $arr['height'] === '') {
        error_log('getImageDimensions: Error -8: Object does not have width/height data.',0);
        return -8;
      } else if($arr['width'] < 1 || $arr['height'] < 1) {
        error_log('getImageDimensions: Error -9: Object does not have valid width and/or height.',0);
        return -9;
      }
    } else {
      //throw error - location argument had invalid input
      error_log('getImageDimensions: Error -7: Invalid input. $location argument was not a 0 or 1.',0);
      return -7;
    }
    
    return $arr;
  }
  
  /*
   * Gets title of image based object as a string, from cdm or local server.
   * This will work for images or letters.
   * 
   * Inputs:
   * $pointer - cdm pointer of image
   * $location - (int) 0 to get straight from cdm, 1 to get locally
   * 
   * Error codes:
   * -1 - Couldn't get image info
   * -2 - Title is null or empty
   * -45 - Title key does not exist in cdm
   * -7 - Invalid input. $location argument was not a 0 or 1
   * -99 - Unforeseen issue caught. Passed all checks until the end of the function but the return value is still null. Debugging likely to be necessary.
   */
  function getImageTitle($pointer, $location) {
    $title = null;
    
    if($location === 0) {
      //get title from cdm
      $info = getCdmImageInfo($pointer);
      if(gettype($info) === 'integer' && $info < 0) {
        error_log('getImageTitle: Error -1: Couldn\'t get image info. (Check getCdmImageInfo for error.)',0);
        return -1;
      } else {
        $t = (string)$info->title;
        if(isset($info->title) && $t !== '') {
          $title = $t;
        } //not set, but could exist and just be null or empty
        else if($t === null || trim($t) === '') {
          error_log('getImageTitle: Error -2: Title is null or empty.',0);
          return -2;
        } //not set and not null or empty, doesn't exist
        else {
          error_log('getImageTitle: Error -45: Title key does not exist in cdm. (Check output of getCdmImageInfo.)',0);
          return -45;
        }
      }
    } else if($location === 1) {
      //get title from local server (source tree)
      
      $types = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_TYPES;
      $types_content = getJson($types);
      if(gettype($types_content) === 'integer' && $types_content < 0) {
        error_log('getImageTitle: Error -3: Error getting json file. Check getJson().',0);
        return -3;
      }
      //$types_content is populated with data from types.json
      //now find the type of $pointer
      $ind = getId($types_content, $pointer);
      if($ind < 0) {
        error_log('getImageTitle: Error -4: Error getting id of pointer. Check getId().',0);
        return -4;
      }
      $pointer_type = $types_content->data[$ind]->type;
      $data_loc = null;
      if($pointer_type === null || $pointer_type === 'undefined' || $pointer_type === '') {
        error_log('getImageTitle: Error -5: Type of pointer is undefined or does not exist at ' . $types,0);
        return -5;
      } else if($pointer_type === 'image') {
        $data_loc = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_IMAGE;
      } else if($pointer_type === 'letter') {
        $data_loc = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_LETTER;
      } else {
        error_log('getImageTitle: Error -6: Type of pointer is invalid or is not a type that supports dimensions. (Must be image or letter)',0);
        return -6;
      }
      //$data_loc contains the path to the json file with the details on the pointer
      $data_content = getJson($data_loc);
      if(gettype($data_content) === 'integer' && $data_content < 0) {
        error_log('getImageTitle: Error -3: Error getting json file. Check getJson().',0);
        return -3;
      }
      //$data_content is populated with json file with details on the pointer
      $ind = getId($data_content, $pointer);
      if(gettype($ind) === 'integer' && $ind < 0) {
        error_log('getImageTitle: Error -4: Error getting id of pointer from ' . $data_loc . '. Check getId().',0);
        return -4;
      }
      //$ind should now have the index where 'pointer' is $pointer
      //return title into $title
      //$ind may be an int or an array with two values
      if(gettype($ind) === 'integer') {
        $title = $data_content->data[$ind]->title;
      } else if(gettype($ind) === 'array') {
        $title = $data_content->data[$ind[0]][$ind[1]]->title;
      }
      //if the value is invalid
      if($title === null || $title === 'undefined' || $title === '') {
        error_log('getImageTitle: Error -8: Object does not have width/height data.',0);
        return -8;
      }
    } else {
      //throw error - location argument had invalid input
      error_log('getImageTitle: Error -7: Invalid input. $location argument was not a 0 or 1.',0);
      return -7;
    }
    if($title === null) {
      error_log('getImageTitle: Error -99: Unforeseen issue caught. Passed all checks until the end of the function but the return value is still null. Debugging likely to be necessary.',0);
      return -99;
    }
    return $title;
    
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
   * Inputs:
   * $pointer - cdm pointer to the item
   * $attrib - php array of all attributes that the function should return
   *        Options include
   *        'relati' - Digital Collection
   *        'publis' - Contributing Institution
   *        'title' - Title
   *        'descri' - Description
   *        'transc' - Transcription
   *        'type' - Object Type
   *        'media' - Media Type
   *        'typea' - Type of Digital Artifact
   *        'creato' - Creator
   *        'date' - Date (Year)
   *        'datea' - Digital Date (year-month-day)
   *        'dateb' - Date of Original Artifact (Century)
   *        'geogra' - Geographic Location
   *        'source' - Source
   *        'subjec' - Subject
   *        'extent' - Extent (size of photograph)
   *        'rights' - Copyright
   *        'langua' - Language
   *        'tribe' - Tribe
   *        'identi' - Cdm Identifier (not pointer)
   *        'width' - pixel width of item
   *        'height' - pixel height of item
   * $location - (int) type 0 to get straight from cdm, type 1 to get locally
   * 
   * Returns:
   * PHP array with a value assigned to each attribute requested
   */
  function getItemInfo($pointer, $attrib, $location) {
    $response = array();
    if($location === 0) {
      $query = CDM_API_WEBSERVICE . 'dmGetItemInfo' . CDM_COLLECTION . '/' . $pointer . '/json';
      $json = json_decode((string)curl($query), true);
    } else if($location === 1) {
      //access db/data/data.json
      //search for pointer and get object type (letter, image, etc)
      //throw error if it's not there
      //if it is, search data/[type]/data.json for pointer
      //put attributes from object in array called $json
    } else {
      //throw error that location argument had invalid input
    }
    
    for($i = 0; $i < count($attrib); $i++) {
      switch($attrib[$i]) {
        case 'relati':
          $response['relati'] = outputVar($json['relati']);
          break;
        case 'publis':
          $response['publis'] = outputVar($json['publis']);
          break;
        case 'title':
          $response['title'] = outputVar($json['title']);
          break;
        case 'descri':
          $response['descri'] = outputVar($json['descri']);
          break;
        case 'type':
          $response['type'] = outputVar($json['type']);
          break;
        case 'media':
          $response['media'] = outputVar($json['media']);
          break;
        case 'creato':
          $response['creato'] = outputVar($json['creato']);
          break;
        case 'dateb':
          $response['dateb'] = outputVar($json['dateb']);
          break;
        case 'datea':
          $response['datea'] = outputVar($json['datea']);
          break;
        case 'geogra':
          $response['geogra'] = outputVar($json['geogra']);
          break;
        case 'source':
          $response['source'] = outputVar($json['source']);
          break;
        case 'extent':
          $response['extent'] = outputVar($json['extent']);
          break;
        case 'rights':
          $response['rights'] = outputVar($json['rights']);
          break;
        case 'tribe':
          $response['tribe'] = outputVar($json['tribe']);
          break;
        default:
          //nothing
      }
    }
    return $response;
  }
  
?>