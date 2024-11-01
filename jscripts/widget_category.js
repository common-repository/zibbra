(function($) {
	
	Zibbra.Category = function() {
		
	}; // end function
	
	Zibbra.Category.arrProducts = {};
	
	Zibbra.Category.prototype.init = function() {
		
	}; // end function
	
	Zibbra.Category.prototype.initAnalytics = function() {
		
		var self = this;
		
		if(typeof(zga)==="function") {
			
			// Register bestsellers
			
			for(var id in Zibbra.Category.arrProducts) {

				var a = {"list":"Category"};
				var o = self._getProductObject(id);	
				
				zga("ec:addImpression", o);
				
			} // end for
			
			zga("ec:setAction", "view", a);					
			zga("send", "event", "UX", "view", "Category");
			
			// Clicks on bestseller links
			
			$("a.zibbra-bestseller-link").click(function(e) {
				
				var id = parseInt($(this).parents("li.zibbra-bestseller").attr("id").replace("zibbra-product-",""));
				var uri = $(this).attr("href");
				var a = {"list":"Category"};
				var o = self._getProductObject(id);
				
				if(o!==false) {
					
					e.preventDefault();
					
					zga("ec:addProduct", o);
					zga("ec:setAction", "click", a);					
					zga("send", "event", "UX", "click", "Category", {
						"hitCallback": function() {
							document.location = uri;
						}
					});
					
				} // end if
				
			}); // end click
			
		} // end if
		
	}; // end function
	
	Zibbra.Category.prototype._getProductObject = function(id) {
		
		if(typeof(Zibbra.Category.arrProducts[id])!=="undefined") {
			
			var product = Zibbra.Category.arrProducts[id];
			var o = {
				"id": product.sku,
				"name": product.name
			};
			
			if(typeof(product.position)!=="undefined") {
				
				o.position = product.position;
				
			} // end if
			
			return o;
			
		} // end if
		
		return false;
		
	}; // end function
	
	Zibbra.Category.registerProduct = function(data) {
		
		if(typeof(data.id)!=="undefined" && typeof(data.name)!=="undefined") {
			
			Zibbra.Category.arrProducts[data.id] = data;
			
		} // end if
		
	}; // end function
	
})(jQuery); // end class

zibbra.register("category", new Zibbra.Category());