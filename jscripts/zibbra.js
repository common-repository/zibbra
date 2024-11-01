(function($) {
	
	Zibbra = function() {
		
		this.components = {};
        this.site_url = "/";
        this.loader = null;
        this.hooks = {};
		
	}; // end function
	
	Zibbra.prototype.register = function(name, obj) {
		
		this.components[name] = obj;
		
	}; // end function
	
	Zibbra.prototype.init = function() {
		
		for(var i in this.components) {
			
			this.components[i].init();
			
		} // end for
		
	}; // end function
	
	Zibbra.prototype.get = function(name) {
		
		if(typeof(this.components[name])!=="undefined") {
			
			return this.components[name];
			
		} // end if
		
		return false;
		
	}; // end function

    Zibbra.prototype.setSiteUrl = function(site_url) {

        this.site_url = site_url + (site_url.substr(-1)=="/" ? "" : "/");

    }; // end function

    Zibbra.prototype.getAjaxUrl = function() {

        return this.site_url + "wp-admin/admin-ajax.php";

    }; // end function

    /**
     * Register a hook in certain component actions. Available for now:
     *
     * register/submit
     * checkout/submit
     *
     * Example: zibbra.registerHook('register', 'submit', function(finished_callback) { ... do your stuff ... finished_callback(); });
     *
     * @param component
     * @param action
     * @param callback
     */
    Zibbra.prototype.registerHook = function(component, action, callback) {

        if(typeof(this.hooks[component]) === "undefined") {

            this.hooks[component] = {};

        } // end if

        if(typeof(this.hooks[component][action]) === "undefined") {

            this.hooks[component][action] = [];

        } // end if

        if(typeof(callback) === "function") {

            this.hooks[component][action].push(callback);

        } // end if

        console.log("Hook registered", this.hooks);

    }; // end function

    /**
     * Execute a registered hook for a certain component action
     *
     * @param component
     * @param action
     * @param callback
     */
    Zibbra.prototype.executeHook = function(component, action, callback) {

        var hooks_found = false;

        if(typeof(this.hooks[component]) !== "undefined" && typeof(this.hooks[component][action]) !== "undefined") {

            var count = this.hooks[component][action].length;
            var left = count;

            for(var i=0; i<count; i++) {

                hooks_found = true;

                var hook = this.hooks[component][action][i];

                hook(function() {

                    left--;

                    if(left === 0) {

                        callback();

                    } // end if

                }); // end hook

            } // end for

        } // end if

        if(!hooks_found) {

            callback();

        } // end if

    }; // end function

    Zibbra.prototype.showLoader = function($parent, label) {

        this.loader = $("<div>").addClass("overlay loading");
        var $container = $("<div>").addClass("overlay-container").appendTo(this.loader);

        var $label = $("<p>").html(label).appendTo($container);
        var $spinner = $("<i>").addClass("fa fa-spinner fa-pulse fa-3x fa-fw").attr("aria-hidden",true).appendTo($container);

        this.loader.css({
            position: 'absolute',
            left: 0,
            top: 0,
            right: 0,
            bottom: 0,
            zIndex: 99999,
            backgroundColor: 'rgba(255,255,255,0)'
        });

        $label.css({
            marginBottom: '20px'
        });

        $spinner.css({
            marginTop: '20px'
        });

        $container.css({
            position: 'absolute',
            left: '50%',
            top: '50%',
            transform: 'translate(-50%, -50%)',
            textAlign: 'center'
        });

        $parent.children().css({
            opacity: '0.15'
        });

        $parent.prepend(this.loader);

    }; // end function

    Zibbra.prototype.hideLoader = function() {

        this.loader.fadeOut("slow", function() {

            $(this).parent().children().css({
                opacity: 1
            });

            $(this).remove();

        }); // end fadeOut

    }; // end function
	
	Zibbra._ = function(key,value) {
		
		if(typeof(value)==="undefined") {
			
			return Zibbra._t[key];
			
		}else{
			
			Zibbra._t[key] = value;
			
		} // end if
		
	}; // end function

	Zibbra._t = {};
	
})(jQuery); // end class

var zibbra = new Zibbra();

jQuery("document").ready(function() {
	
	zibbra.init();
	
}); // end load