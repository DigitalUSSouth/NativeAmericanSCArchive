<?php
  $current_dir = str_replace('globals.php','',__FILE__);

  require_once($current_dir . 'configuration.php');
  //TODO make this into a function called
  //getGlobalsJson
  $arr['CDM_SERVER'] = CDM_SERVER;  
  $arr['CDM_PORT'] = CDM_PORT;
  $arr['CDM_QUERY_BASE'] = CDM_QUERY_BASE;
  $arr['CDM_COLLECTION'] = CDM_COLLECTION;
  $arr['CDM_API_WEBSERVICE'] = CDM_API_WEBSERVICE;
  $arr['CDM_API_UTILS'] = CDM_API_UTILS;
  $arr['REL_HOME'] = REL_HOME;
  $arr['SITE_ROOT'] = SITE_ROOT;
  $arr['PROTOCOL'] = PROTOCOL;
  $arr['DB_ROOT'] = DB_ROOT;
  $arr['DB_TYPES'] = DB_TYPES;
  $arr['DB_HOME'] = DB_HOME;
  $arr['DB_IMAGE'] = DB_IMAGE;
  $arr['DB_INTERVIEW'] = DB_INTERVIEW;
  $arr['DB_LETTER'] = DB_LETTER;
  $arr['DB_VIDEO'] = DB_VIDEO;
  $arr['IMAGE_FORMAT'] = IMAGE_FORMAT;
  $arr['IMAGE_SIZE_THUMBNAIL'] = IMAGE_SIZE_THUMBNAIL;
  $arr['IMAGE_SIZE_SMALL'] = IMAGE_SIZE_SMALL;
  $arr['IMAGE_SIZE_LARGE'] = IMAGE_SIZE_LARGE;
  $arr['IMAGES_START'] = (int)$config->frontend->images->cards_start;
  $arr['IMAGES_CONT'] = (int)$config->frontend->images->cards_per_block;
  echo json_encode($arr);
?>