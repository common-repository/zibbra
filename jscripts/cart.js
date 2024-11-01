(function($) {
	
	Zibbra.Cart = function() {

        this.$form = null;
        this.timer = null;
        this.items = [];
		
	}; // end function
	
	Zibbra.Cart.prototype.init = function() {

        var self = this;

        this.$form = $("#zibbra-cart-form");

        this._registerHandlers();

        // Store the quantities for each item

        this.$form.find(".zibbra-cart-table input").each(function(i, el) {

            var itemid = parseInt($(this).attr("name").replace("quantity[","").replace("]",""));

            self.items[itemid] = parseInt($(this).val());

        }); // end each
    }; // end function

    Zibbra.Cart.prototype._registerHandlers = function() {

        var self = this;

        this.$form.find(".zibbra-cart-table input").bind("click keyup blur change", function(e) {

            e.preventDefault();

            self._onUpdate(this);

        }); // end click

        this.$form.find(".zibbra-cart-table .zibbra-button").click(function(e) {

            e.preventDefault();

            self._onDelete(this);

        }); // end click

    }; // end function

    Zibbra.Cart.prototype._onUpdate = function(el) {

        var self = this;

        clearTimeout(this.timer);

        this.timer = setTimeout(function() {

            var quantity = parseInt($(el).val());
            var itemid = parseInt($(el).attr("name").replace(/[^0-9]/g,""));
            var data = {
                "action": "zibbra_cart_update",
                "update": itemid,
                "quantity": quantity
            };

            if(self.items[itemid]!==quantity) {

                zibbra.showLoader(self.$form, Zibbra._("UPDATING_CART"));

                $.post(zibbra.getAjaxUrl(), data, function(response) {

                    zibbra.hideLoader();

                    if(response!==false) {

                        location.reload();

                    } // end if

                }); // end post

            } // end if

        },Zibbra.Cart.DELAY);

    }; // end function

    Zibbra.Cart.prototype._onDelete = function(el) {

        var confirmed = confirm($(el).attr("confirm"));

        if(confirmed) {

            zibbra.showLoader(this.$form, Zibbra._("UPDATING_CART"));

            var id = $(el).attr("href").replace(/[^0-9]/g,"");
            var data = {
                "action": "zibbra_cart_update",
                "delete": id
            };

            $.post(zibbra.getAjaxUrl(), data, function(response) {

                zibbra.hideLoader();

                if(response!==false) {

                    location.reload();

                } // end if

            }); // end post

        } // end if
		
	}; // end function

    Zibbra.Cart.DELAY = 350;
	
})(jQuery); // end class

zibbra.register("cart", new Zibbra.Cart());