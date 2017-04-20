//<!-- //for old browsers

var CDM_SERVER = '';  
var CDM_PORT = '';
var CDM_QUERY_BASE = '';
var CDM_COLLECTION = '';
  
var CDM_BASE = '';
  
var REL_HOME = '';
var SITE_ROOT = '';

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

      CDM_BASE = json.CDM_BASE;

      REL_HOME = json.REL_HOME;
      SITE_ROOT = json.SITE_ROOT;
  	}
  });
}

//for old browsers -->