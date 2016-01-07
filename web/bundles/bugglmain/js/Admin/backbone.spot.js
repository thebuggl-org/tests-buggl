(function($){
    $.spot = function(options){

        var defaults = {
            fetchMoreUrl: ''
        }

        options = $.extend({},defaults,options);


        Search = Backbone.Model.extend({
            initialize: function(){
                _.bindAll(this,'triggerEvent');

                this.bind('change:page',this.triggerEvent,this);
            },
            triggerEvent: function(){
                this.trigger('changePage');
            }
        });

        SearchView = Backbone.View.extend({
            el: $('ul.site-admin-filter'),
            initialize: function(){
                _.bindAll(this,'render','changeCountry','changeType');

                this.model = this.options.model;
            },
            events: {
                "change select[name=country]" : "changeCountry",
                "change select[name=type]" : "changeType"
            },
            changeCountry: function(event){
                var value = $(event.currentTarget).val();

                this.model.set({
                    country: value,
                    page: 1
                });
            },
            changeType: function(event){
                var value = $(event.currentTarget).val();

                this.model.set({
                    type: value,
                    page: 1
                });
            }
        });

        SpotView = Backbone.View.extend({
            tagName: "li",
            className: "clearfix",
            initialize: function(){
                _.bindAll(this,'render','view');

                this.model = this.options.model;
                this.spotFullView = this.options.spotFullView;

                this.template = _.template( $("#spots").html() );
            },
            events: {
                "click a[name=view-full]" : "view"
            },
            render: function(){
                var renderedTemplate = this.template(this.model.toJSON());

                this.$el.html( renderedTemplate );
                return this;
            },
            view: function(event){
                event.preventDefault();
                this.spotFullView.trigger('changeModel',this.model);
            }

        });

        Spot = Backbone.Model.extend();
        Spots = Backbone.Collection.extend({
            model: Spot,
            url: options.fetchMoreUrl,
            parse: function(response){
                this.trigger('changeTotalCount',response.count);

                return response.values;
            }
        });

        SpotsView = Backbone.View.extend({
            el: $('div#main_content'),
            initialize: function(){
                _.bindAll(this,'render','renderSpotView','search','changeTotalCount');

                // this model refers to the search parameters.
                this.model = this.options.model;
                // pagination model
                this.pagination = this.options.pagination;
                // spot full view
                this.spotFullView = this.options.spotFullView;

                this.collection.bind('sync',this.render,this);
                this.collection.bind('changeTotalCount',this.changeTotalCount,this);

                this.model.bind('change',this.search,this);

                this.template = _.template( $('#spot_content').html() );

                this.$el.append(this.template());

                this.search();
            },
            render: function(){
                this.$el.find('ul.site-admin-list').empty();
                this.collection.each(this.renderSpotView,this);
            },
            renderSpotView: function(model){
                var view = new SpotView({
                    model: model,
                    spotFullView: this.spotFullView
                });

                this.$el.find('ul.site-admin-list').append( view.render().el );
            },
            search: function(){

                self = this;

                this.collection.fetch({
                    data: self.model.toJSON()
                });
            },
            changeTotalCount: function(value){
                this.pagination.set({ totalCount: value });
            }
        });

        PaginationModel = Backbone.Model.extend({
            defaults: {
                softPageLimit: 8,
                hardPageLimit: 12
            },

            initialize: function() {
                _.bindAll(this,'changeTotalPages');

                var limit = this.attributes.limit;
                var currentPage = 1;

                this.bind('change:totalCount',this.changeTotalPages,this);

                this.set({
                    'currentPage': currentPage,
                    'limit': limit,
                    'totalCount': 0
                });
            },
            changeTotalPages: function()
            {
                var totalCount = this.get('totalCount');
                var limit = this.get('limit');
                var totalPages = Math.ceil(totalCount/limit);

                this.set({
                    totalPages: totalPages
                });
            }
        });

        PaginationView = Backbone.View.extend({
            el: $('#pagination'),
            initialize: function() {
                _.bindAll(this,'render','compute','changeCurrentPage');

                this.model = this.options.model;
                this.parameters = this.options.parameters;

                this.parameters.bind('changePage',this.changeCurrentPage,this);

                this.template = _.template( $('#pagination_template').html());

                this.model.bind('change:currentPage',this.compute,this);
                this.model.bind('change:totalPages',this.compute,this);
                this.model.bind('all',this.render,this);
            },
            events: {
                "click a" : "paginate"
            },

            render: function() {
                var renderedTemplate = this.template(this.model.toJSON());

                this.$el.empty().html(renderedTemplate);

                return this;
            },
            paginate: function(event) {
                event.preventDefault();

                nextPage = parseInt($(event.currentTarget).attr('href'));
                this.model.set({'currentPage':nextPage});
                this.parameters.set({page:nextPage});
            },
            compute: function() {
                var totalPages = this.model.get('totalPages');
                var curPageLimit = totalPages > this.model.defaults.hardPageLimit ? this.model.defaults.softPageLimit : this.model.defaults.hardPageLimit;
                var pageGroup = Math.ceil(this.model.get('currentPage')/curPageLimit);
                var startPage = (pageGroup - 1) * curPageLimit + 1;

                if(totalPages > this.model.defaults.hardPageLimit){
                    endPage = startPage + this.model.defaults.softPageLimit - 1;
                }
                else{
                    endPage = totalPages;
                }

                if(endPage > totalPages){
                    endPage = totalPages;
                }
                currentPage = this.model.get('currentPage');

                next = currentPage + 1;
                prev = currentPage - 1;

                next = next < totalPages ? next : totalPages;
                prev = prev == 0 ? 1 : prev;

                this.model.set({
                    'startPage': startPage,
                    'endPage': endPage,
                    'next': next,
                    'prev': prev
                });
            },
            changeCurrentPage: function(){
                page = this.parameters.get('page');

                this.model.set({ currentPage: page });
            }

        });
        return this;
    }
})(jQuery);