(function($) {
	
	Zibbra.Payment = function() {

        this._orderid = null;
        this._adapter = null;
        this._host = null;
        this._countdown = Zibbra.Payment.TIMEOUT;
        this._loading = false;
        this._timer = null;
		
	}; // end function
	
	Zibbra.Payment.prototype.init = function() {
		
	}; // end function

    Zibbra.Payment.prototype.setOrderId = function(orderid) {

        this._orderid = orderid;

        return this;

    }; // end function

    Zibbra.Payment.prototype.setAdapter = function(adapter) {

        this._adapter = adapter;

        return this;

    }; // end function

    Zibbra.Payment.prototype.setHost = function(host) {

        this._host = host;

        return this;

    }; // end function

    Zibbra.Payment.prototype.check = function() {

        var self = this;
        var uri = zibbra.site_url + "zibbra/payment/status/" + this._adapter + "/" + this._orderid;

        if(Zibbra.Payment.TIMEOUT > 0) {

            Zibbra.Payment.TIMEOUT = Zibbra.Payment.TIMEOUT - (Zibbra.Payment.DELAY / 1000);

            clearTimeout(this._timer);

            $.getJSON(uri, function(response) {

                if(typeof(response.status) !== "undefined" && response.status !== "open") {

                    location.href = response.url;

                } // end if

                self._timer = setTimeout(function() {

                    self.check();

                }, Zibbra.Payment.DELAY);

            }); // end getJSON

        }else{

            location.href = this._host;

        } // end if

        return this;

    }; // end function

    Zibbra.Payment.DELAY = 5000;
    Zibbra.Payment.TIMEOUT = 60;
	
})(jQuery); // end class

zibbra.register("payment", new Zibbra.Payment());