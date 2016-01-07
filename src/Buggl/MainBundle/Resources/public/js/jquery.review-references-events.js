$(document).ready(function(){
	if($('#referenceContainer > ul > li').length > 3){
		$('#moreReferences').show();
	}

	if($('#reviewContainer > ul > li.review').length > 3){
		$('#moreReviews').show();
	}

	$('#reviews').on('click',function(e){
		e.preventDefault();
		$('#references').parent().removeClass('selected');
		$(this).parent().addClass('selected');

		$('#referenceContainer').hide();
		$('#reviewContainer').show();
	});

	$('#references').on('click',function(e){
		e.preventDefault();
		$('#reviews').parent().removeClass('selected');
		$(this).parent().addClass('selected');

		$('#reviewContainer').hide();
		$('#referenceContainer').show();

	});

	$('#moreReferences').on('click',function(e){
		e.preventDefault();
		$('#referenceContainer').attr('data-page',(parseInt($('#referenceContainer').attr('data-page'))+1));
		$('.reference_'+$('#referenceContainer').attr('data-page')).show();

		if($('#referenceContainer > ul > li').not(':visible').length == 0){
			$('#moreReferences').hide();
		}
	});

	$('#moreReviews').on('click',function(e){
		e.preventDefault();
		$('#reviewContainer').attr('data-page',(parseInt($('#reviewContainer').attr('data-page'))+1));
		$('.review_'+$('#reviewContainer').attr('data-page')).show();

		if($('#reviewContainer > ul > li.review').not(':visible').length == 0){
			$('#moreReviews').hide();
		}
	});
})