/* global SITE_ROOT, REL_HOME, CDM_BASE, CDM_COLLECTION, CDM_QUERY_BASE, CDM_PORT, CDM_SERVER */

//<!-- //for old browsers

function generateGeoJson(_dataFile) {
	var geojson_template = parent.getJsonObject(parent.SITE_ROOT + '/db/data/map/geojson-template.json');
  //get json data from file
	var data = parent.getJsonObject(_dataFile);
  
	var geojson = [];

	//iteratively add to json string
	for(var i = 0; i < data.features.length; i++) {
    geojson.push(jQuery.extend(true, {}, geojson_template));
    
    if(data.features[i].hasOwnProperty('lng') && data.features[i].hasOwnProperty('lat')) {
      if(data.features[i].lng !== 0.0 && data.features[i].lat !== 0.0) {
        geojson[i].geometry.coordinates = [data.features[i].lng, data.features[i].lat];
      } else {
        console.log(_dataFile + 'is missing a non-zero non-default value for "lat" and/or "lng" at entry #' + i);
      }
    } else {
      console.log(_dataFile + 'is missing a "lat" and/or "lng" key at entry #' + i);
    }
    var hasSiteName = false;
    if(data.features[i].hasOwnProperty('site_name')) {
      if(data.features[i].site_name !== '') {
        //geojson[i].properties.title = data.features[i].site_name;
        hasSiteName = true;
      } else {
        console.log(_dataFile + 'is missing a value for the key "site_name" at entry #' + i);
      }
    } else {
      console.log(_dataFile + 'is missing a "site_name" key at entry #' + i);
    }
    var hasDescription = false;
    if(data.features[i].hasOwnProperty('description')) {
      if(data.features[i].description !== '') {
        hasDescription = true;
      } else {
        console.log(_dataFile + 'is missing a value for the key "description" at entry #' + i);
      }
    } else {
      console.log(_dataFile + 'is missing a "description" key at entry #' + i);
    }
    var hasWebLink = false;
    if(data.features[i].hasOwnProperty('web_link')) {
      if(data.features[i].web_link !== '') {
        hasWebLink = true;
      } else {
        console.log(_dataFile + 'is missing a value for the key "web_link" at entry #' + i);
      }
    } else {
      console.log(_dataFile + 'is missing a "web_link" key at entry #' + i);
    }
    var hasWebLinkHover = false;
    if(data.features[i].hasOwnProperty('web_link_hover')) {
      if(data.features[i].web_link_hover !== '') {
        hasWebLinkHover = true;
      } else {
        console.log(_dataFile + 'is missing a value for the key "web_link_hover" at entry #' + i);
      }
    } else {
      console.log(_dataFile + 'is missing a "web_link_hover" key at entry #' + i);
    }
    var hasImage = false;
    if(data.features[i].hasOwnProperty('img_embed')) {
      if(data.features[i].img_embed !== '') {
        hasImage = true;
      } else {
        console.log(_dataFile + 'is missing a value for the key "img_embed" at entry #' + i);
      }
    }
    var hasVideo = false;
    if(data.features[i].hasOwnProperty('yt_embed')) {
      if(data.features[i].yt_embed !== '') {
        hasVideo = true;
      } else {
        console.log(_dataFile + 'is missing a value for the key "yt_embed" at entry #' + i);
      }
    }
    var descriptionStr = '<div class="map-point-body">';
    if(hasImage) {
      descriptionStr += '<img class="map-point-img" src="' + data.features[i].img_embed + '">';
    }
    descriptionStr += '<div class="map-point-heading">';
    if(hasWebLink) {
      var weblinkhover = '';
      if(hasWebLinkHover) {
        weblinkhover = data.features[i].web_link_hover;
      } else {
        weblinkhover = 'Go To Site...';
      }
      descriptionStr += '<a href="' + data.features[i].web_link + '" target="_blank" title="' + weblinkhover + '">';
    }
    var sitename = '';
    if(hasSiteName) {
      sitename = data.features[i].site_name;
    } else {
      sitename = 'Sample Site Name';
    }
    descriptionStr += sitename;
    if(hasWebLink) {
      descriptionStr += '</a>';
    }
    descriptionStr += '</div>';
    if(hasDescription) {
      descriptionStr += '<div class="map-point-desc">' + data.features[i].description + '</div>';
    }
    if(hasVideo) {
      descriptionStr += '<iframe width="320" height="180" class="map-point-vid" src="' + data.features[i].yt_embed + '" frameborder="0"></iframe>';
    }
    descriptionStr += '</div>';
        
    /*
     * "type": "Feature",
  "geometry":
  {
    "type": "Point",
    "coordinates": [0.000000, 0.000000]
  },
  "properties":
  {
    "title": "Site Name",
    "description": "Sample Description",
    "marker-color": "#990000",
    "marker-size": "medium",
    "marker-symbol": "",
    "icon":
    {
      "iconUrl": "",
      "iconSize": [40, 40],
      "iconAnchor": [20, 20],
      "popupAnchor": [0, -20],
      "className": "dot"
    }
  }
     * 
     */
    geojson[i].properties.description = descriptionStr;
    //add marker color to geojson
    if (data.features[i].hasOwnProperty('marker-color')){
      geojson[i].properties['marker-color'] = data.features[i]['marker-color'];
    }
	}
	return geojson;
}

L.mapbox.accessToken = 'pk.eyJ1IjoidGhlY3J5cHRpeCIsImEiOiJjaXBmb2ZhaWswMDBmdnFtamM1OWY2ajY1In0.EZ6bbL_Yc-oX8ZLykZzmFg';
var mapTooltips = L.mapbox.map('map-tooltips', 'mapbox.light')
  .setView([33.583767, -81.031824], 8);
var myLayer = L.mapbox.featureLayer().addTo(mapTooltips);
var geojson = generateGeoJson(parent.SITE_ROOT + '/db/data/map/NASCAMapPoints.json');
myLayer.setGeoJSON(geojson);
mapTooltips.scrollWheelZoom.enable();

//for old browsers -->