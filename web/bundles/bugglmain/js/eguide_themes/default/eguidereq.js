/**
 * Travel Guide Info
 * Version 2.0
 * March 7, 2013
 * nash.lesigon@goabroad.com
 */
 // var d = new Date();
 // alert("Travel Guide Info v2.0 - scrollTop : " + d.getTime());
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
					//alert(country);
					
					var pac_options = {
		 				componentRestrictions: {country: country }

		 			}


		 			var pac_input = document.getElementById('google-place-search1');

					(function pacSelectFirst(input, options) {
					    // store the original event binding function

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
					                    $(".add-city").trigger('click');
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
						 //    console.log(place);
						 //    me.setCityData(place.name, place.geometry.location.lat(), place.geometry.location.lng());

							// $.each(place.address_components, function(i, val){
							// 	if(val.types[0] == "locality"){
							// 		me.setAddress(val);
							// 	}
							// });

						});
					})(pac_input, pac_options);


				}
			);
 		}
 	}


 	function guideInfo()
 	{ 
 		var options = {};
 		var form = $("form#guide-info-form");

 		var prepareCountry = function(){
 			var cache = {};
 			var country_ajax = null;

		    $( "#country-search-1" ).autocomplete({ 
				minLength: 2,
				source: function( request, response ) {
					var term = request.term;
					if ( term in cache ) {
						response( cache[ term ] );
						return;
					}
					var url = bugglScriptName+'/ajax/fetch-buggl-countries-new';
					if(country_ajax != null)
						country_ajax.abort();

					country_ajax = $.getJSON( url, request, function( data, status, xhr ) {
						cache[ term ] = data;
						response( data );
						if(data[0].id==0)
						{
							$('#country-search-1').removeAttr('value');
						}
						
					 //alert(data.length);
					 $("#newcoun").val(data[0].id);
					 if(data.length==1)
					 {
					 	$("#newcoun").val(data[0].id);
					 }
						
					});
				},
				search: function(event, ui){//console.log(ui);
					//alert($( "#country-search-1" ).val());
					$( "#country-search-1" ).data('id', 0);
					//alert(ui.item);
				},
				select: function(event, ui){
					// console.log(ui);
					if(ui.item.id == 0){
						$( "#country-search-1" ).val("");
						$( "#country-search-1" ).data('id', ui.item.id);
						return false;
					}
					
					prepareLocation(ui.item.label);
					$( "#country-search-1" ).data('id', ui.item.id);
					$("#newcoun").val(ui.item.id);
				}
		    })
		    .on('blur', function(e){
		    	// console.log("data id 123:" + $(this).data('id'));
		    	// if( typeof($(this).data('id')) == "undefined" || $(this).data('id') == 0 ){
		    	// 	$(this).val("");
		    	// 	// console.log('here');
		    	// 	return;
		    	// }

		    	if($(this).val().length > 1)
			    	prepareLocation($(this).val());

		    });

		    if($( "#country-search-1" ).val().length > 0)
		    	prepareLocation($( "#country-search-1" ).val());
 		}

 		var prepareLocation = function(country){ 
 			//var country ='Afghanistan';
 			// console.log('prepareLocation');

 			if(googlePlacesAutoComplete.autocomplete != null){

				var geocoder = new google.maps.Geocoder();
				geocoder.geocode(
					{address:country},
					function(response, status){
						var countryShortName = response[0].address_components[0].short_name.toLowerCase();
						// console.log("setComponentRestrictions : "+countryShortName);
						googlePlacesAutoComplete.autocomplete.setComponentRestrictions({country:countryShortName});
						//alert("setComponentRestrictions : "+countryShortName);
						
				});

			}
			else {
				googlePlacesAutoComplete.init({address:country});
			}
 		}

 		var prepareInterests = function(){
 			var cache = {};
 			var interest_ajax = null;
		    $( "#interests-search" ).autocomplete({
				minLength: 2,
				autoFocus: true,
				select: function( event, ui ) {
					// console.log(ui.item.value);
					setTimeout(function () {
					   $(".add-interest").trigger('click');
					}, 500);
				},
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
		    })
		    .keypress(function(e){
		    	if(e.keyCode == 13){
					setTimeout(function () {
					   $(".add-interest").trigger('click');
					}, 500);
			    }
		    });
		    
 		}

 		var prepareTeaser = function(){
			var textAreaId = 'teaserTextArea';

 			prepareTinyMce(textAreaId);
 		}
		
 		var prepareTitle = function(){
			var textAreaId = 'titleTextArea';

 			prepareTinyMce(textAreaId);
 		}
		
		var prepareTinyMce = function(textAreaId){
			var charLimit = $('#'+textAreaId).attr('data-char-limit');
			var tinymce_buttons = (textAreaId == 'titleTextArea' ? 'forecolor' : 'bold,italic,underline,|,forecolor');
			
 			$('#'+textAreaId)
 			.tinymce({
 				script_url : '/bundles/bugglmain/js/tiny_mce/tiny_mce.js',
 				width: '442px',
 				height: '100%',
 				theme : "advanced",
				plugins : "paste",
				// Theme options
				theme_advanced_buttons1 : tinymce_buttons,
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_buttons4 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : false,
				gecko_spellcheck : true, // enable native browser spell-check
				theme_advanced_path : false, // remove path: p
				handle_event_callback: function(e){
					if('keydown' == e.type){
						// console.log(e.keyCode);
						// console.log($("#"+teaserTextArea+"-char-count"));
						var ed = tinymce.get(textAreaId);
 						//var body = ed.getBody(), text = tinymce.trim(body.innerText || body.textContent);
 						var text = $(ed.getContent()).text();
 						var keycode =  e.keyCode ? e.keyCode : e.which;
				        // console.log(keycode);

				        if(keycode >= 37 && keycode <= 40){
				            return true;
				        }
				        var limit = charLimit;

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

					$(body).css({'background-color':'#CCCCCC'});
					var ed = tinymce.get(textAreaId);
					//var body = ed.getBody(), text = tinymce.trim(body.innerText || body.textContent);
					var text = $(ed.getContent()).text();

					var cnt = charLimit - text.length;
					$("#"+textAreaId+"-char-count").val(cnt);

				},
				paste_preprocess: function(p1,o){
					var ed = tinymce.get(textAreaId);
					// formatting will be removed
					var text = $(ed.getContent()).text();

		            var pastedText = $("<p>"+o.content+"</p>").text();
		            if(text.length + pastedText.length >= charLimit){
		            	o.content = pastedText.substring(0, charLimit - text.length);
		            	$("#"+textAreaId+"-char-count").val(0);
		            }
		            else {
		            	o.content = pastedText;
		            	var cnt = charLimit - (text.length + pastedText.length);
		            	$("#"+textAreaId+"-char-count").val(cnt);
		            }

				}
 			});
		}

 		/*
 		var prepareBudget = function(){
 			var dollar = $("ul.budget", form).find("a");
 			$(dollar).each(function(){
 				var parent = $(this).parent('li:first');

 				$(this)
 				.on('hover',
 					function(){},
 					function(){
 						$(parent).prevAll().children('a').addClass('active');
 						$(parent).nextAll().children('a').removeClass('active');
 						$(this).addClass('active');
 						$("input[name='budget']", form).val($(this).data('budget'));
 					}
 				)
 				.on('click', function(e){
 					e.preventDefault();
 				});
 			});
 		}
 		*/

 		/**
 		 * This for version v 2.0.1
 		 * @return null changed implementation to click
 		 */
 		var prepareBudget = function(){
 			var dollar = $("ul.budget", form).find("a");
 			$(dollar).each(function(){
 				var parent = $(this).parent('li:first');

 				$(this)
 				.on('hover',
 					function(){},
 					function(){
 						$(parent).prevAll().children('a').addClass('hover');
 						$(parent).nextAll().children('a').removeClass('hover');
 						$(this).addClass('hover');
 					}
 				)
 				.on('click', function(e){
 					e.preventDefault();

 					$(parent).prevAll().children('a').removeClass('hover').addClass('active');
 					$(parent).nextAll().children('a').removeClass('active');
 					$(this).removeClass('hover').addClass('active');
 					$("input[name='budget']", form).val($(this).data('budget'));
 				});
 			});

 			$("ul.budget").mouseout(function(){
 				$(this).find('a').removeClass('hover');
 			});
 		}

 		var prepareBestTime = function(){
 			var months = $("ul[name='best-time-to-go']", form).find("a");
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
 		}

 		var prepareGoodFor = function(){
 			$("div[name='good-for-section']")
 				.on('click', ':checkbox', function(e){
 					if($("div[name='good-for-section'] :checkbox:checked").length > 3){
 						e.preventDefault();
 					}
 				});
 		}

 		var prepareAddLocation = function(){
 			var ul = $(".eguide-city-list", form);
 			$(".add-city", form)
 			.on('click', function(){
 				// console.log($("#google-place-search1").val());
 				var location = $("#google-place-search1").val();

 				if(0 == location.length)
 					return;

 				if(checkDuplicateLocation(location) == false)
 				{
					$("<li/>").addClass('clearfix').appendTo(ul);
	 				$("<input/>",{ type: 'hidden',
	 							name: 'locations[]',
	 							value: location })
	 				.appendTo($("li:last-child", ul));

	 				var nLocation = '<div class="to-left">' + location + '</div>' + '<a class="to-right" href="" name="remove-location">remove</a>';
	 				$("ul.eguide-city-list li:last-child").append(nLocation);

 				}

 				$("#google-place-search1").focus();
 				$("#google-place-search1").select();

 				prepareRemoveLocation();
 			});

 		}

 		var prepareRemoveLocation = function(){
 			// alert('prepareRemoveLocation');
 			$("a[name='remove-location']")
 				.unbind('click')
 				.on('click', function(e){
 					e.preventDefault();
 					$(this).parent('li').remove();
 				});
 		}

 		var prepareAddInterest = function(){
 			var ul = $(".eguide-interest-list", form);
 			$(".add-interest", form)
	 			.on('click', function(){
	 				var interest = $("#interests-search").val();

	 				if(0 == interest.length)
	 					return

	 				if(checkDuplicateInterest(interest) == false){

	 					/**
	 					 * Added by vfgt
	 					 */
	 					$("#interests-search").val('');

	 					$("<li/>").addClass('clearfix').appendTo(ul);
		 				$("<input/>",{ type: 'hidden',
		 							name: 'interests[]',
		 							value: interest })
		 				.appendTo($("li:last-child", ul));

		 				var nInterest = '<div class="to-left">' + interest + '</div>' + '<a class="to-right" href="" name="remove-interest">remove</a>';
		 				$("ul.eguide-interest-list li:last-child").append(nInterest);
	 				}

	 				prepareRemoveInterest();
	 			});
 		}

 		var prepareRemoveInterest = function(){
 			// alert('prepareRemoveInterest');
 			$("a[name='remove-interest']")
 				.unbind('click')
 				.on('click', function(e){
 					e.preventDefault();
 					$(this).parent('li').remove();
 				});
 		}

 		var checkDuplicateLocation = function(location){
 			var duplicate = false;
 			if($("ul.eguide-city-list input[type='hidden']").length > 0)
			{
				$("ul.eguide-city-list input[type='hidden']")
					.each(function(){
						if($(this).val() == location)
						{
							duplicate = true;
							return;
						}
					});
			}
			return duplicate;
 		}

 		var checkDuplicateInterest = function(interest){
 			var duplicate = false;
 			if($("ul.eguide-interest-list input[type='hidden']").length > 0)
			{
				$("ul.eguide-interest-list input[type='hidden']")
					.each(function(){
						if($(this).val() == interest)
						{
							duplicate = true;
							return;
						}
					});
			}
			return duplicate;
 		}

 		var checkRequiredFields = function(){
 			
 			var teaser = tinymce.get("teaserTextArea").getContent({format : 'text'});
 			var title = tinymce.get("titleTextArea").getContent({format : 'text'});
 			var noError = true;
			var scrollVal = 0;
 			if( $("#country-search", form).val().length == 0 ){
 				$("#country-search-error-msg").css({'display' : 'inline-block'});
				if( 0 == scrollVal )
					scrollVal = $("#country-search-error-msg").offset().top;
 				noError = false;
 			}

 			if( $("input[name='locations[]']", form).length == 0 ){
 				$("#locations-error-msg").css({'display' : 'inline-block'});
				if( 0 == scrollVal )
					scrollVal = $("#locations-error-msg").offset().top;
 				noError = false;
 			}

 			if( title.length == 0 ){
 				$("#title-error-msg").css({'display' : 'inline-block'});
				if( 0 == scrollVal )
					scrollVal = $("#title-error-msg").offset().top;
 				noError = false;
 			}

 			if( teaser.length == 0 ){
 				$("#teaser-error-msg").css({'display' : 'inline-block'});
				if( 0 == scrollVal )
					scrollVal = $("#teaser-error-msg").offset().top;
 				noError = false;
 			}

 			if( $("select[name='trip-theme'] option:selected").val().length == 0 ){
 				$("#trip-theme-error-msg").css({'display' : 'inline-block'});
				if( 0 == scrollVal )
					scrollVal = $("#trip-theme-error-msg").offset().top;
 				noError = false;
 			}

 			if( $("input[name='interests[]']", form).length == 0 ){
 				$("#interests-error-msg").css({'display' : 'inline-block'});
				if( 0 == scrollVal )
					scrollVal = $("#interests-error-msg").offset().top;
 				noError = false;
 			}

 			if( $("input[name='good_for[]']:checked", form).length == 0 ){
 				$("#good_for-error-msg").css({'display' : 'inline-block'});
				if( 0 == scrollVal )
					scrollVal = $("#good_for-error-msg").offset().top;
 				noError = false;
 			}

 			// if( $("select[name='duration'] option:selected", form).val().length == 0 ){
 			// 	$("#duration-error-msg").css({'display' : 'inline-block'});
 			// 	noError = false;
 			// }

 			if( $("input[name='best-time-to-go[]']", form).length == 0 ){
 				$("#best-time-to-go-error-msg").css({'display' : 'inline-block'});
				if( 0 == scrollVal )
					scrollVal = $("#best-time-to-go-error-msg").offset().top;
 				noError = false;
 			}

 			if( $("input[name='budget']", form).length == 0 ){
 				$("#budget-error-msg").css({'display' : 'inline-block'});
				if( 0 == scrollVal )
					scrollVal = $("#budget-error-msg").offset().top;
 				noError = false;
 			}
			
			if( 0 < scrollVal ){
				$("html, body").animate({
					scrollTop: (scrollVal - 50)
				}, "slow" );
			}
			
			
			
 			return noError;
 		}



 		return {
 			init: function(options){
 				$(".form-field-error").hide();
 				// prepare country
 				prepareCountry();
 				// prepare interests
 				prepareInterests();
 				// prepare add interest button
 				prepareAddInterest();
 				prepareRemoveInterest();
 				// prepare teaser tinymce
 				prepareTeaser();
				// prepare title tinymce
				prepareTitle();
 				// prepare budget
 				//prepareBudget();
 				// prepare best time
 				prepareBestTime();
 				// prepare good for
 				prepareGoodFor();
 				// prepare add location button
 				prepareAddLocation();
 				prepareRemoveLocation();

 				$("#guide-info-form-save")
 					.on('click', function(e){
 						e.preventDefault();
 						$(".form-field-error").hide();
 						
 						if(checkRequiredFields() == true)
 							form.submit();
 							
 						
 					});
 			}
 		}

 	}

 	var guideInfo = new guideInfo();
 	guideInfo.init();
 });