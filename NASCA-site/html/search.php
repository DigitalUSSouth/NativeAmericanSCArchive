<h1 class="text-red">Search results</h1>

<?php
var_dump($_GET);

$queryString = isset($_GET['1']) ? $_GET['1']:"";
$start = isset($_GET['2']) ? $_GET['2']: 0;
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
				    if ($nextPrev == 'prev'){
				        $oldQuery = $_GET;
		                $oldQuery['start'] = $oldQuery['start']-20;
		                $newQuery = http_build_query($oldQuery);
		                return '/sce/?'.$newQuery;
				    }
				    else if ($nextPrev == 'next'){
				        $oldQuery = $_GET;
	                	$oldQuery['start'] = $oldQuery['start']+20;
		                $newQuery = http_build_query($oldQuery);
                		return '/sce/?'.$newQuery;
				    }
				}
				?>

	<section id="primary" class="site-content">
		<div id="content" role="main">
		<?php
		    //var_dump($_GET); 
		    //$start = (isset($_GET['start']))? $_GET['start'] : 0;
		    $query = 'http://www.digitalussouth.org/api?q='.urlencode($queryString).'&start='.urlencode($start).'&fq[]="South+Carolina+Encyclopedia"&fq_field[]=archive_facet';
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
				<h2>Showing results <?php print ($start+1)?> to <?php print ($numFound<=$start+$rows ) ?($numFound):($start+$rows );?> of <?php print ($numFound)?>
				<?php if ($start>0):?>
				<span> <a class="text-red" href="<?php print buildNavQuery('prev');?>"> Previous </a> </span>
				<?php endif;?>
				<?php if ($numFound>$start+20):?>
				<span> <a class="text-red" href="<?php print buildNavQuery('next');?>"> Next </a> </span>
				<?php endif;?>
        </h2>
        <?php
			$docs = $searchResults['response']['docs'];
			
			$posts = array();
			foreach ($docs as $doc) :?>
          <div class="col-xs-11 col-xs-offset-1">
            <a class="text-red" href="<?php print $doc['url'];?>"><h1><?php print $doc['title']?></h1></a>
            <p><big><?php print getExcerpt($doc['full_text']);?>... <a class="text-red" href="<?php print $doc['url'];?>">Read more</a></big></p>
          </div>

    <?php endforeach;

			?>

			<?php //twentytwelve_content_nav( 'nav-below' ); ?>

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