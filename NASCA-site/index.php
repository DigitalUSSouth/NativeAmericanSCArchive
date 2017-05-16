<?php
  require_once 'api/configuration.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    
    <title>NASCA</title>
    
    <!-- metadata tags here -->
    <meta name="description" content="Native American South Carolina Archive"/>
    <meta name="author" content="Matthew Jendrasiak"/>
    
    <!-- minified jquery, jplayer, bootstrap, etcetera -->
    <script type="text/javascript" src="js/jquery/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="js/jquery/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/lightbox.js"></script>
    <script type="text/javascript" src="js/jquery/jquery.jplayer.min.js"></script>
    <script type="text/javascript" src="js/modal.js"></script>
    
    <!-- CSS & BootStrap -->
    <link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui/jquery-ui.min.css"/>
    <!--link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.structure.min.css"/>
    <link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.theme.min.css"/-->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/lightbox.css"/>
    <link rel="stylesheet" type="text/css" href="css/index.css"/>
    <link rel="stylesheet" type="text/css" href="css/jplayer.blue.monday.css"/>
    <link rel="stylesheet" type="text/css" href="css/modal.css"/>
    <link rel="stylesheet" type="text/css" href="css/interviews.css"/>
    <link rel="stylesheet" type="text/css" href="css/home.css"/>
    <link rel="stylesheet" type="text/css" href="css/timeline.css"/>
    <link rel="stylesheet" type="text/css" href="css/images.css"/>
    
    <!-- ICON -->
    <link rel="icon" type="image/x-icon" href="img/favicon.ico"/>

    <!-- Local Javascript, Jquery, Ajax -->
    <script type="text/javascript" src="api/xmlhttp.js"></script>
    <script type="text/javascript" src="api/json.js"></script>
    <script type="text/javascript" src="api/globals.js"></script>
    <script type="text/javascript" src="js/index/fade.js"></script>
    <script type="text/javascript" src="js/index/index.js"></script>
    <script type="text/javascript" src="js/index/interview.js"></script>
    <script type="text/javascript" src="js/index/home.js"></script>
    <script type="text/javascript" src="js/index/timeline.js"></script>
  </head>
  <body>
    <?php
      include_once 'html/modal.php';
    ?>
    <div id="body-container">
      <div id="header-positioner">
        <div id="header-container">
          <div id="header">
            <div id="logo">
              <a href="index.php"><img src="img/NASCA_logo.png"></a>
            </div>
            <div id="nav-bar">
              <ul id="tabs">
                <li><a id="tabs-home" href="#" onclick="changePage('home')">HOME</a></li>
                <li><a id="tabs-interviews" href="#" onclick="changePage('interviews')">INTERVIEWS</a></li>
                <li><a id="tabs-letters" href="#" onclick="changePage('letters')">LETTERS</a></li>
                <li><a id="tabs-images" href="#" onclick="changePage('images')">IMAGES</a></li>
                <li><a id="tabs-video" href="#" onclick="changePage('video')">VIDEO</a></li>
                <li><a id="tabs-map" href="#" onclick="changePage('map')">MAP</a></li>
                <li><a id="tabs-timeline" href="#" onclick="changePage('timeline')">TIMELINE</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div id="header-positioner-height-offset"></div>
      <div id="page-container">
        <div id="page">
          The page failed to load it's content.
        </div>
      </div>
    </div>
    <div id="footer-container">
      <div id="footer">
        <div id="footer-links-container">
          <ul id="footer-links">
            <li id="footer-links-about" class="footer-link">
              <a href="html/about.html">About</a>
            </li>
            <li id="footer-links-credits" class="footer-link">
              <a href="html/credits.html">Credits</a>
            </li>
            <li id="footer-links-dev-resources" class="footer-link">
              <a href="html/dev_resources.html">Developer Resources</a>
            </li>
          </ul>
        </div>
        <div id="copyright-container">
          <div id="copyright">Native American South Carolina Archive (NASCA) &copy; 2016</div>
        </div>
      </div>
    </div>
    <!-- Local Javascript, Jquery, Ajax -->
    <script type="text/javascript">
      $(document).ready(function() {
        init_index();
      });
    </script>
  </body>
  
</html>