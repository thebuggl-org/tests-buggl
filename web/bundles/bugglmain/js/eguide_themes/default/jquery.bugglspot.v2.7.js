/**
 * Buggl Add Spot jquery plugin
 * Version 2.5
 * NOTE:
 * 		Think of way to combine edit spot and duplicate spot functionality
 */
// var d = new Date();
// alert("bugglSpot 2.7 : "+d.getTime());
$().ready(function(){
	var paths = window.location.pathname.split("/");
 	var bugglScriptName = (paths[1] == 'app_dev.php') ? '/app_dev.php' : '';
	var loader = {
 		element : null,
 		insert : function(element){
 			var me = this;
 			me.element = element;
 			$("<img/>",{ src : '/bundles/bugglmain/images/ajax-loader-1.gif'}).appendTo(me.element);
 			$("img", me.element).wrap(function(){
 				return "<div style='margin: 0 auto;text-align:center;' id='modal-loader-gif' />";
 			});
 		},
 		del : function(){
 			var me = this;
 			if(null == me.element)
 				$("div#modal-loader-gif").remove();
 			else
 				$("div#modal-loader-gif", me.element).remove();
 		}
 	}

 	var googleCustomSearch = {
 		id: 'photo_search_web_box',
 		baseUrl: 'https://www.googleapis.com/customsearch/v1',
 		options: {
 			'key' : 'AIzaSyCBfQzUarCd2GZfDNBFj87Z53alxKLgcHs',
			'cx' : '014869400310069128053:hfgxeamadtg',
			'imgType' : 'photo',
			'imgSize' : 'xlarge',
			'searchType' : 'image',
			'rights': 'cc_publicdomain,cc_attribute,cc_sharealike',
			'fileType' : 'jpg', 
			// 'safe': 'high',
			// 'restrict': 'cc_attribute',
			'num' : 6,
			'start' : 1
 		},
 		imageGrabber: null,
 		init: function(options){
 			var me = this;
 			me.options = $.extend(me.options, options);
 			// alert(me.options.start);
 			var qstring = $("#"+me.id).val();
			if(0 == qstring.length)
				return;

			me.execQuery();
 		},
 		buildUrl: function(){
 			var me = this;
 			var url = me.baseUrl + '?q=' + encodeURIComponent($("#"+me.id).val());
 			$.each(me.options, function(index, value){
				url = url + "&" + index + "=" + value;
			});
			url = url + "&biw=1436&bih=806";
			return url;
 		},
 		execQuery: function(){
 			var me = this;
 			var url = me.buildUrl();
 			$.ajax({
				url : url,
				crossDomain : true,
				dataType: 'json',
				beforeSend: function(){
					var holder = $("ul#google_search_results",bugglSpot.modal);
					$(holder).empty();
					$(holder).append("<li/>");
					loader.insert($("li:first", holder));
				},
				success: function(data){
					me.processResponse(data);
				}
			});
 		},
 		processResponse: function(data){
 			var me = this;
 			var modal = bugglSpot.modal;
 			var holder = $("ul#photo_search_web_results",modal);
 			var items = data.items;
 			$(holder).empty();
 			$.each(items, function(index, value){
				var ul = $(holder).append("<li/>");
				$("<img/>", {'src' : value.image.thumbnailLink})
					.appendTo($("li:last-child", ul));
				$("li:last-child", ul)
				.wrapInner(function(){
					return '<a href="" data-bigpiclink="'+value.link+'"/>';
				});
			});

 			var prevStart = data.queries.request[0].startIndex - me.options.num;

 			$("#photo-search-web-prev").data({'start' : prevStart});
 			if(typeof(data.queries.nextPage) != 'undefined'){
 				$("#photo-search-web-next").data({'start' : data.queries.nextPage[0].startIndex});
 			}


			me.prepareImageClick();
			me.prepareButtons();
 		},
 		prepareButtons: function(){
 			var me = this;
 			$("#photo-search-web-prev, #photo-search-web-next")
 			.unbind('click')
 			.on('click', function(e){
 				e.preventDefault();
 				var startIndex = $(this).data('start');
 				if(0 > startIndex)
 					return;

 				me.init({'start': startIndex});
 			});
 		},
 		prepareImageClick: function(){
 			var me = this;
 			var modal = bugglSpot.modal;
 			var holder = $("ul#photo_search_web_results",modal);
 			$("ul#photo_search_web_results",modal)
 				.find('a')
 				.click(function(e){
 					e.preventDefault();
 					$("#spot-photo-holder")
 						.css({'background' : 'url(/bundles/bugglmain/images/ajax-loader-1.gif) no-repeat center center'})
 						.empty();

 					var imgSrc = $(this).data('bigpiclink');
 					// var img = new Image();
 					// $(img)
 					// 	.load(function(){
 					// 		$("#spot-photo-holder").css('background', '');
 					// 		$("#spot-photo-holder").append(img);
 					// 		$("form#spot-photo-ifrm > input[name='photo-url']").val(imgSrc);
 					// 	})
 					// 	.attr({'src' : imgSrc, 'id' : 'spot-photo-img', 'height' : '317px' });
 					me.grabImage(imgSrc);
 				});
 		},
 		// grab image to our server so we could crop it
 		grabImage: function(imgLink){
 			var me = this;
 			var paths = window.location.pathname.split("/");
			var scriptName = (paths[1] == 'app_dev.php') ? '/app_dev.php' : '';
			var action = scriptName+'/local-author/process-temp-travel-guide-photo-from-web';

			if( me.imageGrabber != null )
				me.imageGrabber.abort();

			me.imageGrabber = $.ajax({
					url: action,
					data: { image_url : imgLink },
					dataType: 'json',
					beforeSend: function(){
						$(".crop-area")
							.css({'background' : 'url(/bundles/bugglmain/images/ajax-loader_red-grayBG.gif) no-repeat scroll center center #CCCCCC'});
					},
					success: function(data){
						$("#descphoto-crop-dimensions-ifrm").attr('action', scriptName+'/local-author/crop-spot-desc-photo');
						me.loadImage(data);
					}
				});
 		},
 		// display image
 		loadImage: function(data){
 			var me = this;
 			var newWidth = (350 * data.width)/data.height;
			var img = new Image();
			$("#spot-photo-holder").empty();
			$(img)
				.load(function(){
					$("#spot-photo-holder").append(img);
					$("#spot-cropper-ifrm input[name='filename']").val(data.filename);
					$("form#spot-photo-ifrm > input[name='photo-url']").val(data.url);

					$("#spot-cropper-ifrm input[name='start-x']").val(0);
					$("#spot-cropper-ifrm input[name='start-y']").val(0);
					$("#spot-cropper-ifrm input[name='target-x']").val(250);
					$("#spot-cropper-ifrm input[name='target-y']").val(300);
					
					me.jcropImageCropper(img, data);
				})
				.attr({
					'src' : data.url,
					'height' : '315px',
					'width' : newWidth+'px'
				});
 		},
 		// prepare image cropper
 		jcropImageCropper: function(img, data){
 			// alert("test");
 			
 			$(img).Jcrop({
				aspectRatio: 1.54,
				trueSize: [data.width, data.height],
				setSelect: [0,0,300,250],

				onSelect: function(coord){
					$("#spot-cropper-ifrm input[name='start-x']").val(coord.x);
					$("#spot-cropper-ifrm input[name='start-y']").val(coord.y);
					$("#spot-cropper-ifrm input[name='target-x']").val(coord.w);
					$("#spot-cropper-ifrm input[name='target-y']").val(coord.h);
				}
			});
			// $('.jcrop-tracker','jcrop-holder').css({ 
			// 	"max-width": "514px",
			// 	"overflow": "hidden",
			// 	"width": "514px"
			// })
 		}
 	}

 	var googlePlaces = {
 		options: {
 			'map' : null,
 			'marker': null,
 			'autocomplete': null,
 			'latitude': 0,
 			'longitude': 0,
 			'zoom': 5,
 			'country': "South Africa",
 			'showMarker' : null
 		},
 		init: function(options){
 			var me = this;
 			me.options = $.extend(me.options, options);
 			
 			if( me.options.latitude != 0 && me.options.longitude != 0 )
 			{
 				me.options.map = null;
 				var myLatlng = new google.maps.LatLng(me.options.latitude, me.options.longitude);
 				var mapOptions = {
				    zoom: me.options.zoom,
				    center: myLatlng,
				    disableDefaultUI: true,
				    zoomControl: true,
				    mapTypeId: google.maps.MapTypeId.ROADMAP
				}
				me.showMap(mapOptions);
 			}
 			else {
 				me.options.map = null;
 				me.options.showMarker = null;

 				var geocoderRequest = {
		        	address: me.options.country
		        }
		        
	 			var geocoder = new google.maps.Geocoder();
				geocoder.geocode(
					geocoderRequest,
					function(response, status){
						
						var mapOptions = {
						    zoom: 5,
						    center: response[0].geometry.location,
						    // fitBounds: response[0].geometry.bounds,
						    disableDefaultUI: true,
						    zoomControl: true,
						    mapTypeId: google.maps.MapTypeId.ROADMAP
						}
						
						// if( me.options.map == null )
							me.showMap(mapOptions);

						me.options.country = response[0];
						var country = me.options.country.address_components[0].short_name.toLowerCase();
						var pac_options = {
			 				types: ['(cities)'],
			 				componentRestrictions: {country: country }
			 			}

			 			var pac_input = document.getElementById('google-place-search');
			 			me.autocomplete(pac_input, pac_options, function(place){
			 				
			 				if (place.geometry.viewport) {
								me.options.map.fitBounds(place.geometry.viewport);
							} else {
								me.options.map.setCenter(place.geometry.location);
								me.options.map.setZoom(14);
							}

							me.setCityData(place.name, place.geometry.location.lat(), place.geometry.location.lng());
							me.placeAutoComplete();

			 			}, "city");

					}
				);
 			}

 			
 		},
 		showMap: function(mapOptions){
 			var me = this;
 			
 			me.options.map = new google.maps.Map(document.getElementById("google-map"), mapOptions);

 			google.maps.event.addListener(me.options.map, 'click', function(event) {
				//call function to create marker
				if (me.options.marker) {
					me.options.marker.setMap(null);
					me.options.marker = null;
				}

				me.createMarker(event.latLng);
			
			});

			if( me.options.showMarker != null ){
				me.createMarker(mapOptions.center);
			}

 		},
 		autocomplete: function(input, options, callback, name){
 			var me = this;
 			
 			var _addEventListener = (input.addEventListener) ? input.addEventListener : input.attachEvent;

		    function addEventListenerWrapper(type, listener) {
		        // Simulate a 'down arrow' keypress on hitting 'return' when no pac suggestion is selected,
		        // and then trigger the original listener.
		        if (type == "keydown") {
		            var orig_listener = listener;
		            listener = function(event) {
		                var suggestion_selected = $(".pac-item.pac-item-selected").length > 0;
		                if (event.which == 13 && !suggestion_selected) {
		                    var simulated_downarrow = $.Event("keydown", {
		                        keyCode: 40,
		                        which: 40
		                    });
		                    orig_listener.apply(input, [simulated_downarrow]);
		                }

		                orig_listener.apply(input, [event]);
		            };
		        }

		        _addEventListener.apply(input, [type, listener]);
		    }

		    input.addEventListener = addEventListenerWrapper;
		    input.attachEvent = addEventListenerWrapper;

		    me.options.autocomplete = new google.maps.places.Autocomplete(input, options);
	    	if(me.options.map != null){
		    	me.options.autocomplete.bindTo('bounds', me.options.map);
		    }

		    google.maps.event.addListener(me.options.autocomplete, 'place_changed', function() {
			    var place = me.options.autocomplete.getPlace();
			    callback(place);
			    // me.setCityData(place.name, place.geometry.location.lat(), place.geometry.location.lng());
			});


 		},
 		setCityData: function(name, lat, lng){
 			$("#spot-city").val(name);
 			$("#spot-city-lat").val(lat);
 			$("#spot-city-lng").val(lng);

 			// $("#spot-info-form").find('input[type="text"], select[name="time_of_day"]').removeAttr('disabled');
			// $("#spot-info-form").find('input[type="text"], select[name="time_of_day"]').removeClass('disabled');
 		},
 		placeAutoComplete: function(){
 			var me = this;
 			var input = document.getElementById('spot-name-search');
 			var country = me.options.country.address_components[0].short_name.toLowerCase();
 			
 			var defaultBounds = new google.maps.LatLngBounds(new google.maps.LatLng($("#google-place-search").data('lat'), $("#google-place-search").data('lng')));
 			var options = {
 					bounds: defaultBounds,
	 				types: ['establishment'],
	 				componentRestrictions: {country: country }
	 			}

	 		var infowindow = new google.maps.InfoWindow();
			// me.options.marker = new google.maps.Marker({
			// 	map: me.options.map
			// });

	 		me.autocomplete(input, options, function(place){
 				
 				if (place.geometry.viewport) {
					me.options.map.fitBounds(place.geometry.viewport);
				} else {
					me.options.map.setCenter(place.geometry.location);
					me.options.map.setZoom(17);
				}

				me.options.marker = new google.maps.Marker({
					map: me.options.map,
					position: place.geometry.location
				});
				me.options.marker.setIcon(/** @type {google.maps.Icon} */({
					url: place.icon,
					size: new google.maps.Size(71, 71),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(17, 34),
					scaledSize: new google.maps.Size(35, 35)
				}));
				me.options.marker.setPosition(place.geometry.location);
				me.options.marker.setVisible(true);

				var address = '';
				if (place.address_components) {
					address = [
					(place.address_components[0] && place.address_components[0].short_name || ''),
					(place.address_components[1] && place.address_components[1].short_name || ''),
					(place.address_components[2] && place.address_components[2].short_name || '')
					].join(' ');
				}

				infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
				infowindow.open(me.options.map, me.options.marker);

				me.setSpotName(place.name);
				me.setAddress(place.formatted_address);
				me.setContactNumber(place.formatted_phone_number);
				me.setWebsite(place.website);
				// me.setFlickrPhotoSearchBox(place.name + ", " +place.formatted_address);
				me.setCoordinates(place.geometry.location.lat(), place.geometry.location.lng());
 			},"places");

			
 		},
 		setSpotName: function(name){
 			$("#spot-name").val(name);
 		},
 		setAddress: function(address){
 			$("#spot-address").val(address);
 		},
 		setContactNumber: function(number){
 			$("#spot-contact-number").val(number);
 		},
 		setWebsite: function(website){
 			$("#spot-website").val(website);
 		},
 		setCoordinates: function(lat, lng){
 			$("#latitude").val(lat);
 			$("#longitude").val(lng);
 		},
 		createMarker: function(latlng) {
 			var me = this;
		    
 			$("#spot-address-holder").hide();
			$("#spot-coord-holder").show();
			
		 	marker = new google.maps.Marker({
		        position: latlng,
		        map: me.options.map,
		        draggable: true,
		        zIndex: Math.round(latlng.lat()*-100000)<<5
		        
		    });

		 	me.locationDetails(latlng);
		 	// set the coordinates on click
		 	document.getElementById('latitude').value = latlng.lat();
	        document.getElementById('longitude').value = latlng.lng();

		 	google.maps.event.addListener(marker,'drag',function(event) {
		        document.getElementById('latitude').value = event.latLng.lat();
		        document.getElementById('longitude').value = event.latLng.lng();
		    });

		    google.maps.event.addListener(marker,'dragend',function(event) {
		        document.getElementById('latitude').value = event.latLng.lat();
		        document.getElementById('longitude').value = event.latLng.lng();
		        me.locationDetails(event.latLng);
		    });

		    me.options.marker = marker;
		 	
		},
		locationDetails: function(latlng){
			var me = this;
			var geocoderRequest = {
	        	location: latlng
	        }
	        

 			var geocoder = new google.maps.Geocoder();
			geocoder.geocode(
				geocoderRequest,
				function(response, status){
					var place = response[0];
					
					document.getElementById('spot-address').value = place.formatted_address;
					var arrAddress = place.address_components;
					// iterate through address_component array
					var foundLocality = false;
					$.each(arrAddress, function (i, address_component) {
					  	if ( foundLocality == false && (address_component.types[0] == "locality" || address_component.types[0] == "administrative_area_level_2")){
					    	document.getElementById('spot-city').value = address_component.long_name;
					    	document.getElementById('spot-city-lat').value = place.geometry.location.lat();
					    	document.getElementById('spot-city-lng').value = place.geometry.location.lng();
					    	document.getElementById('google-place-search').value = address_component.long_name;
					    	
					    	// me.placeAutoComplete();
					  		// return false;
					  		foundLocality = true;

					    }
					    else if(address_component.types[0] == "country" && document.getElementById("spot-country-search") !== null){
					    	document.getElementById("spot-country-search").value = address_component.long_name;
					    }
				    });

				    $("#spot-name-search").focus();
				});
		}

 	}

 	var bugglSpot = {
 		options: {
 			'env':'add',
 			'modal': $("#create-eguide-modal"),
 			'slug' : null, // this is the eguide slug
 			'day'  : 0,
 			'time_of_day' : 0,
 			'page' : 0,
 			'spot_id': 0,
 			'spot_detail_id' : 0,
 			'spot' : null,
 			'pagename' : 'localplaces' // localplaces or itinerary
 		},
 		replaceSpot: false,
 		formErrorCount : 0,
 		formErrors : {},
 		hasDuplicates: false,
 		ajaxSave : null,
 		init: function(options){
 			var me = this;
 			me.options = $.extend(me.options, options);
 			$("#create-eguide-modal").reveal({
				'closeOnBackgroundClick':false,
				open: function(){
					$(bugglSpot.options.modal).css({"width":"800px"	});
					me.wrapper = $("#add-spot-wrapper");
					me.getForm();
					
				}
			});

			
 		},
 		getForm: function(){
 			var me = this;
 			var slug = $("section.step-2").attr('id');
 			var data = {slug : slug, pagename: me.options.pagename};
 			if(0 < me.options.spot_id)
 				data = $.extend(data, {spot_id : me.options.spot_id});
 			if(0 < me.options.spot_detail_id)
 				data = $.extend(data, {spot_detail_id : me.options.spot_detail_id});
 			if(0 < me.options.day)
 				data = $.extend(data, {day_num : me.options.day});
 			if(0 < me.options.time_of_day)
 				data = $.extend(data, {time_of_day : me.options.time_of_day});

 			$.ajax({
 				url: bugglScriptName+'/local-author/get-add-spot-form',
 				data: data,
 				dataType: 'json',
 				beforeSend: function(){
 					$(me.options.modal).find('*').not('a.close-reveal-modal').remove();
 					loader.insert(me.options.modal);
 				},
 				success: function(response){
 					$(me.options.modal).find('*').not('a.close-reveal-modal').remove();
 					$(me.options.modal).append(response.html);

 					$("#change-spot").hide();
 					if(me.options.day == 0){
 						$('select[name="time_of_day"]', me.options.modal).parent('div').remove();
 					}

 					var country = $("#spot-country-name").text();
 					if(0 < me.options.spot_id){
 						$("#change-spot").show();
 						$("#google-place-search")
 							.addClass('disabled')
 							.attr('disabled', 'disabled');
 						$(".prev-next input[value='next']").focus();
 						var lat = $("#latitude").val();
 						var lng = $("#longitude").val();
 						mapOptions = {country: country, latitude : lat, longitude: lng, zoom : 14, showMarker : true};
 					}
 					else {
 						$("#spot-info-form").find('input[type="text"], select[name="time_of_day"]').removeAttr('disabled');
						$("#spot-info-form").find('input[type="text"], select[name="time_of_day"]').removeClass('disabled');
						
						if(country.length == 0)
						{
							country = "South Africa";
							me.prepareCountry();
						}

 						mapOptions = {country: country, latitude : 0, longitude: 0};
 						// $("#google-place-search").focus();
 					}
 					
 					// $("#google-place-search").focus();
					// prepare step 1
					googlePlaces.init(mapOptions);
					// if(0 < me.options.spot_id)
					// {
					// 	console.log("create marker");
					// 	googlePlaces.createMarker(new google.maps.LatLng( $("#latitude").val() , $("#longitude").val()));
					// }

					// if(country.length == 0)
 				// 		me.prepareCountry();

					// prepare step 2
 					me.prepareGooglePlacesSearch();
 					me.prepareUploadOwnPhoto();
 					// prepare step 3
 					me.prepareSpotType();
 					me.prepareSpotLike();
 					me.prepareSpotCategories();
 					me.prepareCustomCategory();
 					me.prepareCustomSpotLike();
 					// prepare step 4
 					me.prepareSpotRating();
 					me.prepareTinyMCE();
 					me.prepareCharacterLimits();

 					// prepare general methods
 					me.preparePaginationButtons();
 					me.miscelaneous();

 				}
 			});
 		},
 		// general methods
 		preparePaginationButtons: function(){
 			var me = this;
			$(".prev-next input[type='button']", me.options.modal)
 			.unbind()
 			.on('click', function(){
 				if("next" == $(this).val()){
 					// me.checkRequiredFields(this);
 					if(me.checkRequiredFields(this)){
 						if(1 == $(this).data('step')){
 							// me.options.spot = null; // reset spot
 							// if(me.options.env != "edit" || me.replaceSpot == true){
 							if(me.options.spot_id == 0)
 								me.checkAvailability();
 							else {
								$('div.add-spot-modal:visible')
			 						.hide()
			 						.next('div.add-spot-modal')
			 						.next('div.add-spot-modal')
			 						.show();
			 					$("ul.spots-nav", me.options.modal)
			 						.children('li.active')
			 						.removeClass('active')
			 						.next('li')
			 						.addClass('active');
 							}

	 						var qstring = $("#spot-name").val();
	 						$("#photo_search_web_box").val(qstring);
							
	 						$("#photo_search_web_box").next('input[name="photo_search_web_btn"]').trigger('click');
	 					}
	 					else if(1.5 == $(this).data('step')){
	 						me.checkSelectedDuplicate();
	 					}
	 					else {
	 						$(this).attr('onclick', me.nextPage(this));
	 					}

 					}

 				}
 				else if("prev" == $(this).val()){
 					$(this).attr('onclick', me.prevPage(this));
 				}
 				else if("save" == $(this).val()){
 					if(me.checkRequiredFields(this)){
	 					me.submitForms();
	 				}
 				}
 				else {
 					$(this).attr('onclick', me.cancel);
 				}

 			});
 		},
 		nextPage: function(element){
 			var me = this;
 			$(element).parents('div.add-spot-modal:first').hide().next('div.add-spot-modal').show();
 			// spots nav
 			$("ul.spots-nav", me.options.modal).children('li.active').removeClass('active').next('li').addClass('active');
 		},
 		prevPage: function(element){
 			var me = this;
 			var prevDiv = $(element).parents('div.add-spot-modal:first').hide().prev('div.add-spot-modal');
 			if($(prevDiv).attr('id') != "spot-check-duplicate")
 			{
 				$(prevDiv).show();
 			}
 			else {
 				$(prevDiv).prev('div.add-spot-modal').show();
 			}
 			// spots nav
 			$("ul.spots-nav", me.options.modal).children('li.active').removeClass('active').prev('li').addClass('active');
 		},
 		cancel: function(){
 			var me = this;
			
			$.fn.bugglConfirm({
				'title' : 'Discard changes?',
				'onConfirm' : function(){
					$("a.close-reveal-modal").trigger('click');
				},
				'onCancel' : function(){
					$('#create-eguide-modal').reveal();
				}
			});
 		},
 		checkRequiredFields: function(element){
 			var me = this;
 			var form = $(element).parents('div.add-spot-modal:first').find('form:visible');
 			me.formErrorCount = 0;
 			$(form).find('input, select').each(function(){
 				$(this).removeClass('required');
 				
 				if( ( $(this).data('required') != "undefined" && true == $(this).data('required') ) && ($(this).val() == 0 || $(this).val().length == 0)){
 					me.formErrorCount = me.formErrorCount + 1;
 					$(this).addClass('required');
 				}
 			});
 			
 			if(0 < me.formErrorCount){
 				$(element).parents('div.add-spot-modal:first').find("p.required-message").show();
 				return false;
 			}
 			else{
 				$(element).parents('div.add-spot-modal:first').find("p.required-message").hide();
 				return true;
 			}
 		},
 		checkAvailability: function(){
 			var me = this;
 			var name = $("#spot-name").val();
 			var address = $("#spot-address").val();

 			var lat = $("#latitude").val();
 			var lng = $("#longitude").val();
 			// alert(lat + " : " + lng);

 			$.ajax({
 				url: bugglScriptName+'/local-author/check-spot-availability',
 				data: { address: address, lat : lat, lng : lng, name : name },
 				dataType: 'json',
 				beforeSend: function(){
 					$('div.add-spot-modal:visible')
 						.hide()
 						.next('div.add-spot-modal')
 						.show();
						
 					$('div.add-spot-modal:visible')
 						.find('div.clearfix')
						.empty()
 						.css({'height': '375px', 'background' : 'url(/bundles/bugglmain/images/ajax-loader-1.gif) no-repeat center center'});
 				},
 				success: function(response){
 					if(typeof(response.html) != "undefined"){
						$('div.add-spot-modal:visible')
 							.find('div.clearfix')
 							.css({'background' : '', 'height' : ''})
 							.empty()
 							.append(response.html);
 						// me.prepareDuplicateActions();
 					}
 					else {
 						$('div.add-spot-modal:visible')
	 						.hide()
	 						.next('div.add-spot-modal')
	 						.show();
	 					$("ul.spots-nav", me.options.modal)
	 						.children('li.active')
	 						.removeClass('active')
	 						.next('li')
	 						.addClass('active');
 					}
 				}
 			});
 		},
 		miscelaneous: function(){
 			var me = this;
 			$("#change-spot-yes-btn")
 				.on('hover', function(){
 					$(this).css({ cursor : 'pointer'});
 				})
 				.on('click', function(e){
 					e.preventDefault();
 					me.replaceSpot = true;
 					me.fetchSpotInfo();
 					$("#change-spot").hide();
 					$("#spot-info-form").find('input[type="text"], select[name="time_of_day"]').removeAttr('disabled');
					$("#spot-info-form").find('input[type="text"], select[name="time_of_day"]').removeClass('disabled');
 				});
 			$("#change-spot-no-btn")
 				.on('hover', function(){
 					$(this).css({ cursor : 'pointer'});
 				})
 				.on('click', function(e){
 					e.preventDefault();
 					me.replaceSpot = false;
 					$(this).parents('div.add-spot-modal')
 						.find(".prev-next input[value='next']")
 						.trigger("click");
 				});

 			// change this to change event
 			// $("#spot-name-search")
 			// 	.on('blur', function(){
 			// 		setTimeout(function () {
				// 		if( 0 == $("#spot-name").val() ){
				// 			$("#spot-name").val( $("#spot-name-search").val() );
				// 	 	}
				// 	}, 800);
 			// 	});

 			$("#spot-name-search")
 				.on('change', function(){
 					$("#spot-name").val( $(this).val() );
 				});

 			$("#google-place-search")
 				.on('blur', function(){
 					setTimeout(function () {
 						if( 0 == $("#spot-city").val() ){
							$("#spot-city").val( $("#google-place-search").val() );
					 	}
					}, 800);
 				});
 		},
 		fetchSpotInfo: function(){
 			var me = this;
 			var data = { id : me.options.spot_id };
 			$.ajax({
 				url: bugglScriptName+'/local-author/fetch-spot-info',
 				data: data,
 				dataType: 'json',
 				beforeSend: function(){},
 				success: function(response){
 					if(response.success == true)
 						me.options.spot = response.spot;
 				}
 			});
 		},
 		// added step 1 methods when adding spot from the spot gallery
 		prepareCountry: function(){
 			var me = this;
 			var cache = {};
 			var country_ajax = null;
		    $( "#spot-country-search" ).autocomplete({
				minLength: 2,
				source: function( request, response ) {
					var term = request.term;
					if ( term in cache ) {
						response( cache[ term ] );
						return;
					}

					var url = bugglScriptName+'/ajax/fetch-buggl-countries';
					if(country_ajax != null)
						country_ajax.abort();

					country_ajax = $.getJSON( url, request, function( data, status, xhr ) {
						cache[ term ] = data;
						response( data );
					});
				},
				select: function(event, ui){
					me.prepareLocation(ui.item.label);
				}
		    })
		    .on('blur', function(e){
		    	if($(this).val().length > 1)
			    	me.prepareLocation($(this).val());
		    });

		    // $( "#spot-country-search" ).focus();
		    // if($( "#country-search" ).val().length > 0)
		    	// prepareLocation($( "#country-search" ).val());
 		},
 		prepareLocation: function(country){
 			if(googlePlaces.options.autocomplete != null){

				var geocoder = new google.maps.Geocoder();
				geocoder.geocode(
					{address:country},
					function(response, status){
						googlePlaces.options.country = response[0];
						var countryShortName = response[0].address_components[0].short_name.toLowerCase();
						googlePlaces.options.autocomplete.setComponentRestrictions({country:countryShortName});
				});

			}
			else {
				googlePlacesAutoComplete.init({address:country});
			}
 		},
 		// step 1.5
 		checkSelectedDuplicate: function(){
 			var me = this;
 			var e = $("ul#possible-duplicate-spot-list",  me.options.modal).find('input[type="radio"]:checked');
 			if(0 < $(e).val()){
 				me.options.spot_id = $(e).val();
 				me.fetchSpotInfo();
 				var name = $(e).data('name');

 				$("#photo_search_web_box").val(name);
				$("#photo_search_web_box").next('input[name="photo_search_web_btn"]').trigger('click');
				$('div.add-spot-modal:visible')
					.hide()
					.next('div.add-spot-modal')
					.show();
				$("ul.spots-nav", me.options.modal)
					.children('li.active')
					.removeClass('active')
					.next('li')
					.addClass('active');
 			}
 			else {
 				$('div.add-spot-modal:visible')
					.hide()
					.next('div.add-spot-modal')
					.show();
				$("ul.spots-nav", me.options.modal)
					.children('li.active')
					.removeClass('active')
					.next('li')
					.addClass('active');
 			}
 		},
 		// step 2 methods
 		prepareGooglePlacesSearch: function(){
 			$("#photo_search_web_btn", bugglSpot.modal)
 				.on('click',function(e){
 					e.preventDefault();
		 			googleCustomSearch.init();
 				});
				
			$("#photo_search_web_box", bugglSpot.modal)
				.on('keydown',function(e){
					var keycode =  e.keyCode ? e.keyCode : e.which;
					if(keycode == 13){
						$("#photo_search_web_box").next('input[name="photo_search_web_btn"]').trigger('click');
					}
				});
 		},
 		prepareUploadOwnPhoto: function(){
 			var me = this;
 			$("#spot-photo-ifrm input[name='spot-photo']")
 				.on('change', function(){
 					$("#spot-photo-ifrm").submit();
 				});

 			$("#spot-photo-ifrm")
				.iframePostForm({
					json : false,
					post: function(){
						$("#spot-photo-holder")
 						.css({'background' : 'url(/bundles/bugglmain/images/ajax-loader-1.gif) no-repeat center center'})
 						.empty();
					},
					complete : function (response){
						var response = $.parseJSON(response);
						googleCustomSearch.loadImage(response);
 						// var img = new Image();
	 					// $(img)
	 					// 	.load(function(){
	 					// 		$("#spot-photo-holder").css('background', '');
	 					// 		$("#spot-photo-holder").append(img);
	 					// 		$("form#spot-photo-ifrm > input[name='photo-url']").val(response.url);
	 					// 	})
	 					// 	.attr({'src' : response.url, 'id' : 'spot-photo-img', 'height' : '317px' });
					}
				});
 		},
 		// step 3 methods
 		prepareSpotType: function(){
 			var me = this;
 			$("ul[name='spot-secret-type']", me.options.modal)
 				.find('a')
 				.unbind('click')
 				.on('click', function(e){
 					e.preventDefault();
 					var form = $("#spot-step-3-frm", me.options.modal);
 					if($('input', form).not('[name="spot-secret"]').length > 0){
 						if( confirm("It seems that you have already selected type(s) or category, this action would remove those that you have selected.\nDo you want to continue?") )
 							$('input', form).not('[name="spot-secret"]').remove();
 						else 
 							return;
 					}

 					$("ul[name='spot-secret-type'] li.selected", me.options.modal).removeClass('selected');
 					$("ul[name='spot-secret-type']", me.options.modal).data('typeid', $(this).data('typeid'));
 					$(this).parent('li').addClass('selected');

 					if($(this).parent('li').data('name') == "Hidden Gems")
 					{
 						$("#spot-title-label").show();
 						$("#spot-title-input").show();
 					}
 					else {
 						$("#spot-title-label").hide();
 						$("#spot-title-input").hide();
 					}

 					me.updateTagsForm('secret',$(this).data('typeid'));
 					me.fetchSpotLike();
 					me.fetchSpotCategories();
 					
 				}
 			);
		},
		fetchSpotLike: function(){
 			var me = this;
 			$("div.category-select", me.options.modal).show();
 			
 			var typeId = $("ul[name='spot-secret-type']",me.options.modal).data('typeid');

 			$.ajax({
 				url: bugglScriptName+'/ajax/fetch-spot-likes',
 				data: { type_id : typeId },
 				dataType: 'json',
 				beforeSend: function(){
 					$("ul[name='spot-like']", me.options.modal).empty();
 					loader.insert($("ul[name='spot-like']", me.options.modal));
 				},
 				success: function(response){
 					$("ul[name='spot-like']", me.options.modal).empty();
 					$.each(response, function(index, value){
 						$("<a/>",{ href : ''})
 							.attr('data-likeid', value.id)
 							.text(value.name)
 							.appendTo($("ul[name='spot-like']", me.options.modal))
 							.wrap("<li/>");

 							me.prepareSpotLike();
 					});
 				}
 			});
 		},
 		prepareSpotLike: function(){
 			var me = this;
 			$("ul[name='spot-like']", me.options.modal)
				.find('a')
				.unbind('click')
				.on('click', function(e){
					e.preventDefault();
					var cnt = $("ul[name='spot-like'] li.selected", me.options.modal).length;
					if($(this).parent('li').hasClass('selected')){
						$(this).parent('li').removeClass('selected');
						me.updateTagsForm('like',$(this).data('likeid'), 'remove');
					}
					else if(cnt < 3){
						$(this).parent('li').toggleClass('selected');
						me.updateTagsForm('like',$(this).data('likeid'), 'add');
					}
				});
 		},
		fetchSpotCategories: function(){
			var me = this;
 			$("div.what-like", me.options.modal).show();

 			var typeId = $("ul[name='spot-secret-type']",me.options.modal).data('typeid');

 			$.ajax({
 				url: bugglScriptName+'/ajax/fetch-spot-categories',
 				data: { type_id : typeId },
 				dataType: 'json',
 				beforeSend: function(){
 					$("ul[name='spot-category']", me.options.modal).empty();
 					loader.insert($("ul[name='spot-category']", me.options.modal));
 				},
 				success: function(response){
 					$("ul[name='spot-category']", me.options.modal).empty();
 					$.each(response, function(index, value){
 						$("<a/>",{ href : ''})
 							.attr('data-categoryid', value.id)
 							.text(value.name)
 							.appendTo($("ul[name='spot-category']", me.options.modal))
 							.wrap("<li/>");
 							me.prepareSpotCategories();
 					});
 					
 				}
 			});
		},
		prepareSpotCategories: function(){
			var me = this;
			$("ul[name='spot-category']", me.options.modal)
				.find('a')
				.unbind('click')
				.on('click', function(e){
					e.preventDefault();
					var cnt = $("ul[name='spot-category'] li.selected", me.options.modal).length;
					if($(this).parent('li').hasClass('selected')){
						$(this).parent('li').removeClass('selected');
						me.updateTagsForm('category',$(this).data('categoryid'), 'remove');
					}
					else if(cnt < 3){
						$(this).parent('li').toggleClass('selected');
						me.updateTagsForm('category',$(this).data('categoryid'), 'add');
					}

				});
		},
		prepareCustomCategory: function(){
 			var me = this;
 			$("#save-custom-category")
				.on('click', function(e){
					e.preventDefault();
					var thisBtn = this;
					var newCatName = $(thisBtn).prev('input[name="new-category"]').val();
					var spotTypeId = $("ul[name='spot-secret-type']", me.options.modal).data('typeid');
					if(newCatName.length > 0)
					{

						$.ajax({
							url: bugglScriptName+'/local-author/save-custom-spot-category',
							data: { new_category_name : newCatName, spot_type_id : spotTypeId },
							dataType: 'json',
							beforeSend: function(){},
							success: function(response){
								var newCat = $("<a/>",{ href : ''})
				 							.attr('data-categoryid', response.id)
				 							.text(response.name)
				 							.appendTo($("ul[name='spot-category']", me.options.modal))
				 							.wrap("<li/>");

		 						me.prepareSpotCategories();

		 						if($("ul[name='spot-category'] li.selected", me.options.modal).length == 3 ){
		 							$("ul[name='spot-category'] li.selected:first a", me.options.modal).trigger('click');
		 						}

		 						$(newCat).trigger('click');

		 						$(thisBtn).prev('input[name="new-category"]').val("");
							}
						});
					}
				});
 		},
 		prepareCustomSpotLike: function(){
 			var me = this;
 			$("#save-custom-spot-like")
				.on('click', function(e){
					e.preventDefault();
					var thisBtn = this;
					var newSpotLike = $(thisBtn).prev('input[name="new-spot-like"]').val();
					var spotTypeId = $("ul[name='spot-secret-type']", me.options.modal).data('typeid');
					if(newSpotLike.length > 0)
					{

						$.ajax({
							url: bugglScriptName+'/local-author/save-custom-spot-like',
							data: { new_spot_like : newSpotLike, spot_type_id : spotTypeId },
							dataType: 'json',
							beforeSend: function(){},
							success: function(response){
								$(thisBtn).prev('input[name="new-category"]').val("");

			 					var newLike	= $("<a/>",{ href : ''})
			 							.attr('data-likeid', response.id)
			 							.text(response.name)
			 							.appendTo($("ul[name='spot-like']", me.options.modal))
			 							.wrap("<li/>");

			 					me.prepareSpotLike();

			 					if($("ul[name='spot-like'] li.selected", me.options.modal).length == 3){
			 						$("ul[name='spot-like'] li.selected:first a", me.options.modal).trigger('click');
			 					}
			 					$(newLike).trigger('click');

			 					$(thisBtn).prev('input[name="new-spot-like"]').val("");
							}
						});
					}
				});
 		},
		updateTagsForm: function(updateType, value, action){
 			var me = this;
 			var action = ("undefined" == typeof(action)) ? "add" : action;
 			var form = $("#spot-step-3-frm", me.options.modal);
 			if("secret" == updateType){
 				if($("input[name='spot-secret']",form).length == 0){
 					$("<input/>",{ type: 'text', name: 'spot-secret'}).val(value).appendTo(form);
 				}
 				else {
 					$("input[name='spot-secret']",form).val(value);
 				}
 			}
 			else if("category" == updateType) {
 				var mId = "spot-category-"+value;
 				if("add" == action){
 					$("<input/>",{ type: 'text', name: 'spot-categories[]', id: mId}).val(value).appendTo(form);
 				}
 				else {
 					$("input#"+mId).remove();
 				}
 			}
 			else if("like" == updateType){
 				var mId = "spot-like-"+value;
 				if("add" == action){
 					$("<input/>",{ type: 'text', name: 'spot-likes[]', id: mId}).val(value).appendTo(form);
 				}
 				else {
 					$("input#"+mId).remove();
 				}

 			}
 		},
 		// step 4 methods
 		prepareSpotRating: function(){
 			var me = this;
 			var rating = $("ul.add-spot-rating", me.options.modal).find("a");
 			$(rating).each(function(){
 				var parent = $(this).parent('li:first');

 				$(this)
 				.on('hover',
 					function(){},
 					function(){
 						$(parent).prevAll().addClass('active');
 						$(parent).nextAll().removeClass('active');
 						$(parent).addClass('active');
 						$("input[name='spot-rating']", me.options.modal).val($(this).data('spotrating'));
 						$("span[name='spot-rating-desc']").hide();
 						$("span#spot-rating-"+$(this).data('spotrating')).show();
 					}
 				)
 				.on('click', function(e){
 					e.preventDefault();
 					$(parent).addClass('active').prevAll().addClass('active');
 					$("input[name='spot-rating']", me.options.modal).val($(this).data('spotrating'));
 					$("span[name='spot-rating-desc']").hide();
 					$("span#spot-rating-"+$(this).data('spotrating')).show();
 				});
 			});
 		},
 		prepareTinyMCE: function(){
 			var me = this;
			
 			var textArea = $("textarea", me.options.modal);
			var textAreaId = textArea.attr('id');
			var limit = textArea.attr('char-limit');
			var is_default = false;
			var tiny_mce_default_value = $(textArea).text();
			textArea.tinymce({
 				script_url : '/bundles/bugglmain/js/tiny_mce/tiny_mce.js',
 				width: '100%',
 				height: '100%',
 				theme : "advanced",
				plugins : "paste",
				// Theme options
				theme_advanced_buttons1 : "bold,italic,underline",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_buttons4 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,
				gecko_spellcheck : true, // enable native browser spell-check
				theme_advanced_path : false, // remove path: p
				handle_event_callback: function(e){
					if('keydown' == e.type){
						is_default = false;
						var ed = tinymce.get(textAreaId);
 						var text = $(ed.getContent()).text();
 						var keycode =  e.keyCode ? e.keyCode : e.which;
				        		
				        if(keycode >= 37 && keycode <= 40){
				            return true;
				        }
						
						// maybe we can do a better job here with the +1
				        var initialCount = text.length + 1;
				        var cntr = limit - text.length;
				        
				        if(keycode == 8 || keycode == 46){
							var selectedText = ed.selection.getContent({format : 'text'});
							var subtrahend = selectedText.length > 0 ? selectedText.length : 1
				        	var nCntr = (text.length == 0) ? limit : parseInt(cntr) + subtrahend;
				        	$("#"+textAreaId+"-char-count").val(nCntr > limit ? limit : nCntr);

				            return true;
				        }
				        else {
				            if(cntr <= 0){
								$("#"+textAreaId+"-char-count").val(0);
				                return false;
				            }
				            var x = limit - initialCount;
				            $("#"+textAreaId+"-char-count").val(x);
				        }
 					}
					
 					if('mousedown' == e.type || "keyup" == e.type){
 						if (!is_default)
			                return;
			    		
			    		var ed = tinymce.get(textAreaId);
			            // nothing to do
			            ed.setContent('');
			            // replace the default content with nothing
 					}
				},
				oninit: function(){
					var body = tinymce.get(textAreaId).getBody();
					$(body).css({'background-color':'#CCCCCC'});
					var ed = tinymce.get(textAreaId);

					var cont = ed.getContent();
					slen = cont.length;
		            cont = cont.substring(3,slen-4);
		    		
		            // cut off <p> and </p> to comply with XHTML strict
		            // these can't be part of the default_value 
		            is_default = (cont == tiny_mce_default_value);

		            // compare those strings
		            if (!is_default)
		                return;
		        
		            // nothing to do
		            // ed.selection.select(ed.dom.select('p')[0]);		        
		            // select the first (and in this case only) paragraph


					var text = $(ed.getContent()).text();
					
					var cnt = limit - text.length;
					$("#"+textAreaId+"-char-count").val(cnt);
				},
				paste_preprocess: function(p1,o){
					var ed = tinymce.get(textAreaId);
					// formatting will be removed
					var text = $(ed.getContent()).text();
		            var pastedText = $("<p>"+o.content+"</p>").text();
		            if(text.length + pastedText.length >= limit){
		            	o.content = pastedText.substring(0, limit - text.length);
		            	$("#"+textAreaId+"-char-count").val(0);
		            }
		            else {
		            	o.content = pastedText;
		            	var cnt = limit - (text.length + pastedText.length);
		            	$("#"+textAreaId+"-char-count").val(cnt);
		            }
				}
 			});
 		},
 		prepareCharacterLimits: function(){
			var me = this;
			
 			var field = $(".character-limited", me.options.modal);
			var fieldId = field.attr('id');
			var limit = field.attr('char-limit');
			var remainingCharsDisplay = $('#'+fieldId+'-char-count');
			
			field.val((field.val().substring(0, limit)));
			remainingCharsDisplay.val(limit - field.val().length);
			field.on('keyup keydown',function(){
				var curLen = field.val().length;
			
		        if ( curLen > limit ){
		        	field.val(field.val().substring(0, limit));
		        }
				curLen = field.val().length;
				if(remainingCharsDisplay !== null){
					remainingCharsDisplay.val(limit - curLen);
				}
		    });
			
			field.bind("contextmenu", function(e) {
	            e.preventDefault();
	        });
		},
		submitForms: function(){
			$("#spot-info-form").find('input[type="text"], select[name="time_of_day"]').removeAttr('disabled');
			$("#spot-info-form").find('input[type="text"], select[name="time_of_day"]').removeClass('disabled');

			var me = this;
			var data = { 's' : Math.random(),
						'env' : me.options.env,
						'day_num' : me.options.day,
						'page' : me.options.page,
						'pagename' : me.options.pagename,
						'spotId' : me.options.spot_id,
						'spotDetailId' : me.options.spot_detail_id };

			if(me.options.slug != null)
				data = $.extend(data, {'slug' : me.options.slug});
			
			if(me.options.env == "edit")
 				var url = bugglScriptName+'/local-author/update-spot';
 			else
 				var url = bugglScriptName+'/local-author/save-spot';

			var data = decodeURIComponent($.param(data));

			$("form", me.options.modal)
 				.each(function(){
 					data = data + "&" + $(this).serialize();
 				});

 			if(me.ajaxSave == null)
 			{
 				me.ajaxSave = $.ajax({
			 				url: url,
			 				data : data,
			 				dataType: 'json',
			 				beforeSend: function(){},
			 				success: function(response){
			 					if(me.options.env == "edit"){
			 						window.location.reload();
			 					}
			 					else {
			 						var url = "http://" + window.location.host + response.redirectLink;
				 					window.location.assign(url);
			 					}
			 				}
			 			});
 			}
		}
 		
 	}

 	var spotList = {
 		options : {
 			'context' : 'local-secret',
 			'type' : 'local-secret',
 			'modal': $("#create-eguide-modal"),
 			'slug' : 0,
 			'day'  : 0,
 			'page' : 0,
 			'pagename' : 'localplaces',
 			'show_forgot_spot' : true
 		},
 		init: function(options){
 			var me = this;
 			me.options = $.extend(me.options, options);
 			
 			$(me.options.modal).reveal({
				'closeOnBackgroundClick':false,
				open: function(){
					$(me.options.modal).css({"width":"800px"});
					me.fetchList('all',true,true);
				}
			});
			
 		},
 		fetchList: function(spotType, initialLoad, changeTab){
 			var me = this;
			var page = 0;
			var tab = $('.spotlist-filter > li.selected > a');
			if(!initialLoad){
				page = parseInt(tab.attr('data-page'));
			}
			
 			$.ajax({
 				url: bugglScriptName+'/local-author/get-spots-list',
 				data: { type: me.options.type, slug: me.options.slug, daynum : me.options.day, spotType : spotType, initialLoad : initialLoad, page : page},
 				dataType: 'json',
 				beforeSend: function(){
					if(initialLoad){
	 					$(me.options.modal).find('*').not('a.close-reveal-modal').remove();
	 					loader.insert(me.options.modal);
					}
					else{
						if(changeTab){
							loader.insert('div#loaderContainer');
							$('#spot-gallery > li',me.options.modal).hide();
						}
						else{
							loader.insert('div.show-more');
						}
					}
					$('a#show-more').hide();
 				},
 				success: function(response){
					if(initialLoad){
	 					$(me.options.modal).find('*').not('a.close-reveal-modal').remove();
	 					$(me.options.modal).append(response.html);
						
						tab = $('.spotlist-filter > li.selected > a');
					}
					else{
						loader.del();
						$('#spot-gallery',me.options.modal).append(response.html);
					}
					
					$('a#show-more').show();
					tab.attr('data-page',page+1);
					if(response.hasNext){
						$('div.show-more').show();
						tab.attr('data-has-next',1);
					}
					else{
						$('div.show-more').hide();
						tab.attr('data-has-next',0);
					}
 					
 					me.prepareSelectSpot();
					me.updateSelection();

 					$("#forgot-a-spot", me.options.modal)
 						.on('click', function(e){
 							e.preventDefault();
 							
 							var options = {
								page: me.options.page,
								pagename: $(this).data('pagename'),
								daynum: $(this).data('daynum'),
								name: $(this).attr('name')
							}
							if( $("#spot-gallery-setting-form").children().length > 0 ){
								
								me.premaSave();
							}
							spotChooser.init(options);
 						});

 					(me.options.show_forgot_spot == true) ? $("a.forgot-spot",me.options.modal).show() : $("a.forgot-spot",me.options.modal).hide();
 					$('[name="modal-cancel-btn"]', me.options.modal)
 						.on('click', function(e){
 							e.preventDefault();
 							$("a.close-reveal-modal", me.options.modal).trigger('click');
 						});
 				}
 			});
 		},
		updateSelection: function(){
			$("#spot-gallery-setting-form > input").each(function(){
				var me = this;
				var dayer = $(me).attr('name').substring(13, 14);
				var spotId = $(me).attr('id').substring(18);
				var times = { 
					'0' : 'ADDED',
					'1' : 'MORNING',
					'2' : 'AFTERNOON',
					'3' : 'EVENING'
				};
				
				var parent = $('#spot-gallery > li[data-spotid="'+spotId+'"].column');
				$(parent).attr('name', 'added');
				$(".spot-dayer", parent).text(times[dayer]).show();
			});
		},
 		prepareSelectSpot: function(){
 			var me = this;
 			var form = $('#spot-gallery-setting-form');
 			if(me.options.context == 'local-secret'){
 				$("#spot-gallery").find("li[name='time-of-day-setting']").remove();
 			}
 			else {
 				$("#spot-gallery").find("li[name='add-to-local-secret-setting']").remove();
 			}

 			$("ul[name='add-spot-from-list-setting']")
				.find('a[name="remove-setting"]')
				.off('click')
				.on('click', function(e){
					e.preventDefault();
					
					var spotId = $(this).parents('li.column').data('spotid');
					
					$('#spot-gallery > li[data-spotid="'+spotId+'"].column').each(function(){
						var ako = this;
						
						$(ako).removeAttr('name');
						$(".spot-dayer", ako).hide();
						if(me.options.context == 'itinerary'){
							$(ako).removeData('time');
						}
					});
					
					var inputId = 'spot-gallery-form-'+$(this).parents('li.column').data('spotid');
					$("input#"+inputId, form).remove();
				});

			$("ul[name='add-spot-from-list-setting']")
				.find('a:not([name="remove-setting"])')
				.off('click')
				.on('click', function(e){
					e.preventDefault();
					var ako = this;
					
					var spotId = $(ako).parents('li.column').data('spotid');
					var time_of_day = $(ako).data('time');
					var callback = function(detailID)
					{
						var parent = $(ako).parents('li.column');
						$(parent).attr('name', 'added');
						var spotId = $(ako).parents('li.column').data('spotid');
						if(me.options.context == 'itinerary'){
							var time = $(ako).data('time');
							if(parseInt(time) == 1)
								var timeText = 'MORNING';
							else if(parseInt(time) == 2)
								var timeText = 'AFTERNOON';
							else
								var timeText = 'EVENING';
							$(".spot-dayer", parent).text(timeText).show();
							$(ako).parents('li.column').data('time',$(ako).data('time'));


							var inputName = 'spot-details['+$(ako).data('time')+'][]';
							var inputId = 'spot-gallery-form-'+spotId;
							$("#"+inputId, form).remove();
							$("<input/>",{ type: 'text', name: inputName, id: inputId})
								.val(detailID)
								.appendTo(form);
						}
						else {
							var inputName = 'spot-details[0][]';
							var inputId = 'spot-gallery-form-'+spotId;
							$("#"+inputId, form).remove();
							$("<input/>",{ type: 'text', name: inputName, id: inputId})
								.val(detailID)
								.appendTo(form);
							$(".spot-dayer", parent).text('ADDED').show();
						}
						me.updateSelection();
					}

					if(me.options.type == "spot-library"){
						me.fetchSpotDescriptions( spotId, time_of_day, function( detailID ){
							callback( detailID );
						} );
					}
					else {
						var detailID = $(ako).parents('li.column').data('detailid');
						callback( detailID );
					}

				});

			$("#spot-gallery-save-changes").off('click').on('click',function(){
				me.saveChanges();
			});
 		},
 		fetchSpotDescriptions: function(id, time_of_day, callback){
 			var me = this;
 			var multiDescModal = $("#multiple-decription-modal");
 			var holder = $("ul#multi-desc-list", multiDescModal);

 			var multiDescModalCSS = {'top' : (parseInt($(document).scrollTop()) - 200) + 'px'};
 			
 			$("a.close, a[name='cancel-modal']", multiDescModal)
 				.unbind('click')
 				.on('click', function(e){
 					e.preventDefault();
 					$(multiDescModal).hide();
 				});
 			$("a[name='confirm-btn']",multiDescModal)
 				.unbind('click')
 				.on('click', function(e){
 					// alert('confirmed');
 					var detailID = $("input[name='spot-detail-desc']:checked", holder).val();
 					if(detailID != "undefined"){
 						if(0 < detailID){
 							callback( detailID );
 						}
 						else {
 							$("html, body").animate({ scrollTop: 20 }, 600);
 							var options = {
								"env" : "add",
								"slug" : me.options.slug,
								"day" : me.options.day,
								"time_of_day" : time_of_day,
								"page" : me.options.page,
								"pagename" : me.options.pagename,
								"spot_id" : id,
								"spot_detail_id": 0
							}

							if( $("#spot-gallery-setting-form").children().length > 0 ){
								me.premaSave();
							}

							bugglSpot.init(options);
							bugglSpot.getForm();
 						}
 						
 						$(multiDescModal).hide();
 					}
 				});

 			$.ajax({
 				url: bugglScriptName + "/local-author/fetch-spot-details/" + id,
 				dataType: 'json',
 				beforeSend: function(){
 					$(multiDescModal)
 						.show()
 						.css( multiDescModalCSS );
 					$(holder).empty();
 					$(holder).append("<li/>");
					loader.insert($("li:first", holder));
 				},
 				success: function(response){
 					if(typeof(response.error) == "undefined"){
 						$(holder)
 							.empty()
 							.append(response.html);
 					}
 				}
 			});
 		},
 		saveChanges: function(){
 			var me = this;
 			var gallery = $("#spot-gallery");
 			
 			if($("li[name='added']", gallery).length > 0){
 				$('#spot-list-wrapper .required-message').hide();
 				var guide_id = $("#spot-gallery").data('guideid');
 				var data = $("#spot-gallery-setting-form").serialize();
 				data = data + "&pagename="+me.options.pagename+"&day_num="+me.options.day+"&page="+me.options.page;
 				$.ajax({
	 				url: bugglScriptName+"/local-author/add-spot-to-guide/"+guide_id,
	 				data : data,
	 				dataType: 'json',
	 				beforeSend: function(){},
	 				success: function(response){
	 					var url = "http://" + window.location.host + response.redirectLink;
	 					window.location.assign(url);
	 		// 			window.location.reload();
	 				}
	 			});
 			}
 			else {
 				$('#spot-list-wrapper .required-message').show();
 			}

 		},
 		premaSave: function(){
 			var me = this;
			$("<input/>",{ type: 'text', name: 'day_num', id: 'day_num'})
				.val(me.options.day)
				.appendTo($("#spot-gallery-setting-form"));

			var guide_id = $("#spot-gallery").data('guideid');
			$("#spot-gallery-setting-form")
				.attr("action", bugglScriptName+"/local-author/add-spot-to-guide/"+guide_id);

			$("#spot-gallery-setting-form")
				.iframePostForm({
					post: function(){},
					complete : function (response){
						// console.log("process complete;");
					}
				});
			$("#spot-gallery-setting-form").submit();
 		}

 	}

 	var spotChooser = {
 		options: {
 			page: 0,
 			pagename: "localplaces",
 			daynum: 0,
 			name: 'add-daily-spot'
 		},
 		init: function(options){
 			var me = this;
 			me.options = $.extend(me.options, options);
 			
 			var chooser_modal = $("#add-spot-chooser");
			$("#add-new-eguide-spot").data('page', me.options.page);
			$("#add-new-eguide-spot").data('pagename', me.options.pagename);
			$("#add-new-eguide-spot").data('daynum', me.options.daynum);

			$("#add-spot-chooser").reveal({
				dismissModalClass: "modal-cancel-btn",
 				open: function(){
 					if(me.options.name == 'add-daily-spot'){
 						var context = "itinerary";
 						var pagename = "itinerary";
					}
					else {
						var context = "local-secret";
						var pagename = "localplaces";
					}

					$("#add-from-spot-library").data('context', context);
					$("#add-from-spot-library").data('pagename', pagename);
					$("#add-from-spot-library").data('page', me.options.page);
					$("#add-from-spot-library").data('daynum', me.options.daynum);
					$(".modal-cancel-btn").on('click', function(e){ e.preventDefault(); });
 				}
 			});
 		}
 	}

 	$("[name='add-daily-spot']")
		.unbind('click')
		.on('click', function(e){
			e.preventDefault();
			var options = {
				"context" : "itinerary",
				"type" : "local-secret",
				"slug" : $("section.step-2").attr('id'),
				"day"  : $(this).data('daynum'),
				"page"  : $(this).data('page'),
				"pagename" : "itinerary",
				"show_forgot_spot" : true
			}
			
			spotList.init(options);
		});

	var addSpotOptionsHtml = '<div id="add-spot-chooser" class="reveal-modal small" style="background: none repeat scroll 0 0 #FFFFFF !important;padding: 30px;">'+
							'<div class="content choose-spot-type">'+
								'<h3>You are about to add a new place to your travel guide.</h3>'+
								'<ul>'+
								'<li><a class="admin-button" href="" name="add-new-spot" id="add-new-eguide-spot">Add New Place</a></li>'+
								// '<li><a class="admin-button" href="" name="add-from-local-secrets" id="add-from-local-secrets">Choose from Local Secrets</a></li>'+
								'<li><a class="admin-button" href="" name="spots-list" id="add-from-spot-library">Select from your places library</a></li>'+
								'<li><a class="admin-button cancel modal-cancel-btn" name="modal-cancel-btn" href="">cancel</a></li>'+
								'</ul>'+
							'</div>'+
						'</div>';
	$('body').append(addSpotOptionsHtml);

	$("#add-new-eguide-spot")
		.unbind('click')
		.on('click', function(e){
			e.preventDefault();
			var options = {
				"env" : "add",
				"slug" : $("section.step-2").attr('id'),
				"day" : $(this).data('daynum'),
				"page" : $(this).data('page'),
				"pagename" : $(this).data('pagename'),
				"spot_id" : 0,
				"spot_detail_id": 0,
				"latitude" : 0,
				"longitude" : 0
			}
			bugglSpot.init(options);
		});

	$("#add-from-spot-library")
		.unbind('click')
		.on('click', function(e){
			e.preventDefault();
			
			var options = {
				"context" : $(this).data('context'),
				"type" : "spot-library",
				"slug" : $("section.step-2").attr('id'),
				"day"  : $(this).data('daynum'),
				"page" : $(this).data('page'),
				"show_forgot_spot" : false
			}
			
			spotList.init(options);
		});

	$("[name='add-local-secret']")
		.unbind('click')
		.on('click', function(e){
			e.preventDefault();
			var me = this;
			var options = {
				page: $(this).data('page'),
				daynum: $(this).data('daynum'),
				name: $(this).attr('name')
			}
			spotChooser.init(options);
		});

	$("[name='remove-spot']")
 		.on('click', function(e){
 			e.preventDefault();
 			// if(confirm("Are you sure you want to remove this spot?")){
			var dest_url = $(this).attr('href');

 			$.fn.bugglConfirm({
 				'title': 'Remove Place',
 				'content': "<p>Are you sure you want to remove this place?</p>",
				'onConfirm' : function(){
					$.ajax({
		 				url : dest_url,
		 				dataType: 'json',
		 				beforeSend: function(){},
		 				success: function(response){
		 					
		 					window.location.reload();
		 				}
		 			});
				}
			});
 		});
	
 	$("[name='edit-spot']")
 		.on('click', function(e){
 			e.preventDefault();
 			var options = {
 				"env" : "edit",
				"slug" : $("section.step-2").attr('id'),
				"day" : $(this).data('daynum'),
				"page" : $(this).data('page'),
				"spot_id" : $(this).data('spotid'),
				"spot_detail_id": $(this).data('detailid'),
				"pagename" : $(this).data('pagename')
			}

			
			bugglSpot.init(options);
 		});
	
	$(spotList.options.modal)
		.on('click',"[name='spot-type']",function(e){
			e.preventDefault();
			$(".spotlist-filter > li").removeClass('selected');
			$(this).parent('li').addClass('selected');
			if(parseInt($(this).attr('data-page')) == 0){
				spotList.fetchList($(this).attr('data-spot-type'),false,true);
			}
			else{
				$('#spot-gallery > li').hide();
				$('#spot-gallery .'+$(this).attr('data-spot-type')).show();
				if($(this).attr('data-has-next') == '1'){
					$('div.show-more').show();
				}
				else{
					$('div.show-more').hide();
				}
			}
		});
		
	$(spotList.options.modal)
		.on('click',"#show-more",function(e){
			e.preventDefault();
			
			var activeTab = $('.spotlist-filter > li.selected > a');
			spotList.fetchList(activeTab.attr('data-spot-type'),false,false);
		});

	$("#add-new-spot")
		.on('click', function(e){
			e.preventDefault();
			var options = {
 				"env" : "add"
			}

			bugglSpot.init(options);
		});
		
	$('#multiple-decription-modal').on('click','#multi-desc-list > li',function(e){
		if($(e.target).is('input[name="spot-detail-desc"]')){
			$(this).siblings("li").removeClass('selected');
			$(this).addClass('selected');
		}
		else{
			$(this).children('input[name="spot-detail-desc"]').trigger('click');
		}
	});
});
