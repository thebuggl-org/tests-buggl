$(document).ready(function(){
	$('#checkAll').on('click',function(){
		var checked = $(this).is(':checked');
		$('input:checkbox').each(function(){
			$(this).prop('checked',checked);
		});
	});

	$('.referenceCheckBox').on('click',function(){
		var checked = $(this).is(':checked');
		if(checked){
			$('input:hidden[name="references"]').val($('input:hidden[name="references"]').val()+$(this).val()+' ');
		}
		else{
			var references = $('input:hidden[name="references"]').val();
			references = references.replace($(this).val()+' ','');
			$('input:hidden[name="references"]').val(references);
		}
	});

	$('a.feature').on('click',function(e){
		e.preventDefault();
		var me = $(this);

		$.fn.bugglConfirm({
			'onConfirm' : function(){
				me.hide();
				me.siblings('span.loader').show();
				$.getJSON(me.attr('href'),function(response){
					me.siblings('span.loader').hide();
					$.fn.bugglAlert({
						'title' : response.status,
						'content' : '<p>'+response.message+'</p>',
						'revealOptions' : {
							'closeOnBackgroundClick':false,
							'closed':function(){
								if(response.status == 'SUCCESS')
									me.siblings('.feature').show();
								else
									me.show();
							}
						}
					});
				});
			}
		});

		/*
		$('#confirmModal #confirmModalTitle').empty().html('Are you sure?');
		$('#confirmModal').reveal({'closeOnBackgroundClick':false});
		$('#confirmModalOk').off('click').on('click',function(e){
			e.preventDefault();

			me.hide();
			me.siblings('span.loader').show();
			$.getJSON(me.attr('href'),function(response){
				me.siblings('span.loader').hide();
				$('#alertModal .content .contentHolder').empty().html('<p>'+response.message+'</p>');
				$('#alertModal #alertModalTitle').empty().html(response.status);
				$('#alertModal').reveal({
					'closeOnBackgroundClick':false,
					'closed':function(){
						if(response.status == 'SUCCESS')
							me.siblings('.feature').show();
						else
							me.show();
					}
				});
			});
		});
		*/
	});
});