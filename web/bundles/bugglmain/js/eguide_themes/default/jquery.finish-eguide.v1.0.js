/**
 * Buggl
 * Version 1.0
 * nash.lesigon@goabroad.com
 */
// var d = new Date();
// alert("finishing script:"+d.getTime());
$(function(){
 	
 	var paths = window.location.pathname.split("/");
 	var bugglScriptName = (paths[1] == 'app_dev.php') ? '/app_dev.php' : '';
 	$("a[name='show-more-spots']")
 		.unbind('click')
 		.on('click', function(e){
 			e.preventDefault();
 			
 			var me = this;
 			var guide_id = $(me).data('eguideid');
 			var page = $(me).data('page');
 			$(me).hide();
			$(me).siblings('#modal-loader-gif').show();
 			$.ajax({
 				url: bugglScriptName+'/local-author/finish-your-travel-guide-spot-list/'+guide_id+'/'+page,
 				dataType: 'json',
 				beforeSend: function(){},
 				success:function(response){
 					$("#finishing-spot-list").append(response.html);
					$(me).show();
					$(me).siblings('#modal-loader-gif').hide();
					
 					if(response.moreCount == 0)
 						$(me).parent('div').remove();
 					else
 					{
 						$(me).text("Show "+response.moreCount+" more");
 						$(me).data('page', parseInt(page)+1);
 					}
 				}
 			});
 		});

 	$("#finishing-spot-list")
 		.on('click', 'li[name="feature-this-guide-spot"]', function(e){
 			e.preventDefault();
 			if($(this).hasClass('active')){
				$('#feature-eguide-spot'+$(this).data('detailid')).remove();
 				$(this).removeClass('active');
 			}
 			else if(2 < $(this).parent('ul').children('li.active').length){
 				//alert("You can only feature 3 spots at a time.");
				$.fn.bugglAlert({
					'title' : 'Notice',
					'content':'You can only feature 3 spots at a time.'
				});
 			}
 			else {
 				$(this).addClass('active');
 				$("<input>", {'type' : 'hidden', 'name' : 'feature-eguide-spot[]', 'id' : 'feature-eguide-spot'+$(this).data('detailid')})
 					.val($(this).data('detailid'))
 					.appendTo($("#travel-guide-finishing"));
 			}

 		});

 	$("#finish-eguide-btn")
 		.unbind('click')
 		.on('click', function(e){
 			e.preventDefault();
 			if( $("#finishing-spot-list li.active").length == 0 ){
 				alert('Please select at least one spot to feature.');
 				return false;
 			}

 			$("#travel-guide-finishing").submit();
 		});
		
	$('input[name="is_free"]').on('click',function(){
		if($(this).is(':checked')){
			$('input[name="price"]').attr('disabled','disabled');
			$('input[name="price"]').val('Free')
		}
		else{
			$('input[name="price"]').attr('disabled',false);
			$('input[name="price"]').val('1.00');
		}
	});
 });