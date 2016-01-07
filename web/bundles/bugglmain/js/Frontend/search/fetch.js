define(['text!templates/loader.tpl'],function(loader){
	var page = 1;
	var fetchMoreUrl = '/fetch-more-guides';
	var sort = {'relavant' : 1, 'recent': 1, 'download': 1};
	// var xmlhttp = new XMLHTTPRequest();

	// document.getElementById('loadMore').onclick = function(){
 //    	this.style.display = 'none';
 //    	this.parentNode.innerHTML += loader;
 //    	fetchMore(page+1);
 //    	return false;
 //    }

 //    function fetchMore($page) {
 //    	xmlhttp.open()
 //    }
 	$('#loadMore').on('click',function(event){
 		event.preventDefault();
 		$(this).hide().parent().append(loader);

 		// $.getJSON(fetchMoreUrl, function(data){
 		// 	// do something here...
 		// });

 		var $link = $(this);
 		$.ajax({
			url: fetchMoreUrl,
			type: 'post',
			data: {'page': 1}
		}).done(function(data) {
			if (data.showLoadMore) {
				$link.show();
			}

			if (data.reload == true) {
				window.location.reload();
			} else {
				$('#loader').remove();
				$('section.travelguide').append(data.results)
			}
		});
 	});
})