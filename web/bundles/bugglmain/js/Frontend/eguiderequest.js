(function($){

    
    $(document).ready(function(){

    	$(".requestbtn").on('click',function(e){
            e.preventDefault();
            $("div#req").reveal();
            $(".row-1").css("display","block");
            $(".row-2").css("display","none");
            $(".row-3").css("display","none");
            $("input[type=text]").val("");
            $("textarea[name=description]").val("");
            $(".warning-notif").text("");
        });
        $("#back").on('click',function(e){
            e.preventDefault();
            $(".row-1").css("display","block");
            $(".row-2").css("display","none");
            $("#messagenotif12").text("");
            $("#messagenotif4").text("");
            $("#block-1").addClass("active-circle");
            $("#block-2").removeClass("active-circle");
            $("#messagenotif1").text("");
            $(".totalprice").text("");
            $("#price").removeAttr('value');
            $(".check-hide1").css("display","none");
            $(".check-hide2").css("display","none");
            $("#premium-plan").css({"background-color": "#53ccff", "background-image": "-moz-linear-gradient(center top , #b4e3ff, #65c5fe)"});
            $("#basic-plan").css({"background-color": "#53ccff", "background-image": "-moz-linear-gradient(center top , #b4e3ff, #65c5fe)"});
           
        });
        
    	
        $("#req-1").click(function(event){
            event.preventDefault();
            var okay = true;
            var item=$("input[name=country]").val();
            $("#hotel").val(document.getElementById("range_value5").innerHTML);
            $("#food1").val(document.getElementById("range_value1").innerHTML);
            $("#drinking1").val(document.getElementById("range_value2").innerHTML);
            $("#shopping1").val(document.getElementById("range_value3").innerHTML);
            $("#activities1").val(document.getElementById("range_value4").innerHTML);
            $("#special_touches1").val(document.getElementById("range_value6").innerHTML);
            $("#destination").text($( ".to-left").val());
            $("#going_for").text($( "#good_for option:selected" ).text());
            $("#tripdate").text($("input[name=tripplan]").val());
            $("#reason").text($("input[name=reason]").val());
            $("#duration").text($( "#tripduration option:selected" ).text());
            $("#trip").text($( "#trip_theme option:selected" ).text());
            $("#exp2").text($( "#experience option:selected" ).text());
            $("#pace2").text($( "#pace option:selected" ).text());

            if((parseInt($("#hotel").val())+parseInt($("#food1").val())+parseInt($("#drinking1").val())+parseInt($("#shopping1").val())+parseInt($("#activities1").val())+parseInt($("#special_touches1").val())) !=100)
           {
            okay=false;
             showNotif('budget_not_valid');
           } 
    
           if((isNumber($.trim($("input[name=duration]").val()))) )
            {
                okay=false;
                showNotif('duration_not_int');
            }
            if(Number($.trim($("input[name=duration]").val()))<1 )
            {
                okay=false;
                showNotif('duration_not_valid');
            }

            if($.trim($("input[name=reason]").val())==""){
                okay=false;
                showNotif('reason_empty');
            }
            var radios = document.getElementsByName("tripplan");
            var formValid = false;
            if($.trim($("input[name=tripplan1]").val())==""){  
                var i = 0;
            while (!formValid && i < radios.length) {
           if (radios[i].checked) formValid = true;
           i++;        
           }

            if (!formValid) {
                okay=false; 
                showNotif('trip_empty');
            }
                
            }

            if($.trim($('#upload').val())!="")
            {
            var filesize=($("#upload")[0].files[0].size / 1024);
            if (filesize / 1024 > 1) 
            { filesize = (Math.round((filesize / 1024) * 100) / 100);
            if(filesize>5){
               $("#messagenotif14").text("Maximum upload file size can be 5 mb" );
               okay=false;return false;
             } }
            var allowedFiles = [".doc", ".docx", ".pdf", ".jpg", ".png", ".csv", ".xls"];
            var fileUpload = $("#upload");

            var regex = new RegExp("([A-Za-z0-9-'.,@:?!()$#/\\]+|&[^#])+(" + allowedFiles.join('|') + ")$");
            if (!regex.test(fileUpload.val().toLowerCase())) {
                $("#messagenotif14").text("Please upload files having extensions: " + allowedFiles.join(', ') + " only."); 
                okay= false;
                return false;
            }
            //alert('This file size is: ' + this.files[0].size/1024/1024 + "MB");
            $("#messagenotif14").text("");
            okay= true;
            }
             if($.trim($("textarea[name=description]").val())==""){
                okay=false;
                showNotif('message');
            }
            if($.trim($("input[name=duration]").val())==""){
                okay=false;
                showNotif('duration_empty');
            }
            if($.trim($("input[name=country]").val())==""){
                okay=false;
                showNotif('country_empty');
            }

            if(okay)
            { 
                $(".row-2").css("display","block");
                $(".row-1").css("display","none");
                $("#block-2").addClass("active-circle");
                $("#block-1").removeClass("active-circle");
                window.scrollBy(0, -1000);
            }


                    function showNotif(emptyarea){
            
           /* if(emptyarea=='message'){
                $("#messagenotif2").text("Message should not be empty");
            }*/
            
          if(emptyarea=='reason_empty'){
                $("#messagenotif3").text("Reason of trip should not be empty");
            }
        else if(emptyarea=='trip_empty'){
                $("#messagenotif4").text(" Date should not be empty");
            }
        else if(emptyarea=='duration_empty'){
                $("#messagenotif9").text(" Duration should not be empty");
            }
        else if(emptyarea=='message'){
                $("#messagenotif2").text("Message should not be empty");
            }
         else if(emptyarea=='country_empty'){
                $("#messagenotif10").text("Country should not be empty");
            } 
        else if(emptyarea=='country_not_valid'){
            $("#messagenotif10").text("Country does not exist");
            } 
        else if(emptyarea=='budget_not_valid') 
        {
          $("#messagenotif12").text("Total should be equal to 100%");  
        }
        else if(emptyarea=='duration_not_valid') 
        {
          $("#messagenotif9").text("Duration should be minimum 1 day");  
        }
          else if(emptyarea=='duration_not_int'){
                $("#messagenotif9").text("Duration should be a valid number");
            }
        }

        });

        
        $("#submitrequest").click(function(event){
            event.preventDefault();
            var okay = true;
            
           
            if(isNumber($.trim($("input[name=price]").val())))
            {
                okay=false;
                showNotif('price_not_int');
            }
             if(isNumber($.trim($("input[name=card]").val())))
            {
                okay=false;
                showNotif('card_not_int');
            }
             if(isNumber($.trim($("input[name=cvc]").val())))
            {
                okay=false;
                showNotif('cvc_not_int');
            }
             if(isNumber($.trim($("input[name=expiry-month]").val())))
            {
                okay=false;
                showNotif('month_not_int');
            }
            if(isNumber($.trim($("input[name=expiry-year]").val())))
            {
                okay=false;
                showNotif('year_not_int');
            }
            if($.trim($("input[name=price]").val())==""){
                okay=false;
                showNotif('price_empty');
            }
            if($.trim($("input[name=tripplan]").val())==""){
                okay=false;
                showNotif('trip_empty');
            }
           
           
           if($.trim($("input[name=card]").val())==""){ 
                okay=false;
                showNotif('card_empty');
            }

            if($.trim($("input[name=cvc]").val())==""){
                okay=false;
                showNotif('cvc_empty');
            }

            if($.trim($("#month").val())==""){
                okay=false;
                showNotif('month_empty');
            }

            if($.trim($("#year").val())==""){
                okay=false;
                showNotif('year_empty');
            }

            if(okay)
            {
                var newprice=$("#price").val();
                var duration=$("#tripduration").val();
                $("#price").val(duration*newprice);
            }
           
            if(okay)
            {
            Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler); 
                    return false;
          }
            if(okay){

                
                $(this).attr("disabled",true);
                var serial = $('#request_form').serialize();

                var url = $('#request_form').attr('action');
                        
                $.post(url,serial,function(data){
                    $('.reveal-modal').trigger('reveal:close');
                    $("#submitrequest").attr("disabled",false );
                    /*Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                    return false; */
                     
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

        $("textarea[name=description]").keyup(function(event){
            if($(this).val().length!=0){
                $("#messagenotif2").text("");
            }
        });
        $("input[name=country]").keyup(function(event){
            if($(this).val().length!=0){
                $("#messagenotif10").text("");
            }
        });
       $( "#country-search-1" ).blur(function() {
        var item=$("input[name=country]").val();
        $.ajax({
                   type: "POST",
                   dataType: "json",
                   url: "/ajax/check-buggl-countries",
                   data: "items="+item,
                   success: function(data){

                    if(data[0].id==0)
                    {   
                        okay=false; 
                        $('#country-search-1').removeAttr('value');
                    }
                   else{$("#newcoun").val(data[0].id);}
                        }, async:   false
                   
                 });

       });
       $( "#upload" ).change(function() {
        var allowedFiles = [".doc", ".docx", ".pdf", ".jpg", ".png", ".csv", ".xls"];
            var fileUpload = $("#upload");
            var filesize=($("#upload")[0].files[0].size / 1024);
            if (filesize / 1024 > 1) 
            { filesize = (Math.round((filesize / 1024) * 100) / 100);
            if(filesize>5){
               $("#messagenotif14").text("Maximum upload file size can be 5 mb" );
               okay=false;return false;
             } }
            var regex = new RegExp("([A-Za-z0-9-'.,@:?!()$#/\\]+|&[^#])+(" + allowedFiles.join('|') + ")$");
            if (!regex.test(fileUpload.val().toLowerCase())) {
                $("#messagenotif14").text("Please upload files having extensions: " + allowedFiles.join(', ') + " only."); 
                okay= false;
                return false;
            }
            //alert('This file size is: ' + this.files[0].size/1024/1024 + "MB");
            $("#messagenotif14").text("");
         
        });
        $( "#month" ).change(function() {
        $("#messagenotif6").text("");
        $("#messagenotif11").text("");
        });
        $( "#year" ).change(function() {
        $("#messagenotif7").text("");
        $("#messagenotif11").text("");
        });
        $("input[name=reason]").keyup(function(event){
            if($(this).val().length!=0){
                $("#messagenotif3").text("");
            }
        });
        $("input[name=tripplan]").keyup(function(event){
            if($(this).val()!=''){
                $("#messagenotif4").text("");
            }
        });
        $("input[name=card]").keyup(function(event){
            if($(this).val().length!=0){
                $("#messagenotif8").text("");
                $("#messagenotif11").text("");
            }
        });
        $("input[name=cvc]").keyup(function(event){
            if($(this).val().length!=0){
                $("#messagenotif5").text("");
                $("#messagenotif11").text("");
            }
        });
        $("input[name=expiry-month]").keyup(function(event){
            if($(this).val().length!=0){
                $("#messagenotif6").text("");
            }
        });
        $("input[name=expiry-year]").keyup(function(event){
            if($(this).val().length!=0){
                $("#messagenotif7").text("");
            }
        });
        $("input[name=duration]").keyup(function(event){
            if($(this).val().length!=0){
                $("#messagenotif9").text("");
            }
        });
        /*$('#upload').bind('change', function() {

  //this.files[0].size gets the size of your file.
  alert(this.files[0].size);

});*/
        function showNotif(emptyarea){
            
             if(emptyarea=='price_empty'){
                $("#messagenotif1").text("Please select an Itinerary plan");
            }
            else if(emptyarea=='price_not_int'){
                $("#messagenotif1").text("Price should be a valid number");
            }
           else if(emptyarea=='reason_empty'){
                $("#messagenotif3").text("Reason of trip should not be empty");
            }
             else if(emptyarea=='card_empty'){
                $("#messagenotif8").text("Please enter a card number");
            }
             else if(emptyarea=='cvc_empty'){
                $("#messagenotif5").text("Please enter CVC number");
            }
             else if(emptyarea=='month_empty'){
                $("#messagenotif6").text("Please select Month");
            }
             else if(emptyarea=='year_empty'){
                $("#messagenotif7").text("Please select Year ");
            }
             
            else if(emptyarea=='card_not_int'){
                $("#messagenotif8").text("Card number is invalid");
            }
            else if(emptyarea=='cvc_not_int'){
                $("#messagenotif5").text("CVC number doesn't match");
            }
            else if(emptyarea=='month_not_int'){
                $("#messagenotif6").text("month should be a valid number");
            }
            else if(emptyarea=='year_not_int'){
                $("#messagenotif7").text("year should be a valid number");
            }
           
        }
        function stripeResponseHandler(status, response) {
                if (response.error) { 
                    // re-enable the submit button
                     $("#messagenotif11").text(response.error.message);
                    $('.submit-button').removeAttr("disabled");
                    // show the errors on the form
                    $(".payment-errors").html(response.error.message);
                } else {
                    var form = $("#request_form");
                    // token contains id, last4, and card type
                    var token = response['id'];
                   
                    $("#stripeToken").val(token);
                    $(".reveal-modal").css("position","fixed");
                    $(".row-3").css("display","block");
                    $(".row-1").css("display","none");
                    $(".row-2").css("display","none");
                    $("#block-1").removeClass("active-circle");
                    $("#block-2").removeClass("active-circle");
                    $("#block-3").addClass("active-circle");
                
                    // insert the token into the form so it gets submitted to the server
                    //form.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                    // and submit
                    
                    form.get(0).submit();
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

