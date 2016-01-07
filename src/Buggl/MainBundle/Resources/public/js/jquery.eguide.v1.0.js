/**
 * jQuery eGuide
 * Version 1.0
 * nash.lesigon@goabroad.com
 */

 $().ready(function(){

 	var loader = {
 		element : null,
 		insert : function(element){
 			var me = this;
 			me.element = element;
 			$("<img/>",{ src : '/bundles/bugglmain/images/ajax-loader-1.gif'}).appendTo(me.element);
 			$("img", me.element).wrap(function(){
 				return "<div style='margin: 0 auto;width: 50px;' id='modal-loader-gif' />";
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
 		id: 'google_search_box',
 		baseUrl: 'https://www.googleapis.com/customsearch/v1',
 		options: {
 			'key' : 'AIzaSyCBfQzUarCd2GZfDNBFj87Z53alxKLgcHs',
			'cx' : '014869400310069128053:hfgxeamadtg',
			'imgType' : 'photo',
			'searchType' : 'image',
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
 			var url = me.baseUrl + '?q=' + $("#"+me.id).val();
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
					var holder = $("ul#google_search_results",addSpot.modal);
					$(holder).empty();
					$(holder).append("<li/>");
					loader.insert($("li:first", holder));
				},
				success: function(data){
					console.log(data);
					me.processResponse(data);
				}
			});
 		},
 		processResponse: function(data){
 			var me = this;
 			var modal = addSpot.modal;
 			var holder = $("ul#google_search_results",modal);
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

 			$("#google-search-prev").data({'start' : prevStart});
 			$("#google-search-next").data({'start' : data.queries.nextPage[0].startIndex});

			me.prepareImageClick();
			me.prepareButtons();
 		},
 		prepareButtons: function(){
 			// console.log('prepareButtons');
 			var me = this;
 			$("#google-search-prev, #google-search-next")
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
 			var modal = addSpot.modal;
 			var holder = $("ul#google_search_results",modal);
 			$("ul#google_search_results",modal)
 				.find('a')
 				.click(function(e){
 					console.log('change photo');
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
 		}
 	}

 	var googleMap = {
 		map: null,
 		marker: null,
 		city: "",
 		address: "",
 		spotname: "",
 		displayMap: function(){
 			var me = this;
 			
			var coords = new google.maps.LatLng(0, 0);
 			var mapOptions = {
			    zoom: 0,
			    center: coords,
			    mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		  	me.map = new google.maps.Map(document.getElementById("google-map"), mapOptions);

		  	me.placeMarker(coords);
		  	me.prepareListeners();

 		},
 		locateByAddress: function(){
 			var me = this;

 			var address = me.buildCompleteAdress();
 			if(0 == address.length) return;

 			// console.log(address);
 			var geocoderRequest = {
	        	address: address
	        }

	        var geocoder = new google.maps.Geocoder();
			geocoder.geocode(
				geocoderRequest,
				function(response, status){
					// console.log(me.map.getZoom());
					if(me.map.getZoom() < 6){
						me.map.setZoom(me.map.getZoom() + 2);
					}
					
					me.map.setCenter(response[0].geometry.location);
					me.placeMarker(response[0].geometry.location);
					me.setSpotFormCoords(response[0].geometry.location.lat(), response[0].geometry.location.lng());
				}
			);
 		},
 		placeMarker: function(coords){
 			var me = this;
 			var name = $("#spot-info-form input[name='name']").val();
 			if(0 == name.length)
 				name = "Spot"

 			if(me.marker)
 				me.marker.setPosition(coords);
 			else {
 				me.marker = new google.maps.Marker({
				    position: coords,
				    map: me.map,
				    title: name,
				    draggable: true
				});

				google.maps.event.addListener(me.marker, 'dragend', function(event){
					// me.map.setZoom(me.map.getZoom() + 2);
					me.map.setCenter(event.latLng);
					me.setSpotFormCoords(event.latLng.lat(), event.latLng.lng());
				});
 			}
 				
 		},
 		prepareListeners: function(){
 			var me = this;
 			var form = $("#spot-info-form");
 			var address = "";
 			// $("input[name='city']", form).on('blur', function(){
 			// 	me.city = $(this).val();
 			// 	me.locateByAddress();
 			// });
			$("select[name=city]", form).on('change', function(){
				me.city = $("option:selected", this).text();
				me.locateByAddress();
			});

 			$("input[name='address']", form).on('blur', function(){
 				me.address = $(this).val();
 				me.locateByAddress();
 			});

 			$("input[name='name']", form).on('blur', function(){
 				me.spotname = $(this).val();
 				me.locateByAddress();
 			});

 			
 		},
 		buildCompleteAdress: function(){
 			var me = this;
 			var address = "";
 			if(0 != me.spotname.length)
 				address = me.spotname + ",";
 			if(0 != me.address.length)
 				address = address + " " + me.address + ",";
 			if(0 != me.city.length)
 				address = address + " " + me.city + ",";

 			return $.trim(address.slice(0,-1));
 		},
 		setSpotFormCoords: function(lat, lng){
 			$("input[name='latitude']").val(lat);
			$("input[name='longitude']").val(lng);
 		}
 	}

 	var addSpot = {
 		modal : $("#create-eguide-modal"),
 		wrapper : $("#add-spot-form"),
 		formErrorCount : 0,
 		formErrors : {},
 		init: function(){
 			var me = this;
 			$(".add-spot")
	  			.unbind('click')
	  			.on('click', function(e){
					e.preventDefault();
					$("#create-eguide-modal").reveal({
						open: function(){
							addSpot.getForm();
						}
					});
				});
 		},
 		getForm: function(){
 			var me = this;
 			var slug = $("section.step-2").attr('id');
 			
 			$.ajax({
 				url: '/app_dev.php/local-author/get-add-spot-form',
 				data: { slug : slug },
 				dataType: 'json',
 				beforeSend: function(){
 					$(me.modal).find('*').not('a.close-reveal-modal').remove();
 					loader.insert(me.modal);
 				},
 				success: function(response){
 					$(me.modal).find('*').not('a.close-reveal-modal').remove();
 					$(me.modal).append(response.html);
 					googleMap.displayMap();
 					me.initFormListeners();
 				}
 			});
 		},
 		initFormListeners: function(){
 			var me = this;
			$("input[type='button']", me.modal)
 			.unbind()
 			.on('click', function(){
 				if("next" == $(this).val()){
 					if(me.checkRequiredFields(this)){
 						if(1 == $(this).data('step')){
	 						var qstring = $("select[name='city'] option:selected").text();
	 						$("#google_search_box").val(qstring);
	 						$("input[name='google_search_btn']", me.modal).trigger('click');
	 					}
	 					$(this).attr('onclick', me.nextPage(this));
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

			// initialize google custom search
 			$("input[value='search']", me.modal)
 			.unbind()
 			.on('click', function(){
 				googleCustomSearch.init();
 			});

 			// disable clickable nav
			$("ul.spots-nav", me.modal)
 			.find('a')
			.click(function(e){
				e.preventDefault();
			});

			// prepare photo
			me.preparePhotos();
			// prepare tags
			me.prepareTags();
			// prepare step 4
			me.prepareSpotRating();
 			me.applyTinyMCE();
 		},
 		checkRequiredFields: function(element){
 			var me = this;
 			var form = $(element).parents('div.add-spot-modal:first').find('form:visible');
 			me.formErrorCount = 0;
 			$(form).children().each(function(){
 				// console.log($(this).data('required'));
 				$(this).removeClass('required');
 				if(true == $(this).data('required') && $(this).val().length == 0){
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
 		submitForm: function(element, form){
 			// console.log($(form).serialize());
 			var me = this;
 			if($(form).attr('id') == 'spot-photo-ifrm'){
 				me.savePhoto(form);
 				return;
 			}

 			$(element).attr('onclick', me.nextPage(element));
 			// var slug = $("section.step-2").attr('id');
 			// var data = $(form).serialize()+"&slug="+slug;
 			// $.ajax({
 			// 	url: '/app_dev.php/local-author/save-spot',
 			// 	data : data,
 			// 	type: 'post',
 			// 	dataType: 'json',
 			// 	beforeSend: function(){
 			// 		// loader.insert($(form).parents('div.add-spot-modal:first'));
 			// 		// $(form).parents('div.add-spot-modal:first').find('p.prev-next').hide();
 			// 	},
 			// 	success: function(response){
 			// 		$(me.wrapper).data('spotid',response.id);
 			// 		// console.log(response);
 			// 		// $(form).parents('div.add-spot-modal:first').find('p.prev-next').show();
 			// 		// loader.del();
 			// 		// 
 			// 	}
 			// });
 		},
 		submitForms: function(){
 			console.log('save forms');
 			var me = this;
 			var slug = $("section.step-2").attr('id');
 			var data = "slug="+slug;
 			$("form", me.modal)
 				.each(function(){
 					data = data + "&" + $(this).serialize();
 				});

 			$.ajax({
 				url: '/app_dev.php/local-author/save-spot',
 				data : data,
 				// type: 'post',
 				dataType: 'json',
 				beforeSend: function(){
 					// loader.insert($(form).parents('div.add-spot-modal:first'));
 					// $(form).parents('div.add-spot-modal:first').find('p.prev-next').hide();
 				},
 				success: function(response){
 					console.log(response);
 					// $(me.wrapper).data('spotid',response.id);
 					// console.log(response);
 					// $(form).parents('div.add-spot-modal:first').find('p.prev-next').show();
 					// loader.del();
 					// 
 				}
 			});
 		},
 		nextPage: function(element){
 			var me = this;
 			$(element).parents('div.add-spot-modal:first').hide().next('div.add-spot-modal').show();
 			// spots nav
 			$("ul.spots-nav", me.modal).children('li.active').removeClass('active').next('li').addClass('active');
 		},
 		prevPage: function(element){
 			var me = this;
 			$(element).parents('div.add-spot-modal:first').hide().prev('div.add-spot-modal').show();
 			// spots nav
 			$("ul.spots-nav", me.modal).children('li.active').removeClass('active').prev('li').addClass('active');
 		},
 		cancel: function(){
 			var me = this;
 			if(confirm("Discard changes?")){
 				$("a.close-reveal-modal", me.modal).trigger('click');
 			}
 		},
 		preparePhotos: function(){
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
 			var me = this;
 			me.prepareSecretTypes();
 		},
 		updateTagsForm: function(updateType, value, action){
 			var me = this;
 			var action = ("undefined" == typeof(action)) ? "add" : action;
 			var form = $("#spot-step-3-frm", me.modal);
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
 		prepareSecretTypes: function(){
 			var me = this;
 			$("ul[name='spot-secret-type']", me.modal)
 				.find('a')
 				.unbind('click')
 				.on('click', function(e){
 					e.preventDefault();
 					$("ul[name='spot-secret-type'] li.selected", me.modal).removeClass('selected');
 					$("ul[name='spot-secret-type']", me.modal).data('typeid', $(this).data('typeid'));
 					$(this).parent('li').addClass('selected');
 					me.updateTagsForm('secret',$(this).data('typeid'));
 					me.prepareCategories();
 					me.prepareSpotLike();
 				});
 		},
 		prepareCategories: function(){
 			var me = this;
 			$("div.category-select", me.modal).show();
 			var typeId = $("ul[name='spot-secret-type']",me.modal).data('typeid');

 			$.ajax({
 				url: '/app_dev.php/ajax/fetch-spot-categories',
 				data: { type_id : typeId },
 				dataType: 'json',
 				beforeSend: function(){
 					// var 
 					$("ul[name='spot-category']", me.modal).empty();
 					loader.insert($("ul[name='spot-category']", me.modal));
 				},
 				success: function(response){
 					// console.log(response);
 					$("ul[name='spot-category']", me.modal).empty();
 					$.each(response, function(index, value){
 						$("<a/>",{ href : ''})
 							.data('categoryid', value.id)
 							.text(value.name)
 							.appendTo($("ul[name='spot-category']", me.modal))
 							.wrap("<li/>");
 					});

 					// add click event listeners
 					$("ul[name='spot-category']", me.modal)
 						.find('a')
 						.unbind('click')
 						.on('click', function(e){
 							e.preventDefault();
 							$("ul[name='spot-category'] li.selected", me.modal).removeClass('selected');
 							$("ul[name='spot-category']", me.modal).data('categoryid', $(this).data('categoryid'));
 							me.updateTagsForm('category',$(this).data('categoryid'));
 							$(this).parent('li').addClass('selected');
 						});
 				}
 			});
 		},
 		prepareSpotLike: function(){
 			var me = this;
 			$("div.what-like", me.modal).show();
 			var typeId = $("ul[name='spot-secret-type']",me.modal).data('typeid');
 			$.ajax({
 				url: '/app_dev.php/ajax/fetch-spot-likes',
 				data: { type_id : typeId },
 				dataType: 'json',
 				beforeSend: function(){
 					$("ul[name='spot-like']", me.modal).empty();
 					loader.insert($("ul[name='spot-like']", me.modal));
 				},
 				success: function(response){
 					// console.log(response);
 					$("ul[name='spot-like']", me.modal).empty();
 					$.each(response, function(index, value){
 						$("<a/>",{ href : ''})
 							.data('likeid', value.id)
 							.text(value.name)
 							.appendTo($("ul[name='spot-like']", me.modal))
 							.wrap("<li/>");
 					});

 					// add click event listeners
 					$("ul[name='spot-like']", me.modal)
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
 				}
 			});
 		},
 		prepareSpotRating: function(){
 			var me = this;
 			var rating = $("ul.add-spot-rating", me.modal).find("a");
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
 						$("input[name='spot-rating']", me.modal).val($(this).data('spotrating'));
 					}
 				)
 				.on('click', function(e){
 					e.preventDefault();
 				});
 			});
 		},
 		applyTinyMCE: function(){
 			var me = this;
 			$("textarea", me.modal)
 			.tinymce({
 				script_url : '/bundles/bugglmain/js/tiny_mce/tiny_mce.js',
 				width: '100%',
 				height: '100%',
 				theme : "advanced",
				plugins : "",
				// Theme options
				theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontsizeselect",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_buttons4 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true
 			});
 		}
 		
 	}

 	var stepOne = {
 		form : $("#guide-info-form"),
 		init: function(){
 			var me = this;
 			me.countryOnChange();
 			me.addCity();
 			me.prepareTinyMCE();
 			me.prepareBudgetView();
 			me.prepareBestTime();

 			$("#guide-info-form-save", me.form)
 			.on('click', function(e){
 				e.preventDefault();
 				me.form.submit();
 			});
 		},
 		countryOnChange: function(){
 			var me = this;
 			var element = $("select[name='country']", me.form);
 			$(element).on('change', function(e){
 				var options = {'countryID' : this.value};
 				me.getCities(options);
 				me.getCategories(options)
 			});
 		},
 		addCity: function(){
 			var me = this;
 			var ul = $(".eguide-city-list", me.form);
 			$(".add-city", me.form)
 			.on('click', function(){
 				var city = $("select[name='cities[]'] option:selected", me.form);
 				if(0 == $(city).val().length)
 					return;

 				$("<li/>").appendTo(ul);
 				$("<input/>",{ type: 'hidden',
 							name: 'cities[]',
 							value: $(city).val() })
 				.appendTo($("li:last-child", ul));
 				var nCity = $(city).text() + '<a class="to-right" href="" name="remove-city">remove</a>';
 				$("ul.eguide-city-list li:last-child").append(nCity);
 				
 				$("select[name='cities[]']", me.form).val("").attr('selected', true);
 			});
 		},
 		removeCity: function(){

 		},
 		getCities: function(options){
 			var me = this;
 			$.ajax({
 				url: '/app_dev.php/ajax/fetch-country-cities',
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
 				url: '/app_dev.php/ajax/fetch-country-categories',
 				data: options,
 				dataType: 'json',
 				success: function(response){
 					$("select[name='category']", me.form).empty();
 					$("select[name='category']", me.form).append($("<option/>", { value : ""}).text("Select a category"));
 					$.each(response, function(key, value){
 						$("<option/>", { value : value.id}).text(value.name).appendTo($("select[name='category']", me.form));
 					});
 				}
 			});
 		},
 		prepareTinyMCE: function(){
 			var me = this;
 			$("textarea[name='eguide-teaser']", me.form)
 			.tinymce({
 				script_url : '/bundles/bugglmain/js/tiny_mce//tiny_mce.js',
 				width: '100%',
 				height: '100%',
 				theme : "advanced",
				plugins : "",
				// Theme options
				theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontsizeselect,|,forecolor",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_buttons4 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true
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
 									name : "best-time[]",
 									value: $(this).text()})
 						.appendTo($(this).parents('li:first'));
 					}
 					else {
 						$(this).next("input[type='hidden']").remove();
 					}
 				});
 			});
 		}
 	}

 	var newItineraryPage = {
 		html: '<div class="page">'+
				'<div class="main-page clearfix">'+
					'<div class="first-half half to-left spot">'+
						'<a class="add-spot" href="">'+
							'<span></span>'+
							'ADD SPOT'+
						'</a>'+
					'</div>'+
					'<div class="second-half half spot to-left">'+
						'<a class="add-spot" href="">'+
							'<span></span>'+
							'ADD SPOT'+
						'</a>'+
					'</div>'+
				'</div>'+
			  '</div>',
 		init: function(){
 			var me = this;
 			$("ul.page-navi li.add a")
 				.on('click', function(e){
 					e.preventDefault();
 					
 					$("div.page").replaceWith(me.html);
 					addSpot.init();
 					// insert page number
 					$("li.active").removeClass("active");
 					var num = $(this).parent('li').prev().data('itinerarypagenum') + 1;
 					$("div.page").attr('id', 'itinerary-p'+num);
 					me.insertPageNum(num);
 				});
 		},
 		insertPageNum: function(num){
 			var me = this;
			var pageNum = '<li class="active" data-itinerarypagenum="'+num+'"><a href=""><span></span>'+num+'</a><a class="delete-page" href=""></a></li>';
			// console.log(pageNum);
			// console.log(me.parentUl);
			$("ul.page-navi li.add").before(pageNum);
		}
 	}

 	$(window).on('hashchange', function() {
 		// alert(window.location.hash);
 		if("step-2" != $("ul.create-guide-steps li.active").attr('name'))
	 		return;
	 	
	 	if(0 == window.location.hash.length){
	 		window.location.hash = $("ul.chapters li.active").find('a').attr('href');
	 		return;
	 	}

	 	var name = window.location.hash;
	 	var slug = $("section.step-2").attr('id');
	  	$.ajax({
	  		url: '/app_dev.php/local-author/get-step2-inner-page',
	  		data: { 'name' : name, 'slug' : slug },
	  		dataType: 'json',
	  		success: function(response){
	  			// $("section.step-2").replaceWith(response.html);
	  			$('div.pages').replaceWith(response.html);
	  			addSpot.init();

				$("ul.chapters li.active").removeClass('active');
				$("ul.chapters").find('a[href='+name+']').parent('li').addClass('active');

				newItineraryPage.init();
	  			// alert('assign bubble popup');
				// $("a[rel=bubble-popup]")
				// .unbind()
				// .CreateBubblePopup({
				// 	position : 'right',
				// 	align	 : 'top',
				// 	width	 : '300px',
				// 	themePath: '/bundles/bugglmain/jquerybubblepopup-themes/',
				// 	selectable: true,
				// 	innerHtml: '<label>Edit this section:</label>'+
				// 				'<textarea class="tinymce" id="bubble-popup-txtarea"></textarea>'+
				// 				'<a class="button" href="">Cancel</a>'+
				// 				'<a class="button" href="">Save</a>',
				// 	afterShown: function(){
				// 		$('textarea#bubble-popup-txtarea').text(this);
				// 		$('textarea#bubble-popup-txtarea').tinymce({
							
				// 			script_url : '/bundles/bugglmain/js/tiny_mce//tiny_mce.js',
				// 			theme : "advanced",
				// 			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
				// 			theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,fontselect,fontsizeselect",
				// 			theme_advanced_buttons2 : "forecolor,backcolor",
				// 			theme_advanced_buttons3 : "",
				// 			theme_advanced_buttons4 : "",
				// 			theme_advanced_toolbar_location : "top",
				// 			theme_advanced_toolbar_align : "left",
				// 			theme_advanced_statusbar_location : "bottom",
				// 			theme_advanced_resizing : true
				// 		});

				// 		$(this).FreezeBubblePopup();
				// 	}
				// });
	  		}
	  	});
	});

	
	stepOne.init();
	$(window).trigger('hashchange');
 });