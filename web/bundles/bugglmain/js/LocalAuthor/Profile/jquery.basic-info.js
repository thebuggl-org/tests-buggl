$(document).ready(function(){
	$('.editLocalAuthorInfo').on('click',function(e){
		e.preventDefault();

		$('#formModal .content').empty();
		$('#formModal .loader').show();
		$('#formModal').reveal({'closeOnBackgroundClick':false});
		$.get($(this).attr('href'),function(response){
			$('#formModal .loader').hide();
			$('#formModal .content').empty().html(response);
			$('#formModal .content').on('submit','#localAuthorForm',function(e){
				e.preventDefault();
				$('#formModal .loader').show();
				$.post($(this).attr('action'), $(this).serialize(), function(response){
					$('#formModal .loader').hide();
					if(response.status == 'SUCCESS'){
						$('#formModal').trigger('reveal:close');
						$('.authorContent_'+response.updateId).html(response.content);
					}	
					else{
						$('#formModal .content').empty().append(response.html);
					}
				});
			});
		});
	});
});