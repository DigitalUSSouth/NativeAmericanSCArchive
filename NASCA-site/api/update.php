<?php
  /*
   * When running this script it is a good idea to first delete all .jpg files from
   * db/data/images
   * and
   * db/data/letters
   * 
   * run the script by navigating in brower to serverroot/api/update.php?pw=password&image=1
   * 
   * 'image' should be 1 if you want to also pull images from cdm. This will significantly increase execution time.
   * if image is set to anything that php does not evaluate as '1', or unset, then images will not be pulled from cdm.
   * 
   * Expect the script to take a long time to execute. It will likely take over 60 seconds without images,
   * and over 20 minutes with images.
   */

  header('content-type: text/html; charset=utf-8');

  $current_dir = str_replace('update.php','',__FILE__);
  require_once($current_dir . 'configuration.php');
  require_once($current_dir . 'cdm.php');
  
  set_time_limit(0);
  
  function updateSite() {
    $returnNotes = array();
    $query = CDM_API_WEBSERVICE . 'dmQuery' . CDM_COLLECTION . '/0/fields/nosort/1024/0/0/0/0/0/0/0/json';
    $response = curl($query);
    if(gettype($response) === 'integer' && $response < 0) {
      return '-1: ContentDM could not be queried for it\'s elements. Response from cURL was: ' . (string)$response;
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
    $interviewData = array();
    $homeData = array();
    $typeData = array();
    echo '<p id="status" style="display:inline">Indexing <p id="type" style="display:inline"></p> entry #<p id="progress" style="display:inline">0</p></p><br/>';
    //flush();
    //ob_flush();
    //sleep(1);
    for($i = 0; $i < $total; $i++) {
      if($i === 0) {echo '<script>document.getElementById("type").innerHTML = "image/letter";</script>';}
      echo '<script>document.getElementById("progress").innerHTML = ' . ($i+1) . ';</script>';
      flush();
      ob_flush();
      sleep(1);
      $rec = $collection->records[$i];
      $pointer = (int)$rec->pointer;
      $fn = $rec->find;
      $filetype = $rec->filetype;
      $type = getItemInfo($pointer,array('type'),0);
      if(gettype($type) === 'integer' && $type < 0) {
        array_push($returnNotes,'Item info of cdm entry ' . $pointer . ' of filetype ' . $filetype . ', could not be received. Output from getItemInfo() is error code ' . $type . '. Check error log for more information on given pointer. Skipping to next entry.');
        continue;
      } else if(gettype($type) === 'array' && count($type) === 0) {
        array_push($returnNotes,'Type field of cdm entry ' . $pointer . ' of filetype ' . $filetype . ', is either empty or nonexistent. Can\'t do anything with entry without valid type. Skipping to next entry.');
        continue;
      }
      $type = strtolower(trim((string)$type['type']));
      if($type !== 'letter' && $type !== 'image') {
        array_push($returnNotes,'Type field of cdm entry ' . $pointer . ' of filetype ' . $filetype . ', is not \'letter\' or \'image\'. Can\'t do anything with entry without valid type. Skipping to next entry.');
        continue;
      }
      if($filetype === 'jp2') {
        //save images if it is included in the script arguments
        if(isset($_GET['image']) && $_GET['image'] == 1) {
          $status = saveImageLocal($pointer);
          if($status < 0) {
            array_push($returnNotes,'Image for cdm entry ' . $pointer . ' could not be saved. Output from saveImageLocal() is error code ' . $status . '. Check error log for more information on given pointer. Skipping to next entry');
            continue;
          }
        }
        $dims = getImageDimensions($pointer,0);
        if(gettype($dims) === 'integer' && $dims < 0) {
          //getImageDimensions returned an error
          //if we can't get the image's dimensions, then we can't properly query (and download) the image itself.
          //we may be able to get the title and other metadata but it's really useless without the image so we will omit
          //these entries until the cause of the bad dimensions pull can be fixed.
          array_push($returnNotes,'Image dimensions of cdm entry ' . $pointer . ' of filetype jp2, could not be received. Output from getImageDimensions() is error code ' . $dims . '. Check error log for more information on given pointer. Skipping to next entry.');
          continue;
        }
        $attrib_req = array('title','relati','publis','descri','creato','date','datea','dateb','geogra','extent','rights','langua','tribe');
        //attributes that we want for each entry
        if($type === 'letter') {
          array_push($attrib_req,'transc');
        }
        $attribs = getItemInfo($pointer,$attrib_req,0);
        $arr = array();
        $arr['pointer'] = $pointer;
        $arr['filename'] = $fn;
        //at this point $dims is guaranteed to be populated with something valid
        $arr['height'] = (int)$dims['height'];//[0]
        $arr['width'] = (int)$dims['width'];
        $arr['type'] = $type;
        //check if attribs returned something valid
        if(gettype($attribs) === 'integer' && $attribs < 0) {
          //in this case getItemInfo returned an error
          array_push($returnNotes,'Item info of cdm entry ' . $pointer . ' of filetype ' . $filetype . ', could not be received. Output from getItemInfo() is error code ' . $type . '. Check error log for more information on given pointer. Skipping to next entry.');
          continue;
        }
        if(count($attribs) !== count($attrib_req)) {
          array_push($returnNotes,'Item ' . $pointer . ' of filetype ' . $filetype . ' did not get all requested item info from getItemInfo(). Ignoring requested info but saving remainder.');
          //try to at least get title via alternate method
          //if we can we'll write the object to data, if we can't we'll completely skip it
          //no good without at least a title
          $title = getImageTitle($pointer,0);
          if(gettype($title) === 'integer' && $title < 0) {
            //title returned an error
            array_push($returnNotes,'Couldn\'t get title of item ' . $pointer . ' of filetype ' . $filetype . ', with getItemInfo() OR getImageTitle(). Check error log for more information on given pointer. Skipping to next entry');
            continue;
          }
          //in this case $title has something valid
          $arr['title'] = $title;
        } else {
          //the lengths ARE equal
          for($k = 0; $k < count($attrib_req); $k++) {
            $key = (string)$attrib_req[$k];
            $arr[$key] = outputVar($attribs[$key]);
          }
        }
        if($type === 'image') {
          array_push($imageData, $arr);
        } else if($type === 'letter') {
          //in this case, this single jp2 file must be a one-paged letter
          array_push($letterData, array($arr));
        }
        array_push($homeData, $arr);
        $arr = array();
        $arr['pointer'] = $pointer;
        $arr['type'] = $type;
        array_push($typeData, $arr);
      }
      else if($filetype === 'cpd') {
        //we have to assume that if it's a cpd, it must be a letter
        //but if in this case the type is image, which is the only
        //error that could possibly happen by this point in the script,
        //we have to call it out and skip the entry
        if($type === 'image') {
          array_push($returnNotes,'The \'type\' of cdm entry ' . $pointer . ' of filetype ' . $filetype . ', is an image. Script doesn\'t know what to do with cpd\'s that are not letters. Skipping to next entry.');
          continue;
        }
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
          $page_ptr = (int)$pages->item($j)->getElementsByTagName('pageptr')->item(0)->nodeValue;
          //save images if it is included in the script arguments
          if(isset($_GET['image']) && $_GET['image'] == 1) {
            $status = saveImageLocal($page_ptr);
            if($status < 0) {
              array_push($returnNotes,'Letter page ' . $page_ptr . ' for cdm entry ' . $pointer . ' could not be saved. Output from saveImageLocal() is error code ' . $status . '. Check error log for more information on given pointer. Skipping to next entry');
              continue;
            }
          }
          $page_file = (string)$pages->item($j)->getElementsByTagName('pagefile')->item(0)->nodeValue;
          $page_title = (string)$pages->item($j)->getElementsByTagName('pagetitle')->item(0)->nodeValue;
          $page = array();
          $page['pointer'] = (int)$page_ptr;
          $page['filename'] = $page_file;
          $page['title'] = $page_title;
          $page['type'] = $type;
          $dims = getImageDimensions($page_ptr,0);
          if(gettype($dims) === 'integer' && $dims < 0) {
            array_push($returnNotes,'Dimensions for letter page ' . $page_ptr . ' could not be retrieved from getImageDimensions(). Error code from function is ' . $dims . '. Skipping to next entry.');
            continue;
          }
          $page['height'] = (int)$dims['height'];//[0];
          $page['width'] = (int)$dims['width'];
          $attrib_req = array('relati','publis','transc','descri','creato','date','datea','dateb','geogra','extent','rights','langua','tribe');
          $attribs = getItemInfo($page_ptr,$attrib_req,0);
          if(gettype($attribs) === 'integer' && $attribs < 0) {
            //in this case getItemInfo returned an error
            array_push($returnNotes,'Item info of letter page ' . $page_ptr . ' could not be received. Output from getItemInfo() is error code ' . $attribs . '. Check error log for more information on given pointer. Skipping to next entry.');
            continue;
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
          if($j === 0) {
            array_push($homeData, $page);
          }
          $arr = array();
          $arr['pointer'] = $page_ptr;
          $arr['type'] = $type;
          array_push($typeData, $arr);
        }
        array_push($letterData, $letter);
      } else {
        array_push($returnNotes,'The filetype of cdm entry ' . $pointer . ' was not recognized (' . $rec->filetype . '). Skipping.');
      }
      //get the type of $pointer and record it in $typeData
      //$t = getItemInfo($pointer,array('type'),0);
      //if(gettype($t) === 'integer' && $t < 0) {
      //  printReturnNotes($returnNotes);
      //  return '-5 ' . (string)$t;
      //}
      //if(count($t) !== 1) {
      //  array_push($returnNotes,'Item ' . $pointer . ' could not get \'type\' from getItemInfo() for types.json. Ignoring.');
      //  continue;
      //}
    }
    //by now all cdm entries have been indexed.
    //now to index videos in home/data.json
    $query = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . DB_VIDEO;
    $json = getJsonLocal($query);
    for($i = 0; $i < $json->count; $i++) {
      if($i === 0) {echo '<script>document.getElementById("type").innerHTML = "video";</script>';}
      echo '<script>document.getElementById("progress").innerHTML = ' . ($i+1) . ';</script>';
      flush();
      ob_flush();
      sleep(1);
      $obj = $json->data[$i];
      $arr = array();
      $arr['pointer'] = $obj->pointer;
      $arr['type'] = 'video';
      array_push($typeData,$arr);
      $arr['title'] = $obj->title;
      $arr['description'] = $obj->description;
      $arr['key'] = $obj->key;
      array_push($homeData,$arr);
    }
    
    //no to index interviews in interviews/tabs.json
    $query = $_SERVER['DOCUMENT_ROOT'] . REL_HOME . DB_ROOT . '/interviews/tabs.json';
    $json = getJsonLocal($query,true);
    $lastIndex = 0;
    $index_root = '2000';
    foreach($json as $tribe_section) {
      foreach($tribe_section['interviews'] as $transcript=>$interview) {
        if($lastIndex === 0) {echo '<script>document.getElementById("type").innerHTML = "interview";</script>';}
        echo '<script>document.getElementById("progress").innerHTML = ' . ($lastIndex+1) . ';</script>';
        flush();
        ob_flush();
        sleep(1);
        $arr = array();
        $arr['pointer'] = (int)($index_root.$lastIndex++);
        $arr['type'] = 'interview';
        array_push($typeData,$arr);
        $arr['title'] = $interview;
        $arr['script_file'] = $transcript;
        $arr['tribe'] = $tribe_section['tribe'];
        $arr['ref'] = $tribe_section['logo'];
        $arr['href'] = $tribe_section['href'];
        array_push($interviewData,$arr);
        array_push($homeData,$arr);
      }
    }
    
    $path = $_SERVER['DOCUMENT_ROOT'] . REL_HOME;
    //error_log($path);
    $fp = fopen($path . DB_ROOT . DB_IMAGE, 'w');
    $arr = array();
    $arr['count'] = count($imageData);
    $arr['data'] = $imageData;
    if(fwrite($fp, json_encode($arr))===FALSE){
      array_push($returnNotes,'Couldn\'t write to ' . $path . DB_ROOT . DB_IMAGE);
    }
    fclose($fp);
    $fp = fopen($path . DB_ROOT . DB_LETTER, 'w');
    $arr = array();
    $arr['count'] = count($letterData);
    $arr['data'] = $letterData;
    if(fwrite($fp, json_encode($arr))===FALSE){
      array_push($returnNotes,'Couldn\'t write to ' . $path . DB_ROOT . DB_LETTER);
    }
    fclose($fp);
    $fp = fopen($path . DB_ROOT . DB_INTERVIEW, 'w');
    $arr = array();
    $arr['count'] = count($interviewData);
    $arr['data'] = $interviewData;
    if(fwrite($fp, json_encode($arr))===FALSE){
      array_push($returnNotes,'Couldn\'t write to ' . $path . DB_ROOT . DB_INTERVIEW);
    }
    fclose($fp);
    $fp = fopen($path . DB_ROOT . DB_HOME, 'w');
    $arr = array();
    $arr['count'] = count($homeData);
    $arr['data'] = $homeData;
    if(fwrite($fp, json_encode($arr))===FALSE){
      array_push($returnNotes,'Couldn\'t write to ' . $path . DB_ROOT . DB_HOME);
    }
    fclose($fp);
    $fp = fopen($path . DB_ROOT . DB_TYPES, 'w');
    $arr = array();
    $arr['count'] = count($typeData);
    $arr['data'] = $typeData;
    if(fwrite($fp, json_encode($arr))===FALSE){
      array_push($returnNotes,'Couldn\'t write to ' . $path . DB_ROOT . DB_TYPES);
    }
    fclose($fp);
    
    printReturnNotes($returnNotes);
    $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
    echo "Processing Time: {$time} seconds<br>";
    return 0;
  }
  
  function printReturnNotes($notes) {
    for($i = 0; $i < count($notes); $i++) {
      echo outputVar($notes[$i]);
      echo '<br>';
    }
  }
  
  if(isset($_GET['pw']) && $_GET['pw'] === UPDATE_PW) {
    echo updateSite();
  } else {
    echo 'You do not have sufficient permissions.';
  }
  
  
?>