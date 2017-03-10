//<!-- //for old browsers
/* global url_home */

/*
 * 
 * @param {boolean} cross - is cross-domain
 * @returns {ActiveXObject|XMLHttpRequest|Boolean}
 */
function createXmlHttpRequestObject(cross) {
  if(cross === undefined) {
    cross = false;
  }
  
  var xmlHttp;

  if(cross) {
    if(window.XDomainRequest) {
      try {
        xmlHttp = new window.XDomainRequest();
      } catch(e) {
        xmlHttp = false;
      }
    } else {
      xmlHttp = new XMLHttpRequest();
    }
  } else {
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
  }

  if(!xmlHttp) {
  	alert ("Error: CREATING XMLHTTP REQUEST OBJECT FAILED");
  } else {
  	return xmlHttp;
  }
}

//receives xml object from file
function getXmlObject(URL) {
  //if url contains any of site root, then it is not cross domain
  var cross = false;
  if(!URL.includes(url_home)) {
    cross = true;
  }
  
  var request = createXmlHttpRequestObject(cross);
  var response;
  
  if(window.ActiveXObject || window.XDomainRequest) {
    request.onload = function(){
      response = request.responseXML;
    };
    request.open('GET',URL,false);
    request.send(null);
  } else {
    request.open('GET',URL,false);
    request.onreadystatechange = function(){
      if(request.readyState === 4) {
        if(request.status === 200) {
          response = request.responseXML;
        } else {
          var warn = 'NOT FOUND\n' + URL;
          response = warn;
          alert(warn);
        }
      }
    };
    alert("before");
    request.send(null);
    alert("after");
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

//for old browsers -->