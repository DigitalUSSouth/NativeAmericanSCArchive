//<!-- //for old browsers

var CDM_SERVER = '';  
var CDM_PORT = '';
var CDM_QUERY_BASE = '';
var CDM_COLLECTION = '';
  
var CDM_API_WEBSERVICE = '';
var CDM_API_UTILS = '';
  
var REL_HOME = '';
var SITE_ROOT = '';
var PROTOCOL = '';
var DB_ROOT = '';
var DB_TYPES = '';
var DB_HOME = '';
var DB_IMAGE = '';
var DB_INTERVIEW = '';
var DB_LETTER = '';
var DB_VIDEO = '';
var IMAGE_FORMAT = '';
var IMAGE_SIZE_THUMBNAIL = '';
var IMAGE_SIZE_SMALL = '';
var IMAGE_SIZE_LARGE = '';

var IMAGES_START = '';
var IMAGES_CONT = '';

function setGlobals() {
  var fileLocation = $('script[src*=globals]').attr('src');
  fileLocation = fileLocation.replace('globals.js','');
  $.ajax({
    type:'POST',
    url: fileLocation + 'globals.php',
    async: false,
    dataType: 'json',
    success: function(json) {
  		CDM_SERVER = json.CDM_SERVER;  
      CDM_PORT = json.CDM_PORT;
      CDM_QUERY_BASE = json.CDM_QUERY_BASE;
      CDM_COLLECTION = json.CDM_COLLECTION;

      CDM_API_WEBSERVICE = json.CDM_API_WEBSERVICE;
      CDM_API_UTILS = json.CDM_API_UTILS;

      REL_HOME = json.REL_HOME;
      SITE_ROOT = json.SITE_ROOT;
      PROTOCOL = json.PROTOCOL;
      DB_ROOT = json.DB_ROOT;
      DB_TYPES = json.DB_TYPES;
      DB_HOME = json.DB_HOME;
      DB_IMAGE = json.DB_IMAGE;
      DB_INTERVIEW = json.DB_INTERVIEW;
      DB_LETTER = json.DB_LETTER;
      DB_VIDEO = json.DB_VIDEO;
      IMAGE_FORMAT = json.IMAGE_FORMAT;
      IMAGE_SIZE_THUMBNAIL = json.IMAGE_SIZE_THUMBNAIL;
      IMAGE_SIZE_SMALL = json.IMAGE_SIZE_SMALL;
      IMAGE_SIZE_LARGE = json.IMAGE_SIZE_LARGE;
      
      IMAGES_START = json.IMAGES_START;
      IMAGES_CONT = json.IMAGES_CONT;
  	}
  });
}

//for old browsers -->