<?php
  $current_dir = str_replace('update.php','',__FILE__);
  require_once($current_dir . 'configuration.php');
  require_once($current_dir . 'cdm.php');
  
  set_time_limit(0);
  
  function updateImagesLetters() {
    $query = 'http://digital.tcl.sc.edu:81/dmwebservices/index.php?q=dmQuery/nasca/0/fields/nosort/1024/0/0/0/0/0/0/0/json';
    $collection = json_decode(file_get_contents($query));
    $total = $collection->pager->total;
    $letterData = array();
    $imageData = array();
    $homeData = array();
    for($i = 0; $i < $total; $i++) {
      if($collection->records[$i]->filetype === 'jp2') {
        $rec = $collection->records[$i];
        $title = (string) getImageTitle($rec->pointer);
        $arr = array();
        $arr['pointer'] = $rec->pointer;
        $arr['filename'] = $rec->find;
        $arr['title'] = $title;
        array_push($imageData, $arr);
        $arr['type'] = 'images';
        array_push($homeData, $arr);
      }
    }
    for($i = 0; $i < $total; $i++) {
      if($collection->records[$i]->filetype === 'cpd') {
        $pointer = $collection->records[$i]->pointer;
        $find = $collection->records[$i]->find;
        $query = 'digital.tcl.sc.edu/utils/getfile/collection' . CDM_COLLECTION . '/id/' . $pointer . '/filename/' . $find;
        $cpd = new DOMDocument;
        $cpd->loadXml(curl($query));
        //sort through the pages of cpd, add their pointers to the omit list
        $pages = $cpd->getElementsByTagName('page');
        $letter = array();
        for($j = 0; $j < $pages->length; $j++) {
          $page_ptr = $pages->item($j)->getElementsByTagName('pageptr')->item(0)->nodeValue;
          $page_file = $pages->item($j)->getElementsByTagName('pagefile')->item(0)->nodeValue;
          $page_title = $pages->item($j)->getElementsByTagName('pagetitle')->item(0)->nodeValue;
          $page = array();
          $page['pointer'] = $page_ptr;
          $page['filename'] = $page_file;
          $page['title'] = $page_title;
          array_push($letter, $page);
          $page['type'] = 'letters';
          if($j === 0) {
            array_push($homeData, $page);
          }
        }
        array_push($letterData, $letter);
      }
    }
    $path = $_SERVER['DOCUMENT_ROOT'] . REL_HOME;
    $fp = fopen($path . '/db/data/images/data.json', 'w');
    $arr = array();
    $arr['count'] = count($imageData);
    $arr['data'] = $imageData;
    fwrite($fp, json_encode($arr));
    fclose($fp);
    $fp = fopen($path . '/db/data/letters/data.json', 'w');
    $arr = array();
    $arr['count'] = count($letterData);
    $arr['data'] = $letterData;
    fwrite($fp, json_encode($arr));
    fclose($fp);
    $fp = fopen($path . '/db/data/home/data.json', 'w');
    $arr = array();
    $arr['count'] = count($homeData);
    $arr['data'] = $homeData;
    fwrite($fp, json_encode($arr));
    fclose($fp);
  }
    
  updateImagesLetters();
  
?>