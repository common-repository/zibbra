<?php

class Zibbra_Plugin_Widget_Minicart extends Zibbra_Plugin_Widget_Abstract {
	
	private $cart;
	
	public function __construct() {

		$this->name = __("Zibbra Mini Cart", Zibbra_Plugin::LC_DOMAIN);
		$this->description = __("Show a summary of the shopping cart.", Zibbra_Plugin::LC_DOMAIN);
		
		if(class_exists("ZCart")) {
		
			add_action("init", array($this, "widget_ajax"));
				
			if(($this->cart = ZCart::getInstance())===false) {
			
				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Unable to load the shopping cart", Zibbra_Plugin::LC_DOMAIN));
			
				wp_redirect(site_url("/"));
			    exit;
				
			} // end if
				
			wp_enqueue_script("wp-plugin-zibbra-minicart", plugins_url("jscripts/widget_minicart.js",ZIBBRA_BASE_DIR."/jscripts"));
		
		} // end if

		parent::__construct();
		
	} // end function
	
	public function form($instance) {
		
		$title = isset($instance['title']) ? $instance['title'] : __("Shopping Cart", Zibbra_Plugin::LC_DOMAIN);
		$popup = isset($instance['popup']) ? $instance['popup'] : "Y";
		$links = isset($instance['links']) ? $instance['links'] : "Y";
		$click = isset($instance['click']) ? $instance['click'] : "N";
		
		include(sprintf("%s/widgets/minicart-form.php", ZIBBRA_BASE_DIR));
		
	} // end function
	
	public function update($new_instance, $old_instance) {
		
		$instance = array();
				
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : "";
		$instance['popup'] = (!empty($new_instance['popup'])) ? $new_instance['popup'] : "Y";
		$instance['links'] = (!empty($new_instance['links'])) ? $new_instance['links'] : "Y";
		$instance['click'] = (!empty($new_instance['click'])) ? strip_tags($new_instance['click']) : "N";
	
		return $instance;
		
	} // end function
	
	public function widget_ajax() {

		if(isset($_GET['zibbra_widget']) && $_GET['zibbra_widget']=="minicart") {
			
			$vars = array(
				"title" => sanitize_text_field($_GET['minicart_title']),
				"popup" => $_GET['minicart_popup']=="Y",
				"links" => $_GET['minicart_links']=="Y",
				"click" => $_GET['minicart_click']=="Y",
				"cart"  => $this->cart
			);
			
			$this->template_include("minicart-ajax", array(), $vars);			
			exit;
			
		} // end if
		
	} // end function
	
	public function widget($args, $instance) {
			
		$vars = array(
			"title" => apply_filters("widget_title", $instance['title']),
			"popup" => isset($instance['popup']) ? $instance['popup'] : "Y",
			"links" => isset($instance['links']) ? $instance['links'] : "Y",
			"click" => isset($instance['click']) ? $instance['click']=="Y" : 0
		);

		// Add extra classes depending on the toggles

		$classes = array();

		if($vars['click']) {

			$classes[] = "click";

		}else{

			$classes[] = "hover";

		} // end if

		$args['before_widget'] = str_replace("widget_zibbra_plugin_minicart", "widget_zibbra_plugin_minicart ".implode(" ", $classes), $args['before_widget']);
		
		$this->template_include("minicart", $args, $vars);
		
	} // end function
	
} // end class

?>