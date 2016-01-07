define(['jquery'],function($){
	var	init = function() {
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
	                    // window.location.reload();
	                    document.location.href = '';
	                }
	            });

	        }
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
	}

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

    var $returns = {
    	init: init
    }

    return $returns;
});