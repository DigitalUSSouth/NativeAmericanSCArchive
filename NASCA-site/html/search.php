<?php require_once "../api/configuration.php";?>
<?php
//var_dump($_GET);
global $queryString;
global $start;
$queryString = isset($_GET['1']) ? $_GET['1']:"";
$start = isset($_GET['2']) ? $_GET['2']: "0";
/*$_GET = array(
  "start"=> 0,
  "s"=>""
);*/

function closetags($html) {
  preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
  $openedtags = $result[1];
  preg_match_all('#</([a-z]+)>#iU', $html, $result);
  $closedtags = $result[1];
  $len_opened = count($openedtags);
  if (count($closedtags) == $len_opened) {
    return $html;
  }
  $openedtags = array_reverse($openedtags);
  for ($i=0; $i < $len_opened; $i++) {
    if (!in_array($openedtags[$i], $closedtags)) {
      $html .= '</'.$openedtags[$i].'>';
    } else {
      unset($closedtags[array_search($openedtags[$i], $closedtags)]);
    }
  }
  return $html;
} 

function getExcerpt($text){
  $text = substr($text,0,300);
  return closetags($text);
}

function buildNavQuery($nextPrev){
	global $start;
	global $queryString;
	$uri = '/search/'.$queryString.'/';
	$newStart = "";
   if ($nextPrev == 'prev'){
     $newStart = (string)($start-20);
}
     else if ($nextPrev == 'next'){
     $newStart = (string)($start+20);
	}
	return $uri.$newStart;
    }
    ?>

  <section id="primary" class="site-content">
  <div id="content" role="main">
  <?php
    //var_dump($_GET); 
    //$start = (isset($_GET['start']))? $_GET['start'] : 0;
    $query = 'https://test.digitalussouth.org/api?q='.urlencode($queryString).'&start='.urlencode($start).'&fq[]="Native+American+South+Carolina+Archive"&fq_field[]=archive_facet';
    $searchResultsJson = file_get_contents($query);
    //print $query;
    $searchResults = json_decode($searchResultsJson,true);
    //var_dump($searchResults);
    $haveResults = true;
    
    if ($searchResults['error']!='None') $haveResults = false;
    
    if ($searchResults['response']['numFound']==0) $haveResults = false;
    $numFound = $searchResults['response']['numFound'];
    $rows = 20;


  if ( $haveResults ): ?>
    <h2>Showing results for "<?php print $queryString; ?>" <br><?php print ($start+1)?> to <?php print ($numFound<=$start+$rows ) ?($numFound):($start+$rows );?> of <?php print ($numFound)?>
	<?php
	if ($start>0):?>
    <span> <a class="text-red" href="<?php print SITE_ROOT.buildNavQuery('prev');?>"> Previous </a> </span>
    <?php endif;?>
	<?php if ($numFound>$start+20):?>
    <span> <a class="text-red" href="<?php print SITE_ROOT.buildNavQuery('next');?>"> Next </a> </span>
    <?php endif;?>
    </h2>
    <?php
   $docs = $searchResults['response']['docs'];
    foreach ($docs as $doc) :
      $fa = ' question';
      if(preg_match('/\/images\//',$doc['url'])){
        $fa = 'fa-picture-o';
      }
      if(preg_match('/\/interviews\//',$doc['url'])){
        $fa = 'fa-volume-up';
      }
      if(preg_match('/\/letters\//',$doc['url'])){
        $fa = 'fa-envelope';
      }
      if(preg_match('/\/video\//',$doc['url'])){
        $fa = 'fa-video-camera';
      }
      if(preg_match('/\/map\//',$doc['url'])){
        $fa = 'fa-map-o';
      }
      if(preg_match('/\/timeline\//',$doc['url'])){
        $fa = 'fa-calendar';
      }
      if(preg_match('/\/tribes\//',$doc['url'])){
        $fa = 'fa-file-text-o';
      }
    ?>
    <div class="col-xs-1">
    <i class="fa <?php print $fa ;?>" style="font-size:64px;padding-top:.7em;"></i>
    </div>
    <div class="col-xs-9">
      <a class="text-red" href="<?php print $doc['url'];?>"><h1><?php print $doc['title']?></h1></a>
      <p><big><?php print getExcerpt(strip_tags($doc['full_text']));?>... <a class="text-red" href="<?php print $doc['url'];?>">Read more</a></big></p>
    </div>
    <?php if ($fa=="fa-picture-o" || $fa=="fa-envelope" || $fa=="fa-file-text-o" || $fa=="fa-video-camera"):?>
    <div class="col-xs-2">
      <div class="panel" style="padding:1.5em;margin-top:.5em;"><img class="img-responsive center-block" src="<?php print $doc['thumbnail_url'];?>" style="max-height:10em;"></div>
    </div>
    <?php endif;?>
    <div class="col-xs-12"><hr></div>
  <?php endforeach;

    ?>
  <?php else : //have results ?>

    <article id="post-0" class="post no-results not-found">
    <header class="entry-header">
      <h1 class="entry-title">No results</h1>
    </header>

    <div class="entry-content">
      <p>no results</p>
      <?php //get_search_form(); ?>
    </div><!-- .entry-content -->
   </article><!-- #post-0 -->

  <?php endif; ?>

  </div><!-- #content -->
  </section>