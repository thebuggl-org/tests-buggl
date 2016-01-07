$(document).ready(function(){
	$('.profileInfoContainerv1').on('click','.editProfileInfo,.editProfileInfoContainer',function(e){
		e.preventDefault();
		var link = $(this);
		var execute = true;
		
		if($(this).hasClass('editProfileInfoContainer')){
			link = $(this).find('a.editProfileInfo').first();
		}
		else{
			if(link.parents('.editProfileInfoContainer').length != 0)
 				execute = false;
		}
		if(execute){
			var modalTitle = link.attr('data-modal-title');
			var successBehavior = link.attr('data-success-behavior');
			var buildFor = link.attr('data-profile-info-id');
			
			$('#formModal .content').empty();
			$('#formModal .loader').show();
			$('#formModal').reveal({'closeOnBackgroundClick':false});
			$.get(link.attr('href'),function(response){
				$('#formModal .loader').hide();
				$('#formModal #modalTitle').empty().html(modalTitle);
				$('#formModal .content').empty().html(response);
				$('#formModal .content').on('submit','#profileForm_'+buildFor,function(e){
					e.preventDefault();
					$('#formModal .loader').show();

					$.post($(this).attr('action'), $(this).serialize(), function(response){
						$('#formModal .loader').hide();
						if(response.status == 'SUCCESS'){
							if(response.content == null || successBehavior == 'reload'){
								window.location.reload();
							}
							$('#formModal').trigger('reveal:close');
							$('#profileContent_'+response.updateId).html(response.content)
						}	
						else{
							$('#formModal .content').empty().append(response.content);
						}
					});
				});
			});
		}
	});
});