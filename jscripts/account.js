(function($) {
	
	Zibbra.Account = function() {
		
	}; // end function
	
	Zibbra.Account.FORM = false;
	
	Zibbra.Account.prototype.init = function() {
		
		// Initialize form validation
		
		this._initFormValidation();
		
	}; // end function
	
	Zibbra.Account.prototype._initFormValidation = function() {
		
		/*
		
		// Enable for debugging
		
		jQuery.validator.setDefaults({
			debug: true,
			success: "valid"
		}); // end setDefaults
		
		*/
		
		// Register custom validators
		
		this._registerCustomValidators();
		
		// Create the configuration and get the validation rules
		
		var config = {
			"rules": this._getValidationRules()
		};
		
		// Validate the form
		
		$("#zibbra_account_form").validate(config);
		
	}; // end function
	
	Zibbra.Account.prototype._getValidationRules = function() {
		
		var rules = {};
			
		if(Zibbra.Account.FORM=="customer") {
			
			var rules = {
				"contact[firstname]": {
					"required": true
				},
				"contact[lastname]": {
					"required": true
				},
				"contact[mobile]": {
					"required": false,
					"phonefax": true
				},
				"contact[phone]": {
					"required": false,
					"phonefax": true
				},
				"contact[email]": {
					"required": true,
					"email": true
				},
				"company[phone]": {
					"required": false,
					"phonefax": true
				},
				"company[fax]": {
					"required": false,
					"phonefax": true
				},
				"company[website]": {
					"required": false,
					"website": true
				},
				"company[email]": {
					"required": false,
					"email": true
				}
			};
			
		} // end if
			
		if(Zibbra.Account.FORM=="billing") {
			
			var rules = {
				"billing[street]": {
					"required": true
				},
				"billing[streetnr]": {
					"required": true,
					"streetnr": true
				},
				"billing[zipcode]": {
					"required": true,
					"zipcode": true
				},
				"billing[city]": {
					"required": true
				},
				"billing[countrycode]": {
					"required": true
				}
			};
			
		} // end if
			
		if(Zibbra.Account.FORM=="shipping") {
			
			var rules = {
				"shipping[street]": {
					"required": false
				},
				"shipping[streetnr]": {
					"required": false,
					"streetnr": true
				},
				"shipping[zipcode]": {
					"required": false,
					"zipcode": true
				},
				"shipping[city]": {
					"required": false
				},
				"shipping[countrycode]": {
					"required": false
				}
			};
			
		} // end if
		
		return rules;
		
	}; // end function
	
	Zibbra.Account.prototype._registerCustomValidators = function() {

		// Website validation
		
		jQuery.validator.addMethod("website", function(value, element) {
			
			regex = new RegExp("^(http[s]?:\\/\\/(www\\.)?)([0-9A-Za-z-\\.@:%_\+~#=]+)+((\\.[a-zA-Z]{2,3})+)(/(.)*)?(\\?(.)*)?");
			
			return value == "" ? true : regex.test(value);
			
		}); // end addMethod
		
		// Password validation
		
		jQuery.validator.addMethod("password", function(value, element) {
			
			return value.match(/([a-zA-Z])/) && value.match(/([0-9])/) &&  value.length > 8 ? true : false;
			
		}); // end addMethod

		// Check for the shipping toggle
		
		jQuery.validator.addMethod("toggle", function(value, element) {
			
			return $("#toggle_same_address").is(":checked");
			
		}); // end addMethod
		
		// Zipcode validation
		
		jQuery.validator.addMethod("zipcode", function(value, element) {

			return (value.match(/^[a-z0-9]+[a-z0-9 \-]{3,}$/i)!==null) && (value.match(/[\d]/g).length>=3);
			
		}); // end addMethod
		
		// Streetnr validation
		
		jQuery.validator.addMethod("streetnr", function(value, element) {

			return (value.match(/^[a-z0-9]+.*$/i)!==null) && (value.match(/[\d]/g).length>=1);
			
		}); // end addMethod
		
		// Phone validation

		jQuery.validator.addMethod("phonefax", function(value, element) {

			regex = new RegExp("^\\+\\d{1,4}\\s{0,1}\\(\\d{1,4}\\)\\s{0,1}\\d{5,11}$");
			
			return value == "" ? true : regex.test(value);

		}); // end addMethod
		
	}; // end function
	
})(jQuery); // end class

zibbra.register("account", new Zibbra.Account());