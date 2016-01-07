(function($){
    $.fn.search = function(options)
    {
        var defaults = {
            url: '',
            contentObject: '',
            loader: '',
            selfObject: $(this),
            page: 1,
            lastPage: 1,
            featureUrl: ''
        }

        
        bindPagination = function(){
            $('a.bugglPaginationLink').each(function(){
                $(this).on('click',function(e){
                    e.preventDefault();

                    options.page = $(this).attr('page');

                    if(options.page > options.lastPage)
                    {
                        return;
                    }

                    if(options.page < 1)
                    {
                        return;
                    }

                    searchGuides();
                });  
            })
        }

        bindFeature = function(){
            $('a[name=feature]').each(function(){
                self = this;
                $(self).on('click',function(event){
                    event.preventDefault();

                    clicked = $(event.currentTarget);

                    var id = $(event.currentTarget).attr('href');

                    $.getJSON(options.featureUrl,{id:id},function(data){
                        if(data.success){
                            clicked.parents('li.clearfix').remove();

                            $('ul#eguide_list').append(data.message);
                        }
                        else{
                            alert(data.message);
                        }
                    });
                });
            });
        }

        options = $.extend({},defaults,options);

        $(this).on('change',function(){
            options.page = 1; 
            $(options.contentObject).empty();

            searchGuides();
        });

        searchGuides = function()
        {
            var value = options.selfObject.val();
            $(options.loader).show();
            $(options.contentObject).hide();

            $.getJSON(options.url,{value:value,page:options.page},function(data){
            $(options.loader).toggle();

                $(options.contentObject).html(data.html);

                $(options.contentObject).show();
                $(options.loader).hide();
                
                options.lastPage = data.lastPage;
                bindPagination();
                bindFeature();
            })
        }

        return this;
    }
})(jQuery);