var idlist;

var centerLatitude = 0;
var centerLongitude = 0;
var startZoom = 3; 

var map;

function initMap(id) {
	map = new GMap2(document.getElementById(id));
	map.addControl(new GSmallMapControl());  //add zooming control
	map.addControl(new GMapTypeControl());   //add map type control
	map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);  //set the center of the map and the zoom

	//retrieve data
	retrieveMarkers();
	

}

//window.onload = initMap;
window.onunload = GUnload;

function retrieveMarkers() {
	var request = GXmlHttp.create();

	//tell the request where to retrieve data from.
	request.open('GET', 'tomap.php?id='+idlist, true);

	//tell the request what to do when the state changes.
	request.onreadystatechange = function() {
		if (request.readyState == 4) {
			var xmlDoc = request.responseXML;//get xml document object
			var root=xmlDoc.documentElement;
			var state=root.attributes.getNamedItem("state").nodeValue;
			if(state>0){
				var x=root.childNodes;
			
				for (i=0;i<x.length;i++){ 
					if (x[i].nodeType==1){//Process only element nodes (type 1) 
						
						var lng=x[i].childNodes[0].childNodes[0].nodeValue;
						var lat=x[i].childNodes[1].childNodes[0].nodeValue;
						var latlng=new GLatLng(parseFloat(lat),parseFloat(lng));
                                                
						var html='<div><b>strain</b>: '+x[i].childNodes[2].childNodes[0].nodeValue
                                                         +'<br><b>host</b>: '+x[i].childNodes[3].childNodes[0].nodeValue
                                                         +'<br><b>country</b>: '+x[i].childNodes[4].childNodes[0].nodeValue
                                                         +'<br><b>date</b>: '+x[i].childNodes[5].childNodes[0].nodeValue
                                                         +'<br><b>detail</b>: <a href="detail.php?location='+x[i].childNodes[6].childNodes[0].nodeValue
                                                                +'" target="_blank">'+x[i].childNodes[6].childNodes[0].nodeValue+'</a>'
                                                         +'</div>';

						var marker=createMarker(latlng,html,addIcon());
						map.addOverlay(marker);
  
					} 
				}
			}else{
				alert("Sorry, no record found!");
			}
			
			

		} //if
	} //function

	request.send(null);
}

function createMarker(latlng, html,icon) {
	var marker = new GMarker(latlng,icon);
	GEvent.addListener(marker, 'click', function() {
		var markerHTML = html;
		marker.openInfoWindowHtml(markerHTML);
	});
	return marker;
}

function addIcon(){
    var icon=new GIcon();
	 icon.image='images/loc.gif';

    icon.iconSize=new GSize(24,24);
    icon.iconAnchor=new GPoint(24,14);
    icon.infoWindowAnchor=new GPoint(24,24);
    return icon;
}
