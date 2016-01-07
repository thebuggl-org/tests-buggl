var API_KEY;
var map;
var path;
var openBubble;
var markers = new Array();
var directionsService;
var directionsDisplays;

function initialize() 
{
    drawMap();
	directionsService = new google.maps.DirectionsService();
	directionsDisplays = new Array();
	for(var i=0; i<2; i++){
		var directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});
	    directionsDisplay.setMap(map);
		directionsDisplays.push(directionsDisplay);
	}

	$('#infoWindowContent ul').each(function(){
		// simplify??
		$('#infoWindowContent #'+$(this).attr('id')+' li .nextSpot').last().remove();
	});
	
	
	plotPointsForDay(1);
	attachNavigationEvents();
}

function loadScript() 
{
	API_KEY = $('#map_canvas').attr('api-key');
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "http://maps.googleapis.com/maps/api/js?key="+API_KEY+"&libraries=places&sensor=false&callback=initialize";
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

	google.maps.event.addListener(map, 'click', function() {
		if(openBubble !== undefined && openBubble !== null){
			openBubble.setMap(null);
			openBubble = null;
		}
  	});
}

function plotInfoBubble(htmlContent, marker)
{	
	var infoBubble = new InfoBubble({
           map: map,
           content: htmlContent,
		   position: marker.getPosition(),
           shadowStyle: 1,
           padding: '10px',
           backgroundColor: 'rgb(255,255,255)',
           borderRadius: 5,
           arrowSize: 20,
           borderWidth: 1,
           borderColor: '#2c2c2c',
           disableAutoPan: false,
           hideCloseButton: true,
           arrowPosition: 50,
           backgroundClassName: '',
  		   minWidth: 400,
		   minHeight: 200,
           arrowStyle: 0
       });

	infoBubble.open(map, marker);
	
	return infoBubble;
}

function plotPointsForDay(day)
{
	var latLongBounds = new google.maps.LatLngBounds();
	var pathPlanCoordinates = new Array();
	var prevCoords;
	var index = 0;
	for(var i=0; i<markers.length; i++){
		markers[i].setMap(null);
		markers[i] = null;
	}
	if(openBubble !== undefined && openBubble !== null)
		openBubble.setMap(null);	
	
	markers.splice(0, markers.length);
	
	$('#infoWindowContent #day_'+day+' li').each(function(){
		var me = $(this);
		var coords = new google.maps.LatLng(me.attr('latitude'), me.attr('longitude'));			
		var marker = new google.maps.Marker({
		    position: coords,
		    map: map,
			icon: ''
		});
		google.maps.event.addListener(marker, 'click', function(){
			if(openBubble === undefined || openBubble === null){
				openBubble = plotInfoBubble(me.html(),marker);
			}
			else if(openBubble.get('position') != marker.getPosition()){
				//openBubble.setMap(null);
				//openBubble = null;
				//openBubble = plotInfoBubble(me.html(),marker);
				openBubble.setContent(me.html());
				openBubble.open(map, marker);
			}
		});
		
		markers.push(marker);
		if(prevCoords !== undefined){
			calculateRoute(prevCoords,coords,index);
			index++;
		}	
		prevCoords = coords;
		latLongBounds.extend(coords);
	});
	
	map.fitBounds(latLongBounds);
	google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
	    showMarker(0);
	});
}

function showMarker(index)
{
	if(markers.length > 0 && index < markers.length){
		google.maps.event.trigger(markers[index],'click');
	}
}

function nextSpot(order)
{
	showMarker($('#infoWindowContent #day_'+$('.nav_day ul').attr('current-page')+' li#'+order).index()+1);
}

function calculateRoute(start,end,index) 
{
    var request = {
        origin:start,
        destination:end,
        travelMode: google.maps.TravelMode.DRIVING
    };

    directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplays[index].setDirections(response);
        }
    });
}

function attachNavigationEvents()
{
	var maxPage = $('#infoWindowContent ul').length;
	
	checkNavigationStatus(1,maxPage);
	$('.navigation').on('click',function(e){
		e.preventDefault();
		if($(this).hasClass('disabled')){
			return false;
		}
		
		var curPage = parseInt($(this).parents('ul').attr('current-page'));
		if($(this).attr('id') == 'prev'){
			curPage = curPage-1;
		}
		else{
			curPage = curPage+1;
		}
		
		$(this).parents('ul').attr('current-page',curPage);
		$('#dayCount').html(curPage);
		
		checkNavigationStatus(curPage,maxPage);
		
		plotPointsForDay($(this).parents('ul').attr('current-page'))
	});
}

function checkNavigationStatus(curPage, maxPage)
{
	$('#prev').removeClass('disabled');
	$('#next').removeClass('disabled');
	if(curPage == 1){
		$('#prev').addClass('disabled');
	}
	
	if(curPage == maxPage){
		$('#next').addClass('disabled');
	}
}

window.onload = loadScript;