<?php
  require_once '../api/configuration.php';
  include_once '../api/cdm.php';
  
  print_r($_SERVER);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>API Testing</title>
</head>
<body>
  <div id='text'>
    Hello World
  </div>
  <div id='apiversion'>
    API VERSION IS <?php getApiVersion('xml'); ?>
  </div>
  <script type='text/javascript' src='../js/jquery/jquery-3.1.1.min.js'></script>
  <script type='text/javascript' src='../api/globals.js'></script>
  <script type='text/javascript'>
    $(document).ready(function() {
      setGlobals();
      document.getElementById('text').innerHTML = CDM_SERVER;
    });
  </script>
</body>
</html>