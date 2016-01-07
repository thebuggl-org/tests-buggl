var Twitter = (function($) {
    return {

        enable : function(info) {
            $('#twitter-btn').click(function() { Twitter.share(info, 'twitter-count'); });
            Twitter.getShareCount(info.url, 'twitter-count');
        },

        getShareCount: function(urlSearch, id) {
            // url: 'http://urls.api.twitter.com/1/urls/count.json?url='+urlSearch
            urlSearch = (urlSearch || document.location.href);
            $.getJSON("https://cdn.api.twitter.com/1/urls/count.json?callback=?&url="+ urlSearch, function(data, status, xhr){
                if( xhr.status == 200 )
                  SocialShare.pushShareCount(data.count, id);
                else {
                  //error - check out the values for only in chrome for 'console'
                 // alert("okay, error na naman");
                }
            });

            // .success(function() { alert("second success"); })
            // .error(function() { alert("retry twitter"); getShareCount(urlSearch, id); })
            // .complete(function() { alert("complete"); });

            // $.ajax({
            //     type: 'GET',
            //     dataType: 'json',
            //     url: 'https://cdn.api.twitter.com/1/urls/count.json?callback=?url='+urlSearch,
            //     success:function(responseText){
            //         $("#"+id).val(responseText['count']);
            //     },
            //     error:function(obj, e){
            //         $("#"+id).val(obj+" "+e);
            //         //$(this).showErrorPopup('An error occurred while retrieving tweet count.');
            //     }
            // });

            // alert("twitter count");
        },

        addTweetButton : function(shortenedUrl, longUrl, text) {
            var tweet = document.createElement( "a" );

            tweet.setAttribute("href", "https://twitter.com/share");
            tweet.setAttribute("class", "twitter-share-button");
            $(tweet).append("Tweet");

            tweet.setAttribute("data-url", shortenedUrl);
            tweet.setAttribute("data-counturl", longUrl);
            tweet.setAttribute("data-text", text);
            tweet.setAttribute("data-via", 'Buggl');

            return tweet;
        },

        share : function(info, id) {
            var TWEET_URL = "https://twitter.com/intent/tweet";

            var url = (info.shortenedUrl || document.location.href);
            var originalRefer = (info.url || document.location.href);
            var text = encodeURIComponent(info.title ? $.trim(info.title) : "");
            var via = info.via ? $.trim(info.via) : 'Buggl';
            var related = encodeURIComponent(info.related) || "";
            var hashtags = encodeURIComponent(info.hashtags) || "";
            var lang = info.lang ? $.trim(info.lang) : "en";

            var location = TWEET_URL+"?original_referer="+originalRefer+"&url="+url+"&text="+text+"&source=tweetbutton&via="+via+"&related="+related+"&hashtags="+hashtags;
            // window.open(location, '_blank');

            var share_win = window.open(location, 'sharewin','left=20,top=20,width=500,height=425,toolbar=1,resizable=0');
            var pollTimer = window.setInterval(function() {
                if (share_win.closed !== false) { // !== is required for compatibility with Opera
                    Twitter.getShareCount(info.url, id);
                    window.clearInterval(pollTimer);
                }
            }, 200);

            // share_win.onload = function() {
            //     return;
            // }
            // console.log(share_win.onunload);

            // if (share_win.attachEvent) {share_win.attachEvent('onbeforeunload',

            //     function () {
            //         console.log("hello");
            //         alert(info.url+"-"+id);
            //         // Twitter.getShareCount(info.url, id);
            //         return null;
            //     }

            //     );}
            // else if (share_win.addEventListener) {share_win.addEventListener('beforeunload',

            //     function () {
            //         console.log("hello");
            //         alert(info.url+"-"+id);
            //         // Twitter.getShareCount(info.url, id);
            //         return null;
            //     }

            //     , false);}
            // else {share_win.document.addEventListener('beforeunload',

            //     function () {
            //         console.log("hello");
            //         alert(info.url+"-"+id);
            //         // Twitter.getShareCount(info.url, id);
            //         return null;
            //     }

            //     , false);}
            // alert("twitter share");
        },

        init : function() {
            !function(d,s,id){
                var js,fjs=d.getElementsByTagName(s)[0];

                if(!d.getElementById(id)){
                    js=d.createElement(s);
                    js.id=id;
                    js.src="https://platform.twitter.com/widgets.js";
                    fjs.parentNode.insertBefore(js,fjs);
            }}(document,"script","twitter-wjs");

            // alert("twitter init");
        }
    }

})(jQuery);

var GPlus = (function($){
    return {

        enable : function(info, id) {
            $('#gplus-btn').click(function() { GPlus.share(info, 'gplus-count'); });
            GPlus.getShareCount(info.url, 'gplus-count');
        },

        getShareCount : function(urlSearch, id) {
            // var postData = [{
            //     "method":"pos.plusones.get",
            //     "id":"p",
            //     "params":{
            //         "nolog":true,
            //         "id": urlSearch,
            //         "source":"widget",
            //         "userId":"@viewer",
            //         "groupId":"@self"
            //         },
            //     "jsonrpc":"2.0",
            //     "key":"p",
            //     "apiVersion":"v1"
            // }];

            // [{
            //     "method":"pos.plusones.get",
            //     "id":"p",
            //     "params":{
            //         "nolog":true,
            //         "id":"' . $url . '",
            //         "source":"widget",
            //         "userId":"@viewer",
            //         "groupId":"@self"
            //     },
            //     "jsonrpc":"2.0",
            //     "key":"p",
            //     "apiVersion":"v1"
            // }]'

            // $.getJSON( 'https://clients6.google.com/rpc?key=AIzaSyC-Hr7wMe0bjUdcufonELSHO-NxHlCU7Y4', postData)
            // .done(function(data) {
            //     $("#"+id).val(data.count);
            // })
            // .fail(function( jqxhr, textStatus, error ) {
            //   var err = textStatus + ', ' + error;
            //   alert( "Request Failed: " + err);
            // });

            // $.ajax({
            //     type: 'POST',
            //     dataType: 'json',
            //     data: postData,
            //     url: 'https://clients6.google.com/rpc',
            //     success:function(responseText){
            //         $("#"+id).val(responseText[0].result.metadata.globalCounts.count);
            //     },
            //     error:function(obj, e){
            //         $("#"+id).val(-1);
            //         //$(this).showErrorPopup('An error occurred while retrieving tweet count.');
            //     }
            // });

            // $.ajax({
            //     type: 'GET',
            //     data: ({url: urlSearch}),
            //     url: '/frontend_dev.php/program_content/getGplusCount',
            //     success:function(responseText){
            //         $("#"+id).html(responseText+ ' times');
            //         // alert("gplus count");
            //     },
            //     error:function(obj, e){
            //         alert("retry gplus");
            //         getShareCount(urlSearch, id);
            //     }
            // });

            urlSearch = (urlSearch || document.location.href);
            $.ajax({
                url: '/getGplusCount',
                type : 'GET',
                data: ({url: urlSearch}),
                tryCount : 0,
                retryLimit : 10,
                success : function(data) {
                    if (data.success == true) {
                        SocialShare.pushShareCount(data.count, id);
                    }
                },
                error : function(xhr, textStatus, errorThrown ) {
                    if (textStatus == 'timeout') {
                        this.tryCount++;
                        if (this.tryCount <= this.retryLimit) {
                            //try again
                            $.ajax(this);
                            return;
                        }
                        return;
                    }
                    if (xhr.status == 500) {
                        //handle error
                    } else {
                        //handle error
                    }
                }
            });

        },

        addGPlusButton : function(url) {
            var gplus = document.createElement("g:plusone");

            gplus.setAttribute("size", "medium");
            gplus.setAttribute( "href", url );

            return gplus;
        },

        share : function(info, id)
        {
            var url = encodeURIComponent(info.url || location.href);
            // var sharedTitle = ($.trim(info.title));
            var location = 'https://plus.google.com/share?url=' + url;
             // +'&title='+sharedTitle;
            // var location = 'https://plusone.google.com/_/+1/confirm?hl=en-US&url='+url+'&title='+sharedTitle;
            // window.open(location,'_blank');

            // width=800
            var share_win = window.open(location, 'sharewin','left=20,top=20,width=500,height=480,toolbar=1,resizable=0');
            var pollTimer = window.setInterval(function() {
                if (share_win.closed !== false) { // !== is required for compatibility with Opera
                    GPlus.getShareCount(info.url, id);
                    window.clearInterval(pollTimer);
                }
            }, 200);
            // alert("gplus share");
        },

        init : function() {
            window.___gcfg = {
                lang: 'en-US'
            };

            (function(){
                var po = document.createElement('script');
                po.type = 'text/javascript';
                po.async = true;
                po.src = 'https://apis.google.com/js/plusone.js';

                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(po, s);
            })();

            // alert("gplus init");
        }
    }
})(jQuery);

var Facebook = (function($){
    return {

        enable : function(info, id) {
            $('#facebook-btn').click(function() { Facebook.share(info, 'facebook-count'); });
            Facebook.getShareCount(info.url, 'facebook-count');
        },

        share: function(info, id) {

            FB.login(function(response) {
               if (response.authResponse) {
                    FB.ui({
                      method: (info.method ? $.trim(info.method) : 'feed'),
                      // redirect_uri: 'YOUR URL HERE',
                      link: (info.url || document.location.href),
                      picture: $('meta[property="og:image"]').attr('content'),
                      name: ($.trim(info.title)),
                      // caption: encodeURIComponent($.trim(info.caption)),
                      description: $('meta[property="og:description"]').attr('content'),
                      display: $.trim(info.display)
                    },
                        function(response) {
                            if (response && response.post_id) {
                                // alert("facebook share");
                                // alert('Post was published.');
                                Facebook.getShareCount(info.url, id);
                            } else {
                                // alert('Post was not published.');
                            }
                        }
                     );
               } else {

               }
             });

            // var description = encodeURIComponent($.trim(info.description));
            // var picture = (info.picture ? $.trim(info.picture) : 'http://www.goabroad.com/images/goabroad-logo.png');
            // var url = encodeURIComponent(info.url || document.location.href);
            // var name = encodeURIComponent($.trim(info.title));
            // var caption = encodeURIComponent($.trim(info.caption));


            // //app ids: 160363677347650 || 192597394475 || 150863148440985 ||
            // var FB_SHARE = 'https://www.facebook.com/dialog/feed';
            // var location = FB_SHARE+"?app_id=160363677347650&picture="+picture+"&name="+name
            //             // +"&description="+description
            //             // +"&caption="+caption
            //             +"&redirect_uri="+url+"&link="+url;


            // // window.open(location, '_blank');
            // window.open(location, 'sharewin','toolbar=1,resizable=0');
        },

        getShareCount : function(urlSearch, id) {
            // $.ajax({
            //     type: 'GET',
            //     url: 'https://graph.facebook.com/fql?q=SELECT%20like_count,%20total_count,%20share_count,%20click_count,%20comment_count%20FROM%20link_stat%20WHERE%20url%20=%20%22'+urlSearch+'%22',
            //     success:function(responseText){
            //         $("#"+id).html(responseText['data'][0].share_count +' times');
            //         // alert("facebook count");
            //     },
            //     error:function(obj, e){
            //         // $("#"+id).html("0 times");
            //         //$(this).showErrorPopup('An error occurred while retrieving tweet count.');
            //         // getShareCount(urlSearch, id);
            //     }
            // });

            urlSearch = (urlSearch || document.location.href);
            $.ajax({
                url : 'https://graph.facebook.com/fql?q=SELECT%20like_count,%20total_count,%20share_count,%20click_count,%20comment_count%20FROM%20link_stat%20WHERE%20url%20=%20%22'+urlSearch+'%22',
                type : 'GET',
                tryCount : 0,
                retryLimit : 10,
                success : function(responseText) {

                    if(typeof responseText == 'string')
                    {
                        var responseText=JSON.parse(responseText);
                    }

                    SocialShare.pushShareCount(responseText['data'][0].share_count, id);
                },
                error : function(xhr, textStatus, errorThrown ) {
                    // if (textStatus == 'timeout') {
                        this.tryCount++;
                        if (this.tryCount <= this.retryLimit) {
                            //try again
                            $.ajax(this);
                            return;
                        }
                        return;
                    // }
                    // if (xhr.status == 500) {
                    //     //handle error
                    // } else {
                    //     //handle error
                    // }
                }
            });
        },

        init : function() {
            // Load the SDK asynchronously
              (function(d, s, id){
                 var js, fjs = d.getElementsByTagName(s)[0];
                 if (d.getElementById(id)) {return;}
                 js = d.createElement(s); js.id = id;
                 js.src = "//connect.facebook.net/en_US/all.js";
                 fjs.parentNode.insertBefore(js, fjs);
               }(document, 'script', 'facebook-jssdk'));

              window.fbAsyncInit = function() {
                // init the FB JS SDK
                FB.init({
                  appId      : '517832091587063',                    // App ID from the app dashboard
                  channelUrl : '//www.buggl.com',           // Channel file for x-domain comms
                  status     : true,                                 // Check Facebook Login status
                  xfbml      : true                                  // Look for social plugins on the page
                });

                // Additional initialization code such as adding Event Listeners goes here
              };

              // alert("facebook init");
        }
    }
})(jQuery);

var Pinterest = (function($){
    return {

        enable : function(info, id) {
            $('#pin-btn').click(function() { Pinterest.share(info, 'pin-count'); });
            Pinterest.getShareCount(info.url, 'pin-count');
        },

        getShareCount : function(urlSearch, id) {
			$.getJSON('/get-pin-count',function(response){
				SocialShare.pushShareCount(response.count, id);
			});
			
//			urlSearch = (urlSearch || document.location.href);
//             $.ajax({
//                 url: '/get-pin-count/'+urlSearch,
//                 type : 'GET',
//                 success : function(data) {
// 					alert('count: '+data.count);
//                     if (data.success == true) {
//                         SocialShare.pushShareCount(data.count, id);
//                     }
//                 },
//                 error : function(xhr, textStatus, errorThrown ) {
// 					alert(textStatus);
//                 }
//             });
        },

        share : function(info, id) {
 		   var pic = $('meta[property="og:image"]').attr('content');
		   var description = info.description != null ? info.description : $('meta[property="og:description"]').attr('content');
		   var location = "http://www.pinterest.com/pin/create/button/?url="+info.url+"&media="+pic+"&description="+description;
				   
           var share_win = window.open(location, 'sharewin','left=20,top=20,width=750,height=300,toolbar=1,resizable=0');
           var pollTimer = window.setInterval(function() {
               if (share_win.closed !== false) { // !== is required for compatibility with Opera
                   Pinterest.getShareCount(info.url, id);
                   window.clearInterval(pollTimer);
               }
           }, 200);
        },

        init : function() {
			
			(function(d){
			    var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
			    p.type = 'text/javascript';
			    p.async = true;
			    p.src = '//assets.pinterest.com/js/pinit.js';
			    f.parentNode.insertBefore(p, f);
			}(document));
        }
    }
})(jQuery);

var SocialShare = (function($){
    return {

        toTitleCase : function (str) {
            return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
        },

        enable : function(network, info, id) {
            tag = network.toLowerCase();
            var Handler = {};
            Handler = (eval(network));

            $("#"+tag+"-btn-"+id).click(function() { Handler.share(info, tag+"-count-"+id); });
            Handler.getShareCount(info.url, tag+"-count-"+id);
        },

        pushShareCount: function(count, id) {
            $("#"+id).html(count);
        },
		
		share: function() {
            var share_win = window.open(location, 'sharewin','left=20,top=20,width=500,height=425,toolbar=1,resizable=0');
            var pollTimer = window.setInterval(function() {
                if (share_win.closed !== false) { // !== is required for compatibility with Opera
                    Twitter.getShareCount(info.url, id);
                    window.clearInterval(pollTimer);
                }
            }, 200);
		},

        getDefaultValues : function() {
            var info = {
                "shortenedUrl" : '',
                "url" : (window.location.href).split('#')[0],
                "title" : $(document).attr('title'),
                "method" : 'feed',
                "redirect_uri" : '',
                "picture" : '',
                "name" : 'www.buggl.com name',
                "caption" : 'www.buggl.com caption',
                "description" : null,
                // "display" : 'popup',
                "via" : 'Buggl',
                "related" : '',
                "hashtags" : '',
                "lang" : ''
            };

            return info;
        },

        init : function() {
          Facebook.init();
          Twitter.init();
          GPlus.init();
		  Pinterest.init();

          info = SocialShare.getDefaultValues();
        }
    }
})(jQuery);

(function($) {
    // alert("hello social share");
    SocialShare.init();
	
 	
	//Keep track of last scroll
	/*var lastScroll = 0;
	$(window).scroll(function(event){
		var s = $(window).scrollTop(); 
		var docheight = $(document).height();
		var winheight = $(window).height();
		var scrollPercent = (s/(docheight-winheight)) * 100;
		var position = $(".share-guide").offset();

		var limit = docheight-770;

		//Sets the current scroll position
		var st = $(this).scrollTop();
		//Determines up-or-down scrolling
		if (st > lastScroll){
			if( position.top >= limit  ){
				$(".share-guide").css({
					'position':'absolute',
					'top':'1040px'
				})
			}
		}
		else {
			if( scrollPercent < 88 ){ 
				$(".share-guide").css({
					'position':'FIXED',
					'top':'AUTO'
				})
			}	
		}

		//Updates scroll position
		lastScroll = st;
	});*/
	
	var nav = $('.share-guide');
	var footer = $('.footer');
    var navStart = $(nav).offset().top;
	var navTop = 40;
	
	$.event.add(window, "scroll", function(){
        var start = $(window).scrollTop();
		
		if($(nav).height() + 90 > (footer.position().top - start)){
			navTop = footer.position().top - nav.height() - start - 50;
		}
		else{
			navTop = 40;
		}
		
        $(nav).css('top',navTop+'px');
	});
}(jQuery));
