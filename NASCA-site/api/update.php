<?php
  $current_dir = str_replace('update.php','',__FILE__);
  require_once($current_dir . 'configuration.php');
  
  function getImages() {
    $query = 'http://digital.tcl.sc.edu:81/dmwebservices/index.php?q=dmQuery/nasca/0/fields/nosort/1024/0/0/0/0/0/0/0/json';
    $collection = json_decode(file_get_contents($query));
    $total = $collection->pager->total;
    $omit_list = array();
    for($i = 0; $i < $total; $i++) {
      if($collection->records[$i]->filetype === 'cpd') {
        $pointer = $collection->records[$i]->pointer;
        $find = $collection->records[$i]->find;
        $query = 'http://digital.tcl.sc.edu/utils/collection/nasca/id/' . $pointer . '/filename/' . $find;
        //echo $pointer;
        //echo $find;
        
        $data = parse_url($query);
        print_r($data);
        
        break;
      }
    }
  }
  
  getImages();
?>