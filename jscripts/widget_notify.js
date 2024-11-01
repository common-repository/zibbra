(function($) {
	
	Zibbra.Notify = function() {

	    this._timer = null;
		
	}; // end function
	
	Zibbra.Notify.prototype.init = function() {

	    clearTimeout(this._timer);

	    this._timer = setTimeout(function() {

	        $("#zibbra-notify").fadeOut("slow", function() {

	            $(this).remove();

            }); // end fadeOut

        }, Zibbra.Notify.TIMEOUT);
		
	}; // end function

    Zibbra.Notify.TIMEOUT = 5000;
	
})(jQuery); // end class

zibbra.register("notify", new Zibbra.Notify());