<?php
$api_dir = preg_replace('/html.images\.php/','api/',__FILE__);
include_once ($api_dir . 'configuration.php');
?>
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