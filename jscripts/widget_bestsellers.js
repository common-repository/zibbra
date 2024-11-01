(function($) {
	
	Zibbra.Bestsellers = function() {
		
	}; // end function
	
	Zibbra.Bestsellers.arrProducts = {};
	
	Zibbra.Bestsellers.prototype.init = function() {
		
	}; // end function
	
	Zibbra.Bestsellers.prototype.initAnalytics = function() {
		
		var self = this;
		
		if(typeof(zga)==="function") {
			
			// Register bestsellers
			
			for(var id in Zibbra.Bestsellers.arrProducts) {

				var a = {"list":"Bestsellers"};
				var o = self._getProductObject(id);	
				
				zga("ec:addImpression", o);
				
			} // end for
			
			zga("ec:setAction", "view", a);					
			zga("send", "event", "UX", "view", "Bestsellers");
			
			// Clicks on bestseller links
			
			$("a.zibbra-bestseller-link").click(function(e) {
				
				var id = parseInt($(this).parents("li.zibbra-bestseller").attr("id").replace("zibbra-product-",""));
				var uri = $(this).attr("href");
				var a = {"list":"Bestsellers"};
				var o = self._getProductObject(id);
				
				if(o!==false) {
					
					e.preventDefault();
					
					zga("ec:addProduct", o);
					zga("ec:setAction", "click", a);					
					zga("send", "event", "UX", "click", "Bestsellers", {
						"hitCallback": function() {
							document.location = uri;
						}
					});
					
				} // end if
				
			}); // end click
			
		} // end if
		
	}; // end function
	
	Zibbra.Bestsellers.prototype._getProductObject = function(id) {
		
		if(typeof(Zibbra.Bestsellers.arrProducts[id])!=="undefined") {
			
			var product = Zibbra.Bestsellers.arrProducts[id];
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
	
	Zibbra.Bestsellers.registerProduct = function(data) {
		
		if(typeof(data.id)!=="undefined" && typeof(data.name)!=="undefined") {
			
			Zibbra.Bestsellers.arrProducts[data.id] = data;
			
		} // end if
		
	}; // end function
	
})(jQuery); // end class

zibbra.register("bestsellers", new Zibbra.Bestsellers());