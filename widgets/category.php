<?php

class Zibbra_Plugin_Widget_Category extends Zibbra_Plugin_Widget_Abstract {

	const ORIENTATION_HORIZONTAL = "horizontal";
	const ORIENTATION_VERTICAL = "vertical";

	const SORT_TYPE_TIMES_SOLD = "times_sold";
	const SORT_TYPE_NAME = "name";
	const SORT_TYPE_PRICE = "price";
	const SORT_TYPE_TIMESTAMP_INSERT = "timestamp_insert";

	const SORT_DIR_ASC = "asc";
	const SORT_DIR_DESC = "desc";

	const DEFAULT_LIMIT = 6;
	const DEFAULT_THUMB_SIZE = 160;
	const DEFAULT_ORIENTATION = self::ORIENTATION_HORIZONTAL;
	const DEFAULT_CATEGORYID = null;
	const DEFAULT_SORT_TYPE = self::SORT_TYPE_TIMES_SOLD;
	const DEFAULT_SORT_DIR = self::SORT_DIR_ASC;
	const DEFAULT_SHOW_DESCRIPTION = "Y";

	public function __construct() {

		$this->name = __("Zibbra Category", Zibbra_Plugin::LC_DOMAIN);
		$this->description = __("Display a listing of products from a specific category", Zibbra_Plugin::LC_DOMAIN);

		parent::__construct();

	} // end function

	public function form($instance) {

		$title = isset($instance['title']) ? $instance['title'] : __("In the spotlight", Zibbra_Plugin::LC_DOMAIN);
		$maxval = isset($instance['maxval']) ? $instance['maxval'] : self::DEFAULT_LIMIT;
		$thumbsize = isset($instance['thumbsize']) ? $instance['thumbsize'] : self::DEFAULT_THUMB_SIZE;
		$orientation = isset($instance['orientation']) ? $instance['orientation'] : self::DEFAULT_ORIENTATION;
		$categoryid = isset($instance['categoryid']) ? $instance['categoryid'] : self::DEFAULT_CATEGORYID;
		$sort_type = isset($instance['sort_type']) ? $instance['sort_type'] : self::DEFAULT_SORT_TYPE;
		$sort_dir = isset($instance['sort_dir']) ? $instance['sort_dir'] : self::DEFAULT_SORT_DIR;
		$description = isset($instance['description']) ? $instance['description'] : self::DEFAULT_SHOW_DESCRIPTION;
		$sort_type_options = array(
			self::SORT_TYPE_TIMES_SOLD => __("Best buy", Zibbra_Plugin::LC_DOMAIN),
			self::SORT_TYPE_NAME => __("Name", Zibbra_Plugin::LC_DOMAIN),
			self::SORT_TYPE_PRICE => __("Price", Zibbra_Plugin::LC_DOMAIN),
			self::SORT_TYPE_TIMESTAMP_INSERT => __("Newest", Zibbra_Plugin::LC_DOMAIN)
		);
		$sort_dir_options = array(
			self::SORT_DIR_ASC => __("Ascending", Zibbra_Plugin::LC_DOMAIN),
			self::SORT_DIR_DESC => __("Descending", Zibbra_Plugin::LC_DOMAIN)
		);
		$show_description_options = array(
			"Y" => __("Yes", Zibbra_Plugin::LC_DOMAIN),
			"N" => __("No", Zibbra_Plugin::LC_DOMAIN)
		);
		$categories = Zibbra_Plugin_Cache::load(array("ZCategory","getCategories"));

		include(sprintf("%s/widgets/category-form.php", ZIBBRA_BASE_DIR));

	} // end function

	public function update($new_instance, $old_instance) {

		$instance = array();

		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : "";
		$instance['maxval'] = (!empty($new_instance['maxval'])) ? $new_instance['maxval'] : self::DEFAULT_LIMIT;
		$instance['thumbsize'] = (!empty($new_instance['thumbsize'])) ? $new_instance['thumbsize'] : self::DEFAULT_THUMB_SIZE;
		$instance['orientation'] = (!empty($new_instance['orientation'])) ? $new_instance['orientation'] : self::DEFAULT_ORIENTATION;
		$instance['categoryid'] = (!empty($new_instance['categoryid'])) ? $new_instance['categoryid'] : self::DEFAULT_CATEGORYID;
		$instance['sort_type'] = (!empty($new_instance['sort_type'])) ? $new_instance['sort_type'] : self::DEFAULT_SORT_TYPE;
		$instance['sort_dir'] = (!empty($new_instance['sort_dir'])) ? $new_instance['sort_dir'] : self::DEFAULT_SORT_DIR;
		$instance['description'] = (!empty($new_instance['description'])) ? $new_instance['description'] : self::DEFAULT_SHOW_DESCRIPTION;

		return $instance;

	} // end function

	public function widget($args, $instance=null) {

		// Register JS and CSS

		wp_enqueue_script("wp-plugin-zibbra-category", plugins_url("jscripts/widget_category.js",ZIBBRA_BASE_DIR."/jscripts"));

		// Load settings

		$vars = array(
			"title" => apply_filters("widget_title", $instance['title']),
			"maxval" => isset($instance['maxval']) ? $instance['maxval'] : self::DEFAULT_LIMIT,
			"thumbsize" => isset($instance['thumbsize']) ? $instance['thumbsize'] : self::DEFAULT_THUMB_SIZE,
			"orientation" => isset($instance['orientation']) ? $instance['orientation'] : self::DEFAULT_ORIENTATION,
			"categoryid" => isset($instance['categoryid']) ? $instance['categoryid'] : self::DEFAULT_CATEGORYID,
			"sort_type" => isset($instance['sort_type']) ? $instance['sort_type'] : self::DEFAULT_SORT_TYPE,
			"sort_dir" => isset($instance['sort_dir']) ? $instance['sort_dir'] : self::DEFAULT_SORT_DIR,
			"description" => (isset($instance['description']) ? $instance['description'] : self::DEFAULT_SHOW_DESCRIPTION) === "Y"
		);

		// Check if configured correctly

		if(!empty($vars['categoryid'])) {

			// Load products

			$pagination = array(
				"limit"=>$vars['maxval']
			);
			$opts = array(
				"get_prices" => true
			);
			$vars['products'] = ZProduct::getProducts($pagination, (int) $vars['categoryid'], $vars['sort_type'], $vars['sort_dir'], $opts);

			// Output widget

			$this->template_include("category", $args, $vars);

		} // end if

	} // end function

} // end class