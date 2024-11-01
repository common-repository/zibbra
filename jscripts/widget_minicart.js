(function($) {
	
	Zibbra.Minicart = function() {

        this.popupOpen = false;
		
	}; // end function
	
	Zibbra.Minicart.prototype.init = function() {
		
		var self = this;
		var i;

		for(i=0; i<Zibbra.Minicart.widgets.length; i++) {

			Zibbra.Minicart.widgets[i].init();

		} // end for
		
	}; // end function
	
	Zibbra.Minicart.widgets = [];
	
	Zibbra.Minicart.registerWidget = function(args) {
			
		var minicart = new Zibbra.Minicart.Widget(Zibbra.Minicart.widgets.length, args);
			
		Zibbra.Minicart.widgets.push(minicart);
		
	}; // end function
	
})(jQuery); // end class

(function($) {
	
	Zibbra.Minicart.Widget = function(position, args) {
		
		this.position = position;
		this.title = typeof(args['title'])!=="undefined" ? args['title'] : null;
		this.popup = typeof(args['popup'])!=="undefined" ? args['popup'] : null;
		this.links = typeof(args['links'])!=="undefined" ? args['links'] : null;
        this.site_url = typeof(args['site_url'])!=="undefined" ? args['site_url'] : null;
		this.container = null;
		this.content = null;
		this.callback = null;
		
	}; // end function
	
	Zibbra.Minicart.Widget.prototype.init = function(callback) {
		
		var self = this;

		if(typeof(callback)==="undefined") {

			callback = function() {};

		} // end if

		this.callback = callback;
		
		this.container = $(".zibbra-minicart:nth-of-type("+(this.position + 1)+")");

		if(this.container.length===0) {

			this.container = $("<div>");

		} // end if
		
		this.load(function(response) {
			
			self.onLoadComplete(response);
			
		}); // end load

		return this;
		
	}; // end function
	
	Zibbra.Minicart.Widget.prototype.onLoadComplete = function(response) {
		
		var self = this;
				
		$(this.container).html(response);
		
		this.content = $(this.container).find(".zibbra-minicart-details");

        if(this.popup=="Y") {

            $(this.container).addClass("popup");

        }else{

            $(this.content).remove();

        } // end if
		
		if(this.content!==null) {
				
			$(this.container).unbind();
    	  
			if($("#nav-toggle:visible").length == 0) {
				
				// Desktop version, popout window

                if($(this.container).parent().hasClass("click")) {

                    $(this.container).click(function() {

                        $(self.container).parent().toggleClass("active");

                        self.popupOpen = !self.popupOpen;

                        if(self.popup!=="Y") {

                            window.location.href = self.site_url + "/zibbra/cart";

                        } // end if

                    }); // end click

                }else{

                    $(this.container).live("mouseenter", function() {

                        $(self.container).parent().addClass("active");

                        self.popupOpen = true

                    }); // end mouseenter

                    $(this.container).live("mouseleave", function() {

                        if(self.popupOpen) {

                            $(self.container).parent().removeClass("active");
                            self.popupOpen = false;

                        } // end if

                    }); // end mouseleave

                } // end if

                $("body").click(function(e) {

                    var on_widget = $(e.target).parents(".widget_zibbra_plugin_minicart").length==1;

                    if(self.popupOpen && on_widget) return;

                    if(self.popupOpen) {

                        $(self.container).parent().removeClass("active");
                        self.popupOpen = false;

                    } // end if

                }); // end click
				
			}else{
				
				// Mobile version, redirect to cart
			
				$(this.container).click(function() {
        		  
					window.location.href = self.site_url + "/zibbra/cart";
        		  
				}); // end click
				
			} // end if
    	  
			// Add the number of items in cart
	    	  
			var items = $(this.content).find("ul > li").length;    	  
			$(this.container).find(".icon-cart").attr("data-after", items);
			
			// Add class when empty
			
			if(items==0) {
				
				$(this.container).addClass("empty");
				
			} // end if

			// Callback

			this.callback({
				items: items
			});
		
		} // end if
		
	}; // end function
	
	Zibbra.Minicart.Widget.prototype.load = function(callback) {
		
		var self = this;
		
		$.ajax({
			type: "GET",
			url: "index.php",
			data: {
				zibbra_widget: "minicart",
				minicart_title: this.title,
				minicart_popup: this.popup,
				minicart_links: this.links
			},
			success: function(response) {
				
				self.onLoadComplete(response);
				
			}
		});
		
	}; // end function
	
})(jQuery); // end class

zibbra.register("minicart", new Zibbra.Minicart());