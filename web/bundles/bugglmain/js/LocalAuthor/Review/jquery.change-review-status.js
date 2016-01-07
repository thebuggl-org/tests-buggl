(function($){
    $.fn.changeReviewStatus = function(options)
    {
        options = $.extend({}, $.fn.changeReviewStatus.defaults, options);

        return $(this).click(function(e){
            e.preventDefault();

            var values = $(this).attr('data');

            var parent = $(this).parents('div[name=list]');

            parent.children().toggle();
            $.getJSON(options.url,{qString:values},function(data){
                if(data.success){
                    window.location.reload();
                }
                else{
                    parent.children().toggle();
                }
            });
        });     
    }

    $.fn.changeReviewStatus.defaults = 
    {
        url: '',
    }
})(jQuery);