(function($) {
	
	Zibbra.Product = function() {
		
		this.album = null;
		this._variationsCount = 0;
		this._variations = {length:0};
		this._handlers = [];
		
	}; // end function
	
	Zibbra.Product.PRODUCTID = null;
	Zibbra.Product.SHOW_STOCK = true;
	Zibbra.Product.ALLOW_BACKORDER = false;
	
	Zibbra.Product.arrProducts = {};
	
	Zibbra.Product.prototype.init = function() {

	    var self = this;
		
		this._initAlbum();
		this._initVariations();
		this._initTabs();
        this._initAddons();
		
		// Enable Google Analytics Enhanced E-Commerce
		
		this._initGA();

        // Enable Facebook Pixel tracking

        this._initFB();

		// Button click handler

		$("input.zibbra-product-add-to-cart").click(function(e) {

		    e.preventDefault();

            self.addToCart();

            return false;

		}); // end click
		
	}; // end function
	
	Zibbra.Product.prototype._initAlbum = function() {
		
		this.album = new Zibbra.Album();
		this.album.init();
		
	}; // end function
	
	Zibbra.Product.prototype._initVariations = function() {
		
		var self = this;
		var container = $("div.zibbra-product-variations");
		
		if($(container).length==1) {
			
			var variations = $(container).find("select");
			
			if($(variations).length>0) {
				
				// Convert to bootstrap dropdown
				
				$(variations).fakeSelect();
				
				// Store count
				
				this._variationsCount = $(variations).length;
				
				// Disable button
				
				$("input.zibbra-product-add-to-cart").attr("disabled","disabled").addClass("disabled");
				
				// Add change event on the dropdowns
				
				$(variations).each(function(i,el) {
					
					$(el).bind("change", function() {
						
						self._onChangeVariation(parseInt($(this).attr("id").replace("variations_","")), $(this).val());
						
					}); // end click
					
				}); // end each
				
			} // end if
			
		} // end if
		
	}; // end function
	
	Zibbra.Product.prototype._initTabs = function() {
		
		$("#zibbra-product ul.tabs > li > a").click(function() {
			
			var tab = $(this).attr("href");
			
			$("#zibbra-product div.tab").hide();
			$(tab).show();
			
		}); // end click
		
	}; // end function

    Zibbra.Product.prototype._initAddons = function() {

        $(".addon").click(function(e) {

            e.preventDefault();

            if(!$(e.target).is("input")) {

                var amount = parseInt($(this).find("input").val());

				$(this).find("input").val(amount + 1);

            } // end if

            return false;

        }); // end click

    }; // end function
	
	Zibbra.Product.prototype._initGA = function() {
		
		if(typeof(zga)==="function") {

            // Clicks on add-to-cart

            this._handlers.push(function(callback) {

                if(typeof(Zibbra.Product.arrProducts[Zibbra.Product.PRODUCTID])!=="undefined") {

                    var product = Zibbra.Product.arrProducts[Zibbra.Product.PRODUCTID];
                    var data = {
                        "id": product.sku,
                        "name": product.name,
                        "quantity": 1
                    };

                    if(typeof(product.category)!=="undefined") {

                        data.category = product.category;

                    } // end if

                    if(typeof(product.price)!=="undefined") {

                        data.price = product.price;

                    } // end if

                    zga("ec:addProduct", data);
                    zga("ec:setAction", "add");
                    zga("send", "event", "UX", "click", "Add to cart", {
                        "hitCallback": function() {
                            callback();
                        }
                    });

                }else{

                    callback();

                } // end if

            }); // end handler
			
			// Clicks on suggestion links
			
			$("a.zibbra-suggestion-link").click(function(e) {
				
				var id = parseInt($(this).parents("li.zibbra-product-suggestion").attr("id").replace("zibbra-product-",""));
				var uri = $(this).attr("href");
				
				if(typeof(Zibbra.Product.arrProducts[id])!=="undefined") {
					
					e.preventDefault();
					
					var product = Zibbra.Product.arrProducts[id];
					var productFieldObject = {
						"id": product.sku,
						"name": product.name
					};
					var actionFieldObject = {
						"list": "Suggestions"
					};
					
					if(typeof(product.price)!=="undefined") {
						
						productFieldObject.price = product.price;
						
					} // end if
					
					if(typeof(product.position)!=="undefined") {
						
						productFieldObject.position = product.position;
						
					} // end if
					
					zga("ec:addProduct", productFieldObject);
					zga("ec:setAction", "click", actionFieldObject);					
					zga("send", "event", "UX", "click", "Suggestion", {
						"hitCallback": function() {
							document.location = uri;
						}
					});
					
				} // end if
				
			}); // end click
			
		} // end if
		
	}; // end function

    Zibbra.Product.prototype._initFB = function() {

        if(typeof(fbq)==="function") {

            // Clicks on add-to-cart

            this._handlers.push(function(callback) {

                if(typeof(Zibbra.Product.arrProducts[Zibbra.Product.PRODUCTID])!=="undefined") {

                    fbq('track', 'AddToCart');

                    callback();

                } // end if

            }); // end handler

        } // end if

    }; // end function

	Zibbra.Product.prototype._onChangeVariation = function(id, value) {
		
		var self = this;
		
		// Disable button
		
		$("input.zibbra-product-add-to-cart").attr("disabled","disabled").addClass("disabled");
		
		// Update stored variations object
		
		if(value=="") {
			
			if(typeof(this._variations[id])!="undefined") {
				
				delete(this._variations[id]);
				this._variations.length--;
				
			} // end if
			
		}else{
			
			if(typeof(this._variations[id])=="undefined") {

				this._variations.length++;
				
			} // end if
			
			this._variations[id] = parseInt(value);
			
		} // end if
		
		// Enable button
		
		if(this._variations.length==this._variationsCount) {
			
			$("input.zibbra-product-add-to-cart").removeAttr("disabled").removeClass("disabled");
			
		}else if(this._variationsCount>1) {
			
			// Disable dropdowns
			
			$("div.zibbra-product-variations").find("select").attr("disabled","disabled").css("opacity","0.5");
			$("div.zibbra-product-variations").find(".fake-select-wrap").addClass("disabled").css("opacity","0.5");
			
			// Load the variation combinations
			
			var data = null;
			
			if(this._variations.length>0) {
				
				data = jQuery.extend(true, {}, this._variations);
				delete(data.length);
				
			} // end if
			
			var data = {
				"action": "zibbra_product_variations",
				"id": Zibbra.Product.PRODUCTID,
				"variations": data
			};
			
			$.post(zibbra.getAjaxUrl(), data, function(response) {
					
				self._onLoadVariations(response);
				
			}); // end post
			
		} // end if
		
	}; // end function
	
	Zibbra.Product.prototype._onLoadVariations = function(response) {
		
		for(var i=0; i<response.length; i++) {
			
			var cbo = $("#variations_"+response[i].id);
			
			// Empty the dropdown boxes
			
			$(cbo).html("");
			
			// Add the default option
			
			$("<option>").val("-1").html("&nbsp;").appendTo(cbo);
			
			// Add the new options
			
			for(var j=0; j<response[i].options.length; j++) {
				
				// Create the label
				
				var label = response[i].options[j].name;
				
				if(Zibbra.Product.SHOW_STOCK && response[i].options[j].in_stock!=="Y") {
					
					label += " (Out of stock)";
					
				} // end if
				
				// Add the option to the dropdown box
				
				var option = $("<option>").val(response[i].options[j].id).html(label).appendTo(cbo);
				
				// Mark as selected
				
				if(typeof(this._variations[response[i].id])!="undefined" && this._variations[response[i].id]==response[i].options[j].id) {
					
					$(option).attr("selected","selected");
					
				} // end if
				
				// Enable/disable according to in_stock and allow_backorder
				
				if(!Zibbra.Product.SHOW_STOCK && !Zibbra.Product.ALLOW_BACKORDER && response[i].options[j].in_stock!=="Y") {
					
					$(option).attr("disabled","disabled");
					
				} // end if
				
			} // end for
			
			// Enable dropdown again
			
			$(cbo).attr("disabled",null).css("opacity","1");
				
			// Update bootstrap dropdown
					
			$(cbo).fakeSelect();
			
		} // end for
		
		$("div.zibbra-product-variations").find(".fake-select-wrap").removeClass("disabled").css("opacity","1");
		
	}; // end function
	
	Zibbra.Product.prototype._share = function(url) {
		
		var winWidth = Zibbra.Product.SHARE_WINDOW_WIDTH;
		var winHeight = Zibbra.Product.SHARE_WINDOW_HEIGHT;
		var winTop = (screen.height / 2) - (winHeight / 2);
		var winLeft = (screen.width / 2) - (winWidth / 2);
		
		window.open(url, 'sharer', 'top=' + winTop + ', left=' + winLeft + ', toolbar=0, status=0, location=0, menubar=0, directories=0, scrollbars=0, width=' + winWidth + ', height=' + winHeight);
		
	}; // end function
	
	Zibbra.Product.prototype.shareFacebook = function(url, title, descr, image) {
		
		this._share('http://www.facebook.com/sharer.php?s=100&p[title]=' + encodeURIComponent(title) + '&p[summary]=' + encodeURIComponent(descr) + '&p[url]=' + encodeURIComponent(url) + '&p[images][0]=' + encodeURIComponent(image));
      
	}; // end function
	
	Zibbra.Product.prototype.shareTwitter = function(url, title, descr, image) {
		
		this._share('http://twitter.com/share?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent(title) + '&');
		
	}; // end function
	
	Zibbra.Product.prototype.shareGooglePlus = function(url, title, descr, image) {
		
		this._share('https://plus.google.com/share?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent(title) + '&');

    }; // end function

    Zibbra.Product.prototype.addToCart = function() {

        var self = this;

        if(this._handlers.length > 0) {

            for(var i=0; i< this._handlers.length; i++) {

                var last = (i === (self._handlers.length - 1));

                this._handlers[i](function() {

                    if(last) {

                        $("#zibbra-product-form")[0].submit();

                    } // end if

                }); // end handler

            } // end for

        }else{

            $("#zibbra-product-form")[0].submit();

        } // end if
		
	}; // end function
	
	Zibbra.Product.registerProduct = function(data) {
		
		if(typeof(data.id)!=="undefined" && typeof(data.name)!=="undefined") {
			
			Zibbra.Product.arrProducts[data.id] = data;

            // Facebook Pixel tracking

            if(typeof(fbq)==="function") {

                fbq('track', 'ViewContent');

            } // end if
			
		} // end if
		
	}; // end function
	
	Zibbra.Product.SHARE_WINDOW_WIDTH = 520;
	Zibbra.Product.SHARE_WINDOW_HEIGHT = 350;
	
})(jQuery); // end class

zibbra.register("product", new Zibbra.Product());
