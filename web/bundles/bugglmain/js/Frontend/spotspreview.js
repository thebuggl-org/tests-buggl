(function($){
    $.SpotPreview = function(options) {

        var defaults = {
            searchUrl: '',
        }

        options = $.extend({},defaults,options);

        template = _.template(options.template.html(),{object:null});

        var container = options.view.children('div.clearfix');

        container.append(template);

        options.preview.on('click',function(e){
            e.preventDefault();

            options.view.show();
            var id = $(this).parents(options.parent).attr(options.spot);

            $.post(options.searchUrl,{id:id},function(data){

                if (data.success) {
                    var values = data.details;
                    var rendered = _.template(options.template.html(),{object:values});

                    container.empty().html(rendered);
                } else {
                    options.view.hide();
                }

            });
        });

        options.close.live('click',function(e){
            e.preventDefault();

            options.view.hide();
        });
    }
})(jQuery);