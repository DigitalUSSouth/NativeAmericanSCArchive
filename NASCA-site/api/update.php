<?php
  $current_dir = str_replace('update.php','',__FILE__);
  require_once($current_dir . 'configuration.php');
  require_once($current_dir . 'cdm.php');
  
  set_time_limit(0);
  
  function updateSite() {
    $returnNotes = array();
    $query = CDM_API_WEBSERVICE . 'dmQuery' . CDM_COLLECTION . '/0/fields/nosort/1024/0/0/0/0/0/0/0/json';
    $response = curl($query);
    if(gettype($response) === 'integer' && $response < 0) {
      return '-1 ' . (string)$response;
    }
    //response is successful, may not necessarily be the collection if the query is wrong or
    //something changed with cdm's api\
    //if(isCdmValid($response) === FALSE) {
    //  return -1;
    //}
    //decode response into php array
    $collection = json_decode($response);
    $total = (int)$collection->pager->total;
    $letterData = array();
    $imageData = array();
    $homeData = array();
    $typeData = array();
    for($i = 0; $i < $total; $i++) {
      $pointer = (int)$collection->records[$i]->pointer;
      $rec = $collection->records[$i];
      $fn = $rec->find;
      if($collection->records[$i]->filetype === 'jp2') {
        $dims = getImageDimensions($pointer,0);
        $attrib_req = array('title','relati','publis','descri','media','typea','creato','date','datea','dateb','geogra','source','subjec','extent','rights','langua','tribe','identi');
        $attribs = getItemInfo($pointer,$attrib_req,0);
        $arr = array();
        $arr['pointer'] = $pointer;
        $arr['filename'] = $fn;
        $arr['height'] = (int)$dims['height'];//[0]
        $arr['width'] = (int)$dims['width'];
        if(gettype($attribs) === 'integer' && $attribs < 0) {
          printReturnNotes($returnNotes);
          return '-2 ' . (string)$attribs;
        }
        if(count($attribs) !== count($attrib_req)) {
          array_push($returnNotes,'Image ' . $pointer . ' did not get all requested item info from getItemInfo(). Ignoring.');
        } else {
          for($k = 0; $k < count($attrib_req); $k++) {
            $key = (string)$attrib_req[$k];
            $arr[$key] = outputVar($attribs[$key]);
          }
        }
        array_push($imageData, $arr);
        $arr['type'] = 'image';
        array_push($homeData, $arr);
        $status = saveImageLocal($pointer);
        if($status < 0) {
          array_push($returnNotes,'Image ' . $pointer . ' could not be saved. Error code ' . $status . ' from saveImageLocal().');
          //return '-2 ' . (string)$status;
        }
      }
      else if($collection->records[$i]->filetype === 'cpd') {
        $query = 'http://' . CDM_SERVER . '/utils/getfile/collection' . CDM_COLLECTION . '/id/' . $pointer . '/filename/' . $fn;
        $cpd = new DOMDocument;
        $r = curl($query);
        if(gettype($r) === 'integer' && $r < 0) {
          array_push($returnNotes,'Information for .cpd letter ' . $pointer . ' could not be retrieved via cURL. Skipping to next item.');
          continue;
        }
        $cpd->loadXml($r);
        //sort through the pages of cpd
        $pages = $cpd->getElementsByTagName('page');
        $letter = array();
        for($j = 0; $j < $pages->length; $j++) {
          $page_ptr = $pages->item($j)->getElementsByTagName('pageptr')->item(0)->nodeValue;
          $page_file = $pages->item($j)->getElementsByTagName('pagefile')->item(0)->nodeValue;
          $page_title = $pages->item($j)->getElementsByTagName('pagetitle')->item(0)->nodeValue;
          $page = array();
          $page['pointer'] = (int)$page_ptr;
          $page['filename'] = $page_file;
          $page['title'] = $page_title;
          $dims = getImageDimensions($page_ptr,0);
          if(gettype($dims) === 'integer' && $dims < 0) {
            array_push($returnNotes,'Dimensions for letter page ' . $page_ptr . ' could not be retrieved from getImageDimensions(). Error code from function is ' . $dims . '. Ignoring.');
          } else {
            $page['height'] = (int)$dims['height'];//[0];
            $page['width'] = (int)$dims['width'];
          }
          $attrib_req = array('relati','publis','transc','descri','media','typea','creato','date','datea','dateb','geogra','extent','rights','langua','tribe');
          $attribs = getItemInfo($page_ptr,$attrib_req,0);
          if(gettype($attribs) === 'integer' && $attribs < 0) {
            printReturnNotes($returnNotes);
            return '-4 ' . (string)$attribs;
          }
          if(count($attribs) !== count($attrib_req)) {
            array_push($returnNotes,'Letter page ' . $page_ptr . ' did not get all requested item info from getItemInfo(). Ignoring.');
          } else {
            for($k = 0; $k < count($attrib_req); $k++) {
              $key = (string)$attrib_req[$k];
              $page[$key] = outputVar($attribs[$key]);
            }
          }
          array_push($letter, $page);
          $page['type'] = 'letter';
          if($j === 0) {
            array_push($homeData, $page);
          }
          $status = saveImageLocal($page_ptr);
          if($status < 0) {
            array_push($returnNotes,'Letter page ' . $pointer . ' could not be saved. Error code ' . $status . ' from saveImageLocal().');
          }
        }
        array_push($letterData, $letter);
      }
      //get the type of $pointer and record it in $typeData
      $t = getItemInfo($pointer,array('type'),0);
      if(gettype($t) === 'integer' && $t < 0) {
        printReturnNotes($returnNotes);
        return '-5 ' . (string)$t;
      }
      if(count($t) !== 1) {
        array_push($returnNotes,'Item ' . $pointer . ' could not get \'type\' from getItemInfo() for types.json. Ignoring.');
        continue;
      }
      $arr = array();
      $arr['pointer'] = $pointer;
      $t = trim(strtolower((string)$t['type']));
      $arr['type'] = $t;
      array_push($typeData, $arr);
    }
    $path = $_SERVER['DOCUMENT_ROOT'] . REL_HOME;
    //error_log($path);
    $fp = fopen($path . DB_ROOT . DB_IMAGE, 'w');
    $arr = array();
    $arr['count'] = count($imageData);
    $arr['data'] = $imageData;
    fwrite($fp, json_encode($arr));
    fclose($fp);
    $fp = fopen($path . DB_ROOT . DB_LETTER, 'w');
    $arr = array();
    $arr['count'] = count($letterData);
    $arr['data'] = $letterData;
    fwrite($fp, json_encode($arr));
    fclose($fp);
    $fp = fopen($path . DB_ROOT . DB_HOME, 'w');
    $arr = array();
    $arr['count'] = count($homeData);
    $arr['data'] = $homeData;
    fwrite($fp, json_encode($arr));
    fclose($fp);
    $fp = fopen($path . DB_ROOT . DB_TYPES, 'w');
    $arr = array();
    $arr['count'] = count($typeData);
    $arr['data'] = $typeData;
    fwrite($fp, json_encode($arr));
    fclose($fp);
    
    printReturnNotes($returnNotes);
    return 0;
  }
  
  function printReturnNotes($notes) {
    for($i = 0; $i < count($notes); $i++) {
      echo outputVar($notes[$i]);
      echo "\n";
    }
  }
  
  echo updateSite();
  
?>