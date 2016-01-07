(function($) {
	$.fn.flash = function(options) {
		var opts = $.extend({}, $.fn.flash.defaults, options);
				
		// implementation here
	  	$('.flash').on('click',function(e){
	  		e.preventDefault();
					
			$(this).slideUp(opts.animationSpeed);
	  	});
	
		$('.flash').each(function(){
			var me = $(this);
			if(!me.hasClass('permanent')){
				setTimeout(function(){
					me.parents('.row').slideUp(opts.animationSpeed);
				},opts.delay);
				opts.delay = opts.delay + opts.multipleRemovalDelay;
			}
		});
		  
	};
	$.fn.flash.defaults = {
		'delay': 5000,
		'animationSpeed': 500,
		'multipleRemovalDelay': 1000
	};
})(jQuery);