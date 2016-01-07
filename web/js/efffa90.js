(function(a){a.backbone=function(b){var c={cityUrl:""};b=a.extend({},c,b);window.Countries=Backbone.Collection.extend();window.City=Backbone.Model.extend();window.Cities=Backbone.Collection.extend({model:City,url:b.cityUrl});window.CountryView=Backbone.View.extend({el:a("div[name=country]"),initialize:function(){_.bindAll(this,"render");this.collection=this.options.countries.toJSON();this.cities=this.options.cities;this.parameters=this.options.parameters;this.render()},render:function(){var d=_.template(a("#country_template").html(),{countries:this.collection});this.$el.html(d);return this},events:{"change select":"changeCity"},changeCity:function(d){country=a(d.currentTarget).val();this.parameters.set({country:country,page:1,name:"",city:0});this.cities.trigger("change",d)}});window.CityView=Backbone.View.extend({el:a("div[name=city]"),initialize:function(){_.bindAll(this,"render","changeCity","selectCity");this.template=_.template(a("#city_template").html());this.parameters=this.options.parameters;this.collection=this.options.cities;this.collection.on("change",this.changeCity);this.collection.on("add",this.render);this.collection.on("remove",this.render);this.render()},events:{"change select":"selectCity"},render:function(){cities=this.collection.toJSON();renderedContent=this.template({cities:cities});this.$el.empty().html(renderedContent).el;return this},changeCity:function(d){country=a(d.currentTarget).val();this.collection.fetch({data:{countryID:country}});this.parameters.set({country:country,page:1})},selectCity:function(d){city=a(d.currentTarget).val();this.parameters.set({city:city,page:1})}});window.PaginationModel=Backbone.Model.extend({defaults:{softPageLimit:8,hardPageLimit:12},initialize:function(){_.bindAll(this,"compute");this.bind("change:totalCount",this.compute,this);var e=this.attributes.totalCount;var d=this.attributes.limit;var g=Math.ceil(e/d);var f=1;this.set({totalPages:g,currentPage:f,limit:d})},compute:function(){var e=this.get("totalCount");var d=this.get("limit");var f=Math.ceil(e/d);this.set({totalPages:f})}});window.PaginationView=Backbone.View.extend({el:a("#pagination"),initialize:function(){_.bindAll(this,"render","compute","changeCurrentPage");this.model=this.options.model;this.parameters=this.options.parameters;this.parameters.bind("change:page",this.changeCurrentPage,this);this.template=_.template(a("#pagination_template").html());this.model.bind("change:currentPage",this.compute,this);this.model.bind("change:totalPages",this.compute,this);this.model.bind("all",this.render,this);this.compute()},events:{"click a":"paginate"},render:function(){var d=this.template(this.model.toJSON());this.$el.empty().html(d).el;return this},paginate:function(d){d.preventDefault();nextPage=parseInt(a(d.currentTarget).attr("href"));this.model.set({currentPage:nextPage});this.parameters.set({page:nextPage})},compute:function(){var f=this.model.get("totalPages");var g=f>this.model.defaults.hardPageLimit?this.model.defaults.softPageLimit:this.model.defaults.hardPageLimit;var e=Math.ceil(this.model.get("currentPage")/g);var d=(e-1)*g+1;if(f>this.model.defaults.hardPageLimit){endPage=d+this.model.defaults.softPageLimit-1}else{endPage=f}if(endPage>f){endPage=f}currentPage=this.model.get("currentPage");next=currentPage+1;prev=currentPage-1;next=next<f?next:f;prev=prev==0?1:prev;this.model.set({startPage:d,endPage:endPage,next:next,prev:prev})},changeCurrentPage:function(){this.model.set({currentPage:this.parameters.get("page")})}});LocalAuthorModel=Backbone.Model.extend();LocalAuthors=Backbone.Collection.extend({model:LocalAuthorModel,url:b.searchLocalAuthor,parse:function(d){this.trigger("changeTotalCount",d.count);return d.data}});window.ResultView=Backbone.View.extend({el:a("#author"),initialize:function(){_.bindAll(this,"render","search","changeTotalCount");this.collection=this.options.collection;this.pagination=this.options.pagination;this.parameters=this.options.parameters;this.collection.bind("all",this.render,this);this.collection.bind("changeTotalCount",this.changeTotalCount,this);this.pagination.bind("change:currentPage",this.render,this);this.parameters.bind("change",this.search,this);this.template=_.template(a("#author_list_template").html());this.render()},render:function(){renderedTemplate=this.template({authors:this.collection.toJSON()});this.$el.html(renderedTemplate).el},events:{"click a[name=suspend]":"toggleSuspension"},search:function(){data=this.parameters.getParameters();this.collection.fetch({data:data})},changeTotalCount:function(d){this.pagination.set({totalCount:d})},toggleSuspension:function(e){e.preventDefault();var d=a(e.currentTarget);var f=d.attr("href");a.getJSON(b.toggleSuspensionUrl,{id:f},function(g){d.text(g.text)})}});SearchParams=Backbone.Model.extend({defaults:{country:0,city:0,name:"",page:1,},initialize:function(){_.bindAll(this,"getParameters")},getParameters:function(){country=this.get("country");city=this.get("city");name=this.get("name");page=this.get("page");params={country:country,city:city,name:name,page:page};return params}});return this}})(jQuery);