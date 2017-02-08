<!-- //for old browsers

var SITE_ROOT = "http://localhost:8000";

function createXmlHttpRequestObject() {
  var xmlHttp;

  if(window.ActiveXObject) {
    try {
      xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch(e) {
    	xmlHttp = false;
    }
  } else {
  	try {
  		xmlHttp = new XMLHttpRequest();
  	} catch(e) {
  		xmlHttp = false;
  	}
  }

  if(!xmlHttp) {
  	alert ("Error: CREATING XMLHTTP REQUEST OBJECT FAILED");
  } else {
  	return xmlHttp;
  }
}

//receives xml object from file as string
function getXMLobject(URL) {
  var fullURL = SITE_ROOT + URL;
  var request = createXmlHttpRequestObject();
  request.open('GET',fullURL,false);
  request.send(null);
  var response;
  if(request.status === 404) {
  	response = "NOT FOUND";
  } else {
  	response = request.responseXML;
  }
  return response;
}

//inputs: object XMLDocument,
//        string tag (tag to search for)
//output: finds first tag of argument name and passes string value
function getXMLtag(object, tag) {
  
}
/*
function getJSON(URL) {

}

function getJSONtag(object, tag) {
  
}
*/
//for old browsers -->