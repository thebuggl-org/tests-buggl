(function($) {
	$.fn.limitedTextArea = function(options) {
		var opts = $.extend({}, $.fn.limitedTextArea.defaults, options);
		var me = this;
		
		if(opts.maxLen == undefined || opts.maxLen == null){
			opts.maxLen = 100;
		}
		
		if(opts.remainingCharsDisplay !== null){
			opts.remainingCharsDisplay.val(opts.maxLen - me.val().length);
		}
		
        me.bind("contextmenu", function(e) {
            e.preventDefault();
        });
		
		me.on('keyup keydown',function(){
			var curLen = me.val().length;
			
	        if ( curLen > opts.maxLen ){
	        	me.val(me.val().substring(0, opts.maxLen));
	        }
			curLen = me.val().length;
			if(opts.remainingCharsDisplay !== null){
				opts.remainingCharsDisplay.val(opts.maxLen - curLen);
			}
	    });
		
		return me;
	};
	
	$.fn.limitedTextArea.defaults = {
		'maxLen' : 100,
		'remainingCharsDisplay' : null
	};
})(jQuery);