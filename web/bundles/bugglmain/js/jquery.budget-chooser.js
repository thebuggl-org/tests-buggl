(function($) {
	
	$.fn.budgetChooser = function(options) {
		var opts = $.extend({}, $.fn.budgetChooser.defaults, options);
		
		$(this).each(function(){
			var me = this;
		
			var dollar = $(me).find("a");
			$(dollar).each(function(){
				var parent = $(this).parent('li:first');

				$(this)
				.on('hover',
					function(){},
					function(){
						$(parent).prevAll().children('a').addClass('hover');
						$(parent).nextAll().children('a').removeClass('hover');
						$(this).addClass('hover');
					
						var span = $(me).find('li.budget-text-value span');
						span.text($(this).attr('title'));
					}
				)
				.on('click', function(e){
					e.preventDefault();
					$(parent).prevAll().children('a').removeClass('hover').addClass('active');
					$(parent).nextAll().children('a').removeClass('active');
					$(this).removeClass('hover').addClass('active');
					$("input[name='"+opts.inputname+"']", $(me).parents('form')).val($(this).data('budget'));
				
					var span = $(me).find('li.budget-text-value span');
					span.parent('li.budget-text-value').attr('data-text-value',$(this).attr('title'));
					span.text($(this).attr('title'));
				});
			});

			$(me).mouseout(function(){
				$(me).find('a').removeClass('hover');
				var span = $('li.budget-text-value span',me);
				span.text(span.parent('li.budget-text-value').attr('data-text-value'));
			});
			
			return me;
		});
	};
	
	$.fn.budgetChooser.defaults = {
		'inputname' : 'budget'
	};
	
})(jQuery);