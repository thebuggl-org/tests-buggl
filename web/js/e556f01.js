(function(a){a.fn.paginate=function(b){var c=a.extend({},a.fn.paginate.defaults,b);a(".bugglPaginationLink").on("click",function(f){f.preventDefault();if(a(this).hasClass("disabled")){return false}var d=a(this);d.parents("#"+c.containerId).children(".bugglPaginationContents").append("<span class='loader'>Loading...</span>");a.get(a(this).parents(".bugglPagination").attr("data-url"),{currentPage:d.attr("page")},function(e){a("#"+c.containerId).empty().append(e);a(".bugglPagination").paginate({containerId:c.containerId})})})};a.fn.paginate.defaults={containerId:"paginationContainer"}})(jQuery);