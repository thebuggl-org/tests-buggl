/**
 * Buggl Add Spot jquery plugin
 * Version 2.0
 * NOTE:
 * 		Think of way to combine edit spot and duplicate spot functionality
 */
// var d = new Date();
// alert("bugglSpot encodeURIComponent:"+d.getTime());
$().ready(function(){
	// alert('v2.0 redirect');
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
			// console.log(url);
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
					// console.log(data);
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

 			// console.log(data);
 			var prevStart = data.queries.request[0].startIndex - me.options.num;

 			$("#photo-search-web-prev").data({'start' : prevStart});
 			if(typeof(data.queries.nextPage) != 'undefined'){
 				$("#photo-search-web-next").data({'start' : data.queries.nextPage[0].startIndex});
 			}


			me.prepareImageClick();
			me.prepareButtons();
 		},
 		prepareButtons: function(){
 			// console.log('prepareButtons');
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
 					// console.log('change photo');
 					e.preventDefault();
 					$("#spot-photo-holder")
 						.css({'background' : 'url(/bundles/bugglmain/images/ajax-loader-1.gif) no-repeat center center'})
 						.empty();

 					var imgSrc = $(this).data('bigpiclink');
 					var img = new Image();
 					$(img)
 						.load(function(){
 							$("#spot-photo-holder").css('background', '');
 							$("#spot-photo-holder").append(img);
 							$("form#spot-photo-ifrm > input[name='photo-url']").val(imgSrc);
 						})
 						.attr({'src' : imgSrc, 'id' : 'spot-photo-img', 'height' : '317px' });

 				});
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
 		},
 		init: function(options){
 			var me = this;
 			me.options = $.extend(me.options, options);
 			// console.log(me.options);
 			var geocoderRequest = {
	        	address: me.options.country
	        }
 			var geocoder = new google.maps.Geocoder();
			geocoder.geocode(
				geocoderRequest,
				function(response, status){
					// console.log(response);

					var mapOptions = {
					    zoom: me.options.zoom,
					    center: response[0].geometry.location,
					    disableDefaultUI: true,
					    zoomControl: true,
					    mapTypeId: google.maps.MapTypeId.ROADMAP
					}

					me.options.map = new google.maps.Map(document.getElementById("google-map"), mapOptions);
					// console.log(response[0].address_components[0].short_name);
					me.options.country = response[0];
					var country = me.options.country.address_components[0].short_name.toLowerCase();
					var pac_options = {
		 				types: ['(cities)'],
		 				componentRestrictions: {country: country }
		 			}

		 			var pac_input = document.getElementById('google-place-search');
		 			me.autocomplete(pac_input, pac_options, function(place){
		 				// console.log(place);
		 				if (place.geometry.viewport) {
							me.options.map.fitBounds(place.geometry.viewport);
						} else {
							me.options.map.setCenter(place.geometry.location);
							me.options.map.setZoom(14);  // Why 17? Because it looks good.
						}

						me.setCityData(place.name, place.geometry.location.lat(), place.geometry.location.lng());
						me.placeAutoComplete();

		 			}, "city");

					// $(pac_input).focus();
				}
			);
 		},
 		autocomplete: function(input, options, callback, name){
 			var me = this;
 			// console.log('execute autocomplete');
 			var _addEventListener = (input.addEventListener) ? input.addEventListener : input.attachEvent;

		    function addEventListenerWrapper(type, listener) {
		        // Simulate a 'down arrow' keypress on hitting 'return' when no pac suggestion is selected,
		        // and then trigger the original listener.
		        if (type == "keydown") {
		            var orig_listener = listener;
		            listener = function(event) {
		                var suggestion_selected = $(".pac-item.pac-selected").length > 0;
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
 			// console.log(country);
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
 				// console.log(place);

 				if (place.geometry.viewport) {
					me.options.map.fitBounds(place.geometry.viewport);
				} else {
					me.options.map.setCenter(place.geometry.location);
					me.options.map.setZoom(17);  // Why 17? Because it looks good.
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

			// console.log('set focus');
			
 		},
 		setSpotName: function(name){
 			// $("#spot-name-search").val(name);
 			$("#spot-name").val(name);
 			// alert(name);
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
 		displayMap: function(){
 			var me = this;
 			// me.options = $.extend(me.options, options);
 			// console.log(me.options);
			var coords = new google.maps.LatLng(me.options.latitude, me.options.longitude);

 			var mapOptions = {
			    zoom: me.options.zoom,
			    center: coords,
			    disableDefaultUI: true,
			    zoomControl: true,
			    mapTypeId: google.maps.MapTypeId.ROADMAP
			}

			me.options.map = new google.maps.Map(document.getElementById("google-map"), mapOptions);

 		},
 	}

 	var bugglSpot = {
 		options: {
 			'env':'add',
 			'modal': $("#create-eguide-modal"),
 			'slug' : null, // this is the eguide slug
 			'day'  : 0,
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
 			// $("#create-eguide-modal").reveal('destroy');
 			$("#create-eguide-modal").reveal({
				open: function(){
					$(bugglSpot.options.modal).css({"width":"800px"	});
					// if(me.options.env == "edit"){
					// 	me.wrapper = $("#edit-spot-wrapper");
					// 	me.getEditForm();
					// }
					// else {
						me.wrapper = $("#add-spot-wrapper");
						me.getForm();
					// }


				}
			});
 		},
 		getForm: function(){
 			var me = this;
 			var slug = $("section.step-2").attr('id');

 			if(me.options.env == "edit")
 			{
 				var data = {slug : slug, spot_id : me.options.spot_id};
 			}
 			else {
 				var data = {slug : slug};
 			}

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

 					me.preparePaginationButtons();
 					// me.prepareFlickrPhotoSearch();
 					// if(me.options.env != "edit")
 					// {
 						// alert('prepareGooglePlacesSearch');
 						me.prepareGooglePlacesSearch();
 						me.prepareUploadOwnPhoto();
 					// }

 					if(me.options.day == 0)
 					{
 						$('select[name="time_of_day"]', me.options.modal).parent('div').remove();
 					}

 					var country = $("#spot-country-name").text();

 					me.miscelaneous();
 					me.prepareTags();
 					me.prepareSpotRating();
 					me.prepareTinyMCE();
					me.prepareCharacterLimits();

 					var mapOptions = {};
 					$("#spot-edit-message").hide();
 					$("#change-spot").hide();
 					if(me.options.env == "edit"){
 						$("#change-spot").show();
 						$("#google-place-search")
 							.addClass('disabled')
 							.attr('disabled', 'disabled');
 						$(".prev-next input[value='next']").focus();
 						var lat = $("#spot-city-lat").val();
 						var lng = $("#spot-city-lng").val();
 						mapOptions = {country: country, latitude : lat, longitude: lng, zoom : 14};

 						$("#spot-edit-message").show();

 						me.subPrepareCategoriesEvents();
 						me.subPrepareSpotLikeEvents();
 					}
 					else if(country.length > 0) {
 						mapOptions = {country: country};
 						$("#google-place-search").focus();
 					}

 					googlePlaces.init(mapOptions);

 					if(country.length == 0)
 					{
 						me.prepareCountry();
 					}


 					$("#spot-info-form").find('input[type="text"], select[name="time_of_day"]').removeAttr('disabled');
					$("#spot-info-form").find('input[type="text"], select[name="time_of_day"]').removeClass('disabled');
 				}
 			});
 		},
 		miscelaneous: function(){
 			var me = this;

 			$("#change-spot-yes-btn")
 				.on('click', function(e){
 					e.preventDefault();
 					me.replaceSpot = true;
 					$("#change-spot").hide();
 				});
 			$("#change-spot-no-btn")
 				.on('click', function(e){
 					e.preventDefault();
 					me.replaceSpot = false;
 					$(this).parents('div.add-spot-modal')
 						.find(".prev-next input[value='next']")
 						.trigger("click");
 				});

 			$("ul.spots-nav", me.options.modal)
	 			.find('a')
				.click(function(e){
					e.preventDefault();
				});
			
			$("#spot-name-search")
				.on('blur', function(){
					if($("#spot-name").val().length == 0 || me.replaceSpot == true)
						$("#spot-name").val($(this).val());
				});

			$("#google-place-search")
				.on('blur', function(){
					if($("#spot-city").val().length == 0 || me.replaceSpot == true)
						$("#spot-city").val($(this).val());
				});
				
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
								// console.log(response);
		 						var newCat = $("<a/>",{ href : ''})
				 							.attr('data-categoryid', response.id)
				 							.text(response.name)
				 							.appendTo($("ul[name='spot-category']", me.options.modal))
				 							.wrap("<li/>");

		 						me.subPrepareCategoriesEvents();

		 						if($("ul[name='spot-category'] li.selected", me.options.modal).length == 3 ){
		 							$("ul[name='spot-category'] li.selected:first a", me.options.modal).trigger('click');
		 						}

		 						$(newCat).trigger('click');

		 						$(thisBtn).prev('input[name="new-category"]').val("");
							}
						});
					}
				});
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
								// console.log(response);
		 						$(thisBtn).prev('input[name="new-category"]').val("");

			 					var newLike	= $("<a/>",{ href : ''})
			 							.attr('data-likeid', response.id)
			 							.text(response.name)
			 							.appendTo($("ul[name='spot-like']", me.options.modal))
			 							.wrap("<li/>");

			 					me.subPrepareSpotLikeEvents();

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
 		preparePaginationButtons: function(){
 			// console.log('preparePaginationButtons');
 			var me = this;
			$(".prev-next input[type='button']", me.options.modal)
 			.unbind()
 			.on('click', function(){
 				if("next" == $(this).val()){
 					// me.checkRequiredFields(this);
 					if(me.checkRequiredFields(this)){
 						if(1 == $(this).data('step')){

	 						me.options.spot = null; // reset spot
	 						if(me.options.env != "edit" || me.replaceSpot == true){
	 							me.checkAvailability();
	 						}
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

	 						var qstring = $("#spot-name-search").val();
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
 		checkAvailability: function(){
 			var me = this;

 			var name = $("#spot-name").val();
 			var address = $("#spot-address").val();
 			$.ajax({
 				url: bugglScriptName+'/local-author/check-spot-availability',
 				data: { address: address, name : name },
 				dataType: 'json',
 				beforeSend: function(){
 					$('div.add-spot-modal:visible')
 						.hide()
 						.next('div.add-spot-modal')
 						.show();

 					$('div.add-spot-modal:visible')
 						.find('div.clearfix')
 						.css({'height': '375px', 'background' 
 							: 'url(/bundles/bugglmain/images/ajax-loader-1.gif) no-repeat center center'});
 						// .append("<span>Checking spot availability...</span>");
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
 		checkRequiredFields: function(element){
 			var me = this;
 			var form = $(element).parents('div.add-spot-modal:first').find('form:visible');
 			me.formErrorCount = 0;
 			$(form).find('input, select').each(function(){
 				// console.log($(this).attr('name'));
 				$(this).removeClass('required');
 				// console.log($(this).data('required'));
 				if(true == $(this).data('required') && ($(this).val() == 0 || $(this).val().length == 0)){
 					me.formErrorCount = me.formErrorCount + 1;
 					$(this).addClass('required');
 				}
 			});
 			// console.log(me.formErrorCount);
 			if(0 < me.formErrorCount){
 				$(element).parents('div.add-spot-modal:first').find("p.required-message").show();
 				return false;
 			}
 			else{
 				$(element).parents('div.add-spot-modal:first').find("p.required-message").hide();
 				return true;
 			}
 		},

 		checkSelectedDuplicate: function(){
 			var me = this;
 			var e = $("ul#possible-duplicate-spot-list",  me.options.modal).find('input[type="radio"]:checked');
 			// console.log($(e).val());
 			if(0 < $(e).val()){
 				// if(me.replaceSpot == false){
 					$.ajax({
	 					url: bugglScriptName+"/local-author/get-spot-details/"+$(e).val(),
	 					dataType: "json",
	 					beforeSend: function(){
	 						$('div.add-spot-modal:visible')
	 						.find('div.clearfix')
	 						.css({'height': '375px', 'background' : 'url(/bundles/bugglmain/images/ajax-loader-1.gif) no-repeat center center'})
	 						.empty()
	 						.append("<span>Fetching spot details...</span>");
	 					},
	 					success: function(response){
	 						
	 						if(me.replaceSpot == true){
	 							var guideid = $("#"+me.options.slug).data('guideid');
	 							var removeUrl = bugglScriptName + "/local-author/remove-spot/" + me.options.spot_id + "/local_secret/" + guideid + "/" + me.options.day;
	 							
	 							$.get(removeUrl, function(data){ console.log(data); });
	 							me.replaceSpot = false;
	 						}
	 						
	 						console.log(response);
	 						me.options.spot = response;
	 						// if(response.hasOwnDetail == true){
	 						// 	if(confirm("It seems that you have already added a different description and set of tags for the '"+response.name+"'' spot.\nDo You want to update it?")){
	 						// 		me.updateForms();	
	 						// 	}
	 						// }
	 						// else{
	 							me.updateForms();
	 						// }
	 						// console.log('test');
	 						// console.log(me.options.spot);
	 						
	 						
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
	 				});
 				// }
 				// else {
 				// 	me.replaceSpot = false;
 				// 	$('div.add-spot-modal:visible')
 				// 		.hide()
 				// 		.next('div.add-spot-modal')
 				// 		.show();
 				// 	$("ul.spots-nav", me.options.modal)
 				// 		.children('li.active')
 				// 		.removeClass('active')
 				// 		.next('li')
 				// 		.addClass('active');
 				// }
 				
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
 		prepareGooglePlacesSearch: function(){
 			// console.log('prepareGooglePlacesSearch');
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
 			// console.log('prepareUploadOwnPhoto');
 			var me = this;
 			$("#spot-photo-ifrm input[name='spot-photo']")
 				.on('change', function(){
 					// console.log('upload file');
 					$("#spot-photo-ifrm").submit();
 				});

 			$("#spot-photo-ifrm")
				.iframePostForm({
					json : true,
					post: function(){
						$("#spot-photo-holder")
 						.css({'background' : 'url(/bundles/bugglmain/images/ajax-loader-1.gif) no-repeat center center'})
 						.empty();
					},
					complete : function (response){
 						var img = new Image();
	 					$(img)
	 						.load(function(){
	 							$("#spot-photo-holder").css('background', '');
	 							$("#spot-photo-holder").append(img);
	 							$("form#spot-photo-ifrm > input[name='photo-url']").val(response.url);
	 						})
	 						.attr({'src' : response.url, 'id' : 'spot-photo-img', 'height' : '317px' });
					}
				});
 		},
 		prepareTags: function(){
 			// console.log('prepareTags');
 			var me = this;
 			me.subPrepareSecretTypes();
 		},
 		subPrepareSecretTypes: function(){
 			var me = this;
 			$("ul[name='spot-secret-type']", me.options.modal)
 				.find('a')
 				.unbind('click')
 				.on('click', function(e){
 					e.preventDefault();
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
 					me.subPrepareCategories();
 					me.subPrepareSpotLike();
 				});
 		},
 		subPrepareCategories: function(){
 			var me = this;
 			$("div.category-select", me.options.modal).show();
 			var typeId = $("ul[name='spot-secret-type']",me.options.modal).data('typeid');

 			$.ajax({
 				url: bugglScriptName+'/ajax/fetch-spot-categories',
 				data: { type_id : typeId },
 				dataType: 'json',
 				beforeSend: function(){
 					// var
 					$("ul[name='spot-category']", me.options.modal).empty();
 					loader.insert($("ul[name='spot-category']", me.options.modal));
 				},
 				success: function(response){
 					// console.log(response);
 					$("ul[name='spot-category']", me.options.modal).empty();
 					$.each(response, function(index, value){
 						$("<a/>",{ href : ''})
 							// .data('categoryid', value.id)
 							.attr('data-categoryid', value.id)
 							.text(value.name)
 							.appendTo($("ul[name='spot-category']", me.options.modal))
 							.wrap("<li/>");
 					});
 					me.subPrepareCategoriesEvents();
 				}
 			});
 		},
 		subPrepareCategoriesEvents: function(){
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
 		subPrepareSpotLike: function(){
 			var me = this;
 			$("div.what-like", me.options.modal).show();
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
 					// console.log(response);
 					$("ul[name='spot-like']", me.options.modal).empty();
 					$.each(response, function(index, value){
 						$("<a/>",{ href : ''})
 							// .data('likeid', value.id)
 							.attr('data-likeid', value.id)
 							.text(value.name)
 							.appendTo($("ul[name='spot-like']", me.options.modal))
 							.wrap("<li/>");
 					});

 					me.subPrepareSpotLikeEvents();
 				}
 			});
 		},
 		subPrepareSpotLikeEvents: function(){
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
 		updateTagsForm: function(updateType, value, action){
 			var me = this;
 			var action = ("undefined" == typeof(action)) ? "add" : action;
 			var form = $("#spot-step-3-frm", me.options.modal);
 			if("secret" == updateType){
 				if($("input[name='spot-secret']",form).length == 0){
 					$("<input/>",{ type: 'text', name: 'spot-secret'}).appendTo(form);
 				}
 				$("input[name='spot-secret']",form).val(value);
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
 		prepareSpotRating: function(){
 			var me = this;
 			// $("span[name='spot-rating-desc']").hide();
 			var rating = $("ul.add-spot-rating", me.options.modal).find("a");
 			// console.log(rating);
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
						// console.log(e.keyCode);
						// console.log($("#"+options.identifier+"-char-count"));
						var ed = tinymce.get(textAreaId);
 						//var body = ed.getBody(), text = tinymce.trim(body.innerText || body.textContent);
 						var text = $(ed.getContent()).text();
 						var keycode =  e.keyCode ? e.keyCode : e.which;
				        // console.log(keycode);
								
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
				},
				oninit: function(){
					var body = tinymce.get(textAreaId).getBody();
					// tinymce.dom.Event.add(s.content_editable ? ed.getBody() : (tinymce.isGecko ? ed.getDoc() : ed.getWin()), 'focus', function(e) {
					//     alert('focus');
					// }
					$(body).css({'background-color':'#CCCCCC'});
					var ed = tinymce.get(textAreaId);
					//var body = ed.getBody(), text = tinymce.trim(body.innerText || body.textContent);
					var text = $(ed.getContent()).text();
					
					var cnt = limit - text.length;
					$("#"+textAreaId+"-char-count").val(cnt);
				},
				paste_preprocess: function(p1,o){
					var ed = tinymce.get(textAreaId);
					// formatting will be removed
					var text = $(ed.getContent()).text();
		            var pastedText = $(o.content).text();
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
 		updateForms: function(){
 			var me = this;
 			// update step 1
 			// console.log('update step one');
 			$("#add-spot-step-1")
 				.find('input[name="name"]')
 				.val(me.options.spot.name);
 			$("#add-spot-step-1")
 				.find('input[name="address"]')
 				.val(me.options.spot.address);
 			$("#add-spot-step-1")
 				.find('input[name="contact_number"]')
 				.val(me.options.spot.contact_number);
 			$("#add-spot-step-1")
 				.find('input[name="latitude"]')
 				.val(me.options.spot.location.lat);
 			$("#add-spot-step-1")
 				.find('input[name="longitude"]')
 				.val(me.options.spot.location.lng);
 			
 			var name = me.options.spot.name;
 			var location = me.options.spot.location.city.name + ", " + me.options.spot.location.country.name;
 			var searchText = name + ", " + location;
 			$("#photo_search_web_box").val(searchText);
 			googleCustomSearch.init();


 			if(me.options.spot.hasOwnDetail == true){
 				// update step 2
 				$('#add-spot-step-2 #spot-photo-ifrm')
	 				.find('input[name="photo-url"]')
	 				.val(me.options.spot.detail.photo);
	 			$('#add-spot-step-2 #spot-photo-img')
	 				.attr('src', me.options.spot.detail.photo);

 				// update step 3
	 			$("#add-spot-step-3")
	 				.find('ul[name="spot-secret-type"]')
	 				.find('a[data-typeid="'+me.options.spot.detail.type.id+'"]')
	 				.trigger('click');

	 				// wait for the ajax request fired by the trigger on selecting secret type
	 			$(document).ajaxComplete(function(event,request, settings) {
	 				// console.log('execute');
	 				var category_wrapper = $("#add-spot-step-3 div.category-select");
	 				var what_like_wrapper = $("#add-spot-step-3 div.what-like");
	 				$(category_wrapper).show();
	 				$(what_like_wrapper).show();

	 				var categories = me.options.spot.detail.categories;
	 				$.each(categories, function(i, val){
	 					$("ul[name='spot-category']", category_wrapper)
	 						.find('a[data-categoryid="'+val.id+'"]')
	 						.trigger('click');
	 				});

	 				var likes = me.options.spot.detail.likes
	 				// console.log('set likes');
	 				$.each(likes, function(i, val){
	 					$("ul[name='spot-like']", what_like_wrapper)
		 					.find('a[data-likeid="'+val.id+'"]')
		 					.trigger('click');
	 				});
				});

	 			// update step 4
	 			$("#add-spot-step-4")
	 				.find('ul.add-spot-rating')
	 				.find('a[data-spotrating="'+me.options.spot.detail.rating+'"]')
					.trigger('click');
				$("#add-spot-step-4")
					.find('input[name="title"]')
					.val(me.options.spot.detail.title);
				$("#add-spot-step-4")
					.find('input[name="best-thing"]')
					.val(me.options.spot.detail.best_thing);
	 				// $("ul.add-spot-rating", me.options.modal).find("a");

	 			// console.log('set tinymce content');
	 			tinymce.get("spot-description").setContent(me.options.spot.detail.description);
	 			// tinyMCE.activeEditor.setContent(me.options.spot.detail.description);
 			}

 		},
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

		    $( "#spot-country-search" ).focus();
		    // if($( "#country-search" ).val().length > 0)
		    	// prepareLocation($( "#country-search" ).val());
 		},
 		prepareLocation: function(country){
 			// console.log(googlePlaces.options.autocomplete);
 			if(googlePlaces.options.autocomplete != null){

				var geocoder = new google.maps.Geocoder();
				geocoder.geocode(
					{address:country},
					function(response, status){
						googlePlaces.options.country = response[0];
						var countryShortName = response[0].address_components[0].short_name.toLowerCase();
						// console.log("setComponentRestrictions : "+countryShortName);
						googlePlaces.options.autocomplete.setComponentRestrictions({country:countryShortName});
				});

			}
			// else {
			// 	googlePlacesAutoComplete.init({address:country});
			// }
 		},
 		submitForms: function(){
 			var me = this;

 			if(me.options.slug != null)
 				var data = "slug="+me.options.slug;
 			else
 				var data = "s="+Math.random();

 			data = data + "&env=" + me.options.env + "&day_num=" + me.options.day + "&page=" + me.options.page + "&pagename=" + me.options.pagename;

 			$("form", me.options.modal)
 				.each(function(){
 					data = data + "&" + $(this).serialize();
 				});

 			// console.log(data);
 			if(me.options.env == "edit"){
 				var url = bugglScriptName+'/local-author/update-spot';
 				if(me.options.spot != null){
 					data = data + "&spotId="+me.options.spot.id;

 					if(me.options.spot.hasDetail)
		 				data = data + "&spotDetailId="+me.options.spot.detail.id;

 				}
	 			else {
	 				data = data + "&spotId=" + me.options.spot_id + "&spotDetailId=" +me.options.spot_detail_id;
	 			}
	 			
 			}
 			else {
 				var url = bugglScriptName+'/local-author/save-spot';
 			}

 			if(me.replaceSpot == true)
 				data = data + "&replaceSpot=true";

 			if(me.ajaxSave == null)
 			{
 				me.ajaxSave = $.ajax({
			 				url: url,
			 				data : data,
			 			// 	// type: 'post',
			 				dataType: 'json',
			 				beforeSend: function(){
			 			// 		console.log('saving form');
			 			// 		// loader.insert($(form).parents('div.add-spot-modal:first'));
			 			// 		// $(form).parents('div.add-spot-modal:first').find('p.prev-next').hide();
			 				},
			 				success: function(response){
			 					// console.log(response);
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
 			'show_forgot_spot' : true
 		},
 		init: function(options){
 			var me = this;
 			me.options = $.extend(me.options, options);
 			alert("This feature is undergoing some upgrades, it might not work as expected. \nThe Buggl Dev Team");
 			// var modal = "<div id='spot-list-modal' class='reveal-modal large' style='background: none repeat scroll 0 0 #FFFFFF !important;padding: 30px;'><a class='close-reveal-modal'>×</a></div>";
 			// if($("#spot-list-modal").length == 0){
 			// 	$('body').append(modal);
 			// }
 			// me.options.modal = $("#spot-list-modal");

 			$(me.options.modal).reveal({
				open: function(){
					$(me.options.modal).css({"width":"800px"});
					me.fetchList('all');
				}
			});
 		},
 		fetchList: function(spotType){
 			var me = this;
 			$.ajax({
 				url: bugglScriptName+'/local-author/get-spots-list',
 				data: { type: me.options.type, slug: me.options.slug, daynum : me.options.day, spotType : spotType },
 				dataType: 'json',
 				beforeSend: function(){
 					$(me.options.modal).find('*').not('a.close-reveal-modal').remove();
 					loader.insert(me.options.modal);
 				},
 				success: function(response){
 					$(me.options.modal).find('*').not('a.close-reveal-modal').remove();
 					$(me.options.modal).append(response.html);
 					me.prepareSelectSpot();

 					$("#forgot-a-spot", me.options.modal)
 						.on('click', function(e){
 							e.preventDefault();
 							var options = {
								page: $(this).data('page'),
								pagename: $(this).data('pagename'),
								daynum: $(this).data('daynum'),
								name: $(this).attr('name')
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
 		prepareSelectSpot: function(){
 			var me = this;
 			// console.log("spotList context : "+me.options.context);
 			var form = $('#spot-gallery-setting-form');
 			// $("#spot-gallery .spot-dayer").hide();
 			if(me.options.context == 'local-secret'){
 				$("#spot-gallery").find("li[name='time-of-day-setting']").remove();
 			}
 			else {
 				$("#spot-gallery").find("li[name='add-to-local-secret-setting']").remove();
 			}

 			$("ul[name='add-spot-from-list-setting']")
				.find('a[name="remove-setting"]')
				.on('click', function(e){
					e.preventDefault();
					// console.log('remove settings');
					var parent = $(this).parents('li.column');
					$(parent).removeAttr('name');
					$(".spot-dayer", parent).hide();
					var inputId = 'spot-gallery-form-'+$(this).parents('li.column').data('spotid');
					$("input#"+inputId, form).remove();
					if(me.options.context == 'itinerary'){
						$(parent).removeData('time');
					}

				});

			$("ul[name='add-spot-from-list-setting']")
				.find('a:not([name="remove-setting"])')
				.on('click', function(e){
					e.preventDefault();
					// console.log('changing settings');
					var parent = $(this).parents('li.column');
					$(parent).attr('name', 'added');
					var spotId = $(this).parents('li.column').data('spotid');
					if(me.options.context == 'itinerary'){
						var time = $(this).data('time');
						if(parseInt(time) == 1)
							var timeText = 'MORNING';
						else if(parseInt(time) == 2)
							var timeText = 'AFTERNOON';
						else
							var timeText = 'EVENING';
						$(".spot-dayer", parent).text(timeText).show();
						$(this).parents('li.column').data('time',$(this).data('time'));


						var inputName = 'spots['+$(this).data('time')+'][]';
						var inputId = 'spot-gallery-form-'+spotId;
						$("#"+inputId, form).remove();
						$("<input/>",{ type: 'text', name: inputName, id: inputId})
							.val(spotId)
							.appendTo(form);
					}
					else {
						var inputName = 'spots[0][]';
						var inputId = 'spot-gallery-form-'+spotId;
						$("#"+inputId, form).remove();
						$("<input/>",{ type: 'text', name: inputName, id: inputId})
							.val(spotId)
							.appendTo(form);
						$(".spot-dayer", parent).text('ADDED').show();
					}

				});

			$("#spot-gallery-save-changes").click(function(){
				me.saveChanges();
			});
 		},
 		saveChanges: function(){
 			var me = this;
 			var gallery = $("#spot-gallery");
 			// console.log($("li[name='added']", gallery).length);
 			if($("li[name='added']", gallery).length > 0){
 				$('#spot-list-wrapper .required-message').hide();
 				var guide_id = $("#spot-gallery").data('guideid');
 				var data = $("#spot-gallery-setting-form").serialize();
 				data = data + "&day_num="+me.options.day;
 				
	 			// $.ajax({
	 			// 	url: bugglScriptName+"/local-author/add-spot-to-guide/"+guide_id,
	 			// 	data : data,
	 			// 	dataType: 'json',
	 			// 	beforeSend: function(){},
	 			// 	success: function(response){
	 			// 		window.location.reload();
	 			// 	}
	 			// });
 			}
 			else {
 				$('#spot-list-wrapper .required-message').show();
 			}

 		},

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
						// $("a[name='add-from-local-secrets']", chooser_modal)
						// 	.parent('li').show();
					}
					else {
						var context = "local-secret";
						// $("a[name='add-from-local-secrets']", chooser_modal)
						// 	.parent('li').hide();
					}

					// console.log(context);
					// $("#add-from-local-secrets").data('context', context);
					$("#add-from-spot-library").data('context', context);

					// $("#add-from-local-secrets").data('daynum', me.options.daynum);
					$("#add-from-spot-library").data('daynum', me.options.daynum);
					$(".modal-cancel-btn").on('click', function(e){ e.preventDefault(); });
 				}
 			});
 		}
 	}

 	// $("[name='add-local-secret']")
		// .unbind('click')
		// .on('click', function(e){
		// 	e.preventDefault();
		// 	var options = {
		// 		"slug" : $("section.step-2").attr('id')
		// 	}
		// 	bugglSpot.init(options);
		// });

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
								'<li><a class="admin-button" href="" name="spots-list" id="add-from-spot-library">Choose from your Spot Library</a></li>'+
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
				"spot_detail_id": 0
			}
			bugglSpot.init(options);
		});

	$("#add-from-spot-library")
		.unbind('click')
		.on('click', function(e){
			e.preventDefault();
			// console.log($(this).data('context'));
			var options = {
				"context" : $(this).data('context'),
				"type" : "spot-library",
				"slug" : $("section.step-2").attr('id'),
				"day"  : $(this).data('daynum'),
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
 				'title': 'Remove Spot',
 				'content': "<p>Are you sure you want to remove this spot?</p>",
				'onConfirm' : function(){
					$.ajax({
		 				url : dest_url,
		 				dataType: 'json',
		 				beforeSend: function(){},
		 				success: function(response){
		 					// console.log(response);
		 					window.location.reload();
		 				}
		 			});
				}
			});
 		});
	$("[name='remove-itinerary-day']")
 		.on('click', function(e){
 			e.preventDefault();
 			var me = this;
 			var msg = '<p>Are you sure you want to remove day '+$(me).data('day')+' from your itinerary?<p>';
 			var title = "Remove day "+$(me).data('day')+" from itinerary?";
 			$.fn.bugglConfirm({
 				'title': title,
 				'content': msg,
				'onConfirm' : function(){
					window.location.href = $(me).attr('href');
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

			// console.log(options);
			bugglSpot.init(options);
 		});
	
	$(spotList.options.modal)	
		.on('click',"[name='spot-type']",function(e){
			e.preventDefault();
			spotList.fetchList($(this).attr('data-spot-type'));
		});

	$("#add-new-spot")
		.on('click', function(e){
			e.preventDefault();
			var options = {
 				"env" : "add"
			}

			// console.log(options);
			bugglSpot.init(options);
		});
});