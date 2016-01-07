(function($) {
	$.fn.paginate = function(options) {
		var opts = $.extend({}, $.fn.paginate.defaults, options);
				
		// implementation here
	  	$('.bugglPaginationLink').on('click',function(e){
	  		e.preventDefault();
					
			if($(this).hasClass('disabled')){
				return false;
			}
					
			var me = $(this);
			me.parents('#'+opts.containerId).children('.bugglPaginationContents').append("<span class='loader'>Loading...</span>");
					
			$.get($(this).parents('.bugglPagination').attr('data-url'),
			{
				'currentPage' : me.attr('page')
			},
			function(response){
				$('#'+opts.containerId).empty().append(response);
				$('.bugglPagination').paginate({
					'containerId' : opts.containerId
				});
			});
	  	});
			  
	};
	$.fn.paginate.defaults = {
		'containerId' : 'paginationContainer'
	};
})(jQuery);