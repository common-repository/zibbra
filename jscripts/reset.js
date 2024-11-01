(function($) {
	
	Zibbra.Reset = function() {
		
	}; // end function
	
	Zibbra.Reset.prototype.init = function() {
		
		// Initialize form validation
		
		this._initFormValidation();
		
	}; // end function
	
	Zibbra.Reset.prototype._initFormValidation = function() {
		
		/*
		
		// Enable for debugging
		
		jQuery.validator.setDefaults({
			debug: true,
			success: "valid"
		}); // end setDefaults
		
		*/
		
		// Create the configuration and get the validation rules
		
		var config = {
			"rules": {
				"email": {
					"required": true,
					"email": true
				}
			}
		};
		
		// Validate the form
		
		$("#zibbra_reset_form").validate(config);
		
	}; // end function
	
})(jQuery); // end class

zibbra.register("reset", new Zibbra.Reset());