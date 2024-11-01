<?php

class Zibbra_Plugin_Module_Reset extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "reset";

	public function getPageTitle() {

		return __("Reset your password", Zibbra_Plugin::LC_DOMAIN);

	} // end function

	public function getModuleName() {

		return self::MODULE_NAME;

	} // end function

	public function getQueryVars() {

		return [];

	} // end function

	public function getRewriteRules() {

		return [
			'zibbra/reset/?$' => 'index.php?zibbra='.self::MODULE_NAME
		];

	} // end function

	public function doAjax() {

		return false;

	} // end function

	public function doPost() {

		if(wp_verify_nonce($_POST[Zibbra_Plugin::FORM_ACTION], "do_reset")) {

			// Verify captcha

			if(!Zibbra_Plugin_Recaptcha::verifyRecaptcha()) {

				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Captcha was invalid, please try again", Zibbra_Plugin::LC_DOMAIN));
				return false;

			} // end if

			$response = ZCustomerUser::resetPassword($_POST['email']);

			if($response instanceof ZApiError) {

				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("We were unable to reset your password, please try again and check your email address.", Zibbra_Plugin::LC_DOMAIN));

			}else{

				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_OK, __("We have sent instructions to your email address to reset your password.", Zibbra_Plugin::LC_DOMAIN));

			} // end if

			wp_redirect(home_url());
			exit;

		} // end if

		return false;

	} // end function

	public function doOutput( WP_Query $wp_query, Zibbra_Plugin_Query $z_query ) {

		// Initialize Re-Captcha

		Zibbra_Plugin_Recaptcha::initRecaptcha();

		// Register JS

		wp_enqueue_script("wp-plugin-zibbra-jvalidate", plugins_url("jscripts/jquery.validate.min.js", ZIBBRA_BASE_DIR . "/jscripts"));
		wp_enqueue_script("wp-plugin-zibbra-reset", plugins_url("jscripts/reset.js", ZIBBRA_BASE_DIR . "/jscripts"));

		return self::MODULE_NAME;

	} // end function

} // end class