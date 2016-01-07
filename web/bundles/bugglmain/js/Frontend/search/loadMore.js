/**
 * @author Farly Taboada <farly.taboada@goabroad.com>
 */
require.config({
	paths: {
		text : 'text',
		domReady: 'domReady',
		jquery : 'libs/jquery',
	},
	shim: {
		/**
		 * Format
		 *
		 * alias configured in path : {
		 *   deps: [dependency: use the alias here]
		 *   exports: "name you want to use in calling the module: ex. $ for jQuery"
		 * }
		 *
		 */

		/*
			jquery: {
				exports: '$'
			}
		 */

	}
});

require(['domReady','fetch','text!templates/loader.tpl'],function(domReady, loader){
	domReady(function(){
		// $('#loadMore').on('click',function(event){
  //           event.preventDefault();

  //           $(this).hide();
  //           $(this).parent().append('<span id="loader"></span>');
  //       });
	});
});