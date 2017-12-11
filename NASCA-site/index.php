<?php
  require_once 'api/configuration.php';
  if ((PROTOCOL != "https://" && $_SERVER['SERVER_NAME']=="www.nativesouthcarolina.org") || ($_SERVER['SERVER_NAME']=="nativesouthcarolina.org")){
    header ('Location: https://www.nativesouthcarolina.org'.$_SERVER['REQUEST_URI']);
  }
  global $currentUrl;
  //this global is an array containing
  // the elements of the url
  // example:
  // url: http://site.com/about/page1/page2/
  // $currenUrl:
  // array(
  //   [0] => 'about',
  //   [1] => 'page1',
  //   [2] => 'page2'
  // )
  if (isset($_GET['page'])){
    $currentUrl = array_filter(explode('/',$_GET['page']));
    $authorizedPages  = array(
      "interviews","letters", "images","video","map","timeline","tribes","search","about"
    );
    if (!in_array($currentUrl[0],$authorizedPages)){
      $currentUrl = ["404"];
    }
  }
  else {
    $currentUrl = [];
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    
    <title>NASCA</title>
    
    <!-- metadata tags here -->
    <meta name="description" content="Native American South Carolina Archive"/>
    <meta name="author" content="Matthew Jendrasiak"/>
    
    <?php 
      // the following global js variable contains the same elements as the php $currentUrl
    ?>
    <script>
      var currentUrl = <?php print json_encode($currentUrl);?>;
      console.log (currentUrl);
    </script>


    <!-- minified jquery, jplayer, bootstrap, etcetera -->
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/jquery/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/jquery/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/jquery/jquery.jplayer.min.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/jquery/jquery.fancybox.min.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/jquery/important.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/modal.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <!-- timelineJS base code pulls -->
  <link rel="stylesheet" href="//cdn.knightlab.com/libs/timeline3/latest/css/timeline.css">
  <script type="text/javascript" src="//cdn.knightlab.com/libs/timeline3/latest/js/timeline-min.js"></script>
    
    <!-- CSS & BootStrap -->
    <link rel="stylesheet" type="text/css" href="<?php print SITE_ROOT; ?>/js/jquery/jquery-ui/jquery-ui.min.css"/>
    <!--link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.structure.min.css"/>
    <link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.theme.min.css"/-->
    <link rel="stylesheet" type="text/css" href="<?php print SITE_ROOT; ?>/css/jquery.fancybox.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php print SITE_ROOT; ?>/css/index.css"/>
    <link rel="stylesheet" type="text/css" href="<?php print SITE_ROOT; ?>/css/jplayer.blue.monday.css"/>
    <link rel="stylesheet" type="text/css" href="<?php print SITE_ROOT; ?>/css/interviews.css"/>
    <link rel="stylesheet" type="text/css" href="<?php print SITE_ROOT; ?>/css/home.css"/>
    <link rel="stylesheet" type="text/css" href="<?php print SITE_ROOT; ?>/css/timeline.css"/>
    <link rel="stylesheet" type="text/css" href="<?php print SITE_ROOT; ?>/css/images.css"/>
    <link rel="stylesheet" type="text/css" href="<?php print SITE_ROOT; ?>/css/letters.css"/>
    <link rel="stylesheet" type="text/css" href="<?php print SITE_ROOT; ?>/css/tribes.css"/>
    <link rel="stylesheet" type="text/css" href="<?php print SITE_ROOT; ?>/css/map.css"/>
    <link rel="stylesheet" type="text/css" href="<?php print SITE_ROOT; ?>/css/video.css"/>
    <link rel="stylesheet" type="text/css" href="<?php print SITE_ROOT; ?>/css/bootstrap.min.css"/>

    
    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Anton|Source+Serif+Pro" rel="stylesheet">
    
    
    <!-- ICON -->
    <link rel="icon" type="image/x-icon" href="<?php print SITE_ROOT?>/img/favicon/favicon.ico"/>

    <!-- Local Javascript, Jquery, Ajax -->
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/index/dynamic_css.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/api/xmlhttp.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/api/json.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/api/globals.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/api/functions.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/index/index.js"></script>
    
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/index/home.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/index/interview.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/index/letters.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/index/images.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/index/video.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/index/map.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/index/timeline.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/index/tribes.js"></script>
    <script type="text/javascript" src="<?php print SITE_ROOT; ?>/js/index/search.js"></script>

  </head>
  <body>
    <div id="body-container" class="background-checker">
      <div id="header-positioner">
        <div id="header-container" class="background-black">
          <div id="header-left-container">
            <div id="header-left">
              <div id="logo">
                <a id="logo-anchor" href="<?php print SITE_ROOT; ?>/">
                  <img id="logo-image" src="<?php print SITE_ROOT; ?>/img/coloredLogos/logo/NASCA_single_logo_white.svg" />
                </a>
              </div>
              <div id="logo-verbose-container">
                <div id="logo-verbose">
                  <img id="logo-verbose-image" src="<?php print SITE_ROOT; ?>/img/coloredLogos/type/NASCA_type_white.svg" />
                </div>
              </div>
            </div>
          </div>
          <div id="header-right" class="text-white">
            <div id="search-container">
              <div id="search">
                <!--<div id="search-contents">-->
                  <div id="search-form" data-target="">
                    <div id="search-text" for="search-inputn" class="anton">Search</div>
                    <div id="search-input-container">
                      <input id="search-input" class="source-serif" type="text" value="" name="query">
                    </div>
                    <button id="search-submit" class="background-black" type="submit">
                      <img id="search-submit-img" src="<?php print SITE_ROOT;?>/img/play-go.png">
                    </button>
                  </div>
                <!--</div>-->
              </div>
            </div>
            <div id="nav-bar-container">
              <div id="nav-bar">
                <ul id="tabs" class="source-serif text-white">
                  <li>
                    <div id="tabs-home" class="tab tab-active">
                      Home
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-interviews" class="tab">
                      Interviews
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-letters" class="tab">
                      Letters
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-images" class="tab">
                      Images
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-video" class="tab">
                      Video
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-map" class="tab">
                      Map
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-timeline" class="tab">
                      Timeline
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-tribes" class="tab">
                      Tribes
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                  <li>
                    <div id="tabs-about" class="tab">
                      About
                      <div class="tab-underline half-underline-white"></div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div id="menu-container">
              <div id="menu" class="background-grey">
                <div id="menu-icon">
                  <img id="menu-icon-img" src="<?php print SITE_ROOT; ?>/img/menuBar.svg" />
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
            <li id="pullout-list-home" class="pullout-list-el">Home</li>
            <li id="pullout-list-interviews" class="pullout-list-el">Interviews</li>
            <li id="pullout-list-letters" class="pullout-list-el">Letters</li>
            <li id="pullout-list-images" class="pullout-list-el">Images</li>
            <li id="pullout-list-video" class="pullout-list-el">Video</li>
            <li id="pullout-list-map" class="pullout-list-el">Map</li>
            <li id="pullout-list-timeline" class="pullout-list-el">Timeline</li>
            <li id="pullout-list-tribes" class="pullout-list-el">Tribes</li>
            <li id="pullout-list-about" class="pullout-list-el">About</li>
          </ul>
        </div>
      </div>
      <div id="header-positioner-height-offset"></div>
      <div id="page-container">
        <div id="page">
          <div class="text-center"><h1>Loading...</h1><i class="fa fa-spinner fa-spin" style="font-size:76px"></i></h1></div>
        </div>
      </div>
    </div>
    <div id="footer-container" class="background-black">
      <div id="footer" class="source-serif text-white">
          <div id="footer-logos">
            <img class="footer-logo-img" src="<?php print SITE_ROOT;?>/img/logos/Lancaster_PC_Linear_202.svg">
            <img class="footer-logo-img" src="<?php print SITE_ROOT;?>/img/logos/Libraries_Linear_WebRGB.svg">
            <img class="footer-logo-img" src="<?php print SITE_ROOT;?>/img/logos/ISS_Linear_WebRGB.svg">
            <img class="footer-logo-img" src="<?php print SITE_ROOT;?>/img/logos/Lancaster_NAS_color-USCL.svg">
          </div>
          <div id="footer-copyright">Native American South Carolina Archive (NASCA) &copy; 2016</div>

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