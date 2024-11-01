<?php

class Zibbra_Plugin_Module_Login extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "login";

	public function getPageTitle() {

		return __("Customer Login", Zibbra_Plugin::LC_DOMAIN);

	} // end function

	public function getModuleName() {

		return self::MODULE_NAME;

	} // end function

	public function getQueryVars() {

		return [];

	} // end function

	public function getRewriteRules() {

		return [
			'zibbra/login/?$' => 'index.php?zibbra='.self::MODULE_NAME
		];

	} // end function

	public function doAjax() {

		return false;

	} // end function

	public function doPost() {

		return false;

	} // end function

	public function doOutput(WP_Query $wp_query, Zibbra_Plugin_Query $z_query) {

		return self::MODULE_NAME;

	} // end function

} // end class