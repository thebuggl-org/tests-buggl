$(document).ready(function(){
	$('.profileInfoContainer').on('click','.editProfileInfo',function(e){
		e.preventDefault();
		var link = $(this);
		var modalTitle = link.attr('data-modal-title');
		var successBehavior = link.attr('data-success-behavior');
		var buildFor = link.attr('data-profile-info-id');

		link.hide();
		$('#profileInfoDisplay_'+buildFor).hide();
		$('#profileInfoDisplay_'+buildFor).after('<span class="loader">Loading...</span>');
		$.get(link.attr('href'),function(response){
			$('#profileContent_'+buildFor+' .loader').remove();
			$('#profileContent_'+buildFor).append(response);
			$('#profileContent_'+buildFor).off('submit').on('submit','#profileForm_'+buildFor,function(e){
				e.preventDefault();
				$('#profileContent_'+buildFor).append('<span class="loader">Loading...</span>');

				$.post($(this).attr('action'), $(this).serialize(), function(response){
					$('#profileContent_'+buildFor+' .loader').remove();
					if(response.status == 'SUCCESS'){
						if(response.content == null || successBehavior == 'reload'){
							window.location.reload();
						}
						else{
							link.show();
							$('#profileContent_'+response.updateId).empty().html(response.content)
						}
					}
					else{
						$('#profileForm_'+buildFor).remove();
						$('#profileContent_'+buildFor).append(response.content);
					}
				});
			});
		});
	});

	$('.profileInfoContainer').on('click','.profile-cancel',function(e){
		e.preventDefault();
		var id = $(this).attr('data-profile-content-id');
		$('#profileForm_'+id).remove();
		$('#profileInfoDisplay_'+id).show();
		$('.editProfileInfo[data-profile-info-id="'+id+'"]').show();
	});

	$('#formModal').on('click','.profile-cancel',function(e){
		e.preventDefault();
		$('#formModal').trigger('reveal:close');
	});

	// profile pic

	$('#uploadProfilePicButton').on('click',function(e){
		e.preventDefault();
		$('#uploadProfilePicFile').trigger('click');
	});

	$('#uploadProfilePic').on('click',function(e){
		e.preventDefault();
		$('#uploadProfilePicFile').val('');
		$('#uploadForm').show();

		if($('#uploadProfilePic').attr('data-file-name') != '' )
			applyJCrop($('#uploadProfilePic').attr('data-file-name'), $('#uploadProfilePic .profilePicture').attr('src'),285,285);

		$('#uploadProfilePicButton').show();
		$('#profilePicModal').show();
	});

	$('form#uploadForm').iframePostForm({
		json : false,
		post: function(){
			$('#cropperPhotoDisplay').append('<span class="loader2" style="display: block; margin-top: 180px; position: absolute; text-align: center;width: 100%;"><img style="float:none"src="/bundles/bugglmain/images/ajax-loader.gif"/></span>');
		},
		complete : function (serverResponse){
			var response = jQuery.parseJSON(serverResponse);
			if(response == null){
				$('#cropperPhotoDisplay .loader2').remove();
				$.fn.bugglAlert({
					'title' : 'Error',
					'content' : 'Photo was not found, invalid or file size maybe too large.'
				});
			}
			else{
				applyJCrop(response.filename,response.url,response.width,response.height);
				$('#uploadProfilePicButton').hide();
			}
		}
	});

	$('form#profilePhotoCropDimensions').iframePostForm({
		json : false,
		post: function(){},
		complete : function (serverResponse){
			var response = jQuery.parseJSON(serverResponse);
			$(".profilePicture").attr('src',response.url);
			$('#uploadProfilePic').attr('data-file-name',response.filename)
			$('a.closeButton').first().trigger('click');
		}
	});

	$('#uploadProfilePicFile').on('change',function(e){
		$('#profilePicModal form#uploadForm').submit();
		//$('#uploadForm').hide();
	});

	$('#cropButton').on('click',function(e){
		e.preventDefault();
		if(parseInt($("#profilePhotoCropDimensions input[name='width']").val())){
			$("form#profilePhotoCropDimensions").submit();
			return true;
		}

		$.fn.bugglAlert({
			'title' : 'Error',
			'content' : '<p>Please select a cropped area!</p>'
		});

	    return false;
	});

	$('a.closeButton').on('click',function(e){
		e.preventDefault();
		$("#profilePhotoCropDimensions input[name='x-coord']").val("");
		$("#profilePhotoCropDimensions input[name='y-coord']").val("");
		$("#profilePhotoCropDimensions input[name='width']").val("");
		$("#profilePhotoCropDimensions input[name='height']").val("");
		$("#profilePhotoCropDimensions input[name='filename']").val("");
		$("#profilePhotoCropDimensions input[name='tempPath']").val("");
		$("#cropperPhotoDisplay").empty();
		$('#profilePicModal').hide();
	});

	applyJCrop = function(filename, url, width, height){
		var newWidth = (400 * width)/height;
		$("#cropperPhotoDisplay").empty().append('<span class="loader2" style="display: block; margin-top: 180px; position: absolute; text-align: center;width: 100%;"><img style="float:none"src="/bundles/bugglmain/images/ajax-loader.gif"/></span>');
		var img = new Image();
		$(img)
			.load(function(){
				$("#cropperPhotoDisplay").empty().append(img);
				$("#profilePhotoCropDimensions input[name='filename']").val(filename);

				$(img).Jcrop({
					aspectRatio: 1,
					trueSize: [width, height],
					setSelect: [0,0,250,300],
					onSelect: function(coord){
						$("#profilePhotoCropDimensions input[name='x-coord']").val(coord.x);
						$("#profilePhotoCropDimensions input[name='y-coord']").val(coord.y);
						$("#profilePhotoCropDimensions input[name='width']").val(coord.w);
						$("#profilePhotoCropDimensions input[name='height']").val(coord.h);
					}
				});
			})
			.attr({'src' : url, 'height' : '400px', 'width' : newWidth+'px' });
	}
});