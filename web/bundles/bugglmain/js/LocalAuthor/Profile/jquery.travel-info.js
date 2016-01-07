$(document).ready(function(){
	$('.travelInfoContainer').on('click','.editTravelInfo',function(e){
		e.preventDefault();
		
		var id = $(this).parents('li').attr('id');
		$('#formModal .content').empty();
		$('#formModal .loader').show();
		$('#formModal').reveal({'closeOnBackgroundClick':false});
		$.get($(this).attr('href'),function(response){
			$('#formModal .loader').hide();
			$('#formModal .content').empty().html(response);
			$('#TravelInfo_localAuthorId').val($('#addInterest').attr('data-local-author-id'));
			
			$('#travelInfoForm').on('submit',function(e){
				e.preventDefault();
				
				$('#formModal .loader').show();
				//$('#'+id).append($('#loaderDiv .loader'));
				$.post($(this).attr('action'), $(this).serialize(), function(response){
					if(response.status == 'SUCCESS'){
						$('#formModal .loader').hide();
						$('#formModal').trigger('reveal:close');
						//$('#loaderDiv').append($('#travelInfo_'+response.idToRemove+' .loader'));
						var ul = $('#travelInfo_'+response.idToRemove).parent('ul');
						$('#travelInfo_'+response.idToRemove).remove();				
						ul.append(response.html);
					}
					else{
						alert("ERRORS");
					}
				});
			});
		});
	});
});