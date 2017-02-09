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

//receives xml object from file
function getXmlObject(URL) {
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
function getXmlTag(xml, tag) {
  var tagValue = "";
  tagValue = xml.getElementsByTagName(tag)[0].childNodes[0].nodeValue;
  return tagValue;
}

//inputs: object XMLDocument;
//        string tag (tag to search for)
//output: returns LIST of all string values adjacent to argument tag
function getXmlTags(xml, tag) {
  var elements = xml.getElementsByTagName(tag);
  var length = elements.length;
  var strlist = [];
  for(var i = 0; i < length; i++) {
    strlist.push(elements[i].childNodes[0].nodeValue);
  }
  return strlist;
}

//inputs: object XMLDocument OR object XMLNode
//        string element (element node to search for
//                        under XMLdocument root or XMLnode)
//        int num (if multiple nodes, this asks for which one)
//output: return node object that matches name
function getXmlNode(xml, element, num) {
  if(num === undefined) {
    num = 1;
  }
  return xml.getElementsByTagName(element)[(num-1)];
}

//inputs: object XMLnode
//output: return node text value
function getNodeText(node) {
  return node.childNodes[0].nodeValue;
}
/*
function getJSON(URL) {

}

function getJSONtag(object, tag) {
  
}
*/
//for old browsers -->