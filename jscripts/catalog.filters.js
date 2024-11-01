(function($) {
	
	Zibbra.Catalog.Filters = function() {

		this.params = {
			pi: null,	// price
			po: null,	// properties
			mf: null,	// manufacturers
			is: null,	// in_stock
			lm: null,	// limit
			st: null,	// sort type
			sd: null	// sort direction
		};
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype.init = function() {

		this._initToggle();
		this._initState();
		this._initSlider();
		this._initProperties();
		this._initManufacturers();
		this._initStock();
		this._initSort();
		this._initLimit();
		this._updatePagination();
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._initToggle = function() {
		
		var self = this;
    	  
		if($(".icon-filter:visible").length==1) {
			
			$(".icon-filter:visible").click(function() {
				
				$(".zibbra-catalog-products > .zibbra-catalog-toolbar").toggle();
				$(".zibbra-catalog-filters").toggle();
			
			}); // end click
		
		} // end if
	
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._initState = function() {
		
		var search = location.search.substring(1);
		var data = this._uriToObj(search);
		
		for(var i in data) {
			
			if(typeof(this.params[i])!="undefined") {
				
				if(i=="properties") {
					
					this.params.po = {};
					
					for(var id in data.properties) {
						
						this.params.po[id] = [];
						
						for(var value in data.properties[id]) {
							
							this.params.po[id].push(data.properties[id][value]);
							
						} // end for
						
					} // end for
					
				}else{
				
					this.params[i] = data[i];
				
				} // end if
				
			} // end if
			
		} // end if
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._initSlider = function() {
		
		var self = this;
		
		$("div.zibbra-filter.range > div.range > div.slider").each(function() {	

			var id = $(this).attr("id");
			var slider = document.getElementById(id);
			var prefix = $(this).attr("data-prefix");
			var min = parseInt($(this).attr("data-min"));
			var max = parseInt($(this).attr("data-max"));
			var value_min = parseInt($(this).attr("data-value-min"));
			var value_max = parseInt($(this).attr("data-value-max"));
			var margin = (max - min) / 10;
			
			if(self.params.pi!==null) {
				
				var aPrice = self.params.pi.split("-");
				var value_min = parseInt(aPrice[0]);
				var value_max = parseInt(aPrice[1]);
				
			} // end if
			
			noUiSlider.create(slider, {
				start: [value_min,value_max],
				connect: true,
				margin: margin,
				range: {
					'min': [min],
					'max': [max]
				}
			}); // end create slider
			
			var update = function(min,max) {

				$(slider).parent().children("span.slider-value-min").html(prefix+" "+Math.floor(min));
				$(slider).parent().children("span.slider-value-max").html(prefix+" "+Math.ceil(max));
				
			}; // end update
			
			slider.noUiSlider.on("update", function(values) {
				
				update(values[0],values[1]);
				
			}); // end change
			
			slider.noUiSlider.on("change", function(values) {
				
				self._onChangePrice(Math.floor(values[0]), Math.ceil(values[1]));
				
			}); // end change
			
		}); // end each
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._initProperties = function() {
		
		var self = this;
		var properties = $(".zibbra-filter.list");		
		
		$(properties).each(function(i, property) {
			
			var propertyid = $(property).attr("id");
			
			if(propertyid!="manufacturer" && propertyid!="in_stock") {

				var options = $(property).find("div.list > input");
				var count = 0;
				
				$(options).each(function(j, option) {
					
					var optionid = $(option).attr("name");
					var value = $(option).val();
					var index = optionid+"-"+value;
					
					if(self.params.po!==null && self.params.po.indexOf(index)>=0) {
							
						$(option).attr("checked","checked");
						count++;
						
					} // end if
					
					$(option).click(function() {
						
						self._onSelectProperty(this);
						
					}); // end click
					
					// Check if count = 0
					
					var inputid = $(option).attr("id");
					var label = $(option).parent().find("label[for='"+inputid+"']");
					var labelCount = parseInt($(label).find("span.suffix > span.count").html());
					
					if(labelCount==0) {
		
						$(option).attr("disabled","disabled").addClass("disabled");
						$(label).addClass("disabled");
		
					} // end if
					
				}); // end each
				
				if(count==0 && Zibbra.Catalog.FILTERS_COLLAPSED) {
					
					$(property).addClass("collapsed");
					
				} // end if
				
			} // end if
			
		}); // end each
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._initManufacturers = function() {
		
		var self = this;
		
		if($("#manufacturer").length==1) {

			var options = $("#manufacturer").find("div.list > input");
			
			$(options).each(function(i, option) {

				var key = $(option).attr("id").match(/\d+$/gi);
				var value = parseInt($(option).val());
				var label = $("#manufacturer").find("label[for='manufacturer-"+key+"']");
				var count = parseInt($(label).find("span.suffix > span.count").html());
				
				if(count==0) {
					
					$(option).attr("disabled","disabled");
					$(label).addClass("disabled");
					
				} // end if
				
				for(var j in self.params.mf) {
					
					if(parseInt(self.params.mf[j])==value) {
						
						$(option).attr("checked","checked");
						break;
						
					} // end if
					
				} // end for
				
				$(option).click(function() {
					
					self._onSelectManufacturer(this);
					
				}); // end click
				
			}); // end each
			
		} // end if
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._initStock = function() {
		
		var self = this;
		
		if($("#in_stock").length==1) {

			var options = $("#in_stock").find("div.list > input");
			
			if(this.params.is!==null) $(options).attr("checked","checked");
			
			$(options).click(function() {
				
				self._onSelectStock(this);
				
			}); // end click
			
		} // end if
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._initLimit = function() {
		
		var self = this;
        var links = $(".zibbra-catalog-limit ul > li > a");
		
		if($(links).length>1) {
			
			$(links).click(function() {
				
				self.params.lm = parseInt($(this).attr("data-attr-limit"));
				self._updateCatalog();
				
			}); // end change
			
		} // end if
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._initSort = function() {
		
		/*
		
		var self = this;

		var sort = $("zibbra-catalog-sort");
		
		if(sort!==null) {
			
			if(this.params.st===null) {
				
				this.params.st = Zibbra.Catalog.DEFAULT_SORT_TYPE;
				this.params.sd = Zibbra.Catalog.DEFAULT_SORT_DIR;
				
			} // end if
		
			var links = $("zibbra-catalog-sort").getElements("a");
			
			if(links!==null) {
				
				if(!$("zibbra-catalog-sort-"+this.params.st).hasClass("active")) {
	
					$("zibbra-catalog-sort").getElements("a").removeClass("active");
					$("zibbra-catalog-sort-"+this.params.st).addClass("active");
				
				} // end if
				
				if(!$("zibbra-catalog-sort-"+this.params.st).hasClass(this.params.sd)) {
	
					$("zibbra-catalog-sort-"+this.params.st).removeClass("asc").removeClass("desc").addClass(this.params.sd);
				
				} // end if
	
				for(var i=0; i<links.length; i++) {
					
					links[i].addEvent("click",function() {
						
						if(!this.hasClass("active")) {
							
							$("zibbra-catalog-sort").getElements("a").removeClass("active");
							
							this.addClass("active");
							this.addClass("asc");
							self.params.sd = "asc";
							
						}else{
							
							if(this.hasClass("asc")) {
								
								this.addClass("desc").removeClass("asc");
								self.params.sd = "desc";
								
							}else{
								
								this.addClass("asc").removeClass("desc");
								self.params.sd = "asc";
								
							} // end if
							
						} // end if
						
						self.params.st = this.get("id").replace("zibbra-catalog-sort-","");
	
						self._updateCatalog();
						
					}); // end onClick
					
				} // end for
			
			} // end if
		
		} // end if
		
		*/
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._onChangePrice = function(min,max) {
		
		this.params.pi = min+"-"+max;

		this._updateCatalog();
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._onSelectProperty = function(el) {
		
		var id = $(el).attr("name");
		var value = $(el).val();
		var checked = $(el).attr("checked")=="checked";
		
		if(checked) {
			
			if(this.params.po===null) {
				
				this.params.po = [];
				
			} // end if
			
			this.params.po.push(id+"-"+value);
			
		}else{
				
			this.params.po.splice(this.params.po.indexOf(id+"-"+value),1);
			
			if(this.params.po.length==0) {
				
				this.params.po = null;
				
			} // end if
			
		} // end if

		this._updateCatalog();
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._onSelectManufacturer = function(el) {
		
		var self = this;
		var toggles = $(el).parent().children("input");
		
		this.params.mf = [];
		
		$(toggles).each(function(i, toggle) {
			
			var checked = $(toggle).attr("checked")=="checked";
			var value = $(toggle).val();
			
			if(checked) {
				
				self.params.mf.push(value);
				
			} // end if
			
		}); // end each
		
		if(this.params.mf !== null && this.params.mf.length == 0) {
			
			this.params.mf = null;
			
		} // end if
		
		this._updateCatalog();
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._onSelectStock = function(el) {
		
		if($(el).attr("checked")=="checked") {
			
			this.params.is = "Y";
			
		}else{
			
			this.params.is = null;
			
		} // end if

		this._updateCatalog();
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._updateCatalog = function(el) {
		
		var self = this;
		var url = this._getURI({ajax:"Y"});
		
		this._updateState();		
		this._showLoading();
		
		$.get(url, function(response) {
			
			var catalog = $(response).find("#zibbra-catalog");
			
			$("#zibbra-catalog").replaceWith(catalog);
			
			self._updatePagination();
			self._initSlider();
			self._initProperties();
			self._initManufacturers();
			self._initStock();
			self._initSort();
			self._initLimit();
			self._initToggle();
			
			if(Zibbra.Catalog._arrCallback.length>0) {
				
				for(var i=0; i<Zibbra.Catalog._arrCallback.length; i++) {
					
					Zibbra.Catalog._arrCallback[i](this);
					
				} // end for		
				
			} // end if
			
		}); // end get
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._updateState = function() {
		
		if(typeof(window.history.replaceState)!="undefined") window.history.replaceState("catalog","",this._getURI());
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._updatePagination = function() {
		
		/*
		
		var self = this;
		var timer = null;
		var done = false;
		var container = $("pagination-page");
		
		if(container!==null) {
			
			if(this.params.page===null) {
				
				this.params.page = 1;
				
			} // end if
			
			$("pagination-page").set("value",this.params.page);
			
			var onchange = function() {
				
				if(done) return;
				
				if(timer!==null) {
					
					clearTimeout(timer);
					timer = null;
					
				} // end if
				
				var page = parseInt(this.get("value"));
				
				if(page>Zibbra.Catalog.PAGES) {

					page = Zibbra.Catalog.PAGES;
					this.set("value",Zibbra.Catalog.PAGES);
					
				} // end if
				
				if(page<1) {

					page = 1;
					this.set("value",1);
					
				} // end if
				
				setTimeout(function() { done = false; },500);
				
				timer = setTimeout(function() {
					
					var params = {
						page: page,
						st: self.params.st,
						sd: self.params.sd
					};
					
					var uri = self._getURI(params).toString();
					
					location.href = uri;
					
				},500);
				
			}; // end function
			
			$("pagination-page").addEvent("change",onchange);
			$("pagination-page").addEvent("blur",onchange);
			$("pagination-page").addEvent("keyup",onchange);
			
		} // end if
		
		*/
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._showLoading = function() {
		
		var overlay = $("<div>").addClass("overlay loading").css({
			position: "fixed",
			top: "0px",
			left: "0px",
			right: "0px",
			bottom: "0px",
			backgroundColor: "rgba(255,255,255,.5)",
			zIndex: 1000
		});
		
		$("<span>").addClass("icon icon-loading").appendTo(overlay);
		
		$("#zibbra-catalog").prepend(overlay);
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._getURI = function(o) {
		
		var params = jQuery.extend(true, {}, this.params);
		var data = {};
		
		if(typeof(o)!=="undefined") {
			
			for(var i in o) {
				
				params[i] = o[i];
				
			} // end for
			
		} // end if
		
		for(var i in params) {
			
			if(params[i]!==null) {
					
				data[i] = params[i];
				
			} // end if
				
		} // end for
		
		var uri = location.origin + location.pathname + "?" + $.param(data);
		
		return uri;
		
	}; // end function
	
	Zibbra.Catalog.Filters.prototype._uriToObj = function() {
		
	    var uri = decodeURI(location.search.substr(1));
	    var chunks = uri.split('&');
	    var params = Object();

	    for(var i=0; i < chunks.length ; i++) {
	    	
	        var chunk = chunks[i].split('=');
	        var bracket = chunk[0].search("\\[\\]");
	        
	        if(bracket !== -1) {
	        	
	        	var name = chunk[0].substr(0,bracket);
        		
        		if(typeof params[name] === 'undefined' ) {
        			
        			params[name] = [];
        			
        		} // end if
        		
        		params[name].push(chunk[1]);
	        	
	        }else{
	        	
	            params[chunk[0]] = chunk[1];
	            
	        } // end if
	        
	    } // end for

	    return params;
	    
	}; // end function
	
})(jQuery); // end class