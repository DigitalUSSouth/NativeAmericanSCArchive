<!-- //for old browsers

var xmlHttp = createXmlHttpRequestObject();

function createXmlHttpRequestObject() {
  var xhttp;

  //if using Internet Explorer (hipster)
  if(window.ActiveXObject) {
    try {
      xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch(e) {
      xHttp = false;
    }
  } else {
    try {
      xhttp = new XMLHttpRequest();
    } catch(e) {
      xHttp = false;
    }
  }

  if(!xhttp) {
    alert("Can't createXmlHttpRequestObject")
  } else {
    return xhttp;
  }
}

//for old browsers -->