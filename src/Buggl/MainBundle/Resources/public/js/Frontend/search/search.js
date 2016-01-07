/**
 * @author Farly Taboada <farly.taboada@goabroad.com>
 */
require.config({
	paths: {
		text : 'text',
		domReady: 'domReady',
		jquery : 'libs/jquery',
		Backbone: 'libs/backbone',
		underscore: 'libs/underscore',
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

		underscore: {
			exports: '_'
		},

		Backbone: {
			deps: ['underscore','jquery'],
			exports: 'Backbone'
		}
	}
});

require(['domReady','app'],function(domReady,App){
	domReady(function(){

		var $controller = App.Controller.getController();
		$controller.on('route:index', function(){
			$controller.navigate('relevant',{trigger: true});
		});

		$controller.on('route:sort', function(selected){
			App.Models.parameters.toJSON();
			if (!App.Models.parameters.has('type')) {
				App.Models.parameters.set({'type':selected});
			} else if (App.Models.parameters.get('type') == selected) {
				//change page
				$page = App.Models.parameters.get('page');
				App.Models.parameters.set({'page': $page+1});
			} else {
				App.Models.parameters.set({
					'type':selected,
					'page': 1
				});
			}
			App.Controller.showResults(App.Models);
		});

		App.start();
	});
});