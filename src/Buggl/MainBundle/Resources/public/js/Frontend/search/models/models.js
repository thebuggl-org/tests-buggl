/**
 * @author Farly <farly.taboada@goabroad.com>
 *
 * @description currently models/collections are 'single instance'
 * @todo : refactor -> move Model and Collection to other file.. to instantiate new Models/Collections
 */

define(['Backbone'], function(Backbone){

	EGuide = Backbone.Model.extend();
	Parameters = Backbone.Model.extend();

	Results = Backbone.Collection.extend({
		model: EGuide,
		url: '/app_dev.php/sort/eguide'
	});

	Count = Backbone.Model.extend({
		url: '/app_dev.php/guide-search/count'
	});

	var $results = new Results();
	var $parameters = new Parameters({
		page: 1
	});
	var $count = new Count();

	$models = {
		results: $results,
		parameters: $parameters,
		count: $count
	};

	return $models;
});