/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

/*
 * @param {string} type - may be 'image' 'video' or 'transcript'
 * @param {number/int} id - id on cdm or wherever of entry to load data from
 */
function readMore(type, id) {
  var html = '';
  for(var i = 0; i < 100; i++) {
    html += 'Get ' + type + ' ' + id.toString() + ' from contentDM. ';
  }
  $('.preview #details').html(html);
}

//for old browsers -->