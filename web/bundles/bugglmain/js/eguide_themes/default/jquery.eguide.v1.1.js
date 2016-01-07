/**
 * Buggl utility javascript
 * Version 1.1
 * nash.lesigon@goabroad.com
 */
 // var d = new Date();
 // alert('eguide.v1.1 : bang 123 '+d.getTime());
 (function($){
 	// dependency : jquery reveal plugin
 	//				jquery tinymce plugin
 	$.fn.buggl_modal_field_edit = function(options){

 		var defaults = {
 			'context' : null,
 			'title' : '',
 			'identifier' : '',
 			'default_content': '',
 			'close_on_save': true,
 			'has_limit' : false,
 			'limit' : 5,
 			'type' : 'textarea', // text
 			'tinymce_buttons1': 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,backcolor',
 			'tinymce_event_callback': function(e){},
 			'tinymce_setup': function(ed){},
 			'save': function(content, element){}
 		};

 		var options = $.extend({}, defaults, options); 

 		var template = '<div id="buggl-modal-field-edit" class="reveal-modal large" style="background: none repeat scroll 0 0 #FFFFFF !important;padding: 15px 15px 30px;">'+
 							'<div class="form-section cover"><span><label name="title">test</label></span><a class="close-reveal-modal">×</a>'+
							'<div class="content"></div></div>'+
							'<div class="submit-section form-section"></div>'+
						'</div>';

		function clearModal(){
			// $('#buggl-modal-field-edit').find('*').not('a.close-reveal-modal').remove();
			$('#buggl-modal-field-edit .content, #buggl-modal-field-edit .submit-section').empty();
			$('#buggl-modal-field-edit > span > label[name="title"]').empty();
		}
 		return this.each(function(){
 			if($('#buggl-modal-field-edit').length == 0)
 				$('body').append(template);


 			$('#buggl-modal-field-edit').reveal({
 				open: function(){
 					clearModal();
 					$('#buggl-modal-field-edit a.close-reveal-modal')
 						.on('click',function(){
 							clearModal();
 						});

 					var title = options.title.toLowerCase().replace(/\b[a-z]/g, function(letter) {
					    return letter.toUpperCase();
					});
 					title = "Edit "+title;

					// $("<h2/>").text("Edit "+title)
					// 	.appendTo($('#buggl-modal-field-edit'));
					$('#buggl-modal-field-edit').find('label[name="title"]').text(title);
					$("<button/>")
 						.text('Save')
 						.addClass('button')
 						.appendTo($('#buggl-modal-field-edit .submit-section'))
 						.on('click', function(){

 							if(options.type == 'text'){
 								// console.log($("input#"+options.identifier).val().match(/^\d+(\.\d{1,2})?$/));
 								// return false;
 								
 								if($("input#"+options.identifier).val().match(/^\d+(\.\d{1,2})?$/) == null)
 								{
 									alert('Invalid price range!');
 									return false;
 								}
 								var content = $("input#"+options.identifier).val();
 								
 							}
 							else {
 								var content = tinymce.get(options.identifier).getContent();
 							}
 							
 							var context = (null == options.context) ? $('body') : options.context;
 							options.save(content, context);

 							if(options.close_on_save){
 								$("#buggl-modal-field-edit a.close-reveal-modal").trigger('click');
 								// $("#buggl-modal-field-edit").remove();
 							}
 								

 						});
 					$("<button/>")
 						.text('Cancel')
 						.addClass('button')
 						.appendTo($('#buggl-modal-field-edit .submit-section'))
 						.on('click', function(){
 							$("#buggl-modal-field-edit a.close-reveal-modal").trigger('click');
 						});

 					if(true == options.has_limit){
 						$("<input/>",{'readonly':true,'id':options.identifier+"-char-count"})
	 						.val(options.limit)
	 						.addClass('remaining-chars-display')
	 						.appendTo($('#buggl-modal-field-edit .submit-section'));
 					}


					if(options.type == 'text'){
						var presyo = parseFloat(options.default_content);
						var price = presyo.toFixed(2);
						var text = $('<input/>', { type: 'text', name : options.identifier, id: options.identifier })
									.val(price);
						$('#buggl-modal-field-edit .content').append(text);

					}
					else {
						var textarea = $("<textarea/>",{ name : options.identifier, id: options.identifier })
 										.css('width', '100%').text(options.default_content);
	 					$('#buggl-modal-field-edit .content').append(textarea);

	 					$(textarea)
				 			.tinymce({
				 				script_url : '/bundles/bugglmain/js/tiny_mce/tiny_mce.js',
				 				width: '100%',
				 				height: '100%',
				 				theme : "advanced",
								plugins : "paste",
								gecko_spellcheck : true, // enable native browser spell-check
								theme_advanced_path : false, // remove path: p
								// Theme options
								theme_advanced_buttons1 : options.tinymce_buttons1,
								theme_advanced_buttons2 : "",
								theme_advanced_buttons3 : "",
								theme_advanced_buttons4 : "",
								theme_advanced_toolbar_location : "top",
								theme_advanced_toolbar_align : "left",
								theme_advanced_statusbar_location : "bottom",
								theme_advanced_resizing : true,
								theme_advanced_blockformats: "p,div,h3,blockquote",
								body_class: "tinymce-bg",
								handle_event_callback: function(e){
									
									if('keydown' == e.type && options.has_limit == true){
										// console.log(e.keyCode);
										// console.log($("#"+options.identifier+"-char-count"));
										var ed = tinymce.get(options.identifier);
				 						//var body = ed.getBody(), text = tinymce.trim(body.innerText || body.textContent);
				 						var text = $(ed.getContent()).text();
				 						var keycode =  e.keyCode ? e.keyCode : e.which;
								        // console.log(keycode);
												
								        if(keycode >= 37 && keycode <= 40){
								            return true;
								        }
								        var limit = options.limit;
										
										// maybe we can do a better job here with the +1
								        var initialCount = text.length + 1;
								        var cntr = limit - text.length;
								        
								        if(keycode == 8 || keycode == 46){
											var selectedText = ed.selection.getContent({format : 'text'});
											var subtrahend = selectedText.length > 0 ? selectedText.length : 1
								        	var nCntr = (text.length == 0) ? limit : parseInt(cntr) + subtrahend;
								        	$("#"+options.identifier+"-char-count").val(nCntr > limit ? limit : nCntr);
								            return true;
								        }
								        else {
								            if(cntr <= 0){
												$("#"+options.identifier+"-char-count").val(0);
								                return false;
								            }
								            
								            var x = limit - initialCount;
								            
								            $("#"+options.identifier+"-char-count").val(x);
								        }
										
				 					}


									options.tinymce_event_callback(e);
								},
								setup: function(ed){
									options.tinymce_setup;
								},
								oninit: function(){
									var body = tinymce.get(options.identifier).getBody();
									
									$(body).css({'background-color':'#CCCCCC'});
									if(true == options.has_limit){
										var ed = tinymce.get(options.identifier);
				 						//var body = ed.getBody(), text = tinymce.trim(body.innerText || body.textContent);
				 						var text = $(ed.getContent()).text();
										
										var cnt = options.limit - text.length;
										$("#"+options.identifier+"-char-count").val(cnt);
									}

								},
								paste_preprocess: function(p1,o){
									var ed = tinymce.get(options.identifier);
									// formatting will be removed
			 						var text = $(ed.getContent()).text();
									
						            var pastedText = $("<p>"+o.content+"</p>").text();
						            if(text.length + pastedText.length >= options.limit){
						            	o.content = pastedText.substring(0, options.limit - text.length);
						            	$("#"+options.identifier+"-char-count").val(0);
						            }
						            else {
						            	o.content = pastedText;
						            	var cnt = options.limit - (text.length + pastedText.length);
						            	$("#"+options.identifier+"-char-count").val(cnt);
						            }
									
								}
				 			});
					}
 				}
 			});
 		});
 	}

 	$.fn.buggl_modal_field_edit_v2 = function(options){

 		var defaults = {
 			'context' : null,
 			'title' : '',
 			'introTitle' : '',
 			'introTitleID' : '',
 			'introContentID' : '',
 			'default_content': '',
 			'close_on_save': true,
 			'has_limit' : false,
 			'limit' : 5,
 			'tinymce_buttons1': 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,backcolor',
 			'tinymce_event_callback': function(e){},
 			'tinymce_setup': function(ed){},
 			'save': function(content, element){}
 		};

 		var options = $.extend({}, defaults, options); 

 		var template = '<div id="buggl-modal-field-edit" class="reveal-modal large" style="background: none repeat scroll 0 0 #FFFFFF !important;padding: 15px 15px 30px;">'+
 							'<div class="form-section cover"><span><label name="title">test</label></span><a class="close-reveal-modal">×</a>'+
							'<div class="content"></div></div>'+
							'<div class="submit-section form-section"></div>'+
						'</div>';

		function clearModal(){
			// $('#buggl-modal-field-edit').find('*').not('a.close-reveal-modal').remove();
			$('#buggl-modal-field-edit .content, #buggl-modal-field-edit .submit-section').empty();
			$('#buggl-modal-field-edit > span > label[name="title"]').empty();
		}
 		return this.each(function(){
 			if($('#buggl-modal-field-edit').length == 0)
 				$('body').append(template);


 			$('#buggl-modal-field-edit').reveal({
 				open: function(){
 					clearModal();
 					$('#buggl-modal-field-edit a.close-reveal-modal')
 						.on('click',function(){
 							clearModal();
 						});

 					var title = options.title.toLowerCase().replace(/\b[a-z]/g, function(letter) {
					    return letter.toUpperCase();
					});
 					title = "Edit "+title;

					// $("<h2/>").text("Edit "+title)
					// 	.appendTo($('#buggl-modal-field-edit'));
					$('#buggl-modal-field-edit').find('label[name="title"]').text(title);
					$("<button/>")
 						.text('Save')
 						.addClass('button')
 						.appendTo($('#buggl-modal-field-edit .submit-section'))
 						.on('click', function(){

							var content = tinymce.get(options.introContentID).getContent();
 							
 							var context = (null == options.context) ? $('body') : options.context;
 							var title = $("#"+options.introTitleID).val();
 							options.save(title, content, context);

 							if(options.close_on_save){
 								$("#buggl-modal-field-edit a.close-reveal-modal").trigger('click');
 								// $("#buggl-modal-field-edit").remove();
 							}
 								

 						});
 					$("<button/>")
 						.text('Cancel')
 						.addClass('button')
 						.appendTo($('#buggl-modal-field-edit .submit-section'))
 						.on('click', function(){
 							$("#buggl-modal-field-edit a.close-reveal-modal").trigger('click');
 						});

 					if(true == options.has_limit){
 						$("<input/>",{'readonly':true,'id':options.introContentID+"-char-count"})
	 						.val(options.limit)
	 						.addClass('remaining-chars-display')
	 						.appendTo($('#buggl-modal-field-edit .submit-section'));
 					}


					
					var text = $('<input/>', { type: 'text', name : options.introTitleID, id: options.introTitleID, placeholder: 'Give your day a name' })
									.val(options.introTitle);
					$('#buggl-modal-field-edit .content').append(text);

					var textarea = $("<textarea/>",{ name : options.introContentID, id: options.introContentID })
										.css('width', '100%').text(options.default_content);
 					$('#buggl-modal-field-edit .content').append(textarea);

 					$(textarea)
			 			.tinymce({
			 				script_url : '/bundles/bugglmain/js/tiny_mce/tiny_mce.js',
			 				width: '100%',
			 				height: '100%',
			 				theme : "advanced",
							plugins : "paste",
							gecko_spellcheck : true, // enable native browser spell-check
							theme_advanced_path : false, // remove path: p
							// Theme options
							theme_advanced_buttons1 : options.tinymce_buttons1,
							theme_advanced_buttons2 : "",
							theme_advanced_buttons3 : "",
							theme_advanced_buttons4 : "",
							theme_advanced_toolbar_location : "top",
							theme_advanced_toolbar_align : "left",
							theme_advanced_statusbar_location : "bottom",
							theme_advanced_resizing : true,
							theme_advanced_blockformats: "p,div,h3,blockquote",
							body_class: "tinymce-bg",
							handle_event_callback: function(e){
								
								if('keydown' == e.type && options.has_limit == true){
									// console.log(e.keyCode);
									// console.log($("#"+options.identifier+"-char-count"));
									var ed = tinymce.get(options.introContentID);
			 						//var body = ed.getBody(), text = tinymce.trim(body.innerText || body.textContent);
			 						var text = $(ed.getContent()).text();
			 						var keycode =  e.keyCode ? e.keyCode : e.which;
							        // console.log(keycode);
											
							        if(keycode >= 37 && keycode <= 40){
							            return true;
							        }
							        var limit = options.limit;
									
									// maybe we can do a better job here with the +1
							        var initialCount = text.length + 1;
							        var cntr = limit - text.length;
							        
							        if(keycode == 8 || keycode == 46){
										var selectedText = ed.selection.getContent({format : 'text'});
										var subtrahend = selectedText.length > 0 ? selectedText.length : 1
							        	var nCntr = (text.length == 0) ? limit : parseInt(cntr) + subtrahend;
							        	$("#"+options.introContentID+"-char-count").val(nCntr > limit ? limit : nCntr);
							            return true;
							        }
							        else {
							            if(cntr <= 0){
											$("#"+options.introContentID+"-char-count").val(0);
							                return false;
							            }
							            
							            var x = limit - initialCount;
							            
							            $("#"+options.introContentID+"-char-count").val(x);
							        }
									
			 					}


								options.tinymce_event_callback(e);
							},
							setup: function(ed){
								options.tinymce_setup;
							},
							oninit: function(){
								var body = tinymce.get(options.introContentID).getBody();
								
								$(body).css({'background-color':'#CCCCCC'});
								if(true == options.has_limit){
									var ed = tinymce.get(options.introContentID);
			 						//var body = ed.getBody(), text = tinymce.trim(body.innerText || body.textContent);
			 						var text = $(ed.getContent()).text();
									
									var cnt = options.limit - text.length;
									$("#"+options.introContentID+"-char-count").val(cnt);
								}

							},
							paste_preprocess: function(p1,o){
								var ed = tinymce.get(options.introContentID);
								// formatting will be removed
		 						var text = $(ed.getContent()).text();
								
					            var pastedText = $(o.content).text();
					            if(text.length + pastedText.length >= options.limit){
					            	o.content = pastedText.substring(0, options.limit - text.length);
					            	$("#"+options.introContentID+"-char-count").val(0);
					            }
					            else {
					            	o.content = pastedText;
					            	var cnt = options.limit - (text.length + pastedText.length);
					            	$("#"+options.introContentID+"-char-count").val(cnt);
					            }
								
							}
			 			});
 				}
 			});
 		});
 	}

 	$.fn.buggl_modal_guide_bgphoto_upload = function(options){
 		var defaults = {
 			eguide_id : 0,
 			day: 0,
 			type: 0,
 			default_image: null
 		};

 		var options = $.extend({}, defaults, options);

		var template = '<div class="cropper-modal custom-modal" id="buggl-modal-guide-bgphoto-upload" style="display:none;">'+
							'<div class="shadow">'+
							'</div>'+
							'<div>'+
								'<h2>Change Background<a class="close to-right">x</a></h2>'+
								'<form class="file" id="buggl-modal-guide-bgphoto-upload-ifrm" method="POST" enctype="multipart/form-data" action="">'+
				        			'<input type="text" value="BROWSE PHOTO HERE" name="proxy_2" />'+
									'<input type="file" name="travel-guide-photo" id="origin" class="origin"  />'+
								'</form>'+
								'<div class="cropper clearfix" style="height:410px;" id="cropper-photo-display">'+
								'</div>'+
								'<form id="bgphoto-crop-dimensions-ifrm" method="POST" action="" style="display:none;">'+
									'<input type="text" name="x-coord"/>'+
									'<input type="text" name="y-coord"/>'+
									'<input type="text" name="width"/>'+
									'<input type="text" name="height"/>'+
									'<input type="text" name="filename"/>'+
									'<input type="text" name="eguide_id"/>'+
									'<input type="text" name="day_num"/>'+
									'<input type="text" name="photo-type"/>'+
								'</form>'+
								'<div>'+
									'<a class="button" name="crop-image">Crop image</a>'+
									'<a class="button">Cancel</a>'+
								'</div>'+
							'</div>'+
						'</div>';

		return this.each(function(){
			if($('#buggl-modal-guide-bgphoto-upload').length == 0){
				$('body').append(template);
				var paths = window.location.pathname.split("/");
				var scriptName = (paths[1] == 'app_dev.php') ? '/app_dev.php' : '';
				var action = scriptName+'/local-author/process-temp-travel-guide-photo';
				$("form#buggl-modal-guide-bgphoto-upload-ifrm").attr('action', action);

				$('#buggl-modal-guide-bgphoto-upload')
					.find('a.close')
					.on('click', function(){ 
						$('#buggl-modal-guide-bgphoto-upload').remove();
						// console.log('removed'); 
					});

				$('#buggl-modal-guide-bgphoto-upload')
					.find('a[name="crop-image"]')
					.unbind('click')
					.on('click', function(e){
						e.preventDefault();
						if(parseInt($("#buggl-modal-guide-bgphoto-upload input[name='width']").val())){
							$("#bgphoto-crop-dimensions-ifrm").submit();
							return true;
						} 
						alert('Please select a crop region.');
					    return false;
					});

				$("form#buggl-modal-guide-bgphoto-upload-ifrm input[name='travel-guide-photo']")
	 				.on('change', function(){
	 					// console.log('upload file');
	 					$("#cropper-photo-display").empty();
	 					$("#buggl-modal-guide-bgphoto-upload-ifrm").submit();
	 				});
 				$("#bgphoto-crop-dimensions-ifrm").attr('action', scriptName+'/local-author/crop-travel-guide-photo');
				$("#bgphoto-crop-dimensions-ifrm")
					.iframePostForm({
						json : true,
						post: function(){
							// console.log('post');
						},
						complete : function (response){
							// console.log(response);
							window.location.reload();
						}
					});

				$("#buggl-modal-guide-bgphoto-upload-ifrm")
						.iframePostForm({
							json : true, 
							post: function(){
								// alert('post');
								// console.log('post');
								// $("#buggl-modal-guide-bgphoto-upload-ifrm").hide();
								$("#cropper-photo-display")
		 						.css({'background' : 'url(/bundles/bugglmain/images/ajax-loader.gif) no-repeat scroll center center #CCCCCC', 'height' : '400px'});
		 						// .empty();
							},
							complete : function (response){
								// console.log(options);

								$("#cropper-photo-display").empty();
		 						var img = new Image();
			 					$(img)
			 						.load(function(){
			 							$("#cropper-photo-display").append(img);
			 							$("#buggl-modal-guide-bgphoto-upload input[name='filename']").val(response.filename);
			 							$("#buggl-modal-guide-bgphoto-upload input[name='eguide_id']").val(options.eguide_id);
			 							$("#buggl-modal-guide-bgphoto-upload input[name='day_num']").val(options.day);
			 							$("#buggl-modal-guide-bgphoto-upload input[name='photo-type']").val(options.type);

			 							$(img).Jcrop({
											aspectRatio: 0.669,
											trueSize: [response.width, response.height],
											onSelect: function(coord){
												$("#buggl-modal-guide-bgphoto-upload input[name='x-coord']").val(coord.x);
			 									$("#buggl-modal-guide-bgphoto-upload input[name='y-coord']").val(coord.y);
			 									$("#buggl-modal-guide-bgphoto-upload input[name='width']").val(coord.w);
			 									$("#buggl-modal-guide-bgphoto-upload input[name='height']").val(coord.h);
											}
										});
			 						})
			 						.attr({'src' : response.url, 'height' : '400px', 'width' : 'auto' });

							}
						});


				
			}
 			
 			$('#buggl-modal-guide-bgphoto-upload').show();
 			if(options.default_image != null){
 				var img = new Image();
 					$(img)
 						.load(function(){
 							$("#cropper-photo-display").append(img);
 							var fname = options.default_image.split("/");
 							fname = fname[fname.length - 1];
 							// console.log(fname);
 							// console.log(img.filename);
 							$("#buggl-modal-guide-bgphoto-upload input[name='filename']").val(fname);
 							$("#buggl-modal-guide-bgphoto-upload input[name='eguide_id']").val(options.eguide_id);
 							$("#buggl-modal-guide-bgphoto-upload input[name='day_num']").val(options.day);
 							$("#buggl-modal-guide-bgphoto-upload input[name='photo-type']").val(options.type);
 							// console.log(img.width);
 							$(img).Jcrop({
								aspectRatio: 0.669,
								trueSize: [img.width, img.height],
								onSelect: function(coord){
									$("#buggl-modal-guide-bgphoto-upload input[name='x-coord']").val(coord.x);
 									$("#buggl-modal-guide-bgphoto-upload input[name='y-coord']").val(coord.y);
 									$("#buggl-modal-guide-bgphoto-upload input[name='width']").val(coord.w);
 									$("#buggl-modal-guide-bgphoto-upload input[name='height']").val(coord.h);
								}
							});
 						})
 						.attr({'src' : options.default_image, 'height' : '400px', 'width' : 'auto' });
 			}
 			
		});
 	}

 	$.fn.buggl_modal_guide_bgphoto_upload_v2 = function(options){
 		var defaults = {
 			eguide_id : 0,
 			day: 0,
 			type: 0,
 			default_image: null
 		};

 		var paths = window.location.pathname.split("/");
		var scriptName = (paths[1] == 'app_dev.php') ? '/app_dev.php' : '';
		var action = scriptName+'/local-author/process-temp-travel-guide-photo-from-web';
		var imageGrabber = null;
		var regularAction = scriptName+'/local-author/process-temp-travel-guide-photo';

 		var options = $.extend({}, defaults, options);

 		var template = '<div id="buggl-modal-guide-bgphoto-upload-v2" class="custom-modal" style="">'+
							'<div class="shadow"></div>'+
								'<div>'+
								'<h2>Edit Cover Image<a class="close to-right">x</a></h2>'+
								'<div class="type">'+
									'<a class="button active" name="upload-photo">From computer</a>'+
									'<a class="button" name="search-web">Search google</a>'+
								'</div>'+
								'<div class="crop-area">'+
									'<form id="buggl-modal-guide-bgphoto-upload-ifrm" method="POST" enctype="multipart/form-data" action="'+regularAction+'">'+
										'<div class="p-uploader">'+
											'<div class="file">'+
												'<span>Upload File</span>'+
												'<input type="file" name="travel-guide-photo" id="origin" class="origin"  />'+
											'</div>'+
											'<img id="bgUpload-loader-image" src="/bundles/bugglmain/images/ajax-loader_red1.gif" style="display:none;"/>'+
										'</div>'+	
									'</form>'+
									'<span>Photographs you upload are yours, you own them, we don\'t.  We won\'t sell, license or share them out</span>'+
								'</div>'+
								'<div class="search-area">'+
									'<div name="google-custom-search-area"></div>'+
									// this is where the search are will be inserted
								'</div>'+
								'<form id="bgphoto-crop-dimensions-ifrm" method="POST" action="" style="display:none;">'+
									'<input type="text" name="x-coord"/>'+
									'<input type="text" name="y-coord"/>'+
									'<input type="text" name="width"/>'+
									'<input type="text" name="height"/>'+
									'<input type="text" name="filename"/>'+
									'<input type="text" name="eguide_id"/>'+
									'<input type="text" name="day_num"/>'+
									'<input type="text" name="photo-type"/>'+
								'</form>'+
								'<div class="action-area">'+
									'<a class="button" name="save-crop-img">Save</a>'+
									'<a class="button" name="cancel-modal">Cancel</a>'+
								'</div>'+	
							'</div>'+
						'</div>';

		function init(){
			if($('#buggl-modal-guide-bgphoto-upload-v2').length == 0){
				$('body').append(template);
				var modal = $('#buggl-modal-guide-bgphoto-upload-v2');
				
				$(".crop-area", modal).show();
				$(".search-area", modal).hide();

				$("a[name='search-web']", modal)
					.on('click', function(e){
						e.preventDefault();
						$(".search-area", modal).show();
						$(".crop-area", modal).hide();
						$(".type a[name='upload-photo']").removeClass('active');
						$(this).addClass('active');
					});

				$("a[name='upload-photo']", modal)
					.on('click', function(e){
						e.preventDefault();
						
						if($(".crop-area form#buggl-modal-guide-bgphoto-upload-ifrm", modal).length == 0){
							var form = '<form id="buggl-modal-guide-bgphoto-upload-ifrm" method="POST" enctype="multipart/form-data" action="'+regularAction+'">'+
										'<div class="p-uploader">'+
											'<div class="file">'+
												'<span>Upload File</span>'+
												'<input type="file" name="travel-guide-photo" id="origin" class="origin"  />'+
											'</div>'+
											'<img id="bgUpload-loader-image" src="/bundles/bugglmain/images/ajax-loader_red1.gif" style="display:none;"/>'+
										'</div>'+	
									'</form>'+
									'<span>Photographs you upload are yours, you own them, we don\'t.  We won\'t sell, license or share them out</span>';
							$(".crop-area", modal).empty();
							$(".crop-area", modal).append(form);
							$(".crop-area", modal).css({'background' : ''});
							prepareRegularUpload();
						}

						$(".crop-area", modal).show();
						$(".search-area", modal).hide();
						$(".type a[name='search-web']").removeClass('active');
						$(this).addClass('active');
					});

				$("div[name='google-custom-search-area']", modal)
					.googleCustomSearch({
						'imageClick' : function(event, element){
							imageClick(event, element);
						},
					});

				$("#bgphoto-crop-dimensions-ifrm")
					.iframePostForm({
						json : false,
						post: function(){},
						complete : function (response){
							// console.log(response);
							window.location.reload();
						}
					});


				$(".action-area a[name='cancel-modal']", modal)
					.on('click', function(e){
						e.preventDefault();
						$(modal).hide();
					});

				$("a.close", modal)
					.on('click', function(e){
						e.preventDefault();
						$(modal).hide();
					});

				prepareRegularUpload();
			}
		}

		function imageClick(event, element){
			event.preventDefault();
			var imgLink = $(element).data('bigpiclink');
			$(".crop-area").empty();
			$(".crop-area").show();
			$(".search-area").hide();

			if( imageGrabber != null )
				imageGrabber.abort();

			imageGrabber = $.ajax({
					url: action,
					data: { image_url : imgLink },
					dataType: 'json',
					beforeSend: function(){
						$(".crop-area")
							.css({'background' : 'url(/bundles/bugglmain/images/ajax-loader_red-grayBG.gif) no-repeat scroll center center #CCCCCC'});
					},
					success: function(data){
						$("#bgphoto-crop-dimensions-ifrm").attr('action', scriptName+'/local-author/crop-travel-guide-photo');
						prepareCropper(data);
					}
				});
			
		}

		function prepareRegularUpload(){
			$("form#buggl-modal-guide-bgphoto-upload-ifrm input[name='travel-guide-photo']")
	 				.on('change', function(){
	 					$("#buggl-modal-guide-bgphoto-upload-ifrm").submit();
	 				});
			$("#buggl-modal-guide-bgphoto-upload-ifrm")
				.iframePostForm({
					json : false, 
					post: function(){
						$("#bgUpload-loader-image").show();
					},
					complete : function (response){
						var response = jQuery.parseJSON(response);
						if(response == null){
							$.fn.bugglAlert({
								'title' : 'Error',
								'content' : 'Photo was not found, invalid or file size maybe too large.'
							});
							$("#bgUpload-loader-image").hide();
						}
						else{
							$(".crop-area").empty();

							$("#bgphoto-crop-dimensions-ifrm").attr('action', scriptName+'/local-author/crop-travel-guide-photo');
							prepareCropper(response);
						}
					}
				});
		}

		function prepareCropper(data){
			$(".action-area a[name='save-crop-img']")
				.unbind('click')
				.on('click', function(e){
					e.preventDefault();
					$("#bgphoto-crop-dimensions-ifrm").submit();
				});

			loadImage(data);
			
		}

		function loadImage(data){
			$(".crop-area")
				.css({'background' : 'url(/bundles/bugglmain/images/ajax-loader_red-grayBG.gif) no-repeat scroll center center #CCCCCC'});
			
			var newWidth = parseInt( (400 * data.width)/data.height ) ;
			var imgID = 'img-'+data.filename;
			var img = new Image();
			$(img)
				.load(function(){
					$(".crop-area").append(img);
					$("#bgphoto-crop-dimensions-ifrm input[name='filename']").val(data.filename);
					$("#bgphoto-crop-dimensions-ifrm input[name='eguide_id']").val(options.eguide_id);
					$("#bgphoto-crop-dimensions-ifrm input[name='day_num']").val(options.day);
					$("#bgphoto-crop-dimensions-ifrm input[name='photo-type']").val(options.type);

					$("#bgphoto-crop-dimensions-ifrm input[name='x-coord']").val(0);
					$("#bgphoto-crop-dimensions-ifrm input[name='y-coord']").val(0);
					$("#bgphoto-crop-dimensions-ifrm input[name='width']").val(250);
					$("#bgphoto-crop-dimensions-ifrm input[name='height']").val(300);
					
					if( $.browser.msie && parseInt( $.browser.version ) < 10 ){
					    img.width = newWidth;
						img.height = 400;
					}
					
					prepareJCrop(img, data);
					
				})
				.attr({
					'id' : imgID,
					'src' : data.url,
					'height' : '400px',
					'width' : newWidth+'px'
				});
			
			
		}

		function prepareJCrop(img, data){
			$(img).Jcrop({
				aspectRatio: 0.669,
				trueSize: [data.width, data.height],
				setSelect: [0,0,250,300],

				onSelect: function(coord){
					$("#bgphoto-crop-dimensions-ifrm input[name='x-coord']").val(coord.x);
					$("#bgphoto-crop-dimensions-ifrm input[name='y-coord']").val(coord.y);
					$("#bgphoto-crop-dimensions-ifrm input[name='width']").val(coord.w);
					$("#bgphoto-crop-dimensions-ifrm input[name='height']").val(coord.h);
				}
			});
		}

		return this.each(function(){
			init();
			$('#buggl-modal-guide-bgphoto-upload-v2').show();
		});
 	}

 	$.buggl_utility = { 		
		createItinerary: function(element, cancelUrl){
			var link = $(element).attr('href');
			var paths = window.location.pathname.split("/");
			var scriptName = (paths[1] == 'app_dev.php') ? '/app_dev.php' : '';
			var slug = $("section.step-2").attr('id');
			$.fn.bugglConfirm({
				'title' : 'Add a day around your secret places',
				'content' : "<p>Do you want to create an itinerary?</p>",
				'onConfirm' : function(){
					var url = "http://" + window.location.host + scriptName + "/local-author/create-itinerary/" + slug ;
					$.ajax({
						url: scriptName + "/local-author/create-itinerary/" + slug,
						type: 'post',
						dataType: 'json',
						success: function(response){
							var url = "http://" + window.location.host + link;
							window.location.assign(url);
						}
					});

					
				},
				'onCancel' : function(){
					$("a.close-reveal-modal").trigger('click');
					if( cancelUrl !== undefined )
						window.location.assign(cancelUrl);
				}
			});
		}
 	}

 })(jQuery);

 $().ready(function(){
 	
 	var paths = window.location.pathname.split("/");
 	var bugglScriptName = (paths[1] == 'app_dev.php') ? '/app_dev.php' : '';
 	
 	$("[name='popup-edit']")
 		.unbind('click')
 		.on('hover', function(){ $(this).css('cursor','pointer')})
 		.on('click', function(e){
 			e.preventDefault();
 			var me = this;
 			var type = (typeof($(me).data('type')) == 'undefined') ? 'textarea' : $(me).data('type');
 			// console.log(type);
 			// console.log($(me).data('content'));
 			// console.log($(me).data('fieldname'));
 			var title = ($(me).data('fieldname') == "excerpts") ? "Intro" : $(me).data('fieldname');
 			var tinymce_buttons = ($(me).data('fieldname') == "title") ? 'forecolor' : 'bold,italic,underline,|,forecolor';
 			var limit = ($(me).data('fieldname') == "title") ? 80 : 130;
 			var options = {
 				context : me,
 				type: type,
 				title: title,
 				has_limit: true,
 				limit: limit,
 				identifier: $(me).data('fieldname'),
 				tinymce_buttons1 : tinymce_buttons,
 				default_content: $(me).data('content'),
 				save:function(content, element){
 					// console.log(element);
					var guide_slug = $(element).parents('section').attr('id');
					var field_name = $(element).data('fieldname');
					
					$.ajax({
						url: bugglScriptName+'/local-author/update-travel-guide/'+guide_slug,
						data : { 'field_name' : field_name, 'field_value' : content },
						type: 'post',
						dataType: 'json',
						success: function(response){
							var content = response.content;
							if(type == "text"){
								var price = parseFloat(content);
								var content = "$"+price.toFixed(2);
							}
							// content = (type == "text") ? "$"+content.toFixed(2) : content;
							$(element)
								.empty()
								.append(content)
								.data('content',response.content);
							$(element)
							.append('<a class="editable"><strong>EDIT.</strong><span class="client-edit-button">EDIT</span></a>');
							// console.log(response.content);
							if(field_name == 'title'){
								var fs = $(response.content).text().length > 40 ? 48 : 60;
								$('#title-field').attr('style','font-size:'+fs+'px');
							}
						},
						complete: function(){
							// console.log('ajax complete');
						}
					});
 				}
 			};
 			$(this).buggl_modal_field_edit(options);
 		});

 	$("[name='intro-text']")
 		.unbind('click')
 		.on('hover', function(){ $(this).css('cursor','pointer')})
 		.on('click', function(e){
 			e.preventDefault();
 			var me = this;
			
 			var options = {
 				context : me,
 				title: $(me).data('title'),
 				identifier: $(me).data('fieldname'),
 				has_limit: true,
 				limit: 1100,
 				default_content: $(me).data('content'),
 				tinymce_buttons1: 'bold,italic,underline',
 				tinymce_setup: function(ed){},
 				save:function(content, element){
 					// console.log(element);
					var guide_slug = $(element).parents('section').attr('id');
					var field_name = $(element).data('fieldname');

					$.ajax({
						url: bugglScriptName+'/local-author/update-travel-guide/'+guide_slug,
						data : { 'field_name' : field_name, 'field_value' : content },
						type: 'post',
						dataType: 'json',
						success: function(response){
							$(element)
								.empty()
								.append(response.content)
								.data('content',response.content);
							$(element)
								.append('<a class="editable"><strong>EDIT. </strong><span class="client-edit-button">EDIT</span></a>');
						},
						complete: function(){
							// console.log('ajax complete');
						}
					});
 				}
 			};
			
 			$(this).buggl_modal_field_edit(options);
 		});
	
	$("[name='guide-content']")
		.unbind('click')
 		.on('hover', function(){ $(this).css('cursor','pointer')})
 		.on('click', function(e){
 			e.preventDefault();
 			var me = this;
 			// console.log($(me).data('type'));
			var title = "Content";
			
			if($(me).data('title') != undefined){
				title = $(me).data('title');
			}
			else{
	 			var cType = $(me).data('type');
 			
	 			if(cType == 1){
	 				title = "Overview Content";
	 			}
	 			else if(cType == 2){
	 				title = "Before You Go Content";
	 			}
			}
 			
 			var options = {
 				context : me,
 				has_limit: true,
 				limit: 1800,
 				title: title,
 				identifier: 'eguide-content',
 				default_content: $(me).data('content'),
 				tinymce_buttons1: 'bold,italic,underline',
 				tinymce_setup: function(ed){},
 				save:function(content, element){
 					
					var guide_slug = $(element).parents('section').attr('id');
					var content_id = $(element).data('contentid');
					var content_type = $(element).data('type');

					$.ajax({
						url: bugglScriptName+'/local-author/update-travel-guide-content/'+guide_slug,
						data : { 'field_value' : content, 'content_id' : content_id, 'content_type' : content_type },
						type: 'post',
						dataType: 'json',
						success: function(response){
							$(element)
								.empty()
								.append(response.content)
								.data('content',response.content);
							$(element).data('overviewid',response.id);
							$(element)
								.append('<a class="editable"><strong>EDIT. </strong><span class="client-edit-button">EDIT</span></a>');
						},
						complete: function(){
							// console.log('ajax complete');
						}
					});
 				}
 			};
 			$(this).buggl_modal_field_edit(options);
 		});


	$("[name='daily-intro-text']")
		.unbind('click')
 		.on('hover', function(){ $(this).css('cursor','pointer')})
 		.on('click', function(e){
 			
 			e.preventDefault();
 			var me = this;
 			var title = "Day "+$(me).data('day')+" Intro";
 			var introTitle = $("#daily-intro-title").text();
 			var options = {
 				context : me,
 				has_limit: true,
 				limit: 1100,
 				title: title,
 				introTitle: introTitle,
 				introTitleID: 'day-'+$(me).data('day')+'-intro-title',
 				introContentID: 'day-'+$(me).data('day')+'-intro',
 				default_content: $(me).data('content'),
 				tinymce_buttons1: 'bold,italic,underline',
 				tinymce_setup: function(ed){},
 				save:function(title, content, element){
 					
					var guide_slug = $(element).parents('section').attr('id');
					var day_num = $(me).data('day');
					
					$.ajax({
						url: bugglScriptName+'/local-author/update-itinerary-intro/'+guide_slug,
						data : { 'title' : title, 'description' : content, 'day_num' : day_num },
						type: 'post',
						dataType: 'json',
						success: function(response){
							var title = $("<h3/>", {id:'daily-intro-title'}).text(response.title);
							$(element)
								.empty()
								.append(title)
								.append(response.content)
								.data('content',response.content);
							$(element).data('overviewid',response.id);
							$(element)
								.append('<a class="editable"><strong>EDIT. </strong><span class="client-edit-button">EDIT</span></a>');
							
						},
						complete: function(){
							
						}
					});
 				}
 			};
 			$(this).buggl_modal_field_edit_v2(options);
 		});
 	
 	$("[name='change-background-image']")
 		.unbind('click')
 		.on('hover', function(){ $(this).css('cursor','pointer')})
 		.on('click', function(e){
 			e.preventDefault();
 			
 			var daynum = (typeof($(this).data('daynum')) != "undefined") ? $(this).data('daynum') : 0;
 			// console.log(daynum);	
 			var options = {eguide_id : $(this).data('eguideid'),type: $(this).data('phototype'), day : daynum, default_image : $(this).attr('href')};
 			$(this).buggl_modal_guide_bgphoto_upload_v2(options);
 		});

 	$("[name='before-you-go-content-holder']")
 		.on('click', 'a.editable', function(e){
 			e.preventDefault();
 			// console.log('edit');
 			var me = this;
 			var title = $(me).parents('div[name="before-you-go-content-holder"]').data('title');
 			var holder = $(me).parents('div[name="before-you-go-content-holder"]');
 			var options = {
 				context : holder,
 				has_limit: true,
 				limit: 500,
 				title: title,
 				identifier: 'before-you-go-content-holder',
 				default_content: $(holder).data('content'),
 				tinymce_buttons1: 'bold,italic,underline',
 				tinymce_setup: function(ed){},
 				save:function(content, element){
 					
					var guide_slug = $(element).parents('section').attr('id');
					var field_name = $(element).data('fieldname');
					$.ajax({
						url: bugglScriptName+'/local-author/update-before-you-go/'+guide_slug,
						data : { 'field_value' : content, 'field_name' : field_name, },
						type: 'post',
						dataType: 'json',
						success: function(response){
							$(element)
								.empty()
								.append(response.content)
								.data('content',response.content);

							$(element)
								.append('<a class="editable"><strong>EDIT. </strong><span class="client-edit-button">EDIT</span></a>');
						
						},
						complete: function(){
						
						}
					});
 				}
 			};
 			$(this).buggl_modal_field_edit(options);
 		});

	$("div.guide-page-navi > a[name='itinerary']")
		.on('click', function(e){
			e.preventDefault();
			var hasItinerary =  $("section.step-2").data('hasitinerary');
			
			if(0 == hasItinerary){
				var url = "http://" + window.location.host + $("#overview-chapter-menu").attr('href');
				$.buggl_utility.createItinerary(this, url);
			}
			else {
				var link = $(this).attr('href');
				var url = "http://" + window.location.host + link;
				window.location.assign(url);
			}
			
		});

	$("div.guide-page-navi > a[name='overview']")
		.on('click', function(e){
			
			if( $("#itineraryPagination li[data-spotscnt='0']").length > 0 ){
				var msg = '<p>You have '+$("#itineraryPagination li[data-spotscnt='0']").length+' vacant day(s) in your itinerary, please add spot to it first before moving to the next step.<p>';
	 			var title = "Vacant day in Itinerary!";
	 			$.fn.bugglAlert({
	 				'title': title,
	 				'content': msg
				});
				
				return false;
			}

			
		});

	$("#itinerary-chapter-menu")
		.on('click', function(e){
			e.preventDefault();
			
			var link = $(this).attr('href');
			var hasItinerary =  $("section.step-2").data('hasitinerary');
			if(0 == hasItinerary){
				$.buggl_utility.createItinerary(this);
			}
			else {
				var url = "http://" + window.location.host + link;
				window.location.assign(url);
			}
		
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

	$("ul.create-guide-steps")
		.find('a')
		.on('click', function(e){
			e.preventDefault();
		});
 }); 	 