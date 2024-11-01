<?php

class Zibbra_Plugin_Widget_Brands extends Zibbra_Plugin_Widget_Abstract {
	
	public function __construct() {

		$this->name = __("Zibbra Brands", Zibbra_Plugin::LC_DOMAIN);
		$this->description = __("Display a listing of logos for the manufacturers.", Zibbra_Plugin::LC_DOMAIN);
		
		parent::__construct();
		
	} // end function
	
	public function form($instance) {
		
		$title = isset($instance['title']) ? $instance['title'] : __("Brands", Zibbra_Plugin::LC_DOMAIN);
		$size = isset($instance['size']) ? $instance['size'] : 60;

		include(sprintf("%s/widgets/brands-form.php", ZIBBRA_BASE_DIR));
		
	} // end function
	
	public function update($new_instance, $old_instance) {
		
		$instance = array();
				
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : "";
		$instance['size'] = (!empty($new_instance['size'])) ? strip_tags($new_instance['size']) : "";
	
		return $instance;
		
	} // end function
	
	public function widget($args, $instance=null) {

		wp_enqueue_style("wp-plugin-zibbra-widget-brands", plugins_url("css/widget_brands.css",ZIBBRA_BASE_DIR."/css"));

		define("THUMBNAIL_SIZE", (!empty($instance['size']) ? $instance['size'] : 60));
		
		$vars = array(
			"title" => apply_filters("widget_title", $instance['title']),
			"manufacturers" => Zibbra_Plugin_Cache::load(array("ZManufacturer","getManufacturers"))
		);
		
		$this->template_include("brands", $args, $vars);
		
	} // end function
	
} // end class

?>