
<div class="overlay" id="overlay" style="display:none;"></div>

<?php 
for($i=1; $i<=12; $i++):?>
<div id="box<?php print $i;?>" class="box-t" data-box-index="<?php print $i?>">
<a class="boxclose btn btn-danger" id="boxclose<?php print $i;?>"><strong>Close</strong></a>
  <div id="timeline-embed-<?php print $i;?>" class="timeline-embed">
    <!--<div id="timeline"></div>-->
  </div>
</div>
<!--<a id="openTimeline<?php// print $i;?>" href="#"><h2><?php //print $i;?></h2></a>-->
<?php endfor;?>

<section class="timeline">
  <script>
    //console.log('b')
    //callbackFunc();
    //console.log('a')
  </script>
  <ul class="">
    <?php 
    for ($i=1; $i<=12; $i++):
      $filename = "ht/data/data".$i.".json";
      $data = json_decode(file_get_contents($filename),true);
      $time=$data["title"]["text"]["text"];
      $title = $data["title"]["text"]["headline"];
      ?>
      <li class="background-red">
        <div class="background-red text-white">
          <h4><time><?php print $time;?></time> <?php print $title;?></h4>
          <h4><a class="btn btn-default" id="openTimeline<?php print $i;?>" href="#" data-box-id="<?php print "box".$i;?>">
              Show Timeline
          </a></h4>
          
        </div>
      </li>
    <?php endfor;?>
  </ul>
</section>




<!-- Modal -->
<div id="hTimelineModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div class="opacity"></div>

