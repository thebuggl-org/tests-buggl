(function($){

    $.fn.manageCountry = function(options)
    {

        var defaults = {
            selfObject: $(this),
            modal: '',
            contentContainer: '',
            loader: ''
        }

        options = $.extend({},defaults,options);

        options.selfObject.each(function(){
            $(this).on('click',function(e){
                e.preventDefault();

                var id = $(this).attr('href');

                options.modal.reveal();

                options.loader.show();
                $.getJSON(options.url,{id:id},function(data){
                    options.contentContainer.html(data.html);
                    options.loader.hide();

                    options.contentContainer.find('form').delegate({
                        'loader' : options.loader,
                        'functionOnComplete': functionOnComplete
                    });
                });
            });
        });

        var functionOnComplete = function(data){
            options.loader.hide();
            contentContainer.show();

            if(data.success == true){
                options.modal.trigger('reveal:close');
                window.location.reload()
            }
            else{
                contentContainer.empty().html(data.html);

                contentContainer.find('form').delegate({
                    'loader':options.loader,
                    'functionOnComplete': functionOnComplete
                });
            }
        }

        return this;
    }


    $.fn.manageCategory = function(options)
    {

        var defaults = {
            selfObject: $(this),
            modal: '',
            clickedObject: ''
        }

        options = $.extend({},defaults,options);

        $(options.deleteTarget).deleteCategory({
            'url': options.deleteUrl,
            'parentList': options.parentList
        });

        options.selfObject.each(function(){
            $(this).on('click',function(e){
                e.preventDefault();

                var id = $(this).attr('href'); //for edit
                var createNew = 0;

                if(id == 0){
                    id = $(this).attr('data');
                }
                else{
                    createNew = 1;
                }

                options.clickedObject = $(this);
               
                options.modal.reveal();

                options.loader.show();
                $.getJSON(options.url,{id:id,createNew:createNew},function(data){
                    options.contentContainer.html(data.html);
                    options.loader.hide();
                
                    options.contentContainer.find('form').delegate({
                        'loader' : options.loader,
                        'functionOnComplete': functionOnComplete,
                        'parentContainer': options.parentContainer,
                    });
                });
            });
        });

        var functionOnComplete = function(data)
        {
            options.loader.hide();
            options.contentContainer.show();

            if(data.success == true){
                options.modal.trigger('reveal:close');


                // window.location.reload()
                if(data.isNew > 0){
                    options.clickedObject.parents('ul').prev(options.parentContainer).find('li[name=no-entry]').remove();
                    options.clickedObject.parents('ul').prev(options.parentContainer).find('ul').append(data.html);    
                }
                else{
                    // options.clickedObject.parents('ul.featured-interests').append();
                    // options.clickedObject.parents('li[name=category_list]').remove();
                    remove = options.clickedObject.parents('li[name=category_list]');

                    $(data.html).insertAfter(remove);
                    remove.remove();

                }
                

                $('a[name=category]').manageCategory({
                    'modal' : options.modal,
                    'url': options.url,
                    'contentContainer' : options.contentContainer,
                    'loader' : options.loader,
                    'parentContainer': options.parentContainer,
                    'deleteUrl': options.deleteUrl,
                    'parentList': options.parentList,
                    'deleteTarget': options.deleteTarget
                });
                
            }
            else{
                options.contentContainer.empty().html(data.html);

                    options.contentContainer.find('form').delegate({
                    'loader':options.loader,
                    'functionOnComplete' : functionOnComplete,
                    'parentContainer': options.parentContainer,
                });
            }    
        }

        return this;
    }

    $.fn.delegate = function(options)
    {
        var defaults = {
            selfObject: $(this)
        }

        options = $.extend({},defaults,options);

        contentContainer = options.selfObject.parent();

        options.selfObject.iframePostForm({
            json: true,
            post: function(){
                options.loader.show();
                contentContainer.hide();
            },
            complete: options.functionOnComplete
            // complete: function(data){
            //     options.loader.hide();
            //     contentContainer.show();

            //     console.log(data);

            //     if(data.success == true){

            //     }
            //     else{
            //         contentContainer.empty().html(data.html);

            //         contentContainer.find('form').delegate({
            //             'loader':options.loader
            //         });
            //     }
            // }
        });

        return this;
    }

    $.fn.deleteCategory = function(options)
    {
        var defaults = {
            selfObject: $(this),
            url: ''
        }

        options = $.extend({},defaults,options);

        return options.selfObject.each(function(){
            $(this).on('click',function(e){
                e.preventDefault();

                var clicked = $(this);

                var remove = confirm("Are you sure?");

                if(remove == true){

                    id = $(this).attr('href');

                    $.getJSON(options.url,{id:id},function(data){
                        //delete parent;
                        if(data.success == true){
                            clicked.parent('li').remove()
                        }
                    });
                }
            }); 
        });
    }

})(jQuery);