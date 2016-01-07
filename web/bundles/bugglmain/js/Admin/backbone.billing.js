(function($){
    $.billing = function(options){

        var defaults = {
            searchUrl: '',
        }

        options = $.extend({},defaults,options);

        BillingHistory = Backbone.Model.extend();
        BillingsHistory = Backbone.Collection.extend({
            model: BillingHistory,
            url: options.searchUrl,
            parse: function(response){
                this.trigger('changeTotalCount',response.count);
                return response.data;
            }
        });

        BillingHistoryView = Backbone.View.extend({
            el: $('div#main_content'),
            initialize: function(){
                _.bindAll(this,'render','search','changeFilter','changeSearchKey','resetPage','changeTotalCount');

                this.parameters = this.options.parameters;
                this.pagination = this.options.pagination;

                this.pagination.bind('changePage',this.search,this);

                this.collection.bind('sync',this.render,this);
                this.collection.bind('changeTotalCount',this.changeTotalCount,this);

                this.template = _.template( $('#history_lists_template').html() );
            },
            render: function(){
                this.$el.find('ul#history_lists').empty().html( this.template({objects:this.collection.toJSON()}) );

                return this;
            },
            search: function(){
                var data = this.parameters.toJSON();

                this.collection.fetch({
                    data: data
                });
            },
            events: {
                "click a#search" : "resetPage",
                // "change select#filter" : "changeFilter",
                // "change input#key" : "changeSearchKey"
            },
            changeFilter: function(event){
                event.preventDefault();

                var filter = $(event.currentTarget).val();

                this.parameters.set({filter: filter});
            },
            changeSearchKey: function(event){
                var key = $(event.currentTarget).val();

                this.parameters.set({key:key});
            },
            resetPage: function(event){
                var filter = this.$el.find('select').val();
                var key = this.$el.find('input').val();

                this.parameters.set({
                    filter: filter,
                    key: key,
                    page:1
                });

                this.pagination.set({'currentPage':1});

                this.search();
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
                // var totalCount = this.attributes.totalCount;
                var currentPage = 1;

                this.bind('change:totalCount',this.changeTotalPages,this);

                this.set({
                    'currentPage': currentPage,
                    'limit': limit,
                    'totalCount': 0
                });

                this.changeTotalPages();
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

                // this.compute();
                // this.render();
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

                this.model.trigger('changePage');
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