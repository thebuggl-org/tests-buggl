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
                   /*    $.ajax({
                   type: "POST",
                   dataType: "json",
                   url: "/buggl/web/ajax/check-buggl-countries",
                   data: "items="+item,
                   success: function(data){

                    if(data[0].id==0)
                    {   
                        okay=false; 
                        showNotif('country_not_valid');
                    }
                   else{$("#newcoun").val(data[0].id);}
                        }, async:   false
                   
                 });*/
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
/*
        $("input[name=price]").keyup(function(event){
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
               this.value = this.value.replace(/[^0-9\.]/g, '');
            }
            if($(this).val().length!=0){
                $("#messagenotif1").text("");
            }
        });*/

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
                   url: "/buggl/web/ajax/check-buggl-countries",
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
/* var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container',
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            events: {
                redraw: function () {
                    //update sliders  
                    $( ".newslide" ).remove(); 
                    $.each(chart.series[0].points, function (i, point) {
                        console.log(point.slider, point);
                        $('<input type="hidden" class="newslide" name="slider1[]">').val((point.y).toFixed(0)).appendTo('#slider');
                        point.slider.slider('value', point.percentage);
                    });
                }
            }
        },
        title: {
            text: ''
        },
        tooltip: {
            formatter: function() {
return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage, 0) +' %';
                        },
            percentageDecimals: 1
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                size:'100%',
                dataLabels: {
                    enabled: false
                },
                  formatter: function() {
return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage, 2) +' %';
                        },
                  
                showInLegend: true
            /*},
            series: {
                dataLabels: {
                    enabled: true,
                    formatter: function() {
                        return Math.round(this.percentage*100)/100 + ' %';
                    },
                    distance: -60,
                    color:'yellow'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'trip budget',
            data: [
                ['Food', 10.0],
                ['Drinking', 10],
                ['Shopping', 10],
                ['Activities', 10],
                ['Hotels', 10],
                ['Special Touches', 50]
            ]
        }]
    });
    var pointsLength = chart.series[0].data.length;
    $.each(chart.series[0].points, function (i, point) {
        $('<input type="hidden" name="slider[]">').val(point.y).appendTo('#sliders')
        point.slider = $('<div></div>').appendTo('#sliders').slider({
            value: point.y,
            max: 100,
            min: 0,
            slide: function (event, ui) {
                var prevVal = point.y,
                    step = (ui.value - point.y) / (pointsLength - 1),
                    data = [],
                    newVal;
                
                $.each(chart.series[0].points, function(i, p){
                    if(p === point) {
                        data.push({
                            name: p.name,
                            y: ui.value
                        })
                    } else {
                        data.push({
                            name: p.name,
                            y: p.y - step
                        })
                    }
                }); //alert(data.toSource());
                chart.series[0].setData(data);
                $(this).prev().val(ui.value)
            //alert(document.getElementsByName("slider[]")[0].value);
            }
        })
        point.slider.children('a').css('background', point.color).text('    ' + point.legendItem.textStr)
        $('<input type="hidden" class="newslide" name="slider1[]">').val(0).appendTo('#slider');
    })*/