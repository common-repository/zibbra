(function($) {
	
	Zibbra.Catalog = function() {
		
		this.filters = null;
		
	}; // end function
	
	Zibbra.Catalog.arrProducts = {};
	
	Zibbra.Catalog.prototype.init = function() {
		
		// Register handler on the dropdown for the product-limit per page
		
		$("#zibbra-catalog-limit").bind("change",function() {
			
			location.href = $(this).find(":selected").attr("uri");
			
		}); // end bind
		
		// Enable Google Analytics Enhanced E-Commerce
		
		this._initAnalytics();
		
		// Initialize the filters
		
		this._initFilters();
		
	}; // end function
	
	Zibbra.Catalog.prototype._initAnalytics = function() {

		if(typeof(zga)==="function" && zga.hasOwnProperty('loaded') && zga.loaded === true) {
			
			// Clicks on product links
			
			$("a.zibbra-product-link").click(function(e) {
				
				var id = parseInt($(this).parents("div.zibbra-catalog-product").attr("id").replace("zibbra-product-",""));
				var uri = $(this).attr("href");
				
				if(typeof(Zibbra.Catalog.arrProducts[id])!=="undefined") {
					
					e.preventDefault();
					
					var product = Zibbra.Catalog.arrProducts[id];
					var productFieldObject = {
						"id": product.sku,
						"name": product.name
					};
					var actionFieldObject = {
						"list": "Catalog"
					};
					
					if(typeof(product.category)!=="undefined") {
						
						productFieldObject.category = product.category;
						
					} // end if
					
					if(typeof(product.brand)!=="undefined") {
						
						productFieldObject.brand = product.brand;
						
					} // end if
					
					if(typeof(product.variant)!=="undefined") {
						
						productFieldObject.variant = product.variant;
						
					} // end if
					
					if(typeof(product.position)!=="undefined") {
						
						productFieldObject.position = product.position;
						
					} // end if
					
					zga("ec:addProduct", productFieldObject);
					zga("ec:setAction", "click", actionFieldObject);
					zga("send", "event", "UX", "click", "Catalog", {
						"hitCallback": function() {
							document.location = uri;
						}
					});

					setTimeout(function() {

						document.location = uri;

					},500);
					
				} // end if
				
			}); // end click
			
		} // end if
		
	}; // end function
	
	Zibbra.Catalog.prototype._initFilters = function() {
		
		this.filters = new Zibbra.Catalog.Filters();
		this.filters.init();
		
	}; // end function
	
	Zibbra.Catalog.registerProduct = function(data) {
		
		if(typeof(data.id)!=="undefined" && typeof(data.name)!=="undefined") {
			
			Zibbra.Catalog.arrProducts[data.id] = data;
			
		} // end if
		
	}; // end function
	
	Zibbra.Catalog.registerCallback = function(func) {
		
		Zibbra.Catalog._arrCallback.push(func);
		
	}; // end function
	
	Zibbra.Catalog._arrCallback = [];
	Zibbra.Catalog.PAGE = 1;
	Zibbra.Catalog.PAGES = 1;
	Zibbra.Catalog.LIMIT = 10;
	Zibbra.Catalog.SORT_TYPE = "times_sold";
	Zibbra.Catalog.SORT_DIR = "desc";
	Zibbra.Catalog.FILTERS_COLLAPSED = false;
	
})(jQuery); // end class

zibbra.register("catalog", new Zibbra.Catalog());