/** 
 * Buggl Add Spot jquery plugin
 * Version 1.1
 */

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

 	var flickrPhotoSearch = {
 		id: 'photo_search_web_box',
 		maxpages: 1,
 		options: {
 			'page' : 1
 		},
 		init: function(options){
 			var me = this;
 			me.options = $.extend(me.options, options);
 			var qstring = $("#"+me.id).val();
			if(0 == qstring.length)
				return;

 			this.execQuery();
 		},
 		execQuery: function(){
 			var me = this;
 			var url = bugglScriptName+"/local-author/flickr-photo-search?page="+me.options.page;
 			$.ajax({
				url : url,
				data: { text : $("#"+me.id).val()},
				dataType: 'json',
				beforeSend: function(){
					var holder = $("ul#photo_search_web_results",bugglSpot.modal);
					$(holder).empty();
					$('<li/>').addClass('search-image-loaderz')
						.appendTo($(holder));
					loader.insert($("li:first", holder));
				},
				success: function(data){
					me.processResponse(data);
				}
			});
 		},
 		processResponse: function(data){
 			var me = this;
 			if(data.count > 0){
 				var modal = bugglSpot.modal;
	 			var holder = $("ul#photo_search_web_results",modal);
	 			var items = data.photos;
	 			$(holder).empty();
	 			$.each(items, function(index, value){
					var ul = $(holder).append("<li/>");
					$("<img/>", {'src' : value.thumbnail})
						.appendTo($("li:last-child", ul));
					$("li:last-child", ul)
					.wrapInner(function(){
						return '<a href="" data-bigpiclink="'+value.large+'"/>';
					});
				});

	 			me.maxpages = data.pages;
	 			var prev = 0;
	 			if(data.page > 1)
	 				prev = parseInt(data.page) - 1;

	 			var next = parseInt(data.page) + 1;
	 			if(data.pages == data.page)
	 				next = data.page;
	 		// 	var prevStart = data.queries.request[0].startIndex - me.options.num;

	 			$("#photo-search-web-prev").data({'page' : prev});
	 			$("#photo-search-web-next").data({'page' : next});
				
 			}
 			else {
 				$("ul#photo_search_web_results",modal).empty();
 				$("<li>")
 					.text('Your query yielded no result.')
 					.appendTo($("ul#photo_search_web_results",modal));
 			}

 			me.prepareImageClick();
			me.prepareButtons();
 			
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
 					var img = new Image();
 					$(img)
 						.load(function(){
 							$("#spot-photo-holder").css('background', '');
 							$("#spot-photo-holder").append(img);
 							$("form#spot-photo-ifrm > input[name='photo-url']").val(imgSrc);
 						})
 						.attr({'src' : imgSrc, 'id' : 'spot-photo-img', 'height' : '317px' });
 					// $("#spot-photo").attr({'src': '', 'alt' : 'loading image...'});
 					// $("#spot-photo").attr('src', $(this).data('bigpiclink'));
 					
 				});
 		},
 		prepareButtons: function(){
 			var me = this;
 			$("#photo-search-web-prev, #photo-search-web-next")
 			.unbind('click')
 			.on('click', function(e){
 				e.preventDefault();
 				var page = $(this).data('page');
 				if(page <= 0 || page == me.maxpages)
 					return;

 				me.init({'page': page});
 			});
 		}
 	}

 	var googleMap = {
 		options: {
 			'map' : null,
 			'marker': null,
 			'autocomplete': null,
 			'latitude': 0,
 			'longitude': 0,
 			'zoom': 2,
 			'country': null,
 		},
 		init: function(options){
 			var me = this;
 			me.options = $.extend(me.options, options);
 			me.displayMap();
 			me.prepareAutoComplete();
 		},
 		displayMap: function(){
 			// console.log('display map');
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
 		prepareAutoComplete: function(){
 			var me = this;
 			// console.log(me.options);
 			var coords = new google.maps.LatLng(me.options.latitude, me.options.longitude);
			
			var geocoderRequest = {
	        	location: coords
	        }

	        var geocoder = new google.maps.Geocoder();
			geocoder.geocode(
				geocoderRequest,
				function(response, status){
					// console.log(response);
					if(typeof(response[0]) != "undefined"){
						$.each(response[0].address_components, function(i, val){
							if(val.types[0] == "country"){
								// console.log("found country");
								me.options.country = val;
								// console.log(me.options.country.short_name);
							}
						});
					}
					// console.log(me.options.country);
					me.googleAutoComplete();
				}
			);
 		},
 		resetAutoCompleteOptions: function(){
 			var me = this;
 			var defaultBounds = new google.maps.LatLngBounds(new google.maps.LatLng(me.options.latitude, me.options.longitude));
 			var options = {
 				types: ['establishment']
 			}
 			// console.log(me.options.country);
 			// if(me.options.country != null)
 			// 	options = $.extend(options,{country: me.options.country.short_name});

			me.options.autocomplete.setBounds(defaultBounds);
			me.options.autocomplete.setTypes(options);
			me.options.autocomplete.bindTo('bounds', me.options.map);
 		},
 		googleAutoComplete: function(){
 			var me = this;
 			// console.log('googleAutoComplete');
 			var input = document.getElementById('spot-name-search');/** @type {HTMLInputElement} */

 			// console.log(me.options.country);
 			if(me.options.country == null){
 				var options = {
	 				types: ['establishment']
	 			}
 			}
 			else if(me.options.country != null){
 				var country = me.options.country.short_name.toLowerCase();
 				var options = {
	 				types: ['establishment'],
	 				componentRestrictions: {country: country }
	 			}
 			}
 			
 			// console.log(options);

 			if(me.options.country != null)
 				options = $.extend(options,{country: me.options.country.short_name});

 			// console.log(options);

			me.options.autocomplete = new google.maps.places.Autocomplete(input, options);

			// me.options.autocomplete.bindTo('bounds', me.options.map);
			me.options.autocomplete.bindTo('radius', me.options.map);

			var infowindow = new google.maps.InfoWindow();
			me.options.marker = new google.maps.Marker({
				map: me.options.map
			});

			google.maps.event.addListener(me.options.autocomplete, 'place_changed', function() {
				infowindow.close();
				me.options.marker.setVisible(false);
				input.className = '';
				var place = me.options.autocomplete.getPlace();
				if (!place.geometry) {
					// Inform the user that the place was not found and return.
					input.className = 'notfound';
					return;
				}
				
				// If the place has a geometry, then present it on a map.
				if (place.geometry.viewport) {
					me.options.map.fitBounds(place.geometry.viewport);
				} else {
					me.options.map.setCenter(place.geometry.location);
					me.options.map.setZoom(14);  // Why 17? Because it looks good.
				}
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

				// console.log(place);
				var service = new google.maps.places.PlacesService(me.options.map);
				var request = {
					reference: place.reference
				};
				service.getDetails(request, function(place, status) {
					if (status == google.maps.places.PlacesServiceStatus.OK) {
						var marker = new google.maps.Marker({
							map: me.options.map,
							position: place.geometry.location
						});
						// console.log(place);
						// $("#spot-name-search").val(place.name);
						me.setSpotName(place.name);
						me.setAddress(place.formatted_address);
						me.setContactNumber(place.formatted_phone_number);
						// me.setFlickrPhotoSearchBox(place.name + ", " +place.formatted_address);
						me.setCoordinates(place.geometry.location.lat(), place.geometry.location.lng());
					}
				});
				
			});
 		},
 		setSpotName: function(name){
 			$("#spot-name-search").val(name);
 		},
 		setAddress: function(address){
 			$("#spot-address").val(address);
 		},
 		setContactNumber: function(number){
 			$("#spot-contact-number").val(number);
 		},
 		setCoordinates: function(lat, lng){
 			$("#latitude").val(lat);
 			$("#longitude").val(lng);
 		},
 		setFlickrPhotoSearchBox: function(text){
 			$("#photo_search_web_box").val(text);
 			flickrPhotoSearch.init();
 		}
 	}

 	var bugglSpot = {
 		options: {
 			'env':'add',
 			'modal': $("#create-eguide-modal"),
 			'slug' : null, // this is the eguide slug
 			'day'  : 0,
 			'page' : 0,
 			'spot' : null
 		},
 		formErrorCount : 0,
 		formErrors : {},
 		hasDuplicates: false,
 		init: function(options){
 			var me = this;
 			me.options = $.extend(me.options, options);
 			// console.log(me.options.day);
 			$("#create-eguide-modal").reveal({
				open: function(){
					$(bugglSpot.options.modal).css({"width":"800px"	});
					if(me.options.env == "edit"){
						me.wrapper = $("#edit-spot-wrapper");
						me.getEditForm();
					}
					else {
						me.wrapper = $("#add-spot-wrapper");
						me.getForm();
					}

					
				}
			});
 		},
 		getForm: function(){
 			var me = this;
 			var slug = $("section.step-2").attr('id');
 			
 			$.ajax({
 				url: bugglScriptName+'/local-author/get-add-spot-form',
 				data: { slug : slug },
 				dataType: 'json',
 				beforeSend: function(){
 					$(me.options.modal).find('*').not('a.close-reveal-modal').remove();
 					loader.insert(me.options.modal);
 				},
 				success: function(response){
 					$(me.options.modal).find('*').not('a.close-reveal-modal').remove();
 					$(me.options.modal).append(response.html);
 					
 					me.preparePaginationButtons();
 					me.prepareFlickrPhotoSearch();
 					// me.prepareGooglePlacesSearch();

 					if(me.options.day == 0)
 					{
 						$('select[name="time_of_day"]', me.options.modal).parent('div').remove();
 					}
 					
 					googleMap.displayMap();
 				// 	me.initFormListeners();
 					me.miscelaneous();
 					me.prepareTags();
 					me.prepareSpotRating();
 					me.prepareTinyMCE();
 				}
 			});
 		},
 		miscelaneous: function(){
 			var me = this;
 			$("#spot-info-form", me.options.modal)
 				.find('select[name="city"]')
 				.change(function(){
 					// if(confirm("This action would reset all field values. Continue?")){
 						$("#spot-info-form", me.options.modal).find('input[type="text"]').val("");
 					// }
 					
 					if($("option:selected", this).val() == 0){
 						$("#spot-info-form", me.options.modal).find('input[type="text"], select[name="time_of_day"]').attr('disabled','disabled');
 						$("#spot-info-form", me.options.modal).find('input[type="text"], select[name="time_of_day"]').addClass('disabled');
 					}
 					else {
 						$("#spot-info-form", me.options.modal).find('input[type="text"], select[name="time_of_day"]').removeAttr('disabled');
 						$("#spot-info-form", me.options.modal).find('input[type="text"], select[name="time_of_day"]').removeClass('disabled');
 						var longitude = $("#spot-info-form").find('select[name="city"] option:selected').data('longitude');
	 					var latitude = $("#spot-info-form").find('select[name="city"] option:selected').data('latitude');

	 					googleMap.options.latitude = latitude;
	 					googleMap.options.longitude = longitude;
	 					googleMap.options.zoom = 8;
	 					// console.log(googleMap.options);
	 					// googleMap.displayMap();
	 					// googleMap.resetAutoCompleteOptions();
	 					googleMap.init();
	 					
 					}
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
			 					$(newLike).trigger('click');

			 					$(thisBtn).prev('input[name="new-spot-like"]').val("");
							}
						});
					}
				});
 		},
 		preparePaginationButtons: function(){
 			var me = this;
			$("input[type='button']", me.options.modal)
 			.unbind()
 			.on('click', function(){
 				if("next" == $(this).val()){
 					// me.checkRequiredFields(this);
 					if(me.checkRequiredFields(this)){
 						if(1 == $(this).data('step')){
	 						// var qstring = $("select[name='city'] option:selected").text();
	 						// $("#google_search_box").val(qstring);
	 						// $("input[name='google_search_btn']", me.options.modal).trigger('click');
	 						me.options.spot = null; // reset spot
	 						me.checkAvailability();
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
 				else
 					$(this).attr('onclick', me.cancel);
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
 			if(confirm("Discard changes?")){
 				$("a.close-reveal-modal", me.options.modal).trigger('click');
 			}
 		},
 		checkAvailability: function(){
 			var me = this;

 			// var name = $("#spot-name-search").val();
 			var address = $("#spot-address").val();
 			// console.log('checkAvailability');
 			$.ajax({
 				url: bugglScriptName+'/local-author/check-spot-availability',
 				data: { address: address },
 				dataType: 'json',
 				beforeSend: function(){
 					$('div.add-spot-modal:visible')
 						.hide()
 						.next('div.add-spot-modal')
 						.show();

 					$('div.add-spot-modal:visible')
 						.find('div.clearfix')
 						.css({'height': '375px', 'background' : 'url(/bundles/bugglmain/images/ajax-loader-1.gif) no-repeat center center'});
 						// .append("<span>Checking spot availability...</span>");
 				},
 				success: function(response){

 					if(typeof(response.html) != "undefined"){
 					// 	// console.log('show possible duplicate');
						$('div.add-spot-modal:visible')
 							.find('div.clearfix')
 							.css({'background' : '', 'height' : ''})	
 							.empty()
 							.append(response.html);

 						// me.prepareDuplicateActions();
 					
 					}
 					else {
 						// console.log('show second step');
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
 		prepareFlickrPhotoSearch: function(){
 			// initialize google custom search
 			$("input[value='search']", bugglSpot.modal)
	 			.unbind()
	 			.on('click', function(e){
	 				e.preventDefault();
	 				// googleCustomSearch.init();
	 				flickrPhotoSearch.init();
	 			});
 		},
 		prepareGooglePlacesSearch: function(){
 			// console.log('prepareGooglePlacesSearch');
 			$("#spot-name-search", bugglSpot.modal)
 				.keypress(function(){
 					// console.log('keyUp');
 					var options = {
		 				'qstring' : $("#spot-name-search").val()
		 			}
		 			googlePlaces.search(options);
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
 			// add click event listeners
			$("ul[name='spot-category']", me.options.modal)
				.find('a')
				.unbind('click')
				.on('click', function(e){
					e.preventDefault();
					$("ul[name='spot-category'] li.selected", me.options.modal).removeClass('selected');
					$("ul[name='spot-category']", me.options.modal).data('categoryid', $(this).data('categoryid'));
					me.updateTagsForm('category',$(this).data('categoryid'));
					$(this).parent('li').addClass('selected');
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
 			// add click event listeners
			$("ul[name='spot-like']", me.options.modal)
				.find('a')
				.unbind('click')
				.on('click', function(e){
					e.preventDefault();
					$(this).parent('li').toggleClass('selected');
					if($(this).parent('li').hasClass('selected')){
						me.updateTagsForm('like',$(this).data('likeid'));
					}
					else {
						me.updateTagsForm('like',$(this).data('likeid'), 'remove');
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
 				if($("input[name='spot-category']",form).length == 0){
 					$("<input/>",{ type: 'text', name: 'spot-category'}).appendTo(form);
 				}
 				$("input[name='spot-category']",form).val(value);
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
 						// $("input[name='spot-rating']", me.options.modal).val($(this).data('spotrating'));
 					}
 				)
 				.on('click', function(e){
 					e.preventDefault();
 					$(parent).addClass('active').prevAll().addClass('active');
 					$("input[name='spot-rating']", me.options.modal).val($(this).data('spotrating'));
 				});
 			});
 		},
 		prepareTinyMCE: function(){
 			var me = this;
 			$("textarea", me.options.modal)
 			.tinymce({
 				script_url : '/bundles/bugglmain/js/tiny_mce/tiny_mce.js',
 				width: '100%',
 				height: '100%',
 				theme : "advanced",
				plugins : "",
				// Theme options
				theme_advanced_buttons1 : "bold,italic,underline",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_buttons4 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true
 			});
 		},
 		checkRequiredFields: function(element){
 			// console.log('checkRequiredFields');
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
 						me.options.spot = response;
 						// console.log(me.options.spot);
 						me.updateForms();

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
 				})
 			}
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
 			// update step 2
 			$('#add-spot-step-2 #spot-photo-ifrm')
 				.find('input[name="photo-url"]')
 				.val(me.options.spot.detail.photo);
 			$('#add-spot-step-2 #spot-photo-img')
 				.attr('src', me.options.spot.detail.photo);

 			var name = me.options.spot.name;
 			var location = me.options.spot.location.city.name + ", " + me.options.spot.location.country.name;
 			var searchText = name + ", " + location;
 			$("#photo_search_web_box").val(searchText);
 			flickrPhotoSearch.init();

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

 				// console.log($("ul[name='spot-category']", category_wrapper));
 				$("ul[name='spot-category']", category_wrapper)
 					.find('a[data-categoryid="'+me.options.spot.detail.category.id+'"]')
 					.trigger('click');

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

 		},
 		submitForms: function(){
 			var me = this;
 			
 			if(me.options.slug != null)
 				var data = "slug="+me.options.slug;
 			else
 				var data = "s="+Math.random();

 			data = data + "&day_num=" + me.options.day;
 			
 			$("form", me.options.modal)
 				.each(function(){
 					data = data + "&" + $(this).serialize();
 				});

 			if(me.options.spot != null)
 				data = data + "&spotId="+me.options.spot.id+"&spotDetailId="+me.options.spot.detail.id;

 			// console.log(data);

 			$.ajax({
 				url: bugglScriptName+'/local-author/save-spot',
 				data : data,
 			// 	// type: 'post',
 				dataType: 'json',
 				beforeSend: function(){
 			// 		console.log('saving form');
 			// 		// loader.insert($(form).parents('div.add-spot-modal:first'));
 			// 		// $(form).parents('div.add-spot-modal:first').find('p.prev-next').hide();
 				},
 				success: function(response){
 					window.location.reload();
 			
 				}
 			});
 		}
 	}

 	var spotList = {
 		options : {
 			'context' : 'local-secret',
 			'type' : 'local-secret',
 			'modal': $("#create-eguide-modal"),
 			'slug' : 0,
 			'day'  : 0
 		},
 		init: function(options){
 			var me = this;
 			me.options = $.extend(me.options, options);
 			// console.log(me.options);
 			$("#create-eguide-modal").reveal({
				open: function(){
					$(me.options.modal).css({"width":"800px"});
					me.fetchList();
				}
			});
 		},
 		fetchList: function(){
 			var me = this;
 			$.ajax({
 				url: bugglScriptName+'/local-author/get-spots-list',
 				data: { type: me.options.type, slug: me.options.slug },
 				dataType: 'json',
 				beforeSend: function(){
 					$(me.options.modal).find('*').not('a.close-reveal-modal').remove();
 					loader.insert(me.options.modal);
 				},
 				success: function(response){
 					$(me.options.modal).find('*').not('a.close-reveal-modal').remove();
 					$(me.options.modal).append(response.html);
 					me.prepareSelectSpot();
 				}
 			});
 		},
 		prepareSelectSpot: function(){
 			var me = this;
 			// console.log("spotList context : "+me.options.context);
 			var form = $('#spot-gallery-setting-form');
 			$("#spot-gallery .spot-dayer").hide();
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
	 			$.ajax({
	 				url: bugglScriptName+"/local-author/add-spot-to-guide/"+guide_id,
	 				data : data,
	 				dataType: 'json',
	 				beforeSend: function(){},
	 				success: function(response){
	 					window.location.reload();
	 				}
	 			});
 			}
 			else {
 				$('#spot-list-wrapper .required-message').show();
 			}
 			
 		},

 	}


 	var addSpotOptionsHtml = '<div id="add-spot-chooser" class="reveal-modal small" style="background: none repeat scroll 0 0 #FFFFFF !important;padding: 30px;">'+ 							
							'<div class="content choose-spot-type">'+
								'<h3>You are about to ADD a SPOT.</h3>'+
								'<ul>'+
								'<li><a class="admin-button" href="" name="add-new-spot" id="add-new-eguide-spot">+Add new Spot</a></li>'+
								'<li><a class="admin-button" href="" name="add-from-local-secrets" id="add-from-local-secrets">Choose from Local Secrets</a></li>'+
								'<li><a class="admin-button" href="" name="spots-list" id="add-from-spot-library">Choose from your Spot Library</a></li>'+
								'<li><a class="admin-button cancel" href="">cancel</a></li>'+
								'</ul>'+
							'</div>'+
						'</div>';
	$('body').append(addSpotOptionsHtml);


	$("#add-new-spot")
		.unbind('click')
		.on('click', function(e){
			e.preventDefault();
			bugglSpot.init();
		});

	$("#add-new-eguide-spot")
		.unbind('click')
		.on('click', function(e){
			e.preventDefault();
			var options = {
				"slug" : $("section.step-2").attr('id'),
				"day" : $(this).data('daynum'),
				"page" : $(this).data('page') 
			}
			bugglSpot.init(options);
		});

	$("#add-from-local-secrets")
		.unbind('click')
		.click(function(e){
			e.preventDefault();
			// console.log($(this).data('context'));
			var options = {
				"context" : $(this).data('context'),
				"type" : "local-secret",
				"slug" : $("section.step-2").attr('id'),
				"day"  : $(this).data('daynum')
			}
			spotList.init(options);
		});

	$("#add-from-spot-library")
		.unbind('click')
		.on('click', function(e){
			e.preventDefault();
			console.log($(this).data('context'));
			var options = {
				"context" : $(this).data('context'),
				"type" : "spot-library",
				"slug" : $("section.step-2").attr('id'),
				"day"  : $(this).data('daynum')
			}
			spotList.init(options);
		});

	$("[name='add-daily-spot'], [name='add-local-secret']")
		.unbind('click')
		.on('click', function(e){
			e.preventDefault();
			var me = this;
			var chooser_modal = $("#add-spot-chooser");
			$("#add-new-eguide-spot").data('page', $(this).data('page'));
			$("#add-new-eguide-spot").data('daynum', $(this).data('daynum'));

			$("#add-spot-chooser").reveal({
 				open: function(){
 					if($(me).attr('name') == 'add-daily-spot'){
 						var context = "itinerary";
						$("a[name='add-from-local-secrets']", chooser_modal)
							.parent('li').show();
					}
					else {
						var context = "local-secret";
						$("a[name='add-from-local-secrets']", chooser_modal)
							.parent('li').hide();
					}

					// console.log(context);
					$("#add-from-local-secrets").data('context', context);
					$("#add-from-spot-library").data('context', context);

					$("#add-from-local-secrets").data('daynum', $(me).data('daynum'));
					$("#add-from-spot-library").data('daynum', $(me).data('daynum'));

 				}
 			});
		});

	if($("div.with-highlight").length > 0)
 	{
 		$("div.with-highlight").fadeOut(3000, function(){ $(this).remove()});
 	}

 	$("[name='remove-spot']")
 		.on('click', function(e){
 			e.preventDefault();
 			// if(confirm("Are you sure you want to remove this spot?")){
 			// 	var dest_url = $(this).attr('href');
 			// // console.log(dest_url);
	 		// 	$.ajax({
	 		// 		url : dest_url,
	 		// 		dataType: 'json',
	 		// 		beforeSend: function(){},
	 		// 		success: function(response){
	 		// 			// console.log(response);
	 		// 			window.location.reload();
	 		// 		}
	 		// 	});
 			// }
 			var me = this;
 			$("#confirmModal").reveal({
 				open: function(){
 					var msg = '<p>Are you sure you want to remove this spot?<p>';
	 				$("#confirmModal .contentHolder").empty().append(msg);
	 				$("#confirmModal #confirmModalTitle").text("Remove Spot");
	 				$("#confirmModalOk")
	 					.unbind('click')
	 					.on('click', function(e){
	 						e.preventDefault();
	 						"/travel-guide-pages/{travel_guide_id}/{page_name}/{page}/{day}"
	 						window.location.href = $(me).attr('href');
	 					});
 				}
 				
 			});
 		});

 	$("[name='remove-itinerary-day']")
 		.on('click', function(e){
 			e.preventDefault();
 			var me = this;
 			$("#confirmModal").reveal({
 				open: function(){
 					var msg = '<p>Are you sure you want to remove day '+$(me).data('day')+' from your itinerary?<p>';
	 				$("#confirmModal .contentHolder").empty().append(msg);
	 				$("#confirmModal #confirmModalTitle").text("Remove day "+$(me).data('day')+" from itinerary?");
	 				$("#confirmModalOk")
	 					.unbind('click')
	 					.on('click', function(e){
	 						e.preventDefault();
	 						window.location.href = $(me).attr('href');
	 					});
 				}
 				
 			});

 		});
 	// alert('bugglSpot v1.1');
});