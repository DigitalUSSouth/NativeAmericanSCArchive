<?php
require_once "../api/configuration.php";

$jsonImageData = file_get_contents(SITE_ROOT."/db/data/images/data.json");
$rawImageData = json_decode($jsonImageData,true);
$imageData = array();
$imagePointers = array();
foreach ($rawImageData['data'] as $rawItem){
  $imgPathThumb = "../db/data/images/".$rawItem['pointer']."_thumbnail.jpg";
  $imgPathLarge = "../db/data/images/".$rawItem['pointer']."_large.jpg";
  if (!file_exists($imgPathThumb) || !file_exists($imgPathLarge)) continue;
  $imageData[] = $rawItem;  
  $imageDataJs[$rawItem['pointer']] = $rawItem;
  $imagePointers[] = ''.$rawItem['pointer'];
}

//print '<pre>';
//var_dump($imageData);
//print '</pre>';
?>
<script>
  var images = <?php print json_encode($imageDataJs);?>;
  var imagePointers = <?php print json_encode($imagePointers);?>;
</script>
<h1 class="text-red">Images</h1>

<div class="row">
  <?php
    $counter = 1;
    $totalSize = sizeof($imageData);
    $col1 = intdiv($totalSize,4);
    $col2 = intdiv($totalSize,4);
    $col3 = intdiv($totalSize,4);
    $col4 = intdiv($totalSize,4);
    $mod = $totalSize%4;
    if ($mod==3){
      $col1++;
      $col2++;
      $col3++;
    }
    else if ($mod==2){
      $col1++;
      $col2++;
    }
    else if ($mod==1){
      $col2++;
    }
    print '<div class="col-xs-3 images-col">';
    for ($i=0; $i<$col1; $i++):
      $image = $imageData[$i];?>
    <div class="btn col-xs-12 images-div" data-toggle="modal" data-target="#imagesModal" data-pointer="<?php print $image['pointer'];?>">
      <img class="images-img img-responsive" src="<?php print SITE_ROOT.'/db/data/images/'.$image['pointer'].'_thumbnail.jpg';?>"><strong><?php print $image['title'];?></strong>
    </div>
  <?php
    endfor;
    print '</div>';
    print '<div class="col-xs-3 images-col">';
    for ($i=$col1; $i<$col1+$col2; $i++):
      $image = $imageData[$i];?>
    <div class="btn col-xs-12 images-div" data-toggle="modal" data-target="#imagesModal" data-pointer="<?php print $image['pointer'];?>">
      <img class="images-img img-responsive" src="<?php print SITE_ROOT.'/db/data/images/'.$image['pointer'].'_thumbnail.jpg';?>"><strong><?php print $image['title'];?></strong>
    </div>
  <?php
    endfor;
    print '</div>';
    print '<div class="col-xs-3 images-col">';
    for ($i=$col1+$col2; $i<$col1+$col2+$col3; $i++):
      $image = $imageData[$i];?>
    <div class="btn col-xs-12 images-div" data-toggle="modal" data-target="#imagesModal" data-pointer="<?php print $image['pointer'];?>">
      <img class="images-img img-responsive" src="<?php print SITE_ROOT.'/db/data/images/'.$image['pointer'].'_thumbnail.jpg';?>"><strong><?php print $image['title'];?></strong>
    </div>
  <?php
    endfor;
    print '</div>';
    print '<div class="col-xs-3 images-col">';
    for ($i=$col1+$col2+$col3; $i<$totalSize; $i++):
      $image = $imageData[$i];?>
    <div class="btn col-xs-12 images-div" data-toggle="modal" data-target="#imagesModal" data-pointer="<?php print $image['pointer'];?>">
      <img class="images-img img-responsive" src="<?php print SITE_ROOT.'/db/data/images/'.$image['pointer'].'_thumbnail.jpg';?>"><strong><?php print $image['title'];?></strong>
    </div>
  <?php
    endfor;
    print '</div>';
    ?>
</div>

<!-- Modal -->
<div id="imagesModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="modal-title"></div>
      </div>
      <div class="modal-body">
        <img id="imagesImg" class="img-responsive">
        <div class="modal-foot"></div>
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>