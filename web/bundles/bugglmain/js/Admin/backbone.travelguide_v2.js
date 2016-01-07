(function($){
    $.travelguide = function(options)
    {
        var defaults = {
            fetchUrl: ''
        }

        options = $.extend({},defaults,options);

        Parameters = Backbone.Model.extend({
            initialize: function(){
                this.set({
                    q: '',
                    country: 0,
                    category: 0,
                    page: 1
                });
            }
        });

        FilterView = Backbone.View.extend({
            el: $('ul.site-admin-filter'),
            initialize: function(){
                _.bindAll(this,'changeCountry','changeCategory');
                this.parameters = this.options.parameters;
            },
            events: {
                "change select#countrySelect" : 'changeCountry',
                "change select#categorySelect" : 'changeCategory',
                "keyup input#searchGuideQString" : 'changeQString'
            },
            changeCategory: function(event){
                var id = $(event.currentTarget).val();

                this.parameters.set({
                    category: id,
                    page: 1
                });
            },
            changeCountry: function(event){
                var id = $(event.currentTarget).val();

                this.parameters.set({
                    country : id,
                    page: 1
                });
            },
            changeQString: function(event){
                var q = $(event.currentTarget).val();

                this.parameters.set({
                    q : q,
                    page: 1
                });
            }
        });

        TravelGuide = Backbone.Model.extend();
        TravelGuides = Backbone.Collection.extend({
            model: TravelGuide,
            url: options.fetchUrl ,
            parse: function(response){
                this.trigger('changeTotalCount',response.count);
                return response.data;
            }
        });

        TravelGuidesView = Backbone.View.extend({
            el: $('div#guides_container'),
            initialize: function(){
                _.bindAll(this,'render','changeTotalCount','search');

                this.collection.bind('sync',this.render,this);
                this.collection.bind('changeTotalCount',this.changeTotalCount,this);

                this.pagination = this.options.pagination;

                this.parameters = this.options.parameters;
                this.parameters.bind('change',this.search,this);

                this.eventListener = this.options.eventListener;
                this.eventListener.bind('removeList',this.search,this);

                this.template = _.template( $('#eguide_list_container').html() );

                this.$el.append(this.template);
            },
            render: function(){
                this.$el.children('ul').empty();
                this.collection.each(this.renderGuide,this);
            },
            renderGuide: function(model){
                var view = new TravelGuideView({
                    model: model,
                    pagination: this.pagination,
                    collection: this.collection,
                    eventListener: this.eventListener
                });

                this.$el.children('ul').append(view.render().el);
            },
            changeTotalCount: function(value){
                this.pagination.set({'totalCount':value});
            },
            search: function(){
                var data = this.parameters.toJSON();

                this.collection.fetch({
                    data: data,
                    type: 'post'
                });
            }
        });

        TravelGuideView = Backbone.View.extend({
            tagName: "li",
            className: "clearfix",
            initialize: function(){
                _.bindAll(this,'render','changeStatus','showModal');

                this.model = this.options.model;
                this.pagination = this.options.pagination;
                this.eventListener = this.options.eventListener;

                this.template = _.template($("#eguide_list").html());
                this.messageTemplate = _.template($("#message_template").html());
            },
            events: {
                "click a[name=actions]" : "changeStatus",
                "click a[name=message]" : "showModal",
            },
            render: function(){
                this.$el.html( this.template(this.model.toJSON()) );
                return this;
            },
            changeStatus: function(event){
                event.preventDefault();
                parent = this.$el;

                var url = $(event.currentTarget).attr('href');

                self = this;

                $.getJSON(url,{},function(data){
                    parent.remove();
                    var totalCount = self.pagination.get('totalCount');
                    self.pagination.set({'totalCount':totalCount-1});

                    self.eventListener.trigger('removeList');
                });
            },
            showModal: function(event){
                event.preventDefault();

                var params = {
                    id: this.model.get('localAuthorId'),
                    name: this.model.get('author')
                }

                var view = this.messageTemplate(params);

                $('#main_content').find('div.content').empty().html(view).parent().reveal();
            }

        })

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

                var currentPage = this.get('currentPage');
                if( currentPage > totalPages && totalPages > 0 ){
                    this.set({
                        'currentPage': currentPage - 1
                    });
                }
            }
        });

        PaginationView = Backbone.View.extend({
            el: $('#pagination'),
            initialize: function() {
                _.bindAll(this,'render','compute','changeCurrentPage','changeParametersPage');

                this.parameters = this.options.parameters;
                this.parameters.bind('change:page',this.changeCurrentPage,this);

                this.model = this.options.model;

                this.model.bind('change:currentPage',this.changeParametersPage,this);
                this.model.bind('change:currentPage',this.compute,this);
                this.model.bind('change:totalPages',this.compute,this);
                this.model.bind('all',this.render,this);

                this.template = _.template( $('#pagination_template').html());
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
            },
            changeParametersPage: function(){
                page = this.model.get('currentPage');
                this.parameters.set({'page':page});
            }

        });

        EventListener = Backbone.Model.extend();

        return this;
    }
})(jQuery);