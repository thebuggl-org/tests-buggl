(function($){
    $.contact = function(options){

        var defaults = {
            fetchUrl: '',
        }

        options = $.extend({},defaults,options);

        Message = Backbone.Model.extend();
        Messages = Backbone.Collection.extend({
            model: Message,
            url: options.fetchUrl,
            parse: function(response){
                this.trigger('changeTotalCount', response.count);

                return response.data;
            }
        });

        MessagesView = Backbone.View.extend({
            el: $('#main_content'),
            initialize: function(){
                _.bindAll(this,'render','renderMessageView','changeTotalCount');

                this.collection = this.options.messages;
                this.collection.bind('sync',this.render,this);
                this.collection.bind('changeTotalCount', this.changeTotalCount, this );

                this.pagination = this.options.pagination;
                this.parameters = this.options.parameters;

                this.parameters.bind('change:page',this.changeCurrentPage,this);
                this.parameters.bind('change',this.fetchMore,this);

                this.template = _.template( $('#message_list_container').html() );

                this.$el.append(this.template());
            },
            render: function(){
                this.$el.find('ul.eguide-list').empty();
                this.collection.each(this.renderMessageView,this);

                return this;
            },
            renderMessageView: function(model){
                var view = new MessageView({
                    model: model
                });
                this.$el.find('ul.eguide-list').append(view.render().el);
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

        MessageView = Backbone.View.extend({
            tagName: 'li',
            className: 'clearfix',
            initialize: function(){
                _.bindAll(this,'render');

                this.model = this.options.model;

                this.template = _.template($('#contact_us_list').html());
            },
            render: function(){
                this.$el.html(this.template( this.model.toJSON()));
                return this;
            }
        });

        Parameters = Backbone.Model.extend({
            page: 1
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

        return this;
    }
})(jQuery);