<?php

class Zibbra_Plugin_Module_Brand extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "brand";

	public function getPageTitle() {

		return __("Brands", Zibbra_Plugin::LC_DOMAIN);

	} // end function

	public function getModuleName() {

		return self::MODULE_NAME;

	} // end function

	public function getQueryVars() {

		return [];

	} // end function

	public function getRewriteRules() {

		return [
			'zibbra/brands/?$' => 'index.php?zibbra='.self::MODULE_NAME
		];

	} // end function

	public function doAjax() {

		return false;

	} // end function

	public function doPost() {

		return false;

	} // end function

	public function doOutput( WP_Query $wp_query, Zibbra_Plugin_Query $z_query ) {

		// Load stylesheet and javascript

		wp_enqueue_style("wp-plugin-zibbra-brand", plugins_url("css/brand.css",ZIBBRA_BASE_DIR."/css"));

		// Try to load the list of manufacturers

		if(($manufacturers = Zibbra_Plugin_Cache::load(array("ZManufacturer","getManufacturers")))===false) {

			return false;

		} // end if

		// Assign the manufacturers to the query
		// TODO: Improve the Query class to query for manufacturers instead of loading and assigning them here

		$z_query->init();
		$z_query->set("manufacturers", $manufacturers);

		// Return template name

		return self::MODULE_NAME;

	} // end function

} // end class