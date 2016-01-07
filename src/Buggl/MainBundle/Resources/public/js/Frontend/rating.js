(function($){
    $(document).ready(function(){
        $('span.star-rating > a').on('click',function(e){
            e.preventDefault();

            $('span.star-rating').removeClass('star-on');
            $('#rating').val($(this).attr('star-rating'));

            $(this).parent().removeClass('star-hover').prevAll().removeClass('star-hover');
            $(this).parent().addClass('star-on').prevAll().addClass('star-on');

        });

        $('span.star-rating').hover(
            function(e){
                $('span.star-rating').removeClass('star-hover');
                $(this).addClass('star-hover').prevAll().addClass('star-hover');
            }
        );

        $('span.star-rating').parent('div').on('mouseleave',function(){
            $('span.star-rating').removeClass('star-hover');
        });
    });
})(jQuery);