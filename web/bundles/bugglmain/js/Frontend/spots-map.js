// dependencies:
// '@BugglMainBundle/Resources/public/js/markerclusterer_compiled.js'

var API_KEY;
var map;

var latLongBounds;

function initialize() 
{
    drawMap();
}

function loadScript() 
{
	API_KEY = $('#map_canvas').attr('api-key');
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "http://maps.googleapis.com/maps/api/js?key="+API_KEY+"&sensor=false&callback=initialize";
    document.body.appendChild(script);
}

function drawMap() 
{
	var mapOptions = {
        zoom: 16,
        center: new google.maps.LatLng($('#map_canvas').attr("center-lat"), $('#map_canvas').attr("center-long")),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
       
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	
	plotMarkers();
}

function plotMarkers()
{	  
	latLongBounds = new google.maps.LatLngBounds();
	var markers = Array();
	var infoWindow;
	$('.spots').each(function(){
		var me = $(this);
		var coords = new google.maps.LatLng(me.attr('latitude'), me.attr('longitude'));
		var marker = new google.maps.Marker({
		    position: coords,
		    map: map,
			icon: '/bundles/bugglmain/images/'+me.attr('type')+'.png'
		});
		  
		marker.setMap(map);
		markers.push(marker);
		//if(me.attr('data-show') == '1'){
			google.maps.event.addListener(marker, 'click', function(){
				if(infoWindow)
					infoWindow.close();
					
				infoWindow = new google.maps.InfoWindow({
				      content: me.html()
				  });
	  
				infoWindow.open(map,marker);
			});
		//}
		
		latLongBounds.extend(coords);
	});
	
	var mc = new MarkerClusterer(map,markers);
	map.fitBounds(latLongBounds);
}

function resizeMap()
{
	if(typeof google === 'object' && typeof google.maps === 'object'){
		google.maps.event.trigger(map, 'resize');
		map.fitBounds(latLongBounds);
	}
}

window.onload = loadScript;