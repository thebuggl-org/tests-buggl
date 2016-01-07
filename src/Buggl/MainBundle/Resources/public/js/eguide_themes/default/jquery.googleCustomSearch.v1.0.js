/**
 * Google Custom Search Api for jquery
 * Author: Nash Lesigon <nash.lesigon@goabroad.com>
 * Version: 1.0
 */ 
// var d = new Date();
// alert("googleCustomSearch : "+d.getTime());

$.fn.googleCustomSearch = function(options){
	
	var defaults = {
		id      : 'photo_search_web_box',
		baseUrl : 'https://www.googleapis.com/customsearch/v1',
		google_options : {
			key     : 'AIzaSyCBfQzUarCd2GZfDNBFj87Z53alxKLgcHs',
	 		cx      : '014869400310069128053:hfgxeamadtg',
	 		imgType : 'photo',
			imgSize : 'xxlarge',
			searchType : 'image',
			rights  : 'cc_publicdomain0,cc_attribute,cc_sharealike',
			// safe    : 'high',
			// fileType: 'jpg',
			num     : 6,
			start   : 1
		},
		imageClick: function(event, element){}
 		
	};
	
	var settings = $.extend({}, defaults, options);
	
	var template = '<div class="searcher" >'+
						'<input type="text" name="image-search-web" placeholder="Enter keyword" id="'+settings.id+'" /><input type="submit" name="execute-googleCustomSearch" value="Go"/>'+
						'<img id="gcs-loader-image" src="/bundles/bugglmain/images/ajax-loader_red1.gif" style="margin-left: 5px; !important"/>'+
					'</div>'+
					'<ul class="horizontalize" id="photo_search_web_results">'+
						// '<li><img src="" /></li>'+
						// '<li><img src="" /></li>'+
						// '<li><img src="" /></li>'+
					'</ul>'+
					'<ul class="prev-next horizontalize">'+
						'<li><a href="" id="photo-search-web-prev">&lt;</a></li>'+
						'<li><a href="" id="photo-search-web-next">&gt;</a></li>'+
					'</ul>';

	function init(element){
		$(element).after(template);
		$("#gcs-loader-image").hide();
		$("input[name='execute-googleCustomSearch']")
			.on('click', function(){
				settings.google_options.start = 1;
				execute();
			});
		$("input[name='image-search-web']")
			.on('keydown',function(e){
				var keycode =  e.keyCode ? e.keyCode : e.which;
				if(keycode == 13){
					$("input[name='execute-googleCustomSearch']").trigger('click');
				}
			});
	}

	function buildUrl(){
		var url = settings.baseUrl + '?q=' + $("#"+settings.id).val();
		$.each(settings.google_options, function(index, value){
			url = url + "&" + index + "=" + value;
			// if(index == "start")
			// 	console.log(index + ":" +value);
		});
		url = url;
		return url;
	}

	function processResponse(data){
		
		var holder = $("ul#photo_search_web_results");
		var items = data.items;
		$(holder).empty();
		$.each(items, function(index, value){
			var ul = $(holder).append("<li/>");
			$("<img/>", {'src' : value.link})
				.appendTo($("li:last-child", ul));
			$("li:last-child", ul)
				.wrapInner(function(){
					return '<a href="'+value.link+'" data-bigpiclink="'+value.link+'" title="Click to select"/>';
				});
			$("li:last-child", ul)
				.find('a').prepend("<span>click to select</span>");	
		});

		var prevStart = data.queries.request[0].startIndex - settings.google_options.num;

		$("#photo-search-web-prev").data({'start' : prevStart});
		if(typeof(data.queries.nextPage) != 'undefined'){
			$("#photo-search-web-next").data({'start' : data.queries.nextPage[0].startIndex});
		}

		selectImage();
		prepareButtons();
	}

	function prepareButtons(){
		$("#photo-search-web-prev, #photo-search-web-next")
		.unbind('click')
		.on('click', function(e){
			e.preventDefault();
			var startIndex = $(this).data('start');
			if(0 > startIndex)
				return;

			settings.google_options.start = startIndex;
			execute();
		});
	}

	function selectImage(){

		$("ul#photo_search_web_results")
			.find('a')
			.unbind('click')
			.on('click', function(e){
				e.preventDefault();
				settings.imageClick(e, this);
			});
	}

	function execute(){
		var url = buildUrl();
		
		$.ajax({
			url : url,
			type: 'GET',
			crossDomain : true,
			async: false,
			dataType: 'jsonp',
			contentType: "application/json; charset=utf-8",
			beforeSend: function(){

				$("#gcs-loader-image").show();
				// var holder = $("ul#photo_search_web_results");
				// $(holder).empty();
				// $(holder).append("<li/>");
				// loader.insert($("li:first", holder));
			},
			success: function(data){
				$("#gcs-loader-image").hide();
				
				processResponse(data);
			},
			error: function (xhr, ajaxOptions, thrownError) {
		        console.log(xhr.status);
		        console.log(thrownError);
		    }
		});
	}

	return this.each(function(){
		init(this);
	});
}