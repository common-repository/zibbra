<?php

class Zibbra_Plugin_Widget_Bestsellers extends Zibbra_Plugin_Widget_Abstract {

	const DEFAULT_LIMIT = 3;
	const DEFAULT_THUMB_SIZE = 100;
	
	public function __construct() {

		$this->name = __("Zibbra Bestsellers", Zibbra_Plugin::LC_DOMAIN);
		$this->description = __("Display a listing of the best selling products.", Zibbra_Plugin::LC_DOMAIN);
		
		parent::__construct();
		
	} // end function
	
	public function form($instance) {
		
		$title = isset($instance['title']) ? $instance['title'] : __("Bestsellers", Zibbra_Plugin::LC_DOMAIN);
		$maxval = isset($instance['maxval']) ? $instance['maxval'] : self::DEFAULT_LIMIT;
		$thumbsize = isset($instance['thumbsize']) ? $instance['thumbsize'] : self::DEFAULT_THUMB_SIZE;

		include(sprintf("%s/widgets/bestsellers-form.php", ZIBBRA_BASE_DIR));
		
	} // end function
	
	public function update($new_instance, $old_instance) {
		
		$instance = array();
				
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : "";
		$instance['maxval'] = (!empty($new_instance['maxval'])) ? $new_instance['maxval'] : self::DEFAULT_LIMIT;
		$instance['thumbsize'] = (!empty($new_instance['thumbsize'])) ? $new_instance['thumbsize'] : self::DEFAULT_THUMB_SIZE;
	
		return $instance;
		
	} // end function
	
	public function widget($args, $instance=null) {
		
		// Register JS and CSS
		
		wp_enqueue_style("wp-plugin-zibbra-bestsellers", plugins_url("css/widget_bestsellers.css",ZIBBRA_BASE_DIR."/css"));
		wp_enqueue_script("wp-plugin-zibbra-bestsellers", plugins_url("jscripts/widget_bestsellers.js",ZIBBRA_BASE_DIR."/jscripts"));
		
		// Load settings
		
		$vars = array(
			"title" => apply_filters("widget_title", $instance['title']),
			"maxval" => isset($instance['maxval']) ? $instance['maxval'] : self::DEFAULT_LIMIT,
			"thumbsize" => isset($instance['thumbsize']) ? $instance['thumbsize'] : self::DEFAULT_THUMB_SIZE
		);
		
		// Load bestsellers
			
		$vars['bestsellers'] = Zibbra_Plugin_Cache::load(array("ZProduct","getBestsellers"), array($vars['maxval'], $vars['thumbsize']));
		
		// Output widget
		
		$this->template_include("bestsellers", $args, $vars);
		
	} // end function
	
} // end class

?>