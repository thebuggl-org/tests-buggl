(function(a){a.billing=function(b){var c={searchUrl:"",};b=a.extend({},c,b);BillingHistory=Backbone.Model.extend();BillingsHistory=Backbone.Collection.extend({model:BillingHistory,url:b.searchUrl,parse:function(d){this.trigger("changeTotalCount",d.count);return d.data}});BillingHistoryView=Backbone.View.extend({el:a("div#main_content"),initialize:function(){_.bindAll(this,"render","search","changeFilter","changeSearchKey","resetPage","changeTotalCount");this.parameters=this.options.parameters;this.pagination=this.options.pagination;this.pagination.bind("changePage",this.search,this);this.collection.bind("sync",this.render,this);this.collection.bind("changeTotalCount",this.changeTotalCount,this);this.template=_.template(a("#history_lists_template").html())},render:function(){this.$el.find("ul#history_lists").empty().html(this.template({objects:this.collection.toJSON()}));return this},search:function(){var d=this.parameters.toJSON();this.collection.fetch({data:d})},events:{"click a#search":"resetPage",},changeFilter:function(e){e.preventDefault();var d=a(e.currentTarget).val();this.parameters.set({filter:d})},changeSearchKey:function(e){var d=a(e.currentTarget).val();this.parameters.set({key:d})},resetPage:function(f){var e=this.$el.find("select").val();var d=this.$el.find("input").val();this.parameters.set({filter:e,key:d,page:1});this.pagination.set({currentPage:1});this.search()},changeTotalCount:function(d){this.pagination.set({totalCount:d})}});PaginationModel=Backbone.Model.extend({defaults:{softPageLimit:8,hardPageLimit:12},initialize:function(){_.bindAll(this,"changeTotalPages");var d=this.attributes.limit;var e=1;this.bind("change:totalCount",this.changeTotalPages,this);this.set({currentPage:e,limit:d,totalCount:0});this.changeTotalPages()},changeTotalPages:function(){var e=this.get("totalCount");var d=this.get("limit");var f=Math.ceil(e/d);this.set({totalPages:f})}});PaginationView=Backbone.View.extend({el:a("#pagination"),initialize:function(){_.bindAll(this,"render","compute","changeCurrentPage");this.model=this.options.model;this.parameters=this.options.parameters;this.parameters.bind("changePage",this.changeCurrentPage,this);this.template=_.template(a("#pagination_template").html());this.model.bind("change:currentPage",this.compute,this);this.model.bind("change:totalPages",this.compute,this);this.model.bind("all",this.render,this)},events:{"click a":"paginate"},render:function(){var d=this.template(this.model.toJSON());this.$el.empty().html(d);return this},paginate:function(d){d.preventDefault();nextPage=parseInt(a(d.currentTarget).attr("href"));this.model.set({currentPage:nextPage});this.parameters.set({page:nextPage});this.model.trigger("changePage")},compute:function(){var f=this.model.get("totalPages");var g=f>this.model.defaults.hardPageLimit?this.model.defaults.softPageLimit:this.model.defaults.hardPageLimit;var e=Math.ceil(this.model.get("currentPage")/g);var d=(e-1)*g+1;if(f>this.model.defaults.hardPageLimit){endPage=d+this.model.defaults.softPageLimit-1}else{endPage=f}if(endPage>f){endPage=f}currentPage=this.model.get("currentPage");next=currentPage+1;prev=currentPage-1;next=next<f?next:f;prev=prev==0?1:prev;this.model.set({startPage:d,endPage:endPage,next:next,prev:prev})},changeCurrentPage:function(){page=this.parameters.get("page");this.model.set({currentPage:page})}});return this}})(jQuery);