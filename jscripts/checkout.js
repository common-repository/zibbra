(function($) {
	
	Zibbra.Checkout = function() {
		
		this.shipping = null;
		this.payment = null;
		this.comments = null;
        this.terms = null;
		
	}; // end function
	
	Zibbra.Checkout.prototype.init = function() {

		this._initForm();
		this._initShipping();
		this._initPayment();
		this._initVoucher();
		this._initComments();
        this._initAgreeTerms();
        this._initFB();
		this.checkForm();
		
	}; // end function
	
	Zibbra.Checkout.prototype._initForm = function() {
		
		var self = this;
        var $form = $("#zibbra-checkout-form");

        $("#zibbra-checkout-login-form").find("#user_login").attr("type","email").attr("required","");
        $("#zibbra-checkout-login-form").find("#user_pass").attr("required","");

        $form.submit(function(e) {

            e.preventDefault();
			
			if(self.checkForm()) {

                zibbra.showLoader($("#zibbra-checkout"), Zibbra._("PROCESSING"));

                zibbra.executeHook("checkout", "submit", function() {

                    document.createElement("form").submit.call(document.getElementById($form.attr("id")));

                }); // end executeHook
				
			} // end if
			
			return false;
			
		}); // end submit
		
	}; // end function
	
	Zibbra.Checkout.prototype._initShipping = function() {
		
		var self = this;
		var shipping = $("#zibbra-checkout-shipping");
		var values = $(shipping).find("input:checked");
		var toggles = $(shipping).find("input");
		
		if(values.length) {
			
			this.shipping = $(values[0]).val();
			
		}else{
			
			this.shipping = null;
			
		} // end if
		
		$(toggles).click(function() {
			
			if(self.shipping!==$(this).val()) {
				
				self.shipping = $(this).val();
				self.update();
				
			} // end if
			
		}); // end click
		
	}; // end function
	
	Zibbra.Checkout.prototype._initPayment = function() {

		var self = this;
		var payment = $("#zibbra-checkout-payment");
		var values = $(payment).find("input:checked");
		var toggles = $(payment).find("input");
		
		if(values.length) {
			
			this.payment = $(values[0]).val();
			
		}else{
			
			this.payment = null;
			
		} // end if
		
		$(toggles).click(function() {
			
			if(self.payment!==$(this).val()) {
				
				self.payment = $(this).val();
				self.update();
				
			} // end if
			
		}); // end click

	}; // end function

	Zibbra.Checkout.prototype._initVoucher = function() {

		var voucher = $("#zibbra-checkout-voucher");

		if(voucher!==null) {

            var nonce = $(voucher).attr("nonce");
			var input = $(voucher).find("input");
			var button = $(voucher).find("button");

			if(input!==null && button!==null) {

				$(button).click(function(e) {

                    e.preventDefault();

                    var form = $("<form>").attr({action:"/zibbra/checkout",method:"post"});

                    $("<input>").attr({name:"voucher"}).val($(input).val()).appendTo(form);
                    $("<input>").attr({name:"zibbra"}).val(nonce).appendTo(form);

                    $(form).appendTo("body").submit();

                    return false;

				}); // end click

			} // end if

		} // end if

	}; // end function
	
	Zibbra.Checkout.prototype._initComments = function() {
		
		var self = this;
		var comments = $("#zibbra-checkout-comments");
		
		if(comments!==null) {
		
			var textarea = $(comments).find("textarea");
			
			if(textarea!==null) {
			
				$(textarea).change(function() { self._onChangeComments($(this).val()); }); // end chnage
				$(textarea).keyup(function() { self._onChangeComments($(this).val()); }); // end keyup
				$(textarea).blur(function() { self._onChangeComments($(this).val()); }); // end blur
			
			} // end if
		
		} // end if

    }; // end function

    Zibbra.Checkout.prototype._initAgreeTerms = function() {

        var self = this;

        this.terms = $("#agree_terms");

        if(this.terms.length > 0) {

            $(this.terms).click(function() {

                self.checkForm();

            }); // end click

        } // end if
		
	}; // end function

    Zibbra.Checkout.prototype._initFB = function() {

        if(typeof(fbq)==="function") {

            fbq('track', 'InitiateCheckout');

        } // end if

    }; // end function
	
	Zibbra.Checkout.prototype._onChangeComments = function(value) {
		
		if(this.comments!==value) {
			
			this.comments = value;
			
		} // end if
		
	}; // end function
	
	Zibbra.Checkout.prototype.update = function() {
		
		var self = this;
		var data = {
			"action": "zibbra_checkout_update",
			"shipping": this.shipping,
			"payment": this.payment,
			"comments": this.comments
		};
		
		zibbra.showLoader($("#zibbra-checkout"), Zibbra._("LOADING"));
		
		$.post(zibbra.getAjaxUrl(), data, function(response) {
			
			self._onUpdate(response);
			
		}); // end post
		
	}; // end function
	
	Zibbra.Checkout.prototype._onUpdate = function(response) {
				
		for(var i in response) {
				
			// Get reference to the CELL
			
			var td = $("#zibbra-checkout-"+i);
			
			// Show or hide the row and set the value
			
			if(response[i]!==false) {
				
				$(td).html(response[i]);				
				$(td).parent().show();
				
			}else{
				
				$(td).parent().hide();
				
			} // end if
			
		} // end for

        zibbra.hideLoader();
		
	}; // end function
	
	Zibbra.Checkout.prototype.checkForm = function() {
		
		var submit = $("#zibbra-checkout-confirm").find("input[type='submit']");
		var login = $("#user_login");
        var terms = false;

        if($(this.terms).length === 0 || $(this.terms).is(":checked")) {

            terms = true;

        } // end if
		
		if(!terms || $(login).length!=0 || this.shipping==null || this.payment==null) {
			
			submit.attr("disabled","disabled").addClass("disabled");
			return false;
			
		}else{
			
			submit.attr("disabled",null).removeClass("disabled");			
			return true;
			
		} // end if
		
	}; // end function	
	
})(jQuery); // end class

zibbra.register("checkout", new Zibbra.Checkout());