<?php
  require_once 'api/configuration.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    
    <title>NASCA</title>
    
    <!-- metadata tags here -->
    <meta name="description" content="Native American South Carolina Archive"/>
    <meta name="author" content="Matthew Jendrasiak"/>
    
    <!-- minified jquery, jplayer, bootstrap, etcetera -->
    <script type="text/javascript" src="js/jquery/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/jquery/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery/jquery.jplayer.min.js"></script>
    <script type="text/javascript" src="js/jquery/jquery.fancybox.min.js"></script>
    <script type="text/javascript" src="js/modernizr-custom.js"></script>
    <script type="text/javascript" src="js/modal.js"></script>
    
    <!-- CSS & BootStrap -->
    <link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui/jquery-ui.min.css"/>
    <!--link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.structure.min.css"/>
    <link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.theme.min.css"/-->
    <link rel="stylesheet" type="text/css" href="css/jquery.fancybox.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/index.css"/>
    <link rel="stylesheet" type="text/css" href="css/jplayer.blue.monday.css"/>
    <link rel="stylesheet" type="text/css" href="css/modal.css"/>
    <link rel="stylesheet" type="text/css" href="css/interviews.css"/>
    <link rel="stylesheet" type="text/css" href="css/home.css"/>
    <link rel="stylesheet" type="text/css" href="css/timeline.css"/>
    <link rel="stylesheet" type="text/css" href="css/images.css"/>
    <link rel="stylesheet" type="text/css" href="css/tribes.css"/>
    <link rel="stylesheet" type="text/css" href="css/map.css"/>
    <link rel="stylesheet" type="text/css" href="css/video.css"/>
    
    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Anton|Source+Serif+Pro" rel="stylesheet">
    
    
    <!-- ICON -->
    <link rel="icon" type="image/x-icon" href="img/favicon/favicon.ico"/>

    <!-- Local Javascript, Jquery, Ajax -->
    <script type="text/javascript" src="js/index/dynamic_css.js"></script>
    <script type="text/javascript" src="api/xmlhttp.js"></script>
    <script type="text/javascript" src="api/json.js"></script>
    <script type="text/javascript" src="api/globals.js"></script>
    <script type="text/javascript" src="js/api/functions.js"></script>
    <script type="text/javascript" src="js/index/index.js"></script>
    
    <script type="text/javascript" src="js/index/home.js"></script>
    <script type="text/javascript" src="js/index/interview.js"></script>
    <script type="text/javascript" src="js/index/letters.js"></script>
    <script type="text/javascript" src="js/index/images.js"></script>
    <script type="text/javascript" src="js/index/video.js"></script>
    <script type="text/javascript" src="js/index/map.js"></script>
    <script type="text/javascript" src="js/index/timeline.js"></script>
    <script type="text/javascript" src="js/index/tribes.js"></script>
  </head>
  <body>
    <?php
      include_once 'html/modal.php';
    ?>
    <div id="body-container" class="background-checker">
      <div id="header-positioner">
        <div id="header-container" class="background-black">
          <div id="header-left-container">
            <div id="header-left">
              <div id="logo">
                <a href="index.php">
                  <img src="img/coloredLogos/logo/NASCA_single_logo_white.svg" />
                </a>
              </div>
              <div id="logo-verbose-container">
                <div id="logo-verbose">
                  <img src="img/coloredLogos/type/NASCA_type_white.svg" />
                </div>
              </div>
            </div>
          </div>
          <div id="header-right" class="text-white">
            <div id="search-container">
              <div id="search">
                <div id="search-contents">
                  <div id="search-text" class="anton text-white">Search</div>
                  <div id="search-input-container">
                    <input id="search-input" type="text" value="..." />
                  </div>
                  <img id="search-go" onclick="" src="img/play-go.png" />
                </div>
              </div>
            </div>
            <div id="nav-bar-container">
              <div id="nav-bar">
                <ul id="tabs" class="source-serif text-white">
                  <li>
                    <div id="tabs-home" class="tab" onclick="changePage('home')">
                      Home
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-interviews" class="tab" onclick="changePage('interviews')">
                      Interviews
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-letters" class="tab" onclick="changePage('letters')">
                      Letters
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-images" class="tab" onclick="changePage('images')">
                      Images
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-video" class="tab" onclick="changePage('video')">
                      Video
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-map" class="tab" onclick="changePage('map')">
                      Map
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-timeline" class="tab" onclick="changePage('timeline')">
                      Timeline
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-tribes" class="tab" onclick="changePage('tribes')">
                      Tribes
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div id="menu-container">
              <div id="menu" class="background-grey">
                <div id="menu-icon">
                  <img id="menu-icon-img" src="img/menuBar.svg" />
                </div>
                <div id="menu-text" class="anton text-white">Menu</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="pullout-positioner">
        <div id="pullout-container" class="background-white">
          <ul id="pullout-list" class="source-serif">
            <li id="pullout-list-home" class="pullout-list-el" onclick="changePage('home')">Home</li>
            <li id="pullout-list-interviews" class="pullout-list-el" onclick="changePage('interviews')">Interviews</li>
            <li id="pullout-list-letters" class="pullout-list-el" onclick="changePage('letters')">Letters</li>
            <li id="pullout-list-images" class="pullout-list-el" onclick="changePage('images')">Images</li>
            <li id="pullout-list-video" class="pullout-list-el" onclick="changePage('video')">Video</li>
            <li id="pullout-list-map" class="pullout-list-el" onclick="changePage('map')">Map</li>
            <li id="pullout-list-timeline" class="pullout-list-el" onclick="changePage('timeline')">Timeline</li>
            <li id="pullout-list-tribes" class="pullout-list-el" onclick="changePage('tribes')">Tribes</li>
          </ul>
        </div>
      </div>
      <div id="header-positioner-height-offset"></div>
      <div id="page-container">
        <div id="page">
          Loading . . .
        </div>
      </div>
    </div>
    <div id="footer-container" class="background-black">
      <div id="footer" class="source-serif text-white">
        <div id="copyright-container">
          <div id="copyright">Native American South Carolina Archive (NASCA) &copy; 2016</div>
        </div>
        <div id="footer-links-container">
          <ul id="footer-links">
            <li id="footer-links-about">
              <a href="html/about.html">about</a>
            </li>
            <li id="footer-links-credits">
              <a href="html/credits.html">credits</a>
            </li>
            <li id="footer-links-dev-resources">
              <a href="html/dev_resources.html">developer-resources</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- Local Javascript, Jquery, Ajax -->
    <script type="text/javascript">
      $(document).ready(function() {
        init_index();
        dynamic_css();
        dynamic_css();
      });
    </script>
  </body>
  
</html>