/**
 * Travel Guide Info
 * Version 1.0
 * March 7, 2013
 * nash.lesigon@goabroad.com
 */
 alert('prepareInterests');
 $().ready(function(){
 	
 	var paths = window.location.pathname.split("/");
 	var bugglScriptName = (paths[1] == 'app_dev.php') ? '/app_dev.php' : '';

 	var googlePlacesAutoComplete = {
 		autocomplete: null,
 		init: function(geocoderRequest){
 			var me = this;
			
			var geocoderRequest = $.extend({address: 'South Africa'}, geocoderRequest);

			console.log(geocoderRequest);

	        var geocoder = new google.maps.Geocoder();
			geocoder.geocode(
				geocoderRequest,
				function(response, status){
					// console.log(response);
					// console.log(response[0].address_components[0].short_name);
					var country = response[0].address_components[0].short_name.toLowerCase();
					var pac_options = {
		 				types: ['(cities)'],
		 				componentRestrictions: {country: country }
		 			}


		 			var pac_input = document.getElementById('google-place-search');

					(function pacSelectFirst(input, options) {
					    // store the original event binding function


					    // $(input).unbind();

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

					    me.autocomplete = new google.maps.places.Autocomplete(input, options);
					    google.maps.event.addListener(me.autocomplete, 'place_changed', function() {
						    var place = me.autocomplete.getPlace();
						    me.setCityData(place.name, place.geometry.location.lat(), place.geometry.location.lng());
						});
					})(pac_input, pac_options);
					

				}
			);
 		},
 		setCityData: function(name, lat, lng){
 			$("#google-place-search").data('name', name);
 			$("#google-place-search").data('lat', lat);
 			$("#google-place-search").data('lng', lng);
 		},
 		setCityName: function(name){
 			$("#google-place-search").data('name', name);

 		}
 	}

 	var guideInfo = {
 		form : $("#guide-info-form"),
 		init: function(){
 			var me = this;
 			me.prepareCountries();
 			me.prepareInterests();

 			// me.countryOnChange();
 			me.prepareAddCity();
 			me.prepareRemoveCity();
 			me.prepareTinyMCE();
 			me.prepareBudgetView();
 			me.prepareBestTime();
 			me.prepareGoodFor();
 			$("#guide-info-form-save", guideInfo.form)
 			.on('click', function(e){
 				e.preventDefault();
 				guideInfo.form.submit();
 			});

 			// console.log($("select[name='country'] option:selected", me.form).val());
 			if(0 < $("select[name='country'] option:selected", me.form).val()){
 				$("select[name='country']", me.form).trigger('change');	
 			}

 			
 			googlePlacesAutoComplete.init();
 			// var wrapper = $("div[name='interests-wrapper']");
			// var ids = $("div[name='interests-wrapper']").data('ids');

			// console.log(typeof(ids));
			
			// console.log($.inArray(10, ids));

 		},
 		prepareCountries: function(){
 			var cache = {};
 			var country_ajax = null;
		    $( "#country-search" ).autocomplete({
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
					if(googlePlacesAutoComplete.autocomplete != null){

						var geocoder = new google.maps.Geocoder();
						geocoder.geocode(
							{address:ui.item.label}, 
							function(response, status){
								var country = response[0].address_components[0].short_name.toLowerCase();
								console.log("setComponentRestrictions : "+country);
								googlePlacesAutoComplete.autocomplete.setComponentRestrictions({country:country});
						});

					}
					else {
						googlePlacesAutoComplete.init({address: ui.item.label});
					}
				}
		    });
 			
 		},
 		prepareInterests: function(){
 			var cache = {};
 			var interest_ajax = null;
		    $( "#interests-search" ).autocomplete({
				minLength: 2,
				source: function( request, response ) {
					var term = request.term;
					if ( term in cache ) {
						response( cache[ term ] );
						return;
					}

					if(interest_ajax != null)
						interest_ajax.abort();

					var url = bugglScriptName+"/ajax/fetch-interests";
					interest_ajax = $.getJSON( url, request, function( data, status, xhr ) {
						cache[ term ] = data;
						response( data );
					});
				}
		    });
 		},
 		countryOnChange: function(){
 			var me = this;
 			var element = $("select[name='country']", me.form);
 			// alert($("select[name='country'] option:selected", me.form));
 			$(element).on('change', function(e){
 				var options = {'countryID' : this.value};
 				// me.getCities(options);
 				me.getCategories(options);
 			});
 		},
 		getCities: function(options){
 			var me = this;
 			$.ajax({
 				url: bugglScriptName+'/ajax/fetch-country-cities',
 				data: options,
 				dataType: 'json',
 				success: function(response){
 					$("select[name='cities[]']", me.form).empty();
 					$("select[name='cities[]']", me.form).append($("<option/>", { value : ""}).text("Select a City"));
 					$.each(response, function(key, value){
 						$("<option/>", { value : value.id}).text(value.name).appendTo($("select[name='cities[]']", me.form));
 					});
 				}
 			});
 		},
 		getCategories: function(options){
 			var me = this;
 			$.ajax({
 				url: bugglScriptName+'/ajax/fetch-country-categories',
 				data: options,
 				dataType: 'json',
 				success: function(response){
 					
 					var wrapper = $("div[name='interests-wrapper']");
 					var ids = $("div[name='interests-wrapper']").data('ids');
 					// console.log(ids);
 					// console.log($.parseJSON(ids));
 					// ids = ids.split(",");
 					// console.log(typeof(ids));
 					$(wrapper).empty();
 					$.each(response, function(key, value){
 						$("<label/>").appendTo(wrapper);
 						var input = $("<input/>", { type: 'checkbox', value : value.name, 'name' : 'categories[]'});
 						$("label:last-child", wrapper).append(input)

 						var key = $.inArray(value.id, ids);
 						if(key >= 0){
 							$(input).attr('checked', 'checked');
 						}
 						
 						$("<span>")
 							.appendTo($("label:last-child", wrapper))
 							.text(value.name);

 					});

 					// $(wrapper)
 					// 	.append("<label><input type='text' name='custom-category' value='' placeholder='Not in the list? Add your own here!'/></label>");

 					me.prepareCategories();
 				}
 			});
 		},
 		prepareAddCity: function(){
 			var me = this;
 			var ul = $(".eguide-city-list", me.form);
 			$(".add-city", me.form)
 			.on('click', function(){
 				// console.log($("#google-place-search").data('name'));
 				var city_name = $("#google-place-search").data('name');
 				var lat = $("#google-place-search").data('lat');
 				var lng = $("#google-place-search").data('lng');
 				// var city = $("select[name='cities[]'] option:selected", me.form);
 				if(0 == city_name.length)
 					return;

 					// console.log($("ul.eguide-city-list input[type='hidden']").length);
 				// console.log(me.checkDuplicateCity(city_name) == false);
 				if(me.checkDuplicateCity(city_name) == false)
 				{
 					$("<li/>").appendTo(ul);
	 				$("<input/>",{ type: 'hidden',
	 							name: 'cities[]',
	 							value: city_name })
	 				.appendTo($("li:last-child", ul));
	 				
	 				// latitude
	 				$("<input/>",{ type: 'hidden',
	 							name: 'coords['+city_name+'][lat]',
	 							value: lat })
	 				.appendTo($("li:last-child", ul));

	 				// latitude
	 				$("<input/>",{ type: 'hidden',
	 							name: 'coords['+city_name+'][lng]',
	 							value: lng })
	 				.appendTo($("li:last-child", ul));

	 				var nCity = city_name + '<a class="to-right" href="" name="remove-city">remove</a>';
	 				$("ul.eguide-city-list li:last-child").append(nCity);

	 				// $("#google-place-search").data('name','');
	 				// $("#google-place-search").val("");
 				}
 				else {
 					alert('You have already added this city!');
 					

 				}
 				
 				$("#google-place-search").data('name','');
 				$("#google-place-search").data('lat','');
 				$("#google-place-search").data('lng','');
 				$("#google-place-search").val("");
 				$("#google-place-search").focus();
 				// $("select[name='cities[]']", me.form).val("").attr('selected', true);
 			});
 		},
 		prepareRemoveCity: function(){
 			var me = this;
 			$(".eguide-city-list", me.form)
 				.on('click', 'a[name="remove-city"]', function(e){
 					e.preventDefault();
 					$(this).parent('li').remove();
 				});

 		},
 		prepareTinyMCE: function(){
 			var me = this;
 			$("textarea[name='eguide-teaser']", me.form)
 			.tinymce({
 				script_url : '/bundles/bugglmain/js/tiny_mce/tiny_mce.js',
 				width: '442px',
 				height: '100%',
 				theme : "advanced",
				plugins : "",
				// Theme options
				theme_advanced_buttons1 : "bold,|,forecolor",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_buttons4 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : false
 			});
 		},
 		prepareBudgetView: function(){
 			var me = this;
 			var dollar = $("ul.budget", me.form).find("a");
 			$(dollar).each(function(){
 				var parent = $(this).parent('li:first');

 				$(this)
 				.on('hover', 
 					function(){},
 					function(){
 						$(parent).prevAll().children('a').addClass('active');
 						$(parent).nextAll().children('a').removeClass('active');
 						$(this).addClass('active');
 						$("input[name='budget']", me.form).val($(this).data('budget'));
 					}
 				)
 				.on('click', function(e){
 					e.preventDefault();
 				});
 			});

 		},
 		prepareBestTime: function(){
 			var me = this;
 			var months = $("ul[name='best-time-to-go']", me.form).find("a");
 			$(months).each(function(){
 				$(this)
 				.on('click', function(e){
 					e.preventDefault();
 					$(this).toggleClass("selected");
 					if($(this).hasClass("selected")){
 						$("<input/>",{ type: "hidden",
 									name : "best-time-to-go[]",
 									value: $(this).text()})
 						.appendTo($(this).parents('li:first'));
 					}
 					else {
 						$(this).next("input[type='hidden']").remove();
 					}
 				});
 			});
 		},
 		checkDuplicateCity: function(name){
 			var duplicate = false;
 			if($("ul.eguide-city-list input[type='hidden']").length > 0)
			{
				$("ul.eguide-city-list input[type='hidden']")
					.each(function(){
						if($(this).val() == name)
						{
							duplicate = true;
							return;
						}
					});
			}
			return duplicate;
 		},
 		prepareCategories: function(){
 			console.log('prepareCategories click');
 			$("div[name='interests-wrapper']")
 				.on('click', ':checkbox', function(e){
 					// console.log($("div[name='interests-wrapper'] :checkbox:checked").length);
 					if($("div[name='interests-wrapper'] :checkbox:checked").length > 3){
 						// console.log('should not check');
 						e.preventDefault();
 					}
 						
 				});
 			// $("input[name='categories[]']")
 			// 	.on('click', function(){
 			// 		console.log($("div[name='interests-wrapper'] :checkbox:checked").length);
 			// 		if($("div[name='interests-wrapper'] :checkbox:checked").length > 3) return false;
 			// 	});
 		},
 		prepareGoodFor: function(){
 			$("div[name='good-for-section']")
 				.on('click', ':checkbox', function(e){
 					if($("div[name='good-for-section'] :checkbox:checked").length > 3){
 						e.preventDefault();
 					}
 				});
 		}
 	}

 	guideInfo.init();
 });