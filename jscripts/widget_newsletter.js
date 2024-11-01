(function($) {
	
	Zibbra.Newsletter = function() {
		
		this.container = null;
		this.form = null;
		this.field = null;
		this.value = null;
		
	}; // end function
	
	Zibbra.Newsletter.prototype.init = function() {
		
		var self = this;

		this.container = $("#zibbra-newsletter");
		this.form = $("#zibbra-newsletter-form");
		this.field = $("#zibbra-newsletter-email");
		this.value = $(this.field).val();
		
		$(this.field).focus(function() {
			
			if($(this).val()==self.value) $(this).val("");
			
		}); // end focus
		
		$(this.field).blur(function() {
			
			if($(this).val()=="") $(this).val(self.value);
			
		}); // end blur
		
		$(this.form).submit(function(e) {
			
			e.preventDefault();
			
			$(self.container).children("p.message").remove();
			
			$.post(zibbra.getAjaxUrl(), $(this).serialize(), function(response) {
				
				var msg = $("<p>").addClass("message").html(response.message);
				
				if(response.status) {
					
					$(self.container).html(msg);
					
				}else{
					
					$(self.container).prepend(msg);
					
				} // end if
				
			}); // end post
			
		}); // end submit
		
	}; // end function
	
})(jQuery); // end class

zibbra.register("newsletter", new Zibbra.Newsletter());