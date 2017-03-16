//<!-- //for old browsers
/* global cdm_server, cdm_port, cdm_api_query_base */

/*
cdm_functions.js is to make all contentDM functions that are available and
potentially needed, easy to use through javascript
*/

/*
 * getCdmApiVersion
 * return either 'json' or 'xml' object of the contentDM api version
 */
function getCdmApiVersion(format) {
  //grab information from
  var query = BASE + 'wsAPIDescribe/' + format;
  if(format === 'json') {
    return getJsonObject(query);
  } else if(format === 'xml') {
    return getXmlObject(query);
  } else {
    var notice = 'bad argument: getCdmApiVersion (use \'xml\' or \'json\')';
    alert(notice + '\n' + query);
    return notice;
  }
}

/*
 * getCollectionFields
 * return either 'json' or 'xml' object of nasca's collection fields
 */
function getCollectionFields(format) {
  var query = BASE + 'dmGetCollectionFieldInfo' + cdm_collection + '/' + format;
  if(format === 'json') {
    return getJsonObject(query);
  } else if(format === 'xml') {
    return getXmlObject(query);
  } else {
    var notice = 'bad argument: getCollectionFields (use \'xml\' or \'json\')';
    alert(notice);
    return notice;
  }
}

/*
 * getCollectionVocabulary
 * return either 'json' or 'xml' object of the language/vocabulary used in
 * a specific collection field
 * 
 * Input:     field - nickname of the collection field you're looking in
 *            format - 'json' or 'xml'
 * TODO if necessary add optional forcedict and forcefullvoc arguments
 * TODO they don't seem to make any difference to the output at the moment
 */
function getCollectionVocabulary(field, format) {
  var query = BASE + 'dmGetCollectionFieldVocabulary' + cdm_collection + '/'
              + field + "/0/0/" + format;
  if(format === 'json') {
    return getJsonObject(query);
  } else if(format === 'xml') {
    return getXmlObject(query);
  } else {
    var notice = 'bad argument: getCollectionVocabulary (use \'xml\' or \'json\')';
    alert(notice);
    return notice;
  }
}

/*
 * getCollectionImageSettings
 * return either 'json' or 'xml' object of nasca's image settings
 */
function getCollectionImageSettings(format) {
  var query = BASE + 'dmGetCollectionImageSettings' + cdm_collection
              + '/' + format;
  if(format === 'json') {
    return getJsonObject(query);
  } else if(format === 'xml') {
    return getXmlObject(query);
  } else {
    var notice = 'bad argument: getCollectionImageSettings (use \'xml\' or \'json\')';
    alert(notice);
    return notice;
  }
}

//TODO ever more functions

//for old browsers -->