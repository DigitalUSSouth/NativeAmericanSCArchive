<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
  <head>
    <title>TODO supply a title</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body style="width: 100%">
    <div id="flex-container" style="position: relative; width: 100%; height: auto; display: flex; flex-wrap: wrap;">
    <?php
    for($i = 0; $i < 12; $i++) {
      ?>
      <div class="card-container" style="position: relative; width: 25%; min-width: 150px; height: 150px; background: green; overflow: hidden; display: flex; flex-direction: column; flex-wrap: nowrap;">
        <div class="card-content" style="position: relative; width: 100% !important; height: 150px !important; background: #003eff;">
          hello lovebird
        </div>
        <div class="card-expand-container" style="position: relative; width: 100% !important; height: 250px !important; background: grey; opacity: 0.6;">
          you'll never catch mee fuckerrrrrs
        </div>
      </div>
      <?php
    }
    ?>
    </div>
  </body>
</html>