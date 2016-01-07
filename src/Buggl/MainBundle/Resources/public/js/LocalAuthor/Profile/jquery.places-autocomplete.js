/**
 * from jquery.guideInfo.v2.0.js by nash.lesigon@goabroad.com
 */

 $().ready(function(){

 	var paths = window.location.pathname.split("/");
 	var bugglScriptName = (paths[1] == 'app_dev.php') ? '/app_dev.php' : '';

 	var googlePlacesAutoComplete = {
 		autocomplete: null,
 		init: function(geocoderRequest){
 			var me = this;

			var geocoderRequest = $.extend({address: 'South Africa'}, geocoderRequest);

			// console.log(geocoderRequest);

	        var geocoder = new google.maps.Geocoder();
			geocoder.geocode(
				geocoderRequest,
				function(response, status){

					var country = response[0].address_components[0].short_name.toLowerCase();
					var pac_options = {
						types: ['(cities)'],
		 				componentRestrictions: {country: country }
		 			}


		 			var pac_input = document.getElementById('profile-google-place-search');
					google.maps.event.addDomListener(pac_input, 'keydown', function(e) { 
						if (e.keyCode == 13){
							if (e.preventDefault){
						    	e.preventDefault();
							}
							else{
						       // Since the google event handler framework does not handle early IE versions, we have to do it by our self. :-(
						       e.cancelBubble = true;
						       e.returnValue = false;
							}
						} 
					 }); 
					  
					 (function pacSelectFirst(input, options) {
					    // store the original event binding function

					    var _addEventListener = (input.addEventListener) ? input.addEventListener : input.attachEvent;
					    function addEventListenerWrapper(type, listener) {
					        // Simulate a 'down arrow' keypress on hitting 'return' when no pac suggestion is selected,
					        // and then trigger the original listener.
					        if (type == "keydown") {
					            var orig_listener = listener;
					            listener = function(event) {
					                var suggestion_selected = $(".pac-item-selected").length > 0;
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
						});
					})(pac_input, pac_options);
				}
			);
 		}
 	}

 	function autocomplete()
 	{
 		var options = {};

 		var prepareCountry = function(){
 			var cache = {};
 			var country_ajax = null;
		    $( "#profile-country-search" ).autocomplete({
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
				search: function(event, ui){
					$( "#profile-country-search" ).data('id', 0);
				},
				select: function(event, ui){
					// console.log(ui);
					if(ui.item.id == 0){
						$( "#profile-country-search" ).val("");
						$( "#profile-country-search" ).data('id', ui.item.id);
						return false;
					}

					prepareLocation(ui.item.label);
					$( "#profile-country-search" ).data('id', ui.item.id);
				}
		    })
		    .on('blur', function(e){
		    	// console.log("data id 123:" + $(this).data('id'));
		    	if(typeof($(this).data('id')) == "undefined" || $(this).data('id') == 0){
		    		$(this).val("");
					$('#profile-google-place-search').val("").unbind();
		    		// console.log('here');
		    		return;
		    	}

		    	if($(this).val().length > 1)
			    	prepareLocation($(this).val());

		    });
			
			if($( "#profile-country-search" ).attr('data-id') != undefined ){
				$( "#profile-country-search" ).data('id', $( "#profile-country-search" ).attr('data-id'));
				$( "#profile-country-search" ).removeAttr('data-id');
			}

		    if( $( "#profile-country-search" ).val() != undefined && $( "#profile-country-search" ).val().length > 0)
		    	prepareLocation($( "#profile-country-search" ).val());
 		}

 		var prepareLocation = function(country){
 			// console.log('prepareLocation');
			//$('#profile-google-place-search').val("");
 			if(googlePlacesAutoComplete.autocomplete != null){

				var geocoder = new google.maps.Geocoder();
				geocoder.geocode(
					{address:country},
					function(response, status){
						var countryShortName = response[0].address_components[0].short_name.toLowerCase();
						// console.log("setComponentRestrictions : "+countryShortName);
						googlePlacesAutoComplete.autocomplete.setComponentRestrictions({country:countryShortName});
				});

			}
			else {
				googlePlacesAutoComplete.init({address:country});
			}
 		}

 		return {
 			init: function(options){
 				// prepare country
 				prepareCountry();
 			}
 		}

 	}

 	var autocomplete = new autocomplete();
 	autocomplete.init();
 });