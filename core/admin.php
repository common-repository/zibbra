<?php

class Zibbra_Plugin_Admin {
	
	public function __construct() {
		
		// Register custom actions
		
		add_action("admin_init", array(&$this, "admin_init"));
		add_action("admin_menu", array(&$this, "add_menu"));
		
		// Register custom filters
		
		add_filter("plugin_action_links_zibbra/zibbra.php", array(&$this, "plugin_settings_link"));
		
	} // end function
	  
	public function plugin_settings_link($links) {
		
		$settings_link = "<a href=\"options-general.php?page=zibbra_plugin\">Settings</a>";
		
		array_unshift($links,$settings_link);
		
		return $links;
		
	} // end function
	
	public function add_menu() {
		
		add_options_page("Zibbra Settings", "Zibbra", "manage_options", "zibbra_plugin", array(
			&$this,
			"plugin_settings_page" 
		));
		
	} // end function
	
	public function admin_init() {
		
		register_post_type("zibbra");
		
		$this->init_settings();

		// Register custom menu-type for Zibbra categories

		add_meta_box("add-zibbra-categories", __( 'Zibbra Categories' ), array(&$this,'wp_nav_menu_item_zcategory_meta_box'), 'nav-menus', 'side', 'low' );

	} // end function

	public function wp_nav_menu_item_zcategory_meta_box() {

		$categories = ZCategory::getCategories();
		$i = -1;

		echo '<div id="posttype-zibbra-category" class="posttypediv">';
		echo '<div id="tabs-panel-zibbra-category" class="tabs-panel tabs-panel-active">';
		echo '<ul id="zibbra-category-checklist" class="categorychecklist form-no-clear">';

		foreach($categories as $category) {

			if(!$category->hasParent()) {

				$this->meta_box_category($category, $i);

			} // end if

		} // end foreach

		echo '</ul>';
		echo '</div>';
		echo '</div>';
		echo '<p class="button-controls">';
		echo '<span class="list-controls">';
		echo '<a href="/wordpress/wp-admin/nav-menus.php?page-tab=all&amp;selectall=1#posttype-page" class="select-all">Select All</a>';
		echo '</span>';
		echo '<span class="add-to-menu">';
		echo '<input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-zibbra-category">';
		echo '<span class="spinner"></span>';
		echo '</span>';
		echo '</p>';

	} // end function

	public function meta_box_category($category, &$i, $depth=0) {

		echo '<li>';

		if($depth > 0) {

			echo str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $depth);

		} // end if

		echo '<label class="menu-item-title">';
		echo '<input type="checkbox" class="menu-item-checkbox" name="menu-item[' . $i . '][menu-item-object-id]" value="' . ($i * -1) . '"> ' . $category->getName();
		echo '</label>';
		echo '<input type="hidden" class="menu-item-type" name="menu-item[' . $i . '][menu-item-type]" value="custom">';
		echo '<input type="hidden" class="menu-item-title" name="menu-item[' . $i . '][menu-item-title]" value="' . $category->getName() . '">';
		echo '<input type="hidden" class="menu-item-url" name="menu-item[' . $i . '][menu-item-url]" value="' . site_url("/zibbra/catalog/" . $category->getSlug() . "/") . '">';
		echo '<input type="hidden" class="menu-item-classes" name="menu-item[' . $i . '][menu-item-classes]" value="zibbra-category">';
		echo '</li>';

		$i--;

		if($category->hasChildren()) {

			foreach($category->getChildren() as $child) {

				$this->meta_box_category($child, $i, ($depth+1));

			} // end foreach

		} // end if

	} // end function
	
	public function init_settings() {
		
		register_setting("zibbra_plugin-group", "zibbra_api_client_id");
		register_setting("zibbra_plugin-group", "zibbra_api_client_secret");
		register_setting("zibbra_plugin-group", "zibbra_api_env");
		register_setting("zibbra_plugin-group", "zibbra_debug");
		register_setting("zibbra_plugin-group", "zibbra_catalog_products_per_page");
		register_setting("zibbra_plugin-group", "zibbra_catalog_default_sort_type");
		register_setting("zibbra_plugin-group", "zibbra_catalog_default_sort_dir");
		register_setting("zibbra_plugin-group", "zibbra_catalog_grid_list");
		register_setting("zibbra_plugin-group", "zibbra_catalog_show_filters");
		register_setting("zibbra_plugin-group", "zibbra_catalog_filters_collapsed");
		register_setting("zibbra_plugin-group", "zibbra_catalog_header_sidebar");
		register_setting("zibbra_plugin-group", "zibbra_catalog_toolbar_sidebar");
		register_setting("zibbra_plugin-group", "zibbra_catalog_show_stock");
		register_setting("zibbra_plugin-group", "zibbra_catalog_show_stock_backorder");
		register_setting("zibbra_plugin-group", "zibbra_catalog_show_stock_quantity");
		register_setting("zibbra_plugin-group", "zibbra_catalog_category_thumbnail_size");
		register_setting("zibbra_plugin-group", "zibbra_catalog_redirect_addtocart");
		register_setting("zibbra_plugin-group", "zibbra_product_split_properties");
		register_setting("zibbra_plugin-group", "zibbra_product_title_top");
		register_setting("zibbra_plugin-group", "zibbra_product_quantities");
		register_setting("zibbra_plugin-group", "zibbra_cart_incl_vat");
		register_setting("zibbra_plugin-group", "zibbra_cart_link_products");
		register_setting("zibbra_plugin-group", "zibbra_cart_lock_quantity");
		register_setting("zibbra_plugin-group", "zibbra_cart_lock_addons");
		register_setting("zibbra_plugin-group", "zibbra_cart_show_continue_shopping");
		register_setting("zibbra_plugin-group", "zibbra_cart_url_continue_shopping");
		register_setting("zibbra_plugin-group", "zibbra_checkout_redirect");
		register_setting("zibbra_plugin-group", "zibbra_checkout_link_products");
		register_setting("zibbra_plugin-group", "zibbra_checkout_allow_comments");
		register_setting("zibbra_plugin-group", "zibbra_checkout_vouchers");
		register_setting("zibbra_plugin-group", "zibbra_checkout_agree_terms");
		register_setting("zibbra_plugin-group", "zibbra_checkout_url_terms");
        register_setting("zibbra_plugin-group", "zibbra_register_generate_password");
		register_setting("zibbra_plugin-group", "zibbra_register_shipping_first");
		register_setting("zibbra_plugin-group", "zibbra_recaptcha_key");
		register_setting("zibbra_plugin-group", "zibbra_recaptcha_secret");
		register_setting("zibbra_plugin-group", "zibbra_ga_tracking_id");
		register_setting("zibbra_plugin-group", "zibbra_ga_enable_ecommerce");
        register_setting("zibbra_plugin-group", "zibbra_fb_tracking_id");
		register_setting("zibbra_plugin-group", "zibbra_wrapped_theme");
		register_setting("zibbra_plugin-group", "zibbra_bootstrap_container_title");
		register_setting("zibbra_plugin-group", "zibbra_bootstrap_container_content");
		register_setting("zibbra_plugin-group", "zibbra_log_dir");
		register_setting("zibbra_plugin-group", "zibbra_api_client_token_reset", function($value) {
			
			if($value=="Y") {
				
				delete_option("zibbra_api_client_token");
				delete_option("zibbra_api_client_expiry");
				
			} // end if
			
			return false;
			
		});
		
	} // end function
	
	public function plugin_settings_page() {
		
		if(!current_user_can("manage_options")) {
			
			wp_die(__("You do not have sufficient permissions to access this page."));
			
		} // end if
				
		// Render the settings template
		
		include(sprintf("%s/core/admin-form.php", ZIBBRA_BASE_DIR));
		
	} // end function
	
} // end class

?>