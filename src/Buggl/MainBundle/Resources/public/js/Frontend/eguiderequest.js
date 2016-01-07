(function($){
    $(document).ready(function(){

    	$(".requestbtn").on('click',function(e){
            e.preventDefault();
            $("div#req").reveal();
            //$("a[name=budget]").parent().find('a').removeClass();
            //$("a[name=budget]:lt(3)").addClass("active");
            $("input[type=text]").val("");
            $("textarea[name=description]").val("");
            //$("input[name=budgetserial]").val(3);
            $(".warning-notif").text("");
        });

    	/*$("a[name=budget]").hover(function(){
            $("a[name=budget]").removeClass("active");
            $(this).addClass("active").parent().prevAll().find('a').addClass("active");
            var value= $(this).attr("data-budget");
            $("input[name=budgetserial]").val(value);
            $(this).click(function(event){
                event.preventDefault();
            })
        });*/


        $("#submitrequest").click(function(event){
            event.preventDefault();
            var okay = true;

            if(isNumber($.trim($("input[name=price]").val())))
            {
                okay=false;
                showNotif('price_not_int');
            }

            if($.trim($("input[name=price]").val())==""){
                okay=false;
                showNotif('price_empty');
            }

            if($.trim($("textarea[name=description]").val())==""){
                okay=false;
                showNotif('message');
            }

            if(okay){
                $(this).attr("disabled",true);
                var serial = $('#request_form').serialize();

                var url = $('#request_form').attr('action');

                $.post(url,serial,function(data){
                    $('.reveal-modal').trigger('reveal:close');
                    $("#submitrequest").attr("disabled",false );

                    if (data.success == true) {
                        window.location.reload();
                    }
                });

            }

            // if($.trim($("textarea[name=description]").val())==""){
            //     showNotif();
            // }
            // else{
            //     var serial = $('#request_form').serialize();
            //     console.log(serial);

            //     var url = $('#request_form').attr('action');

            //     $.post(url,serial,function(data){
            //     });

            //     $('.reveal-modal').trigger('reveal:close');
            // }

        });

        $("input[name=price]").keyup(function(event){
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
               this.value = this.value.replace(/[^0-9\.]/g, '');
            }
            if($(this).val().length!=0){
                $("#messagenotif1").text("");
            }
        });

        $("textarea[name=description]").keyup(function(event){
            if($(this).val().length!=0){
                $("#messagenotif2").text("");
            }
        });

        function showNotif(emptyarea){
            if(emptyarea=='message'){
                $("#messagenotif2").text("Message should not be empty");
            }
            else if(emptyarea=='price_empty'){
                $("#messagenotif1").text("Price should not be empty");
            }
            else if(emptyarea=='price_not_int'){
                $("#messagenotif1").text("Price should be a valid number");
            }
        }

        function isNumber(n) {
            return isNaN(n);
        }

        $('a.disabled').click(function(){
            return false;
        });

    });
})(jQuery);