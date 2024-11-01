(function($) {
	
	Zibbra.Register = function() {

		this.validator = null;
		
	}; // end function
	
	Zibbra.Register.prototype.init = function() {
		
		// Register handler for the shipping address toggle
		
		this._initAddressToggle();
		
		// Initialize form validation
		
		this._initFormValidation();
		
	}; // end function
	
	Zibbra.Register.prototype._initAddressToggle = function() {
		
		$("#toggle_same_address").click(function() {
			
			if($(this).is(":checked")) {
				
				$("#hidden_form_shipping").hide();
				
			}else{
				
				$("#hidden_form_shipping").show();
				
			} // end if
			
		}); // end function
		
	}; // end function
	
	Zibbra.Register.prototype._initFormValidation = function() {

		var self = this;
		var $form = $("#zibbra_registration_form");
		
		/*
		jQuery.validator.setDefaults({
			debug: true,
			success: "valid"
		}); // end setDefaults
		*/

		// Register custom validators
		
		this._registerCustomValidators();
		
		// Create the configuration and get the validation rules
		
		var config = {
			rules: this._getValidationRules(),
			invalidHandler: function(form) {

				if(typeof(console.log)!=="undefined") {

					console.log("Invalid fields:",self.validator.errorList);

				} // end if

			}, // end invalidHandler
            submitHandler: function(form) {

                if(typeof(fbq)==="function") {

                    fbq('track', 'CompleteRegistration');

                } // end if

                zibbra.executeHook("register", "submit", function() {

                    document.createElement("form").submit.call(document.getElementById($form.attr("id")));

                }); // end executeHook

				return false;

            } // end submitHandler
		};
		
		// Validate the form

		this.validator = $form.validate(config);
		
	}; // end function
	
	Zibbra.Register.prototype._getValidationRules = function() {

        var account_rules = {};

        if($("#account_password").length == 1) {

            account_rules['account[password]'] = {
                "required": true,
                "password": true
            };

            account_rules['account[confirm_password]'] = {
                "required": true,
                "password": true,
                equalTo: "#account_password"
            };

        } // end if

		var contact_rules = {
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
			}
		};

		var company_rules = {
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

		var billing_rules = {
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

		var shipping_rules = {
			"shipping[street]": {
				"required": true
			},
			"shipping[streetnr]": {
				"required": true,
				"streetnr": true
			},
			"shipping[zipcode]": {
				"required": true,
				"zipcode": true
			},
			"shipping[city]": {
				"required": true
			},
			"shipping[countrycode]": {
				"required": true
			}
		};

		var rules = {};

		for(var i in account_rules) { rules[i] = account_rules[i]; }
		for(var i in contact_rules) { rules[i] = contact_rules[i]; }
		for(var i in company_rules) { rules[i] = company_rules[i]; }
		for(var i in billing_rules) { rules[i] = billing_rules[i]; }
		for(var i in shipping_rules) { rules[i] = shipping_rules[i]; }
		
		return rules;
		
	}; // end function
	
	Zibbra.Register.prototype._registerCustomValidators = function() {

		// Website validation
		
		jQuery.validator.addMethod("website", function(value, element) {
			
			regex = new RegExp("^(http[s]?:\\/\\/(www\\.)?)([0-9A-Za-z-\\.@:%_\+~#=]+)+((\\.[a-zA-Z]{2,3})+)(/(.)*)?(\\?(.)*)?");
			
			return value == "" ? true : regex.test(value);
			
		}); // end addMethod
		
		// Password validation
		
		jQuery.validator.addMethod("password", function(value, element) {
			
			return value.match(/^(?=.*?[a-zA-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-\/\.,]).{8,}$/);
			
		}); // end addMethod
		
		// Zipcode validation
		
		jQuery.validator.addMethod("zipcode", function(value, element) {

			return parseInt(value)!==0 && (value.match(/^[a-z0-9]+[a-z0-9 \-]{3,}$/i)!==null) && (value.match(/[\d]/g)!==null) && (value.match(/[\d]/g).length>=2);
			
		}); // end addMethod
		
		// Streetnr validation
		
		jQuery.validator.addMethod("streetnr", function(value, element) {

			return parseInt(value)!==0 && (value.match(/^[a-z0-9]+.*$/i)!==null) && (value.match(/[\d]/g)!==null);
			
		}); // end addMethod
		
		// Phone validation

		jQuery.validator.addMethod("phonefax", function(value, element) {

			regex = new RegExp("^\\+\\d{1,4}\\s{0,1}\\(\\d{1,4}\\)\\s{0,1}\\d{5,11}$");
			
			return value == "" ? true : regex.test(value);

		}); // end addMethod
		
	}; // end function

	Zibbra.Register.BILLING = "billing";
	Zibbra.Register.SHIPPING = "shipping";
	Zibbra.Register.MODE_ADDRESS = Zibbra.Register.BILLING_FIRST;
	
})(jQuery); // end class

zibbra.register("register", new Zibbra.Register());