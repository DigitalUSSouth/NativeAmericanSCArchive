//<!-- //for old browsers

//receives xml object from file
function getJsonObject(URL) {
  var data = [];
  $.ajax({
  	url: URL,
  	async: false,
  	dataType: 'json',
  	type: 'get',
  	success: function(json) {
  		data = json;
  	}
  });
  return data;
}

/*
 * Take in a parsed json object
 * replace the value at a certain key with a new value
 * return the new parsed json object
 *
function updateJsonTag(jsonobject,key,newValue) {
  
}*/

//for old browsers -->