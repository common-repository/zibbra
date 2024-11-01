<?php

class Zibbra_Plugin_Module_Register extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "register";

	public function getPageTitle() {

		return __("Register a new customer account", Zibbra_Plugin::LC_DOMAIN);

	} // end function

	public function getModuleName() {

		return self::MODULE_NAME;

	} // end function

	public function getQueryVars() {

		return [];

	} // end function

	public function getRewriteRules() {

		return [
			'zibbra/register/?$' => 'index.php?zibbra='.self::MODULE_NAME
		];

	} // end function

	public function doAjax() {

		return false;

	} // end function

	public function doPost() {

		if(wp_verify_nonce($_POST[Zibbra_Plugin::FORM_ACTION], "do_register")) {

			// Verify captcha

			if(!Zibbra_Plugin_Recaptcha::verifyRecaptcha()) {

				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Captcha was invalid, please try again", Zibbra_Plugin::LC_DOMAIN));
				return false;

			} // end if

			// Sanitize input

			foreach($_POST as $section=>&$data) {

				if(is_array($data)) {

					foreach($data as $key=>&$value) {

						if($key!=="password" && $key!=="confirm_password") {

							$value = sanitize_text_field($value);

						} // end if

					} // end foreach

				} // end if

			} // end foreach

			// Prepare return URL

			$return = isset($_POST['return']) ? esc_url(urldecode($_POST['return']), ['http','https']) : site_url("/zibbra/account/");

			// Check if we need to generate a password

			if(get_option("zibbra_register_generate_password","N")=="Y") {

				$_POST['account']['password'] = ZCustomerUser::generatePassword();
				$_POST['account']['confirm_password'] = $_POST['account']['password'];

			} // end if

			// Save the customer

			if($this->saveCustomer($_POST['account'], $_POST['contact'], $_POST['company'], $_POST['billing'], $_POST['shipping'])) {

				if($this->loginCustomer($_POST['account'], $_POST['contact'])) {

					Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_OK, __("Thank you for registering your account", Zibbra_Plugin::LC_DOMAIN));

				}else{

					Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Your account has been created, but we were unable to login", Zibbra_Plugin::LC_DOMAIN));
					$return = site_url("/");

				} // end if

			}else{

				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Sorry, but we were unable to create your account", Zibbra_Plugin::LC_DOMAIN));
				$return = site_url("/zibbra/register/?return=" . esc_url(urldecode($return)), ['http','https']);

			} // end if

			wp_redirect($return);
			exit;

		} // end if

		return false;

	} // end function

	public function doOutput( WP_Query $wp_query, Zibbra_Plugin_Query $z_query ) {

		// Initialize Re-Captcha

		Zibbra_Plugin_Recaptcha::initRecaptcha();

		// Load css and js scripts

		wp_enqueue_style("wp-plugin-zibbra-register", plugins_url("css/register.css",ZIBBRA_BASE_DIR."/css"));
		wp_enqueue_script("wp-plugin-zibbra-jvalidate", plugins_url("jscripts/jquery.validate.min.js",ZIBBRA_BASE_DIR."/jscripts"));
		wp_enqueue_script("wp-plugin-zibbra-register", plugins_url("jscripts/register.js",ZIBBRA_BASE_DIR."/jscripts"));

		// Get Countries

		$countries = ZCountry::load();

		// Assign the countries to the query
		// TODO: Improve the Query class to query for countries instead of loading and assigning them here

		$z_query->init();
		$z_query->set("countries", $countries);

		if(isset($_POST['contact'])) {

			$contact = ZCustomerContact::parse($_POST['contact']);
			$z_query->set("contact", $contact);

		} // end if

		if(isset($_POST['company'])) {

			$company = ZCustomerCompany::parse($_POST['company'],false);
			$z_query->set("company", $company);

		} // end if

		if(isset($_POST['billing'])) {

			$billing_address = ZCustomerAddress::parse("billing", $_POST['billing'], false);
			$z_query->set("billing_address", $billing_address);

		} // end if

		if(isset($_POST['shipping'])) {

			$shipping_address = ZCustomerAddress::parse("shipping", $_POST['shipping'], false);
			$z_query->set("shipping_address", $shipping_address);

		} // end if

		if(isset($_POST['return'])) {

			$return = urldecode($_POST['return']);
			$z_query->set("return", $return);

		} // end if

		// Return template name

		return self::MODULE_NAME;

	} // end function

	private function saveCustomer($account,$contact,$company,$billing,$shipping) {

		// Get the adapter and languagecode

		$languagecode = $this->adapter->getLanguageCode();

		// Create empty objects

		$oCustomer = new ZCustomer();
		$oUser = new ZCustomerUser();

		// Create company and contact objects

		$oCompany = ZCustomerCompany::parse($company,null);
		$oContact = ZCustomerContact::parse($contact);

		// Create address objects

		$shippingFirst = get_option("zibbra_register_shipping_first","N")=="Y";

		if($shippingFirst && isset($billing['toggle']) && $billing['toggle']=="Y") {

			$oBillingAddress = ZCustomerAddress::parse(ZCustomerAddress::TYPE_BILLING, $shipping, false);
			$oShippingAddress = ZCustomerAddress::parse(ZCustomerAddress::TYPE_SHIPPING, $shipping, false);

		}elseif(!$shippingFirst && isset($shipping['toggle']) && $shipping['toggle']=="Y") {

			$oBillingAddress = ZCustomerAddress::parse(ZCustomerAddress::TYPE_BILLING, $billing,false);
			$oShippingAddress = ZCustomerAddress::parse(ZCustomerAddress::TYPE_SHIPPING, $billing, false);

		}else{

			$oBillingAddress = ZCustomerAddress::parse(ZCustomerAddress::TYPE_BILLING, $billing,false);
			$oShippingAddress = ZCustomerAddress::parse(ZCustomerAddress::TYPE_SHIPPING, $shipping, false);

		} // end if

		// Set the user info

		$oUser->setFirstname($contact['firstname']);
		$oUser->setLastname($contact['lastname']);
		$oUser->setEmail($contact['email']);
		$oUser->setUsername($contact['email']);
		$oUser->setPassword($account['password']);

		// Set the customer info and assign objects

		$oCustomer->setLanguagecode($languagecode);
		$oCustomer->setCompany($oCompany);
		$oCustomer->addContact($oContact);
		$oCustomer->setBillingAddress($oBillingAddress);
		$oCustomer->setShippingAddress($oShippingAddress);
		$oCustomer->addUser($oUser);

		// Save and return

		$response = $oCustomer->save();

		if($response instanceof ZApiError) {

			switch($response->getCode()) {

				case "AXCU_COMPANY_ALREADY_EXISTS":
				case "AXCU_INVALID_PARAM_VALUE":
				case "AXCU_INVALID_VAT_NUMBER":
				case "AXCU_INCOMPLETE_BILLING_ADDRESS":
				case "AXCU_INCOMPLETE_DELIVERY_ADDRESS":
				case "AXCU_EMPTY_VALUE":
				case "AXCU_USER_ALREADY_EXISTS":
				case "AXCU_COULD_NOT_CREATE_USER": {

					$msg = $response->getMessage();

				};break;

				default: {

					$msg = __("Unable to create your account", Zibbra_Plugin::LC_DOMAIN);
					$this->adapter->log(LOG_ERR, "Error when registering a new customer: ".$response->getMessage()." [".$response->getCode()."]");

				};break;

			} // end switch

			Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, $msg);

			return false;

		} // end if

		return true;

	} // end function

	private function loginCustomer($account,$contact) {

		$username = $contact['email'];
		$password = $account['password'];
		$hostname = $_SERVER['HTTP_HOST'];
		$hash = md5($hostname."|".$username);

		$controller = Zibbra_Plugin_Controller::getInstance();
		$controller->authenticate($username, $password);

		if($password!==$hash) {

			return false;

		} // end if

		// Login user on the Wordpress side

		$user = get_userdatabylogin($username);
		wp_set_current_user($user->ID, $username);
		wp_set_auth_cookie($user->ID);
		do_action("wp_login", $username);

		// Check and return

		return is_customer_logged_in();

	} // end function

} // end class