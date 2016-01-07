(function($){
    $.manage = function(options)
    {
        var defaults = {
            fetchUrl: '',
            searchUrl: ''
        }

        options = $.extend({},defaults,options);

        SearchParameters = Backbone.Model.extend({
            initialize: function(){
                this.set({
                    country : 0 ,
                    page: 1,
                    key: ''
                });
            }
        });

        Eguide = Backbone.Model.extend();
        Eguides  = Backbone.Collection.extend({
            model: Eguide,
            url: options.fetchUrl,
            parse: function(reponse){
                this.trigger('changeTotalCount',reponse.count);
                return reponse.data;
            }
        });

        TravelGuideView = Backbone.View.extend({
            tagName: "li",
            className: "clearfix",
            initialize: function(){
                _.bindAll(this,'render','removeList');

                this.model = this.options.model;
                this.pagination = this.options.pagination;
                this.eventListener = this.options.eventListener;

                this.template = _.template($("#eguide_list").html());
            },
            events: {
                "click a": "removeList"
            },
            render: function(){
                this.$el.html( this.template(this.model.toJSON()) );
                return this;
            },
            removeList: function(event){
                event.preventDefault();

                var data = this.model.toJSON();
                var parent = this.$el;
                var self = this;
                $.ajax({
                    url: options.featureUrl,
                    data: data,
                    success: function(data){
                        if(data.success){
                            var totalCount = self.pagination.get('totalCount');
                            self.pagination.set({totalCount:totalCount-1});

                            self.eventListener.trigger('removeList');

                            var view = new FeatureGuide({
                                model: self.model
                            });
                            self.eventListener.trigger('addList',view.render().el);

                            parent.remove();
                        }
                    }
                });

            }
        });

        EguidesView = Backbone.View.extend({
            el: $('#featureModal'),
            initialize: function(){
                _.bindAll(this,'render','search','addList');

                this.parameters = this.options.parameters;
                this.pagination = this.options.pagination;
                this.eventListener = this.options.eventListener;

                this.parameters.bind('change:page',this.search,this);

                this.collection.bind('sync',this.render,this);
                this.collection.bind('changeTotalCount',this.changeTotalCount,this);

                this.eventListener.bind('removeList',this.search,this);
                this.eventListener.bind('addList',this.addList,this);

                this.template = _.template($('#guides_container').html());

                this.$el.find('div.content').append(this.template);
            },
            events: {
                "click a[name=search]" : "changeKey"
            },
            changeKey: function(event){
                event.preventDefault();
                var key = this.$el.find('input').val();

                this.parameters.set({
                    key:key,
                    page: 1,
                });

                if(this.parameters.hasChanged()){
                    this.search();
                }
            },
            search: function(){
                var data = this.parameters.toJSON();

                this.collection.fetch({
                    data: data
                })
            },
            render: function(){
                this.$el.find('div.content').empty().append(this.template);

                if(this.collection.length > 0){
                    this.collection.each(this.renderView,this);
                }
                else{
                    this.$el.find('div.content').children('ul').append('<li class="clearfix"> No Result </li>');
                }

            },
            renderView: function(model){
                var view = new TravelGuideView({
                    model: model,
                    pagination: this.pagination,
                    eventListener: this.eventListener
                });

                this.$el.find('div.content').find('ul.eguide-list').append( view.render().el );
            },
            changeTotalCount: function(value){
                this.pagination.set({'totalCount':value});
            },
            addList: function(html){
                var clicked = this.eventListener.get('clicked');
                clicked.parents('li[name=country_list]').find('ul[name=guide_lists]').append(html);
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
                // this.model.bind('change:currentPage',this.compute,this);
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

        FeatureGuide = Backbone.View.extend({
            tagName: "li",
            className: "clearfix",
            initialize: function(){
                _.bindAll( this, 'render' );

                this.model = this.options.model;

                this.template = _.template( $('#feature_eguide_list').html() );

                this.render();
            },
            events: {
                "click a[name=remove-feature]" : "remove"
            },
            render: function(){
                this.$el.html( this.template( this.model.toJSON() ) );

                return this;
            },
            remove: function(event){
                event.preventDefault();

                var data = this.model.toJSON();

                self = this;
                $.ajax({
                    url: options.featureUrl,
                    data: data,
                    success: function(data){
                        if(data.success){
                            self.$el.remove();
                        }
                    }
                })
            }
        })

        EventListener = Backbone.Model.extend();
    }
})(jQuery);