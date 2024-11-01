<?php

class Zibbra_Plugin_Widget_Filters extends Zibbra_Plugin_Widget_Abstract {

	public function __construct() {

		$this->name = __("Zibbra Filters", Zibbra_Plugin::LC_DOMAIN);
		$this->description = __("Display catalog filters as a widget", Zibbra_Plugin::LC_DOMAIN);

		parent::__construct();

	} // end function

	public function form($instance) {

		$title = isset($instance['title']) ? $instance['title'] : __("Filters", Zibbra_Plugin::LC_DOMAIN);

		include(sprintf("%s/widgets/filters-form.php", ZIBBRA_BASE_DIR));

	} // end function

	public function update($new_instance, $old_instance) {

		$instance = array();

		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : "";

		return $instance;

	} // end function

	public function widget($args, $instance=null) {

		// Register CSS

		wp_enqueue_style("wp-plugin-zibbra-filters", plugins_url("css/widget_filters.css",ZIBBRA_BASE_DIR."/css"));

		// Load settings

		$vars = array(
			"title" => apply_filters("widget_title", $instance['title'])
		);

		// Output widget

		$this->template_include("filters", $args, $vars);

	} // end function

} // end class