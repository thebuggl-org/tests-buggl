(function($){
    $.ranking = function(options){

        var defaults = {
            eguideRankUrl: ''
        }

        options = $.extend({},defaults,options);

        ERankingParameter = Backbone.Model.extend({
            initialize: function(){
                this.set({
                    'filter':0
                });
            }
        });

        EguideRanking = Backbone.Model.extend();

        EguideRankings = Backbone.Collection.extend({
            model: EguideRanking,
            url: options.eguideRankUrl
        });

        EguideRankingView = Backbone.View.extend({
            el: $('#guide_ranking'),
            initialize: function(){
                _.bindAll(this,'render','search');

                this.parameters = this.options.parameters;
                this.parameters.bind('change',this.search,this);

                this.collection.bind('sync',this.render,this);

                this.template = _.template( $('#guide_ranking_template').html() );
            },
            events: {
                "change select": "change"
            },
            render: function(){
                renderedTemplate = this.template({objects:this.collection.toJSON()});

                this.$el.find('ul#revenue_rank').empty().html( renderedTemplate );

                return this;
            },
            change: function(event){
                var value = $(event.currentTarget).val();
                this.parameters.set({ filter: value });
            },
            search: function(){

                var data = this.parameters.toJSON();

                this.collection.reset();
                this.collection.fetch({
                    data: data
                });
            }
        });
    }
})(jQuery)