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

  define('REL_HOME', $config->rel_home);
  define('SITE_ROOT', 'http://' . $_SERVER["SERVER_NAME"] . REL_HOME);
  
  define('CDM_SERVER', $config->databases->cdm->server);
  define('CDM_PORT', $config->databases->cdm->port);
  define('CDM_QUERY_BASE', $config->databases->cdm->api_query_base);
  define('CDM_UTILS', $config->databases->cdm->api_utils);
  define('CDM_COLLECTION', $config->databases->cdm->collection);
  
  define('CDM_API_WEBSERVICE', 'http://' . CDM_SERVER . ':' . CDM_PORT . CDM_QUERY_BASE);
  define('CDM_API_UTILS', 'http://' . CDM_SERVER . CDM_UTILS);
?>