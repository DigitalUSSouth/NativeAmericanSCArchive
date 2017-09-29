<?php
/*ugly script to update the json file in 
  this dir with the new images provided 
  at the last minute by the client :/
*/
require_once "../../../api/configuration.php";
//header("Content-Type: application/json");
//print '<pre>';
for ($i=1; $i<=12; $i++){
  //print $i.'<br>';
  $filename = "data".$i.".json";
  $data = json_decode(file_get_contents($filename),true);
  $imageDataJson = file_get_contents("update-timelines.json");
  $imageData = json_decode($imageDataJson,true);
//var_dump($imageData);
  foreach ($imageData[$i-1] as $slideImages){
    //var_dump($slideImages);
    $eventIndex = (int)$slideImages['pos']-1;  
    $outString = '';
    foreach ($slideImages['img'] as $path => $desc){
      $thumbPath = SITE_ROOT.'/db/data/timelines/timeline-img-300/'.$path.'thumb.jpg';
      $fullPath = SITE_ROOT.'/db/data/timelines/timeline-img-large/'.$path.'large.jpg';
      $outString = $outString . '<div class="col-xs-6"><img src="'.$thumbPath.'"><br><a target="_blank" href="'.$fullPath.'">View larger</a></div>';
    }
    //print htmlspecialchars($outString).'<br>';
    //print ($outString).'<br>';
    $newEvent = $data['events'][$eventIndex];
    $newEvent['text']['text'] = $newEvent['text']['text'].$outString;
    $data['events'][$eventIndex] = $newEvent;
    //var_dump($newEvent);
  }
  $newData = json_encode($data);
  //print($newData);
  file_put_contents($filename,$newData);
}  
//print '</pre>';