<?php
  libxml_use_internal_errors(true);

  $current_dir = str_replace('configuration.php','',__FILE__);

  $config = simplexml_load_file($current_dir . 'configuration.xml');
  if ($config === false) {
    $str = 'Failed to get site configuration: ';
    foreach(libxml_get_errors() as $error) {
      $str .= '<br>'. $error->message;
    }
    exit($str);
  }

  $protocol = 'http://';
  // checking $protocol in HTTP or HTTPS
  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
      // this is HTTPS
      $protocol  = "https://";
  }
  
  define('REL_HOME', $config->rel_home);
  define('SITE_ROOT', $protocol . $_SERVER['SERVER_NAME'] . REL_HOME);
  define('PROTOCOL', $protocol);
  define('DB_ROOT', $config->databases->db->root);
  define('DB_TYPES', $config->databases->db->type_key);
  define('DB_HOME', $config->databases->db->home_data);
  define('DB_IMAGE', $config->databases->db->image_data);
  define('DB_INTERVIEW', $config->databases->db->interview_data);
  define('DB_LETTER', $config->databases->db->letter_data);
  define('DB_VIDEO', $config->databases->db->video_data);

  define('CDM_SERVER', $config->databases->cdm->server);
  define('CDM_PORT', $config->databases->cdm->port);
  define('CDM_QUERY_BASE', $config->databases->cdm->api_query_base);
  define('CDM_UTILS', $config->databases->cdm->api_utils);
  define('CDM_COLLECTION', $config->databases->cdm->collection);

  define('CDM_API_WEBSERVICE', $protocol . CDM_SERVER . ':' . CDM_PORT . CDM_QUERY_BASE);
  define('CDM_API_UTILS', $protocol . CDM_SERVER . CDM_UTILS);
?>
