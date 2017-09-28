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
   * -40 to -49: 'Doesn't exist' and null issues
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
    if(curl_errno($ch)) {
      curl_close($ch);
      return -1;
    } else {
      $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      if($resultStatus == 200) {
        curl_close($ch);
        return $data;
      } else {
        curl_close($ch);
        return -1;
      }
    }
  }
  
  /*
   * Returns whether some given data from cdm is valid.
   * 
   * Input:
   * (string) json data straight from cdm api
   * 
   * Return:
   * TRUE if valid
   * FALSE if invalid
   */
  function isCdmValid($data) {
    
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
  
  /*
   * Takes image by pointer from cdm and saves it with proper directory and name, locally
   * THIS FUNCTION REQUIRES given pointer to be populated in db/data/types.json
   * 
   * Input:
   * $pointer - cdm pointer to image to be saved
   * 
   * Returns:
   * 0 for success
   * -negative number for failure
   */
  function saveImageLocal($pointer) {
    //check inputs
    $vartype = (string)gettype($pointer);
    if($vartype !== 'integer' && $vartype !== 'string') {
      error_log('saveImageLocal: Error -10: Invalid input type. $pointer must be an int or string.',0);
      return -10;
    }
    $type = getItemInfo($pointer,array('type'),0);
    if(gettype($type) === 'integer' && $type < 0) {
      error_log('saveImageLocal: Error -20: Bad output from getItemInfo(). Input was pointer ' . $pointer . ' with $location=0. Check errors from getItemInfo() for more details.',0);
      return -20;
    }
    $type = strtolower(trim((string)$type['type']));
    $sizes = array('thumbnail', 'small', 'large', 'full');
    $filename = $pointer . '_';
    $dest = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT;
    switch($type) {
      case 'image':
        $dest = $dest . '/images/';
        break;
      case 'letter':
        $dest = $dest . '/letters/';
        break;
      default:
        error_log('saveImageLocal: Error -42: The \'type\' of the pointer ' . $pointer . ' at cdm is innapropriate for the function. Is not image or letter.',0);
        return -42;
    }
    for($i = 0; $i < count($sizes); $i++) {
      $ref = getImageReference($pointer,$sizes[$i],0);
      if(gettype($ref) === 'integer' && $ref < 0) {
        error_log('saveImageLocal: Error -20: Bad output from getImageReference(). Input was ' . $pointer . '. Error from getImageReference() was ' . $ref);
        return -20;
      }
      curlSave($ref,$dest . $filename . $sizes[$i] . '.' . IMAGE_FORMAT);
    }
    return 0;
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
   * Returns the contents of a json file at a given absolute address.
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
  function getJsonLocal($url) {
    if(gettype($url) !== 'string') {
      error_log('getJsonLocal: Error -5: Input must be a string (url or absolute path).',0);
      return -5;
    }
    if(contains($url,'.json')) {
      //it's a json file
    } else {
      error_log('getJsonLocal: Error -1: Input does not have a .json extension.',0);
      return -1;
    }
    $data = null;
    if(file_exists($url)) {
      $data = json_decode(file_get_contents($url));
      if($data === null && json_last_error() === JSON_ERROR_NONE) {
        error_log('getJsonLocal: Error -3: Json data at given location is null.',0);
        return -3;
      } else if($data === null) {
        error_log('getJsonLocal: Error -4: Error decoding json data. Probably malformed.',0);
        return -4;
      }
    } else {
      error_log('getJsonLocal: Error -2: File does not exist at ' . $url,0);
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
   * Searches local types.json file for the given cdm pointer and returns the type.
   * 
   * Return:
   * either
   * NULL if there was a problem
   * or
   * (string) of the type of the cdm pointer
   * 
   * Inputs:
   * $pointer - (int or string) cdm pointer to item
   * 
   * Error codes:
   * 
   */
  function getTypeLocal($pointer) {
    //check inputs
    $type = (string)gettype($pointer);
    if($type !== 'integer' && $type !== 'string') {
      error_log('getTypeLocal: Error -10: Invalid input type. $pointer must be an int or string.',0);
      return -10;
    }
    $query = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_TYPES;
    $json = getJsonLocal($query);
    if(gettype($json) === 'integer' && $json < 0) {
      error_log('getTypeLocal: Error -20: Bad output from getJsonLocal(). Input was ' . $query . '. Check errors from getJsonLocal() for more details.',0);
      return -20;
    }
    //take $json and find the index where the type of $pointer is kept
    $ind = getId($json, $pointer);
    if(gettype($ind) === 'integer' && $ind < 0) {
      error_log('getTypeLocal: Error -20: Bad output from getId(). Input was json data from ' . $query . ' and pointer ' . $pointer . '. Check errors from getId() for more details.',0);
      return -20;
    }
    //take the index and check the type there
    $obj = $json->data[$ind];
    //check if the key 'type' exists or is set
    if(array_key_exists('type',$obj) === FALSE) {
      error_log('getTypeLocal: Error -40: Key called \'type\' does not exist at index ' . $ind . ' in json file ' . $query . '.',0);
      return -40;
    }
    //declare return variable
    $t = $obj->type;
    if($t === null || trim($t) === '') {
      error_log('getTypeLocal: Error -41: The \'type\' key in ' . $query . ' at index ' . $ind . ' is empty or null.',0);
      return -41;
    }
    
    return $t;
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
   * Return:
   * $arr (array) with two keys:
   *    'width' = (integer) width
   *    'height' = (integer) height
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
        $arr['width'] = (int)$info->width;
        $arr['height'] = (int)$info->height;
      }
      if($arr['width'] < 1 || $arr['height'] < 1) {
        error_log('getImageDimensions: Error -2: Received width and/or height are invalid. (less than or equal to zero)',0);
        return -2;
      }
    } else if($location === 1) {
      //first get type of pointer
      $pointer_type = getTypeLocal($pointer);
      if(gettype($pointer_type) === 'integer' && $pointer_type < 0) {
        error_log('getImageDimensions: Error -20: Bad output from getTypeLocal(). Input was id ' . $pointer . '. Check errors from getTypeLocal() for more details.',0);
        return -20;
      }
      $data_loc = null;
      if($pointer_type === 'image') {
        $data_loc = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_IMAGE;
      } else if($pointer_type === 'letter') {
        $data_loc = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_LETTER;
      } else {
        error_log('getImageDimensions: Error -6: Type of pointer (' . $pointer . ', ' . $pointer_type . ') is invalid or is not a type that supports dimensions. (Must be image or letter)',0);
        return -6;
      }
      //$data_loc contains the path to the json file with the details on the pointer
      $data_content = getJsonLocal($data_loc);
      if(gettype($data_content) === 'integer' && $data_content < 0) {
        error_log('getImageDimensions: Error -3: Error getting json file. Check getJsonLocal().',0);
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
        $arr['width'] = (int)$data_content->data[$ind]->width;
        $arr['height'] = (int)$data_content->data[$ind]->height;
      } else if(gettype($ind) === 'array') {
        $arr['width'] = (int)$data_content->data[$ind[0]][$ind[1]]->width;
        $arr['height'] = (int)$data_content->data[$ind[0]][$ind[1]]->height;
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
        error_log('getImageTitle: Error -1: Couldn\'t get image info of pointer ' . $pointer . '. (Check getCdmImageInfo for error.)',0);
        return -1;
      } else {
        $t = trim((string)$info->title);
        if(isset($info->title) && $t !== '') {
          $title = $t;
        } //not set, but could exist and just be null or empty
        else if($t === null || $t === '') {
          error_log('getImageTitle: Error -2: Title of pointer ' . $pointer . ' is null or empty.',0);
          return -2;
        } //not set and not null or empty, doesn't exist
        else {
          error_log('getImageTitle: Error -45: Title key does not exist in cdm for pointer ' . $pointer . '. (Check output of getCdmImageInfo.)',0);
          return -45;
        }
      }
    } else if($location === 1) {
      //get title from local server (source tree)
      
      $types = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_TYPES;
      $types_content = getJsonLocal($types);
      if(gettype($types_content) === 'integer' && $types_content < 0) {
        error_log('getImageTitle: Error -3: Error getting json file. Check getJsonLocal().',0);
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
      $data_content = getJsonLocal($data_loc);
      if(gettype($data_content) === 'integer' && $data_content < 0) {
        error_log('getImageTitle: Error -3: Error getting json file. Check getJsonLocal().',0);
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
   * 
   * Inputs:
   * $pointer - (int or string) cdm pointer of image
   * $size - (string)
   * $location - (int) 0 to get straight from cdm, 1 to get locally
   * 
   * Return:
   * $query - (string) url to image
   * 
   * Error codes:
   * 
   */
  function getImageReference($pointer, $size, $location) {
    //check inputs
    $type = (string)gettype($pointer);
    if($type !== 'integer' && $type !== 'string') {
      error_log('getImageReference: Error -10: Invalid input type. $pointer must be an int or string.',0);
      return -10;
    }
    $type = (string)gettype($size);
    if($type !== 'string') {
      error_log('getImageReference: Error -10: Invalid input type. $size must be a string.',0);
      return -10;
    }
    $type = (string)gettype($location);
    if($type !== 'integer') {
      error_log('getImageReference: Error -10: Invalid input type. $location must be an int.',0);
      return -10;
    }
    //declare return variable
    $query = null;
    //check method of retrieving image
    if($location === 0) {
      //compile a php url query to cdm to get the image
      $query = CDM_API_UTILS . 'CISOROOT=' . substr(CDM_COLLECTION, 1) . '&CISOPTR=' . $pointer . '&action=2&DMSCALE=';
      $arr = getImageDimensions($pointer,$location);
      if($arr < 0) {
        error_log('getImageReference: Error -20: Bad output from getImageDimensions(). Input pointer was ' . $pointer . '. Check errors from getImageReference() for more details.',0);
        return -20;
      }
      $width = (int)$arr['width'];
      $height = (int)$arr['height'];
      $scale = null;
      $new_width = null;
      $new_height = null;
      if($size === 'full') {
        $scale = 100;
        $new_width = $width;
        $new_height = $height;
        //$query .= '100' . '&DMWIDTH=' . $width . '&DMHEIGHT=' . $height;
      } else if($size === 'large') {
        if($width >= $height) { //if width is larger
          if($width > IMAGE_SIZE_LARGE) {
            $new_width = IMAGE_SIZE_LARGE;
          } else {
            $new_width = $width;
          }
        } else { //if height is larger
          if($height > IMAGE_SIZE_LARGE) {
            $new_height = IMAGE_SIZE_LARGE;
          } else {
            $new_height = $height;
          }
        }
      } else if($size === 'small') {
        if($width >= $height) { //if width is larger
          if($width > IMAGE_SIZE_SMALL) {
            $new_width = IMAGE_SIZE_SMALL;
          } else {
            $new_width = $width;
          }
        } else { //if height is larger
          if($height > IMAGE_SIZE_SMALL) {
            $new_height = IMAGE_SIZE_SMALL;
          } else {
            $new_height = $height;
          }
        }
      } else if($size === 'thumbnail') {
        if($width >= $height) { //if width is larger
          if($width > IMAGE_SIZE_THUMBNAIL) {
            $new_width = IMAGE_SIZE_THUMBNAIL;
          } else {
            $new_width = $width;
          }
        } else { //if height is larger
          if($height > IMAGE_SIZE_THUMBNAIL) {
            $new_height = IMAGE_SIZE_THUMBNAIL;
          } else {
            $new_height = $height;
          }
        }
      } else {
        error_log('getImageReference: Error -11: Invalid input value. $size must be \'thumbnail\', \'small\', \'large\', or \'full\'.',0);
        return -11;
      }
      if($new_height === null) {
        $new_height = $new_width * $height / $width;
      } else if($new_width === null) {
        $new_width = $new_height * $width / $height;
      }
      $scale = 100 * $new_width / $width;
      //if($size !== 'full') {
      if($scale <= 0 || $new_width <= 0 || $new_height <= 0 || $scale === null || $new_width === null || $new_height === null) {
        error_log('getImageReference: Error -90: Unexpected variable value. $scale, $new_width, or $new_height were not set properly or at all.',0);
        return -90;
      }
      //}
      $query .= $scale . '&DMWIDTH=' . $new_width . '&DMHEIGHT=' . $new_height;
    } else if($location === 1) {
      //compile a url query to our server to get the image
      $query = SITE_ROOT . DB_ROOT;
      $types_loc = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_TYPES;
      $types_data = getJsonLocal($types_loc);
      if(gettype($types_data) === 'integer' && $types_data < 0) {
        error_log('getImageReference: Error -20: Bad output from getJsonLocal(). Input was ' . $types_loc . '. Check errors from getJsonLocal() for more details.',0);
        return -20;
      }
      //take $types_data and find the index where the type of $pointer is kept
      $ind = getId($types_data, $pointer);
      if(gettype($ind) === 'integer' && $ind < 0) {
        error_log('getImageReference: Error -20: Bad output from getId(). Input was json data from ' . $types_loc . ' and pointer ' . $pointer . '. Check errors from getId() for more details.',0);
        return -20;
      }
      //take the index and check the type there
      $obj = $types_data->data[$ind];
      //check if the key 'type' exists or is set
      if(array_key_exists('type',$obj) === FALSE) {
        error_log('getImageReference: Error -40: Key called \'type\' does not exist at index ' . $ind . ' in json file ' . $types_loc . '.',0);
        return -40;
      }
      $t = $obj->type;
      if($t === null || trim($t) === '') {
        error_log('getImageReference: Error -41: The \'type\' key in ' . $types_loc . ' at index ' . $ind . ' is empty or null.',0);
        return -41;
      }
      //we know type exists, it's not null, it's not an empty string
      $t = (string)$t; //cast as a string in case it was an object for some reason
      $f = null;
      if($t === 'image') {
        $f = '/images/';
      } else if($t === 'letter') {
        $f = '/letters/';
      } else {
        //in this case the type is inappropriate to the function
        error_log('getImageReference: Error -42: The \'type\' of the pointer ' . $pointer . ' at ' . $types_loc . ' is innapropriate for the function. Does not involve images.',0);
        return -42;
      }
      $query = $query . $f . (string)$pointer . '_' . $size . '.' . IMAGE_FORMAT;
    } else {
      error_log('getImageReference: Error -11: Invalid input value. $location must be a 0 or 1.',0);
      return -11;
    }
    
    if($query === null) {
      error_log('getImageReference: Error -99: Unexpected results. $query is still null by the end of the function. If you are seeing this error, something was fatally mixed up and bugfixing is necessary.',0);
      return -99;
    }
    
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
   * PHP array with a value assigned to each attribute requested. Any attributes that don't exist or aren't filled will be ignored in output.
   * 
   * Error codes:
   * 
   */
  function getItemInfo($pointer, $attrib, $location) {
    //check inputs
    $type = (string)gettype($pointer);
    if($type !== 'integer' && $type !== 'string') {
      error_log('getItemInfo: Error -10: Invalid input type. $pointer must be an int or string.',0);
      return -10;
    }
    $type = (string)gettype($attrib);
    if($type !== 'array') {
      error_log('getItemInfo: Error -10: Invalid input type. $attrib must be an array.',0);
      return -10;
    }
    $type = (string)gettype($location);
    if($type !== 'integer') {
      error_log('getItemInfo: Error -10: Invalid input type. $location must be an int.',0);
      return -10;
    }
    //declare return variable
    $response = null;
    $query = null;
    $json = null;
    if($location === 0) {
      $query = CDM_API_WEBSERVICE . 'dmGetItemInfo' . CDM_COLLECTION . '/' . $pointer . '/json';
      $json = json_decode((string)curl($query), true);
    } else if($location === 1) {
      //get the type of the pointer
      $t = getTypeLocal($pointer);
      if(gettype($t) === 'integer' && $t < 0) {
        error_log('getItemInfo: Error -20: Bad output from getTypeLocal(). Input was id ' . $pointer . '. Check errors from getTypeLocal() for more details.',0);
        return -20;
      }
      //search data/[type]/data.json for pointer
      $query = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT;
      switch($t) {
        case 'image':
          $query = $query . DB_IMAGE;
          break;
        case 'letter':
          $query = $query . DB_LETTER;
          break;
        case 'interview':
          $query = $query . DB_INTERVIEW;
          break;
        case 'video':
          $query = $query . DB_VIDEO;
          break;
        default:
          error_log('getItemInfo: Error -42: The \'type\' of the pointer ' . $pointer . ' at ' . $t . ' is not valid. Must be \'image\', \'letter\', \'interview\', or \'video\'.',0);
          return -42;
      }
      $json = getJsonLocal($query);
      if(gettype($json) === 'integer' && $json < 0) {
        error_log('getItemInfo: Error -20: Bad output from getJsonLocal(). Input was ' . $query . '. Check errors from getJsonLocal() for more details.',0);
        return -20;
      }
      //we have the data
      //search for pointer
      $ind = getId($json, $pointer);
      //throw error if it's not there
      if(gettype($ind) === 'integer' && $ind < 0) {
        error_log('getItemInfo: Error -20: Bad output from getId(). Input was json data from ' . $query . ' and pointer ' . $pointer . '. Check errors from getId() for more details.',0);
        return -20;
      }
      //put attributes from object in array called $json
      //$ind may be an int or an array with two values
      if(gettype($ind) === 'integer') {
        $json = $json->data[$ind];
      } else if(gettype($ind) === 'array') {
        $json = $json->data[$ind[0]][$ind[1]];
      }
    } else {
      //throw error that location argument had invalid input
      error_log('getItemInfo: Error -11: Invalid input value. $location must be a 0 or 1.',0);
      return -11;
    }
    
    //now $json should be populated with an array of attributes for the pointer
    if($json === null) {
      error_log('getItemInfo: Error -98: Unexpected results. $json is still null where it should have been populated. If you are seeing this error, something was fatally mixed up and bugfixing is necessary.',0);
      return -98;
    }
    
    //sort out the requested attributes
    //you can do this by flipping $attrib
    //then return the key/value pairs from $json that have matching keys in $attrib
    //this is called array intersection
    $attrib = array_flip($attrib);
    $response = array_intersect_key((array)$json,$attrib);
    
    /* old inefficient code
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
     * */
    
    if($response === null) {
      error_log('getItemInfo: Error -99: Unexpected results. $response is still null by the end of the function. If you are seeing this error, something was fatally mixed up and bugfixing is necessary.',0);
      return -99;
    }
    
    return $response;
  }
  
?>