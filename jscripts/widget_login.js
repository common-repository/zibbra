(function($) {
	
	Zibbra.Login = function() {
		
		this.popupOpen = false;
		
	}; // end function
	
	Zibbra.Login.prototype.init = function() {
		
		var self = this;
		
		$(".widget_zibbra_plugin_login.popup.click > a.toggle").bind("click", function(e) {
			
			e.preventDefault();
			
			$(this).parent().toggleClass("active");
			
			self.popupOpen = !self.popupOpen;
			
			return false;
			
		}); // end click

        $(".widget_zibbra_plugin_login.popup.hover > a.toggle").bind("mouseenter", function(e) {

            e.preventDefault();

            $(this).parent().addClass("active");

            self.popupOpen = true;

            return false;

        }); // end click

        $(".widget_zibbra_plugin_login.popup.hover > a.toggle").bind("mouseleave", function(e) {

            e.preventDefault();

            if(self.popupOpen) {

                $(".widget_zibbra_plugin_login.popup").removeClass("active");
                self.popupOpen = false;

            } // end if

            return false;

        }); // end click
		
		$("body").click(function(e) {
			
			var on_widget = $(e.target).parents(".widget_zibbra_plugin_login.popup").length==1;
			
			if(self.popupOpen && on_widget) return;
			
		    if(self.popupOpen) {
		    	
		    	$(".widget_zibbra_plugin_login.popup").removeClass("active");
		    	self.popupOpen = false;
		        
		    } // end if
		    
		}); // end click
		
	}; // end function
	
})(jQuery); // end class

zibbra.register("login", new Zibbra.Login());