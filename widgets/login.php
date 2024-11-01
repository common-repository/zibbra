<?php

class Zibbra_Plugin_Widget_Login extends Zibbra_Plugin_Widget_Abstract {
	
	public function __construct() {

		$this->name = __("Zibbra Login", Zibbra_Plugin::LC_DOMAIN);
		$this->description = __("Login widget for customers", Zibbra_Plugin::LC_DOMAIN);

		parent::__construct();
		
	} // end function
	
	public function form($instance) {
		
		$title = isset($instance['title']) ? $instance['title'] : __("Login", Zibbra_Plugin::LC_DOMAIN);
		$icon = isset($instance['icon']) ? $instance['icon'] : null;
		$popup = isset($instance['popup']) ? $instance['popup'] : "N";
		$click = isset($instance['click']) ? $instance['click'] : "N";
		
		include(sprintf("%s/widgets/login-form.php", ZIBBRA_BASE_DIR));
		
	} // end function
	
	public function update($new_instance, $old_instance) {
		
		$instance = array();
				
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : "";
		$instance['popup'] = (!empty($new_instance['popup'])) ? strip_tags($new_instance['popup']) : "N";
		$instance['icon'] = (!empty($new_instance['icon'])) ? strip_tags($new_instance['icon']) : null;
		$instance['click'] = (!empty($new_instance['click'])) ? strip_tags($new_instance['click']) : "N";
	
		return $instance;
		
	} // end function
	
	public function widget($args, $instance) {
		
		$vars = array(
			"title" => apply_filters("widget_title", $instance['title']),
			"icon" => isset($instance['icon']) ? $instance['icon'] : null,
			"popup" => isset($instance['popup']) ? $instance['popup']=="Y" : 0,
			"click" => isset($instance['click']) ? $instance['click']=="Y" : 0
		);		
		
		// Add extra classes depending on the toggles

		$classes = array();
		
		if($vars['popup']) {

			$classes[] = "popup";
			
		} // end if

		if($vars['click']) {

			$classes[] = "click";

		}else{

			$classes[] = "hover";

		} // end if

		$args['before_widget'] = str_replace("widget_zibbra_plugin_login", "widget_zibbra_plugin_login ".implode(" ", $classes), $args['before_widget']);

		// Include CSS & Jscripts
		
		wp_enqueue_style("wp-plugin-zibbra-login", plugins_url("css/widget_login.css",ZIBBRA_BASE_DIR."/css"));
		wp_enqueue_script("wp-plugin-zibbra-login", plugins_url("jscripts/widget_login.js",ZIBBRA_BASE_DIR."/jscripts"));
		
		// Render the widget
		
		$this->template_include("login", $args, $vars);
		
	} // end function
	
} // end class

?>