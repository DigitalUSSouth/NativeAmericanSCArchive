<?php
echo '<div class="book" id="home_book">';
echo '  <div class="col" id="home_left">';
for($i = 1; $i <= 6; $i++) {
  echo '<div class="home_card" id="home_card_' . $i . '">';
  echo '  <img src="img/native_' . $i . '.jpg">';
  echo '  <h1>Sample Title ' . $i . '</h1>';
  echo '  <div class="readmore">';
  echo '    <a href="img/native_' . $i . '.jpg" data-lightbox="featured" data-title="native ' . $i . '" onclick="">READ MORE</a>';
  echo '</div></div>';
}
echo '<div class="col" id="home_right">';
echo '</div></div>';
?>