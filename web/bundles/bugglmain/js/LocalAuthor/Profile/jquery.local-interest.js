/*
version 1
	uses reveal modal
*/
$(document).ready(function() {
     $('#addInterest').on('click', function(e) {
     	e.preventDefault();
		
		$('#formModal .content').empty();
		$('#formModal .loader').show();
		$('#formModal #modalTitle').empty().html('Add Interest');
		$('#formModal').reveal({'closeOnBackgroundClick':false});
		$.get($(this).attr('href'),function(response){
			$('#formModal .loader').hide();
			$('#formModal .content').empty().html(response);
			$('#LocalInterest_localAuthorId').val($('#addInterest').attr('data-local-author-id'));
			attachAddEvent();
		});
     });

	$('#localInterestList').on('click', '.deleteInterest',function(e){
		e.preventDefault();
		if(confirm("Are you sure?")){
			$('#localInterestContainer').append($('#loaderDiv .loader'));
			$.get($(this).attr('href'), function(response){
				$('#localInterestList #localInterest_'+response.idToRemove).remove();
				$('#loaderDiv').append($('#localInterestContainer .loader'));
			});
		}
	});
	
	$('#localInterestList').on('click', '.editInterest',function(e){
		e.preventDefault();
		
		$('#formModal .content').empty();
		$('#formModal .loader').show();
		$('#formModal #modalTitle').empty().html('Edit Interest');
		$('#formModal').reveal({'closeOnBackgroundClick':false});
		$.get($(this).attr('href'),function(response){
			$('#formModal .loader').hide();
			$('#formModal .content').empty().html(response);
			$('#LocalInterest_localAuthorId').val($('#addInterest').attr('data-local-author-id'));
			attachEditEvent();
		});
	});
	
	attachAddEvent = function(){
		$('#interestForm').iframePostForm({
			json : true, 
			post: function(){
				$('#formModal .loader').show();
			},
			complete : function (response){
				$('#formModal .loader').hide();
				if(response.status == 'SUCCESS'){
					$("#localInterestList > li:last-child").before(response.list);
					$('#formModal').trigger('reveal:close');
				}
				else if(response.status == "ERROR"){
					$('#formModal .content').empty().append(response.html);
					attachAddEvent();
				}
			}
		});
	}
	
	attachEditEvent = function(){
		$('#interestForm').iframePostForm({
			json : true, 
			post: function(){
				$('#formModal .loader').show();
			},
			complete : function (response){
				$('#formModal .loader').hide();
				if(response.status == 'SUCCESS'){
					var id = $('#localInterestList #localInterest_'+response.idToRemove).next('li').attr('id');
					$('#localInterestList #localInterest_'+response.idToRemove).remove();
					if(id === undefined)
						$('#localInterestList').append(response.list);
					else
						$('#localInterestList #'+id).before(response.list);
					$('#formModal').trigger('reveal:close');
				}
				else if(response.status == "ERROR"){
					$('#formModal .content').empty().append(response.html);
					attachEditEvent();
				}
			}
		});
	}
	
	$('#formModal .content').on('change','#LocalInterest_file',function(event){
        var reader = new FileReader();
		var file = $(event.currentTarget)[0].files[0];
		
        reader.onload = function(e){
			$('#previewPhoto').attr('src',e.target.result);
			$('#previewPhoto').show();
        }
		reader.readAsDataURL(file);
		
	});
});