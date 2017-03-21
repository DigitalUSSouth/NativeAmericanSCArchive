//<!-- //for old browsers

/*
globals.js is intended to read in all necessary
global information on load time of site.
This includes database and contentdm configuration info,
and relevant .json files like imagePointers.json
*/

var cdm_server = '';
var cdm_port = '';
var cdm_api_query_base = '';
var cdm_collection = '';

var BASE = '';

var url_home = 'http://www.duss.sc.edu'; //root

function populateCdmGlobals() {
  var xml = getXmlObject(url_home + '/config.xml');
  cdm_server = getXmlTag(xml,'server');
  cdm_port = getXmlTag(xml,'port');
  cdm_api_query_base = getXmlTag(xml,'api_query_base');
  cdm_collection = getXmlTag(xml,'collection');
  
  BASE = cdm_server + cdm_port + cdm_api_query_base;
}

//for old browsers -->