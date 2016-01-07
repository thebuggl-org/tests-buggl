/*
version 2
	in place add/edit
*/
$(document).ready(function() {
     $('#addInterest').on('click', function(e) {
     	e.preventDefault();
		$(this).hide();
		$("#localInterestList > li:last-child").before('<li class="interestLoader"><span class="loader">Loading...</span></li>');
		$.get($(this).attr('href'),function(response){
			$('.interestLoader').remove();
			$("#localInterestList > li:last-child").before(response);
			$('#LocalInterest_localAuthorId').val($('#addInterest').attr('data-local-author-id'));
			attachAddEvent();
		});
     });

	$('#localInterestList').on('click', '.deleteInterest',function(e){
		e.preventDefault();
		var link = $(this).attr('href');
		$.fn.bugglConfirm({
			'onConfirm' : function(){
				$("#localInterestList > li:last-child").before('<li id="interestLoader"><span class="loader">Loading...</span></li>');
				$.fn.bugglConfirmLoaderShow();
				$.get(link, function(response){
					$('#localInterestList #localInterest_'+response.idToRemove).remove();
					$('#interestLoader').remove();
					$.fn.bugglConfirmClose();
				});
			}
		});
	});
	
	$('#localInterestList').on('click', '.editInterest',function(e){
		e.preventDefault();
		var me = $(this);
		
		me.parent('li').hide().before('<li class="interestLoader"><span class="loader">Loading...</span></li>');
		$.get($(this).attr('href'),function(response){
			me.parent('li').prev('.interestLoader').remove();
			me.parent('li').before(response);
			$('#LocalInterest_localAuthorId').val($('#addInterest').attr('data-local-author-id'));
			attachEditEvent(me.parent('li').attr('data-local-interest-id'));
		});
		
		/*$('#formModal .content').empty();
		$('#formModal .loader').show();
		$('#formModal #modalTitle').empty().html('Edit Interest');
		$('#formModal').reveal({'closeOnBackgroundClick':false});
		$.get($(this).attr('href'),function(response){
			$('#formModal .loader').hide();
			$('#formModal .content').empty().html(response);
			$('#LocalInterest_localAuthorId').val($('#addInterest').attr('data-local-author-id'));
			attachEditEvent();
		});*/
	});
	
	$('#localInterestList').on('click', '.cancelAddLocalInterestButton',function(e){
		e.preventDefault();
		$(this).parents('li').next().show();
		$(this).parents('li').remove();
		$('#addInterest').show();
	});
	
	attachAddEvent = function(){
		$('#interestForm_0').off('submit').iframePostForm({
			json : false, 
			post: function(){
				//$('#formModal .loader').show();
			},
			complete : function (serverResponse){
				var response = jQuery.parseJSON(serverResponse);
				//$('#formModal .loader').hide();
				if(response.status == 'SUCCESS'){
					$('#newInterest_0').remove();
					$("#localInterestList > li:last-child").before(response.list);
					$('#addInterest').show();
				}
				else if(response.status == "ERROR"){
					$('#newInterest_0').empty().append(response.html);
					attachAddEvent();
				}
			}
		});
		attachUploadEvent(0);
	}
	
	attachEditEvent = function(elementId){
		$('#interestForm_'+elementId).off('submit').iframePostForm({
			json : false, 
			post: function(){
				//$('#formModal .loader').show();
			},
			complete : function (serverResponse){
				//$('#formModal .loader').hide();
				var response = jQuery.parseJSON(serverResponse);
				if(response.status == 'SUCCESS'){
					$('#newInterest_'+elementId).remove();
					var id = $('#localInterestList #localInterest_'+response.idToRemove).next('li').attr('id');
					$('#localInterestList #localInterest_'+response.idToRemove).remove();
					if(id === undefined)
						$("#localInterestList > li:last-child").before(response.list);
					else
						$('#localInterestList #'+id).before(response.list);
					//$('#formModal').trigger('reveal:close');
				}
				else if(response.status == "ERROR"){
					$('li#newInterest_'+elementId).addClass('remove').hide().before(response.html);
					$('li#newInterest_'+elementId+'.remove').remove();
					attachEditEvent(elementId);
				}
			}
		});
		attachUploadEvent(elementId);
	}
	
	attachUploadEvent = function(elementId){
		$('.interestPhotoUpload_'+elementId).off('change').on('change',function(){
			$('#interestPhotoForm_'+elementId).submit();
		});
		
		$('#interestPhotoForm_'+elementId).off('submit').iframePostForm({
			json : false, 
			post: function(){
				$('#interestPhotoForm_'+elementId).append('<span class="loader">Loading...</span>');
				$('#newInterestPhoto_'+elementId).hide();
			},
			complete : function (serverResponse){
				var response = jQuery.parseJSON(serverResponse);
				$('#interestPhotoForm_'+elementId+' .loader').remove();
				if(response === null ){
					$.fn.bugglAlert({
						'title' : 'Error',
						'content' : 'Photo was not found, invalid or file size maybe too large.'
					});
				}
				else{
					if(response.status == 'SUCCESS'){
						$('#newInterestPhoto_'+elementId).attr('src',response.webPath).show();
						$('#interestForm_'+elementId+' #LocalInterest_imageFilename').val(response.filename);
					}
					else if(response.status == "ERROR"){
						$('#interestPhotoForm_'+elementId).empty().append(response.html);
					}
				}
			}
		});
	}
});