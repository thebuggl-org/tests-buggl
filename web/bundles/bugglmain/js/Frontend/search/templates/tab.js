/**
 * @author Farly Taboada <farly.taboada@goabroad.com>
 * @todo  Refactor: this is not the best abstraction.. rethink..
 */
define(['Backbone','underscore','text!templates/tab.tpl','text!templates/resultContainer.tpl','text!templates/loadMore.tpl','jquery'],
	function(Backbone, _, template, container, loadMore) {

	View = Backbone.View.extend({
		el: $('#result_content'),
		initialize: function(options) {
			_.bindAll(this,'render','changeTab','appendResult');
			this.template = _.template(template);
			this.controller = options.controller;
		},
		render: function(parameters) {
			if (parameters.get('page') == 1) {
                this.$el.find('span#loader').remove();
				this.$el.html(this.template({selected:parameters.get('type')}));
				this.$el.append(container);
			}

			return this;
		},
		events: {
            "click a.link" : "changeTab",
        },
        changeTab: function(event) {
        	event.preventDefault();

        	var $self = $(event.currentTarget);
        	var $order = $self.attr('data');

        	if ($order !== 'page') {
        		this.controller.navigate($order,{trigger: true});
        	} else {
        		$order = Backbone.history.fragment;
        		Backbone.history.stop();
        		Backbone.history.start();
        		this.controller.navigate($order,{trigger: true});
        	}

        },
        appendResult: function(data) {
        	this.$el.find('.travelguide').append(data.at(0).get('html'));

        	return this;
        },
        addLoadMore: function() {
        	if (this.$el.find('#loadMore').length == 0) {
        		this.$el.append(loadMore);
        	}
        },
        removeLoadMore: function() {
        	if (this.$el.find('#loadMore').length != 0) {
        		$('a#loadMore').parent().remove();
        	}
        }
	});

	var $view;

	var $action = {
		init: function(controller) {
			$view = new View({
				controller: controller
			});

			return $view;
		}
	}

	return $action;
});