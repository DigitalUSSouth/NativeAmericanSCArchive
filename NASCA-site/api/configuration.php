<?php
  libxml_use_internal_errors(true);

  $current_dir = str_replace('configuration.php','',__FILE__);
  
  $config = simplexml_load_file($current_dir . 'configuration.xml');
  if ($config === false) {
    echo 'Failed to get site configuration: ';
    foreach(libxml_get_errors() as $error) {
      echo '<br>', $error->message;
    }
  }

  define('REL_HOME', $config->rel_home);
  define('SITE_ROOT', 'http://' . $_SERVER["SERVER_NAME"] . REL_HOME);
  
  define('CDM_SERVER', $config->databases->cdm->server);
  define('CDM_PORT', $config->databases->cdm->port);
  define('CDM_QUERY_BASE', $config->databases->cdm->api_query_base);
  define('CDM_COLLECTION', $config->databases->cdm->collection);
  
  define('CDM_BASE', 'http://' . CDM_SERVER . ':' . CDM_PORT . CDM_QUERY_BASE);
?>