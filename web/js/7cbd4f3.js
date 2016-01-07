(function($){
    $(document).ready(function(){
        $('.edit-category').on('click',function(e){
            e.preventDefault();
            $(this).toggle();
            $(this).siblings('a.save-category').toggle();

            var parent = $(this).parents('li.clearfix');

            parent.find('h3.to-left').toggle();
            parent.find('div.input-edit').toggle();
        });

        $('.save-category').on('click',function(e){
            e.preventDefault();
            $(this).toggle();
            $(this).siblings('a.edit-category').toggle();

            var parent = $(this).parents('li.clearfix');

            parent.children('div').toggle();
            parent.children('span.loader').toggle();

            var input = parent.find('input[name=category_name]').val();
            var text =  parent.find('h3.to-left').text();

            if(input != text && input.length > 0){
                var url = $(this).attr('href');

                $.getJSON(url, {qString:input} ,function(data){
                    if(data.success){
                        parent.children('span.loader').toggle();
                        parent.find('h3.to-left').text(data.name);
                        parent.find('input[name=category_name]').val(data.name);
                    }
                    else{
                        alert('error while saving...');
                    }
                    hideFields(parent);
                });
            }
            else{
                hideFields(parent);
            }
        });

        $('a[name=publish]').on('click',function(e){
            e.preventDefault();

            var status = $(this).attr('data-value');
            var url = $(this).attr('href');

            var me = $(this);
            $.post(url,{status:status},function(data){
                if (data.success) {
                    me.text(data.word);
                    me.attr('data-value',data.status);
                }
            });
        });

        $('.cancel-edit').on('click',function(e){
            e.preventDefault();
            var parent = $(this).parents('li.clearfix');

            parent.find('h3.to-left').toggle();
            parent.find('div.input-edit').toggle();
            parent.find('a.save-category').toggle();
            parent.find('a.edit-category').toggle();
        });

        $('#add-category').on('click',function(e){
            e.preventDefault();

            var parent = $('#modal .content');

            $('#modal .loader').toggle();
            parent.empty();
            $('#modal').reveal({'closeOnBackgroundClick':false});

            var url = $(this).attr('href');
            $.getJSON(url, function(data){
                $('#modal .loader').toggle();
                parent.empty().html(data.html);

                delegate(parent);
            });
        });

        delegate = function(parent){
            $('#modal').find('form').iframePostForm({
                json: true,
                post: function(){
                    $('#modal .loader').toggle();
                    $('#modal .content').toggle();
                },
                complete: function(data){
                    if(data.success){
                        parent.find('form').trigger('reveal:close');
                        window.location.reload();
                    }else{
                        parent.empty().html(data.html);
                        delegate(parent);
                    }
                    $('#modal .loader').toggle();
                    $('#modal .content').toggle();
                }
            });
        }

        hideFields = function(parent){
            parent.find('span.loader').hide();
            parent.children('div').toggle();
            parent.find('div.input-edit').toggle();
            parent.find('h3.to-left').toggle();
        }
    });
})(jQuery);