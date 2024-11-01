(function($) {
	
	Zibbra.Album = function() {
		
		this.container = $("#zibbra-product-album");
		
	}; // end function
	
	Zibbra.Album.prototype.init = function() {
		
		this._initPreview();
		
	}; // end function
	
	Zibbra.Album.prototype._initPreview = function() {
		
		var self = this;
		var preview = $(this.container).find(".carousel-inner > .item.active");
		var src = $(preview).find("img").attr("src");
		
		if(typeof(src)!=="undefined") {
			
			var zoom = this.resizeImageUri(src, Zibbra.Album.SIZE_ZOOMED);
			
			$("<img>").attr("src",zoom).one("load", function() {
				
				$(preview).zoom({url: zoom});
				
			}); // end load
			
		} // end if
		
		$('#zibbra-product-album').on("slid.bs.carousel", function () {
			self._initPreview();
		});
		
	}; // end function
	
	Zibbra.Album.prototype.resizeImageUri = function(uri, size) {
		
		var regexp = new RegExp("(width|height)=[0-9]+","g");
		var resized = uri.replace(regexp, "$1="+size);
		
		return resized;
		
	}; // end function

	Zibbra.Album.SIZE_NORMAL = 300;
	Zibbra.Album.SIZE_ZOOMED = 600;
	
})(jQuery); // end class