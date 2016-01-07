/**
 * Buggl utility javascript
 * Version 1.0
 * nash.lesigon@goabroad.com
 */
 // var d = new Date();
 // alert('multidesc description v1.0 : tags 123 '+d.getTime());
 (function($){

 	$.fn.multidesc_edit_photo = function(options){
 		var defaults = {
 			spot_id : 0,
 			desc_id : 0,
 			src : ''
 		};
 		
 		var options = $.extend({}, defaults, options);
 		
 		var imageGrabber = null;

 		var paths = window.location.pathname.split("/");
		var scriptName = (paths[1] == 'app_dev.php') ? '/app_dev.php' : '';
		var action = scriptName+'/local-author/process-temp-travel-guide-photo-from-web';

		var regularAction = scriptName+'/local-author/process-temp-travel-guide-photo';

 		var form = '<form id="buggl-modal-guide-descphoto-upload-ifrm" method="POST" enctype="multipart/form-data" action="'+regularAction+'">'+
						'<div class="p-uploader">'+
							'<div class="file">'+
								'<span>Upload File</span>'+
								'<input type="file" name="travel-guide-photo" id="origin" class="origin"  />'+
							'</div>'+
							'<img id="bgUpload-loader-image" src="/bundles/bugglmain/images/ajax-loader_red1.gif" style="display:none;"/>'+
						'</div>'+
					'</form>'+
					'<span>Photographs you upload are yours, you own them, we don\'t.  We won\'t sell, license or share them out</span>';

 		var template = '<div id="multidesc-edit-photo-modal" class="custom-modal" style="">'+	
 						'<div class="shadow"></div>'+
						'<div name="custom-modal-content">'+
							'<h2>Edit Image<a class="close to-right">x</a></h2>'+
							'<div class="type">'+
								// '<p><a href="">Upload Photo</a> or <input type="text" placeholder="Search Google"/><input type="submit" value="Go"></p>'+
								'<a class="button active" name="upload-photo">From computer</a>'+
								'<a class="button" name="search-web">Search google</a>'+
							'</div>'+
							'<div class="crop-area">'+
								form +
							'</div>'+
							'<div class="search-area" style="display:none;">'+
								'<div name="google-custom-search-area"></div>'+
								// this is where the search are will be inserted
							'</div>'+
							'<form id="descphoto-crop-dimensions-ifrm" method="POST" action="" style="display:none;" >'+
								'<input type="text" name="start-x"/>'+
								'<input type="text" name="start-y"/>'+
								'<input type="text" name="target-x"/>'+
								'<input type="text" name="target-y"/>'+
								'<input type="text" name="filename"/>'+
								'<input type="text" name="desc_id"/>'+
								'<input type="text" name="spot_id"/>'+
							'</form>'+
							'<div class="action-area">'+
								'<a class="button" name="save-crop-img">Crop image</a>'+
								'<a class="button cancel">Cancel</a>'+
							'</div>'+
						'</div>'+
					   '</div>';

		function init(){
			
			if($("#multidesc-edit-photo-modal").length == 0){
				$('body').append(template);
				var modal = $('#multidesc-edit-photo-modal');

				$("div[name='custom-modal-content']",modal)
					.css({'margin-top':(parseInt($(document).scrollTop()) + 130) + 'px'});

				// $("form > div.p-uploader", modal)
				// 	.css({
				// 		"margin-top": "165px",
				// 		"margin-left": "145px",
				// 		"margin-bottom": "165px",
				// 		"width": "500px",
				// 		"text-align": "center",
				// 	});


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
						
						if($(".crop-area form#buggl-modal-guide-descphoto-upload-ifrm", modal).length == 0){
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

				$("a.close, .cancel", modal)
					.on('click', function(e){
						e.preventDefault();
						$(modal).remove();
					});

				// initialize regular upload
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
							$("#descphoto-crop-dimensions-ifrm").attr('action', scriptName+'/local-author/crop-spot-desc-photo');
							prepareCropper(data);
						}
					});
			
		}

		function prepareRegularUpload(){
			$("form#buggl-modal-guide-descphoto-upload-ifrm input[name='travel-guide-photo']")
				.unbind()
 				.on('change', function(){
 					$("#buggl-modal-guide-descphoto-upload-ifrm").submit();
 				});
			$("#buggl-modal-guide-descphoto-upload-ifrm")
				.iframePostForm({
					json : false, 
					post: function(){
						$("#bgUpload-loader-image").show();
					},
					complete : function (response){
						var response = $.parseJSON(response);
						if(response == null){
							$.fn.bugglAlert({
								'title' : 'Error',
								'content' : 'Photo was not found, invalid or file size maybe too large.'
							});
							$("#bgUpload-loader-image").hide();
						}
						else{
							$(".crop-area").empty();

							$("#descphoto-crop-dimensions-ifrm").attr('action', scriptName+'/local-author/crop-spot-desc-photo');
							prepareCropper(response);
						}
					}
				});
		}

		function prepareCropper(data){
			$("#descphoto-crop-dimensions-ifrm")
				.unbind()
				.iframePostForm({
					json : false,
					complete: function(response){
						var response = $.parseJSON(response);
						$("#desc_photo_" + options.desc_id).attr({ src : response.img_src});
						$("#multidesc-edit-photo-modal").hide('slow').remove();
					}
				});

			$(".action-area a[name='save-crop-img']")
				.unbind('click')
				.on('click', function(e){
					e.preventDefault();
					$("#descphoto-crop-dimensions-ifrm").submit();
					
				});

			loadImage(data);
			
		}

		function loadImage(data){
			var newWidth = (400 * data.width)/data.height;
			var img = new Image();
			$(img)
				.load(function(){
					$(".crop-area").append(img);
					$("#descphoto-crop-dimensions-ifrm input[name='filename']").val(data.filename);
					$("#descphoto-crop-dimensions-ifrm input[name='spot_id']").val(options.spot_id);
					$("#descphoto-crop-dimensions-ifrm input[name='desc_id']").val(options.desc_id);

					$("#descphoto-crop-dimensions-ifrm input[name='start-x']").val(0);
					$("#descphoto-crop-dimensions-ifrm input[name='start-y']").val(0);
					$("#descphoto-crop-dimensions-ifrm input[name='target-x']").val(250);
					$("#descphoto-crop-dimensions-ifrm input[name='target-y']").val(300);
					
					prepareJCrop(img, data);	
				})
				.attr({
					'src' : data.url,
					'height' : '400px',
					'width' : newWidth+'px'
				});
		}

		function prepareJCrop(img, data){
			$(img).Jcrop({
				aspectRatio: 1.54,
				trueSize: [data.width, data.height],
				setSelect: [0,0,300,250],

				onSelect: function(coord){
					$("#descphoto-crop-dimensions-ifrm input[name='start-x']").val(coord.x);
					$("#descphoto-crop-dimensions-ifrm input[name='start-y']").val(coord.y);
					$("#descphoto-crop-dimensions-ifrm input[name='target-x']").val(coord.w);
					$("#descphoto-crop-dimensions-ifrm input[name='target-y']").val(coord.h);
				}
			});
		}

		return this.each(function(){
			init();
			$('#multidesc-edit-photo-modal').show();
		});
 	}

 	$.fn.multidesc_description = function(options){

 		var defaults = {
 			'introContentID' : '',
 			'default_content': '',
 			'close_on_save': true,
 			'limit' : 600,
 			'tinymce_buttons1': 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,backcolor',
 			'tinymce_event_callback': function(e){},
 			'tinymce_setup': function(ed){},
 			'save': function(content, element){}
 		};

 		var options = $.extend({}, defaults, options); 

 		var template = '<div id="multidesc-description" class="reveal-modal large" style="background: none repeat scroll 0 0 #FFFFFF !important;padding: 15px 15px 30px;">'+
 							'<div class="form-section cover"><span><label name="title">Update Description</label></span><a class="close-reveal-modal">×</a>'+
							'<div class="content"></div>'+
							'<input id="'+options.introContentID+'-char-count" class="remaining-chars-display" type="text" disabled="" value="600">'+
							'</div>'+
							'<div class="submit-section form-section"></div>'+
						'</div>';

		function init(){
			if($('#multidesc-description').length == 0){
				$('body').append(template);

				var textarea = $("<textarea/>",{ name : options.introContentID, id: options.introContentID })
									.css('width', '100%').text(options.default_content);
				$('#multidesc-description .content').append(textarea);
			}

		}

		function startEditor(){
			$("#"+options.introContentID)
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
						
						if('keydown' == e.type){
							var ed = tinymce.get(options.introContentID);
	 						//var body = ed.getBody(), text = tinymce.trim(body.innerText || body.textContent);
	 						var text = $(ed.getContent()).text();
	 						var keycode =  e.keyCode ? e.keyCode : e.which;
					        		
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
						var ed = tinymce.get(options.introContentID);
 						//var body = ed.getBody(), text = tinymce.trim(body.innerText || body.textContent);
 						var text = $(ed.getContent()).text();
						
						var cnt = options.limit - text.length;
						$("#"+options.introContentID+"-char-count").val(cnt);
						

					},
					paste_preprocess: function(p1,o){
						var ed = tinymce.get(options.introContentID);
						// formatting will be removed
 						var text = $(ed.getContent()).text();
						
			            var pastedText = $("<p>"+o.content+"</p>").text();
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



		
 		return this.each(function(){
 			init();


 			$('#multidesc-description').reveal({
 				open: function(){
 					startEditor();

					$("<button/>")
 						.text('Save')
 						.addClass('button')
 						.appendTo($('#multidesc-description .submit-section'))
 						.on('click', function(){

							var content = tinymce.get(options.introContentID).getContent();
 							
 							var context = (null == options.context) ? $('body') : options.context;
 							options.save(content, context);

 							if(options.close_on_save){
 								$("#multidesc-description a.close-reveal-modal").trigger('click');
 								// $("#buggl-modal-field-edit").remove();
 							}
 								

 						});
 					$("<button/>")
 						.text('Cancel')
 						.addClass('button')
 						.appendTo($('#multidesc-description .submit-section'))
 						.on('click', function(){
 							$("#multidesc-description a.close-reveal-modal").trigger('click');
 						});
 				},
 				closed: function(){
 					$('#multidesc-description').remove();
 				}
 			});
 		});
 	}

 	$("a[name='multidesc-edit-photo']")
 		.on('click', function(e){
 			e.preventDefault();
 			
 			var options = {
 				src : $(this).attr('href'),
 				spot_id : $(this).data('spotid'),
 				desc_id : $(this).data('descid')
 			}
 			$(this).multidesc_edit_photo(options);
 		});

 	$("a[name='multidesc-description']")
 		.on('click', function(e){
 			e.preventDefault();
 			var desc_id = $(this).data('descid');
 			var me = this;
 			var options = {
 				'introContentID' : 'multidesc-description-form',
	 			'default_content': $(this).data('content'),
	 			save:function(content, element){
 					var paths = window.location.pathname.split("/");
				 	var bugglScriptName = (paths[1] == 'app_dev.php') ? '/app_dev.php' : '';
				 	
					$.ajax({
						url: bugglScriptName+'/local-author/update-spot-description',
						data : { 'description' : content, 'desc_id' : desc_id },
						type: 'post',
						dataType: 'json',
						success: function(response){
							$("#spot-description-"+desc_id)
								.empty()
								.append(content);

							$(me).data('content', content);
						},
						complete: function(){
							
						}
					});
 				}
 			}
 			$(this).multidesc_description(options);
 		});
 	
 })(jQuery);