(function($){

    $.backbone = function( options ){

        var defaults = {
            cityUrl: ''
        }

        options = $.extend({},defaults,options);

        window.Countries = Backbone.Collection.extend();

        window.City = Backbone.Model.extend();

        window.Cities = Backbone.Collection.extend({
            model: City,
            url: options.cityUrl
        });

        window.CountryView = Backbone.View.extend({
            el: $('div[name=country]'),

            initialize: function() {
                _.bindAll(this,'render');
                this.collection = this.options.countries.toJSON();
                this.cities = this.options.cities;
                this.parameters = this.options.parameters;

                this.render();
            },

            render: function() {
                var template = _.template( $("#country_template").html(), {countries:this.collection} );
                this.$el.html(template);
                return this;
            },

            events: {
              "change select": "changeCity"
            },

            changeCity: function( event ) {

                country = $(event.currentTarget).val();
                this.parameters.set({
                    'country':country,
                    'page': 1,
                    'name': '',
                    'city': 0
                });
                this.cities.trigger('change', event);
            }
        });

        window.CityView = Backbone.View.extend({
            el: $('div[name=city]'),
            initialize: function() {
                _.bindAll(this,'render','changeCity','selectCity');
                this.template = _.template( $("#city_template").html());

                this.parameters = this.options.parameters;

                this.collection = this.options.cities;

                this.collection.on('change',this.changeCity);
                this.collection.on('add',this.render);
                this.collection.on('remove',this.render);

                this.render();
            },

            events: {
                'change select' : 'selectCity'
            },

            render: function() {
                cities = this.collection.toJSON();

                renderedContent = this.template({cities:cities});
                this.$el.empty().html(renderedContent).el;

                return this;
            },

            changeCity: function(event) {
                country = $(event.currentTarget).val();

                this.collection.fetch({
                    data: {
                        countryID:country
                    }
                });

                this.parameters.set({
                    'country' : country,
                    'page' : 1
                });
            },

            selectCity: function(event) {
                city = $(event.currentTarget).val();
                this.parameters.set({
                    'city' : city,
                    'page' : 1
                });
            }
        });

        window.PaginationModel = Backbone.Model.extend({
            defaults: {
                softPageLimit: 8,
                hardPageLimit: 12
            },

            initialize: function() {
                _.bindAll(this,'compute');

                this.bind('change:totalCount',this.compute,this);

                var totalCount = this.attributes.totalCount;
                var limit = this.attributes.limit;
                var totalPages = Math.ceil(totalCount/limit);
                var currentPage = 1;

                this.set({
                    'totalPages':totalPages,
                    'currentPage': currentPage,
                    'limit': limit
                });
            },
            compute: function(){
                var totalCount = this.get('totalCount');
                var limit = this.get('limit');
                var totalPages = Math.ceil(totalCount/limit);

                this.set({
                    'totalPages':totalPages
                });
            }
        });

        window.PaginationView = Backbone.View.extend({
            el: $('#pagination'),
            initialize: function() {
                _.bindAll(this,'render','compute','changeCurrentPage');

                this.model = this.options.model;
                this.parameters = this.options.parameters;

                this.parameters.bind('change:page',this.changeCurrentPage,this);

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

                this.$el.empty().html(renderedTemplate).el;

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
                this.model.set({'currentPage':this.parameters.get('page')});
            }

        });

        LocalAuthorModel = Backbone.Model.extend();

        LocalAuthors = Backbone.Collection.extend({
            model: LocalAuthorModel,
            url: options.searchLocalAuthor,
            parse: function( response ){
                this.trigger( 'changeTotalCount', response.count );
                return response.data;
            }
        });

        window.ResultView = Backbone.View.extend({
            el: $('#author'),
            initialize: function() {
                _.bindAll(this,'render','search','changeTotalCount');

                this.collection = this.options.collection;
                this.pagination = this.options.pagination;
                this.parameters = this.options.parameters;

                this.collection.bind('all', this.render, this);
                this.collection.bind('changeTotalCount',this.changeTotalCount,this);
                this.pagination.bind('change:currentPage', this.render, this);
                this.parameters.bind('change',this.search,this);

                this.template = _.template( $('#author_list_template').html() );

                this.render();
            },
            render: function() {
                renderedTemplate = this.template( { authors: this.collection.toJSON() } );

                this.$el.html( renderedTemplate ).el;
            },
            events: {
                "click a[name=suspend]" : "toggleSuspension"
            },
            search: function() {

                data = this.parameters.getParameters();

                this.collection.fetch({
                    data: data
                });
            },
            changeTotalCount: function(value){
                this.pagination.set({'totalCount':value});
            },
            toggleSuspension: function(event){
                event.preventDefault();

                var object = $(event.currentTarget);

                var id = object.attr('href');

                $.getJSON(options.toggleSuspensionUrl, {id:id}, function(data){
                    object.text(data.text);
                });
            }
        });

        SearchParams = Backbone.Model.extend({
            defaults: {
                country: 0,
                city: 0,
                name: '',
                page: 1,
            },

            initialize: function(){
                _.bindAll(this,'getParameters');
            },

            getParameters: function(){

                country = this.get('country');
                city = this.get('city');
                name = this.get('name');
                page = this.get('page');

                params = {
                    country:country,
                    city: city,
                    name: name,
                    page: page
                };

                return params;
            }
        });

        return this;
    }
})(jQuery);