$(document).ready(function(){
	$('.infoContainer').on('click','.editInfo',function(e){
		e.preventDefault();
		var link = $(this);
		var modalTitle = link.attr('data-modal-title');
		var successBehavior = link.attr('data-success-behavior');
		
		$('#formModal .content').empty();
		$('#formModal .loader').show();
		$('#formModal').reveal({'closeOnBackgroundClick':false});
		$.get(link.attr('href'),function(response){
			$('#formModal .loader').hide();
			$('#formModal #modalTitle').empty().html(modalTitle);
			$('#formModal .content').empty().html(response);
			$('#formModal .content .profileFormCancel').remove();
			$('#formModal .content').on('submit','form',function(e){
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
	});
});