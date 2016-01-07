(function($){
    $.uploader = function( options ){
        var defaults = {
            uploadUrl: '',
            fetchUrl: ''
        }

        options = $.extend({},defaults,options);

        Pic = Backbone.Model.extend();
        window.Pictures = Backbone.Collection.extend({
            model: Pic,
            initialize: function(){
                _.bind(this,'upload');

                this.bind('upload',this.upload);
            },

            upload: function(){

                var files = this.toJSON();
                var length = files.length;

                var finished = 0;

                var self = this;
                for( var i = 0; i < length; i++ ){
                    $.ajax({
                        url: options.uploadUrl,
                        dataType: 'JSON',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: files[i].file,
                        type: 'post',
                        // beforeSend: function(xhr){
                        //     xhr.setRequestHeader("X-File-Name", encodeURIComponent(name));
                        // },
                        success: function(data){
                            finished = finished + 1;
                            if(data.success){
                                self.trigger('success',data);
                            }

                            if( finished == length ){
                                $('#close').show();
                            }
                        }
                    });
                }
            }
        });

        window.UploadView = Backbone.View.extend({
            el: $('div#preview'),
            initialize: function(){
                _.bind(this,'render','removeLoader');
                
                this.pics = this.options.pics;

                this.pics.bind('add',this.render,this);
                this.pics.bind('success',this.removeLoader,this);

                this.template = _.template( $('#preview_template').html() );

            },

            render: function(){

                renderedTemplate = this.template( {images:this.pics.toJSON()} );
                this.$el.html( renderedTemplate );
            },
            removeLoader: function(response) {
                this.$el.find('#image_'+response.imageId).attr('src',response.path).prev('span.noti').remove();
            }

        });

        window.UploadActionView = Backbone.View.extend({
            defaults: {
                limit: 12
            },
            el: $('article.column'),
            initialize: function(){
                _.bind(this,'add');

                this.pics = this.options.pics;
                this.uploads = [];
            },
            events: {
                'click a.to-right' : 'select',
                'change #upload' : 'add'
            },

            add: function(event){

                $('#preview').reveal({
                    closeOnBackgroundClick: false
                });

                this.pics.reset();

                var files = $(event.currentTarget)[0].files;
                var length = files.length;

                if(length > this.defaults.limit){
                    length = this.defaults.limit;
                }

                pics = this.pics;
                for( var i=0; i < length; i++ ){

                    var formData = new FormData();
                    formData.append("file", files[i]);
                    formData.append("imageId",i);
                    
                    info = { name:files[i].name, id: i, file: formData };

                    pics.add(info);
                }
                pics.trigger('upload');
            },
            select: function(e){
                e.preventDefault();
                $('#upload').trigger('click');
            }
        });

        window.ModalPreviewView = Backbone.View.extend({
            el: $('div#preview'),
            initialize: function(){
                this.pics = this.options.pics;
            },
            events: {
                'click a.close-reveal-modal' : 'closeModal',
                'click input[name=upload]': 'upload',
                'click a#close': 'reloadPage'
            },
            closeModal: function(e){
                e.preventDefault();
                

                this.$el.trigger('reveal:close');
            },
            reloadPage: function(e){
                e.preventDefault();
                
                window.location.reload();
            },
            upload: function(e){
                e.preventDefault();

                this.$el.find('span.noti').show();
                this.pics.trigger('upload',e);
            }
        });

        window.SearchParams = Backbone.Model.extend({
            defaults: {
                page: 1
            },
            initialize: function(){
                _.bind(this,'reset');
            },
            reset: function(){
                this.set({
                    'page' : 1
                })

                return this;
            }
        });

        window.PaginationModel = Backbone.Model.extend({
            defaults: {
                softPageLimit: 8,
                hardPageLimit: 12
            },

            initialize: function() {
                // _.bind(this,'compute');

                var totalCount = this.attributes.totalCount;
                var limit = this.attributes.limit;
                var totalPages = Math.ceil(totalCount/limit);
                var currentPage = 1;

                this.set({
                    'totalPages':totalPages,
                    'currentPage': currentPage,
                    'limit': limit
                });
            }
        });

        window.PaginationView = Backbone.View.extend({
            el: $('#pagination'),
            initialize: function() {
                _.bind(this,'render','compute');

                this.model = this.options.model;
                this.parameters = this.options.parameters;

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

                // console.log(renderedTemplate);
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
            }
        });

        Image = Backbone.Model.extend();
        window.Images = Backbone.Collection.extend({
            model: Image,
            url: options.fetchUrl
        })

        window.ImageView = Backbone.View.extend({
            initialize: function(){
                _.bind(this,'render');

                this.model = this.options.model;

                this.template = _.template( $('#image_template').html() );
            },

            render: function(){

                var renderedTemplate = this.template({object:this.model.toJSON()});
                return renderedTemplate
            }
        });

        window.ThumbnailView = Backbone.View.extend({
            // el: $('#thumbnail'),
            initialize: function(){
                _.bind(this,'render','renderImages','fetchImages','empty');

                this.images = this.options.images;
                this.parameters = this.options.parameters;

                this.images.bind("changeSet",this.render,this);
                this.parameters.bind("change:page",this.fetchImages,this);

                this.render();
            },
            empty: function(){
                $('#thumbnail').empty();
            },
            render: function(){
                $('#thumbnail').empty();
                this.images.each( this.renderImages );
            },
            renderImages: function(model){
                var view = new ImageView({
                    model: model
                });

                $('#thumbnail').append( view.render());
            },
            fetchImages: function(){
                self = this;
                // this.images.reset();
                this.images.fetch({
                    data: { page: this.parameters.get('page') },
                    success: function(){
                        self.images.trigger('changeSet');
                    }
                });
            }
        });
        
        return this;
    }
})(jQuery);