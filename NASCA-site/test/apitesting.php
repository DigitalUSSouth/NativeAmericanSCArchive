<?php
  require_once '../api/configuration.php';
  include_once '../api/cdm.php';
  
  //print_r($_SERVER);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>API Testing</title>
</head>
<body>
  <!--div id='text'>
    Hello World
  </div-->
  <div id="typetest">
    <?php
      $image = getImageReference(1541,'full',1);
      echo outputVar($image);
    ?>
  </div>
  <script type='text/javascript' src='../js/jquery/jquery-3.2.1.min.js'></script>
  <script type='text/javascript' src='../api/globals.js'></script>
  <script type='text/javascript'>
    $(document).ready(function() {
      setGlobals();
      //document.getElementById('text').innerHTML = CDM_SERVER;
    });
  </script>
</body>
</html>