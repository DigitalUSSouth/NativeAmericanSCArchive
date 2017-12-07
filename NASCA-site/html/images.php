<?php
$api_dir = preg_replace('/html.images\.php/','api/',__FILE__);
include_once ($api_dir . 'configuration.php');
include_once ($api_dir . 'cdm.php');

$url = $_SERVER['DOCUMENT_ROOT'].REL_HOME.DB_ROOT.DB_IMAGE;
$jsonImageData = getJsonLocal($url);
$imagePointers = array();
if(gettype($jsonImageData) === 'integer' && $jsonImageData < 0) {
  die('There was a problem. Please check back later');
}
$jsonImageData = json_decode(json_encode($jsonImageData), true);
foreach($jsonImageData['data'] as $el) {
  $imagePointers[] = ''.$el['pointer'];
}

?>
<script>
  var imagePointers = <?php print json_encode($imagePointers); ?>;
</script>

<div class="custom-row text-dark-grey" id="featured-container">
  <div id="featured" class="source-serif">
    Images
    <div class="half-underline-black"></div>
  </div>
  <div id="custom-about-section-row" class="anton">
    <div id="custom-about-section-inner">
      <div id="custom-about-section-content" class="text-dark-grey">
        The Images section includes photographs of various South Carolina tribal entities from the early 1980s to present day. Most of these photographs were taken by noted University of South Carolina photographer Gene Crediford.
      </div>
      <div id="custom-about-section-click" class="clickable text-red">About this page</div>
    </div>
  </div>
</div>

<div class="custom-row source-serif text-dark-grey">
  <div id="select-container">
    <select id="select">
      <!--option value="indexical">All (By Index)</option-->
      <option value="alphabetical">All</option>
      <!--option value="tribal">All (By Tribe)</option-->
      <option value="catawba">Catawba Tribe</option>
      <option value="chicora">Chicora Tribe</option>
      <option value="ecsiut">ECSIUT Tribe</option>
      <option value="edisto">Edisto Tribe</option>
      <option value="peedee">Pee Dee Tribe</option>
      <option value="santee">Santee Tribe</option>
      <option value="waccamaw">Waccamaw Tribe</option>
      <option value="wassamasaw">Wassamasaw Tribe</option>
    </select>
    <div class="half-underline-black"></div>
  </div>
</div>
<div id="image-cards-flex">
  <!-- IMAGE CARDS GO HERE -->
</div>
<div id="images-loading" class="custom-row">
  <img src="<?php print SITE_ROOT; ?>/img/loadingBar.gif" alt="Loading...">
</div>