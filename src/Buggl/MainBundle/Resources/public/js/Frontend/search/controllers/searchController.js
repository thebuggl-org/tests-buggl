/**
 * @author Farly <farly.taboada@goabroad.com>
 * @todo REFACTOR PLEASE!
 */

define(['Backbone','jquery','templates/tab','../requestguide','../../../bundles/bugglmain/js/jquery.budget-chooser.js'], function(Backbone,$,TabView,Request){
	Controller = Backbone.Router.extend({
		routes: {
			'' : 'index',
			':selected' : 'sort',
		},
	});

	var $controller;
	var $parentContainer = document.getElementById('result_content');
	var $templateUrl  = '/app_dev.php/get-empty-search-result-template';
	var $tabView;

	var $returns = {
		start: function() {
			$controller = new Controller();
			$tabView = TabView.init($controller);
		},

		getController: function() {
			return $controller;
		},

		/**
		 * @todo : REFACTOR. PLEASE.
		 */
		showResults: function(Models) {
			Models.count.fetch({
				data: Models.parameters.toJSON(),
				type: 'post',
				success: function(data) {
					var $loadMore = data.get('loadMore')
					if (data.get('count') != 0) {

						if (Models.parameters.get('page') == 1) {
							$tabView.render(Models.parameters);
						}

						Models.results.fetch({
							data: Models.parameters.toJSON(),
							type: 'post',
							success: function(data) {
								// if (Models.parameters.get('page') != 1) {
									$tabView.appendResult(data);

									if ($loadMore) {
										$tabView.addLoadMore();
									} else {
										$tabView.removeLoadMore();
									}
								// }
							}
						});
					} else {
						while ($parentContainer.firstChild) {
						    $parentContainer.removeChild($parentContainer.firstChild);
						}

						$.getJSON($templateUrl,function(data){
							$($parentContainer).append(data.html);

							Request.init();

							$(".budget").budgetChooser({
								'inputname' : 'budgetserial'
							});
						});
					}
				}
			});
		}
	}

	return $returns;
});