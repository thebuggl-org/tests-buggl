// depends on jquery.reveal.js
// include modals.html.twig
// see jquery.references.js for sample usage

(function($) {
	$.fn.bugglConfirm = function(options) {
		var opts = $.extend({}, $.fn.bugglConfirm.defaults, options);
		// implementation here
		$('#confirmModal .content .contentHolder').empty().html(opts.content);
		$('#confirmModal #confirmModalTitle').empty().html(opts.title);
		$('#confirmModalOk').off('click').on('click',function(e){
			e.preventDefault();
			opts.onConfirm();
		});
		$('#confirmModalCancel').off('click').on('click',function(e){
			e.preventDefault();
			opts.onCancel();
		});
		$('#confirmModal').reveal(opts.revealOptions);
	};
	
	$.fn.bugglConfirm.defaults = {
		'title' : 'Are you sure?',
		'content' : '',
		'onConfirm' : function(){
			$('#confirmModal').trigger('reveal:close');
		},
		'onCancel' : function(){
			$('#confirmModal').trigger('reveal:close');
		},
		revealOptions : {'closeOnBackgroundClick':false}
	};
	
	$.fn.bugglAlert = function(options) {
		var opts = $.extend({}, $.fn.bugglAlert.defaults, options);
				
		// implementation here
		$('#alertModal .content .contentHolder').empty().html(opts.content);
		$('#alertModal #alertModalTitle').empty().html(opts.title);
		$('#alertModalOk').off('click').on('click',function(e){
			e.preventDefault();
			opts.onConfirm();
		});
		$('#alertModal').reveal(opts.revealOptions);
			  
	};
	
	$.fn.bugglAlert.defaults = {
		'title' : 'Success',
		'content' : '',
		'onConfirm' : function(){
			$('#alertModal').trigger('reveal:close');
		},
		revealOptions : {'closeOnBackgroundClick':false}
	};
	
	$.fn.bugglConfirmLoaderShow = function(){
		$('#confirmModal .loader').show();
	};
	
	$.fn.bugglConfirmLoaderHide = function(){
		$('#confirmModal .loader').hide();
	};
	
	$.fn.bugglConfirmClose = function(){
		$('#confirmModal').trigger('reveal:close');
	};
	
})(jQuery);