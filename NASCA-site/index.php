<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    
    <title>NASCA</title>
    
    <!-- metadata tags here -->
    <meta name="description" content="Native American South Carolina Archive"/>
    <meta name="author" content="Matthew Jendrasiak, Vassil Kurktchiev, Siddhartha Pathak"/>
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0"/>-->
    
    <!-- minified jquery, jplayer, bootstrap, etcetera -->
    <script type="text/javascript" src="js/jquery/jquery-3.1.1.min.js"></script>
    <!--script type="text/javascript" src="js/jquery/external/jquery.js"></script-->
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
    
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto+Slab:400,700" rel="stylesheet"/>
    
    <!-- ICON -->
    <link rel="icon" type="image/x-icon" href="img/favicon.ico"/>

    <!-- Local Javascript, Jquery, Ajax -->
    <script type="text/javascript" src="js/api/requests/xmlhttp.js"></script>
    <script type="text/javascript" src="js/api/requests/json.js"></script>
    <script type="text/javascript" src="js/api/globals.js"></script>
    <script type="text/javascript" src="js/index/fade.js"></script>
    <script type="text/javascript" src="js/index/index.js"></script>
    <script type="text/javascript" src="js/index/interviews.js"></script>
    
  </head>
  <body>
    <div class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <span class="modal-close">&times;</span>
        </div>
        <div class="modal-body">
          <p>Modal layout here.</p>
        </div>
        <div class="modal-footer">

        </div>
      </div>
    </div>
    <!-- === Gradient-Wrapper === -->
    <!-- === Gradient-Wrapper === -->
    <!-- === Gradient-Wrapper === -->
    <div class="gradient-wrapper">
      <!-- === Header === -->
      <!-- === Header === -->
      <!-- === Header === -->
      <header class="header">
        <div class="logo">
          <a href="index.php"><img src="img/NASCA_logo.png"></a>
        </div>
        <!-- === Navigation === -->
        <!-- === Navigation === -->
        <!-- === Navigation === -->
        <div class="nav">
          <ul class="tabs">
            <li><a id="tb-home" href="#" onclick="changePage('home')">HOME</a></li>
            <li><a id="tb-interviews" href="#" onclick="changePage('interviews-list')">INTERVIEWS</a></li>
            <li><a id="tb-images" href="#" onclick="changePage('images')">IMAGES</a></li>
            <li><a id="tb-video" href="#" onclick="changePage('video')">VIDEO</a></li>
            <li><a id="tb-timeline" href="#" onclick="changePage('timeline')">TIMELINE</a></li>
            <li><a id="tb-map" href="#" onclick="changePage('map')">MAP</a></li>
            <li><a id="tb-census" href="#" onclick="changePage('census')">CENSUS</a></li>
            <li><a id="tb-tribes" href="#" onclick="changePage('tribes')">TRIBES</a></li>
          </ul>
        </div>
      </header>
      
      <div class="body-container">
        <div class="content">
          Content
        </div>
        <!-- === Copyright === -->
        <!-- === Copyright === -->
        <!-- === Copyright === -->
        <div class="row">
          <div class="col-md-12">
            <p id="copyright" class="copyright">NASCA &copy; 2016</p>
          </div>
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