<?php

class Zibbra_Plugin_Module_Search extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "search";

	const QUERY_VAR = "s";

	public function getPageTitle() {

		return __("Search", Zibbra_Plugin::LC_DOMAIN);

	} // end function

	public function getModuleName() {

		return self::MODULE_NAME;

	} // end function

	public function getQueryVars() {

		return [
			self::QUERY_VAR
		];

	} // end function

	public function getRewriteRules() {

		return [];

	} // end function

	public function doAjax() {

		return false;

	} // end function

	public function doPost() {

		return false;

	} // end function

	public function doOutput( WP_Query $wp_query, Zibbra_Plugin_Query $z_query ) {

		if(($searchstring = $wp_query->get(self::QUERY_VAR))!=="") {

			// Load stylesheet and javascript

			wp_enqueue_style("wp-plugin-zibbra-search", plugins_url("css/search.css",ZIBBRA_BASE_DIR."/css"));

			// Search products

			$products = ZProduct::search($searchstring);

			// Assign the products to the query

			$z_query->init();
			$z_query->set("products", $products);

			// Return template name

			return self::MODULE_NAME;

		} // end if

		return false;

	} // end function

} // end class