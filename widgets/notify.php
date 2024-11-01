<?php

class Zibbra_Plugin_Widget_Notify extends Zibbra_Plugin_Widget_Abstract {
	
	public function __construct() {

		$this->name = __("Zibbra Notifications", Zibbra_Plugin::LC_DOMAIN);
		$this->description = __("Display notifications.", Zibbra_Plugin::LC_DOMAIN);
		
		parent::__construct();
		
	} // end function
	
	public function form($instance) {
		
	} // end function
	
	public function update($new_instance, $old_instance) {
		
		return array();
		
	} // end function
	
	public function widget($args, $instance=null) {
		
		wp_enqueue_style("wp-plugin-zibbra-notify", plugins_url("css/widget_notify.css",ZIBBRA_BASE_DIR."/css"));
		wp_enqueue_script("wp-plugin-zibbra-notify", plugins_url("jscripts/widget_notify.js",ZIBBRA_BASE_DIR."/jscripts"));
		
		$vars = array(
			"notifications" => Zibbra_Plugin_Notify::getNotifications()
		);
		
		$this->template_include("notify", $args, $vars);
		
	} // end function
	
} // end class

?>