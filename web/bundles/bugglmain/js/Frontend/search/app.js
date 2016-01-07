/**
 * @author Farly <farly.taboada@goabroad.com>
 * @todo : Refactor. Rethink implementation.
 */

define(['Backbone','controllers/searchController','models/models'], function(Backbone, Controller, Models){

	Controller.start();

	var $returns = {
		Controller: Controller,
		Models: Models,
		start: function() {
			Backbone.history.start();
		}
	}

	return $returns;
});