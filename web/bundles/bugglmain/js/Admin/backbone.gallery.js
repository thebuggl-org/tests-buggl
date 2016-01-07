(function($){
    $.gallery = function(options){
        var defaults = {
            cityUrl: '',
            fetchMoreUrl: ''
        }

        options = $.extend({},defaults,options);

        Country = Backbone.Model.extend();

        Countries = Backbone.Collection.extend({
            model: Country,
        })

        CountryView = Backbone.View.extend({
            el: $('#country'),
            initialize: function(){
                _.bindAll(this,'render');

                this.cityCollection = this.options.cityCollection;
                this.parameters = this.options.parameters;

                this.collection.bind('add',this.render,this);


                this.template = _.template( $('#country_template').html() );
            },
            events: {
                "change select" : "search"
            },
            render: function(){
                renderedTemplate = this.template({countries:this.collection.toJSON()});

                this.$el.html( renderedTemplate );

                return this;
            },
            search: function(event){
                id = $(event.currentTarget).val();

                this.parameters.set({
                    country: id,
                    page: 1,
                    city: 0
                });

                this.cityCollection.trigger('changeCities',event);
            }
        });

        City = Backbone.Model.extend();

        Cities = Backbone.Collection.extend({
            model: City,
            url: options.cityUrl
        });

        CityView = Backbone.View.extend({
            el: $('#city'),
            initialize: function(){
                _.bindAll(this,'render','changeCities');

                this.parameters = this.options.parameters;

                this.collection.bind('changeCities',this.changeCities,this);
                this.collection.bind('sync',this.render,this);

                this.template = _.template( $("#city_template").html() );

                this.render();
            },
            events: {
                "change select" : "selectCity"
            },
            render: function(){
                renderedTemplate = this.template( {cities:this.collection.toJSON()} );

                this.$el.html( renderedTemplate );

                return this;
            },
            changeCities: function(event){
                id = $(event.currentTarget).val();

                this.collection.fetch({
                    data: {
                        countryID : id
                    }
                });
            },
            selectCity: function(event){
                var id = $(event.currentTarget).val();

                this.parameters.set({
                    city: id,
                    page: 1,
                });
            }
        });

        SearchParams = Backbone.Model.extend({
            defaults: {
                page: 1,
                country: 0,
                city: 0
            }
        });

        PaginationModel = Backbone.Model.extend({
            defaults: {
                softPageLimit: 8,
                hardPageLimit: 12
            },

            initialize: function() {
                _.bindAll(this,'changeTotalPages');

                var totalCount = this.attributes.totalCount;
                var limit = this.attributes.limit;
                var totalPages = Math.ceil(totalCount/limit);
                var currentPage = 1;

                this.bind('change:totalCount',this.changeTotalPages,this);

                this.set({
                    'totalPages':totalPages,
                    'currentPage': currentPage,
                    'limit': limit,
                    'totalCount': totalCount
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

                this.compute();
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

        Photo = Backbone.Model.extend();

        Photos = Backbone.Collection.extend({
            model: Photo,
            url: options.fetchMoreUrl,
            parse: function(response){
                this.trigger('changeTotalCount', response.count);

                return response.result;
            }
        });

        ImageView = Backbone.View.extend({
            tagName: "li",
            className: "clearfix",
            initialize: function(){
                _.bindAll(this,'render');

                this.model = this.options.model;
                this.full = this.options.full;

                this.template = _.template( $('#image_content').html() );
            },
            events: {
                "click a[name=view-full]" : "view"
            },
            render: function(){
                this.$el.html(this.template( this.model.toJSON()));
                return this;
            },
            view: function(event){
                event.preventDefault();
                this.full.trigger('changeModel',this.model);
            }
        });

        ThumbnailView = Backbone.View.extend({
            el: $('#main_content'),
            initialize: function(){
                _.bindAll(this,'render','renderImageView','fetchMore','changeCurrentPage');

                this.collection.bind('reset',this.render,this);
                this.collection.bind('sync',this.render,this);
                this.collection.bind('changeTotalCount', this.changeTotalCount, this );

                this.pagination = this.options.pagination;
                this.parameters = this.options.parameters;
                this.full = this.options.full;

                this.parameters.bind('change:page',this.changeCurrentPage,this);
                this.parameters.bind('change',this.fetchMore,this);

                this.template = _.template( $('#page_content').html() );

                this.$el.append(this.template());
            },
            render: function(){
                this.$el.find('ul.image-list').empty();
                this.collection.each(this.renderImageView,this);

                return this;
            },
            renderImageView: function(model){
                var view = new ImageView({
                    model: model,
                    full: this.full
                });
                this.$el.find('ul.image-list').append(view.render().el);
            },
            fetchMore: function(){
                self = this;
                this.collection.fetch({
                    data: self.parameters.toJSON()
                });
            },
            changeTotalCount: function(value){
                this.pagination.set({ totalCount: value });
            },
            changeCurrentPage: function(){
                this.pagination.set({currentPage: this.parameters.get('page')});
            }
        });

        return this;
    }
})(jQuery);