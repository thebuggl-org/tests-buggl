/**
 * Travel Guide Info
 * Version 1.0
 * March 7, 2013
 * nash.lesigon@goabroad.com
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

 	var flickrPhotoSearch = {
 		id: 'google_search_box',
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
 			var holder = $("ul#google_search_results",modal);
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

 			// console.log(data.pages);
 			// console.log(data.page);
 			me.maxpages = data.pages;
 			var prev = 0;
 			if(data.page > 1)
 				prev = parseInt(data.page) - 1;

 			var next = parseInt(data.page) + 1;
 			if(data.pages == data.page)
 				next = data.page;
 		// 	var prevStart = data.queries.request[0].startIndex - me.options.num;

 			$("#google-search-prev").data({'page' : prev});
 			$("#google-search-next").data({'page' : next});

			me.prepareImageClick();
			me.prepareButtons();
 		},
 		prepareImageClick: function(){
 			var me = this;
 			var modal = bugglSpot.modal;
 			var holder = $("ul#google_search_results",modal);
 			$("ul#google_search_results",modal)
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
 					// $("#spot-photo").attr({'src': '', 'alt' : 'loading image...'});
 					// $("#spot-photo").attr('src', $(this).data('bigpiclink'));
 					
 				});
 		},
 		prepareButtons: function(){
 			// console.log('prepareButtons');
 			var me = this;
 			$("#google-search-prev, #google-search-next")
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

 	var googleCustomSearch = {
 		id: 'google_search_box',
 		baseUrl: 'https://www.googleapis.com/customsearch/v1',
 		options: {
 			'key' : 'AIzaSyCBfQzUarCd2GZfDNBFj87Z53alxKLgcHs',
			'cx' : '014869400310069128053:hfgxeamadtg',
			'imgType' : 'photo',
			'searchType' : 'image',
			// 'rights': 'cc_attribute',
			'restrict': 'cc_attribute',
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
 			var modal = bugglSpot.modal;
 			var holder = $("ul#google_search_results",modal);
 			$("ul#google_search_results",modal)
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
 		lat: 0,
 		lng: 0,
 		zoom: 15,
 		displayMap: function(lat, lng){
 			googleMap.map = null;
 			googleMap.marker = null;
 			var me = this;
 			if(typeof(lat) == 'undefined')
 				lat = 0;
 			if(typeof(lng) == 'undefined')
 				lng = 0;

			var coords = new google.maps.LatLng(lat, lng);
			// console.log(coords);
 			var mapOptions = {
			    zoom: me.zoom,
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

 			if(me.marker){
 				me.marker.setPosition(coords);
 			}
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

 	var bugglSpot = {
 		env: 'add',
 		modal : $("#create-eguide-modal"),
 		wrapper : $("#add-spot-wrapper"),
 		slug: null,
 		spotId: 0,
 		formErrorCount : 0,
 		formErrors : {},
 		day_num: 1,
 		init: function(){
 			var me = this;
 			$("#create-eguide-modal").reveal({
				open: function(){
					// console.log(me.day_num);
					$(bugglSpot.modal).css({"width":"800px"	});
					me.slug = $("section.step-2").attr('id');
					if(me.env == "edit"){
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
 		initFormListeners: function(){
 			// disable clickable nav
			$("ul.spots-nav", this.modal)
 			.find('a')
			.click(function(e){
				e.preventDefault();
			});

 			this.preparePaginationButtons();
 			this.prepareGoogleCustomSearch();
 			// prepare photo
			this.preparePhotos();
			// prepare tags
			this.prepareTags();
			// prepare step 4
			this.prepareSpotRating();
 			this.prepareTinyMCE();
 			// console.log('done preparing');
 		},
 		getForm: function(){
 			var me = this;
 			var slug = $("section.step-2").attr('id');
 			
 			$.ajax({
 				url: bugglScriptName+'/local-author/get-add-spot-form',
 				data: { slug : slug },
 				dataType: 'json',
 				beforeSend: function(){
 					$(me.modal).find('*').not('a.close-reveal-modal').remove();
 					loader.insert(me.modal);
 				},
 				success: function(response){
 					$(me.modal).find('*').not('a.close-reveal-modal').remove();
 					$(me.modal).append(response.html);
 					
 					if(me.day_num == 0)
 					{
 						$('select[name="time_of_day"]').parent('div').remove();
 					}

					googleMap.zoom = 6;

 					googleMap.displayMap();
 					me.initFormListeners();
 				}
 			});
 		},
 		getEditForm: function(){
 			var me = this;
 			var slug = $("section.step-2").attr('id');
 			
 			$.ajax({
 				url: bugglScriptName+'/local-author/get-edit-spot-form',
 				data: { 'slug' : slug, 'spot_id' : me.spotId },
 				dataType: 'json',
 				beforeSend: function(){
 					$(me.modal).find('*').not('a.close-reveal-modal').remove();
 					loader.insert(me.modal);
 				},
 				success: function(response){
 					$(me.modal).find('*').not('a.close-reveal-modal').remove();
 					$(me.modal).append(response.html);

 					if(me.day_num == 0)
 					{
 						$('select[name="time_of_day"]').parent('div').remove();
 					}
 					// console.log($("input[name='latitude']").val());
 					// console.log($("input[name='longitude']").val());
 					var lat = $("input[name='latitude']").val();
 					var lng = $("input[name='longitude']").val();
 					googleMap.zoom = 4;
 					googleMap.displayMap(lat, lng);
 					me.initFormListeners();
 					// console.log(googleMap.lat);
 					me.subPrepareCategoriesEvents();
 					me.subPrepareSpotLikeEvents();
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
 		preparePaginationButtons: function(){
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
 		},
 		prepareGoogleCustomSearch: function(){
 			// initialize google custom search
 			$("input[value='search']", bugglSpot.modal)
 			.unbind()
 			.on('click', function(){
 				// googleCustomSearch.init();
 				flickrPhotoSearch.init();
 			});
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
 		prepareTinyMCE: function(){
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
 		},
 		prepareTags: function(){
 			var me = this;
 			me.subPrepareSecretTypes();
 		},
 		subPrepareSecretTypes: function(){
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
 					me.subPrepareCategories();
 					me.subPrepareSpotLike();
 				});
 		},
 		subPrepareCategories: function(){
 			var me = this;
 			$("div.category-select", me.modal).show();
 			var typeId = $("ul[name='spot-secret-type']",me.modal).data('typeid');

 			$.ajax({
 				url: bugglScriptName+'/ajax/fetch-spot-categories',
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
 					me.subPrepareCategoriesEvents();
 				}
 			});
 		},
 		subPrepareCategoriesEvents: function(){
 			var me = this;
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
 		},
 		subPrepareSpotLike: function(){
 			var me = this;
 			$("div.what-like", me.modal).show();
 			var typeId = $("ul[name='spot-secret-type']",me.modal).data('typeid');
 			$.ajax({
 				url: bugglScriptName+'/ajax/fetch-spot-likes',
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

 					me.subPrepareSpotLikeEvents();
 				}
 			});
 		},
 		subPrepareSpotLikeEvents: function(){
 			var me = this;
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
 		submitForms: function(){
 			var me = this;
 			
 			if(me.slug != null){
 				var data = "slug="+me.slug;
 			}
 			else {
 				var data = "s="+Math.random();
 			}

 			data = data + "&day_num=" + me.day_num;
 			
 			$("form", me.modal)
 				.each(function(){
 					data = data + "&" + $(this).serialize();
 				});

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
 					alert('Spot successfully saved!');
 					// console.log(response);
 					// window.location.reload();
 			// 		// $(me.wrapper).data('spotid',response.id);
 			// 		// console.log(response);
 			// 		// $(form).parents('div.add-spot-modal:first').find('p.prev-next').show();
 			// 		// loader.del();
 			// 		// 
 				}
 			});
 		}
 	}

 	var spotsList = {
 		init: function(){
 			var me = this;
 			$("#create-eguide-modal").reveal({
				open: function(){
					$(bugglSpot.modal).css({"width":"800px"	});
					me.getList();
				}
			});
 		},
 		getList: function(){
 			
 			$.ajax({
 				url: bugglScriptName+'/local-author/get-spots-list',
 				dataType: 'json',
 				beforeSend: function(){
 					$(bugglSpot.modal).find('*').not('a.close-reveal-modal').remove();
 					loader.insert(bugglSpot.modal);
 				},
 				success: function(response){
 					$(bugglSpot.modal).find('*').not('a.close-reveal-modal').remove();
 					$(bugglSpot.modal).append(response.html);
 				}
 			});
 		},
 		submitForms: function(){
 			
 		}
 	}

 	
	var addSpotOptionsHtml = '<div id="add-spot-chooser" class="reveal-modal small" style="background: none repeat scroll 0 0 #FFFFFF !important;padding: 30px;">'+ 							
							'<div class="content choose-spot-type">'+
								'<h3>You are about to ADD a SPOT.</h3>'+
								'<ul>'+
								'<li><a class="admin-button" href="" name="add-spot">+Add new Spot</a></li>'+
								'<li><a class="admin-button" href="" name="spots-list">Choose from your Spot Library</a></li>'+
								'<li><a class="admin-button cancel" href="">cancel</a></li>'+
								'</ul>'+
							'</div>'+
						'</div>';
	$('body').append(addSpotOptionsHtml);

	$("[name='add-spot']")
		.unbind('click')
		.on('click', function(e){
			e.preventDefault();
			bugglSpot.env = "add";
			bugglSpot.spotId = 0;
			bugglSpot.day_num = $(this).data('daynum');
			bugglSpot.init();
		});

 	$("#add-spot, [name='add-daily-spot']")
 		.unbind('click')
 		.on('click', function(e){
 			e.preventDefault();
 			// alert('prepare flickrPhotoSearch');
 			// console.log('test 123');
 			var daynum = $(this).data('daynum');
 			// console.log(daynum);
 			$("#add-spot-chooser").find("a[name='add-spot']").data('daynum', daynum);
 			$("#add-spot-chooser").reveal({
 				open: function(){
 					$("[name='spots-list']")
 						.unbind('click')
 						.on('click', function(e){
 							e.preventDefault();
 							spotsList.init();
 						});
 				}
 			});
 		});


 	$("#spot-gallery")
 		.find('.edit')
 		.unbind('click')
 		.on('click', function(e){
 			e.preventDefault();
 			bugglSpot.env = 'edit';
 			bugglSpot.spotId = $(this).parents('li.column').data('spotid');
 			bugglSpot.init();
 			
 		});

 	if($("div.with-highlight").length > 0)
 	{
 		$("div.with-highlight").fadeOut(3000, function(){ $(this).remove()});
 	}


 	// alert('hoy');
 });