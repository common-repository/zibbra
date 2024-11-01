<?php

class Zibbra_Plugin_Module_Account extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "account";

	const QUERY_VAR_RETURN = "return";
	const QUERY_VAR_EDIT = "edit";
	const QUERY_VAR_VIEW = "view";
	const QUERY_VAR_ID = "id";

	public function getPageTitle() {

		return __("Your account", Zibbra_Plugin::LC_DOMAIN);

	} // end function

	public function getModuleName() {

		return self::MODULE_NAME;

	} // end function

	public function getQueryVars() {

		return [
			self::QUERY_VAR_RETURN,
			self::QUERY_VAR_EDIT,
			self::QUERY_VAR_VIEW,
			self::QUERY_VAR_ID
		];

	} // end function

	public function getRewriteRules() {

		return [
			'zibbra/account/?$' => 'index.php?zibbra='.self::MODULE_NAME,
			'zibbra/account/customer/edit/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_EDIT.'=customer',
			'zibbra/account/customer/edit/\?return=(.*)$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_EDIT.'=customer&'.self::QUERY_VAR_RETURN.'=$matches[1]',
			'zibbra/account/billing/edit/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_EDIT.'=billing',
			'zibbra/account/billing/edit/\?return=(.*)$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_EDIT.'=billing&'.self::QUERY_VAR_RETURN.'=$matches[1]',
			'zibbra/account/shipping/edit/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_EDIT.'=shipping',
			'zibbra/account/shipping/edit/\?return=(.*)$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_EDIT.'=shipping&'.self::QUERY_VAR_RETURN.'=$matches[1]',
			'zibbra/account/order/(.*)/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_VIEW.'=order&'.self::QUERY_VAR_ID.'=$matches[1]',
			'zibbra/account/invoice/(.*)/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_VIEW.'=invoice&'.self::QUERY_VAR_ID.'=$matches[1]',
			'zibbra/account/orders/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_VIEW.'=orders',
			'zibbra/account/invoices/?$' => 'index.php?zibbra='.self::MODULE_NAME.'&'.self::QUERY_VAR_VIEW.'=invoices'
		];

	} // end function

	public function doAjax() {

		return false;

	} // end function

	public function doPost() {

		if(wp_verify_nonce($_POST[Zibbra_Plugin::FORM_ACTION], "update_account")) {

			$customer = ZCustomer::load();
			$section = $_POST['section'];

			// Sanitize input

			foreach($_POST as $key=>&$data) {

				if(is_array($data)) {

					foreach($data as $key2=>&$value) {

						if($key2!=="password" && $key2!=="confirm_password") {

							$value = sanitize_text_field($value);

						} // end if

					} // end foreach

				} // end if

			} // end foreach

			switch($section) {

				case "customer": {

					$company = $customer->getCompany();

					$company->setName($_POST['company']['name']);
					$company->setPhone($_POST['company']['phone']);
					$company->setFax($_POST['company']['fax']);
					$company->setEmail($_POST['company']['email']);
					$company->setWebsite($_POST['company']['website']);
					$company->setVatNr($_POST['company']['vat_nr']);

					$contact = $customer->getPrimaryContact();

					$contact->setFirstname($_POST['contact']['firstname']);
					$contact->setLastname($_POST['contact']['lastname']);

					if(isset($_POST['contact']['gender']) && !empty($_POST['contact']['gender'])) {

						$contact->setGender($_POST['contact']['gender']);

					} // end if

					if(isset($_POST['contact']['mobile']) && !empty($_POST['contact']['mobile'])) {

						$contact->setMobile($_POST['contact']['mobile']);

					} // end if

					if(isset($_POST['contact']['phone']) && !empty($_POST['contact']['phone'])) {

						$contact->setMobile($_POST['contact']['phone']);

					} // end if

					if(isset($_POST['contact']['title']) && !empty($_POST['contact']['title'])) {

						$contact->setGender($_POST['contact']['title']);

					} // end if

				};break;

				case "billing":
				case "shipping": {

					/** @var ZCustomerAddress $address */

					$func = "get".ucfirst($section)."Address";
					$address = $customer->$func();
					$address->setStreet($_POST[$section]['street']);
					$address->setStreetnr($_POST[$section]['streetnr']);
					$address->setBox($_POST[$section]['box']);
					$address->setZipcode($_POST[$section]['zipcode']);
					$address->setCity($_POST[$section]['city']);
					$address->setCountrycode($_POST[$section]['countrycode']);

				};break;

			} // end switch

			$customer->save();

			wp_redirect(isset($_POST['return']) ? esc_url($_POST['return'], ['http', 'https']) : site_url("/zibbra/account/"));
			exit;

		} // end if

		return false;

	} // end function

	public function doOutput(WP_Query $wp_query, Zibbra_Plugin_Query $z_query) {

		// Declare variables

		$customer = true;
		$orders = true;
		$invoices = true;
		$edit = false;
		$template = null;
		$arrCountries = null;
		$oDefaultCountry = null;

		// Check which template to load

		if(($edit = $wp_query->get(self::QUERY_VAR_EDIT))!=="") {

			$template = "edit";
			$orders = false;
			$invoices = false;

			// Get Countries and default country

			if($edit=="billing" || $edit=="shipping") {

				$countries = ZCountry::load();
				$default_country = ZCountry::getDefaultCountry();

			} // end if

			// Register JS

			wp_enqueue_script("wp-plugin-zibbra-jvalidate", plugins_url("jscripts/jquery.validate.min.js",ZIBBRA_BASE_DIR."/jscripts"));
			wp_enqueue_script("wp-plugin-zibbra-account", plugins_url("jscripts/account.js",ZIBBRA_BASE_DIR."/jscripts"));

		} // end if

		if(($view = $wp_query->get(self::QUERY_VAR_VIEW))!=="") {

			switch($view) {

				case "orders": {

					$invoices = false;
					$template = "orders";

				};break;

				case "invoices": {

					$orders = false;
					$template = "invoices";

				};break;

				case "order": {

					ZCustomerOrder::download($wp_query->get(self::QUERY_VAR_ID));
					exit;

				};break;

				case "invoice": {

					ZCustomerInvoice::download($wp_query->get(self::QUERY_VAR_ID));
					exit;

				};break;

			} // end switch

		} // end if

		// Try to load the customer

		if($customer===true && ($customer = ZCustomer::load())===false) {

			Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("You are not allowed to access this page", Zibbra_Plugin::LC_DOMAIN));

			wp_redirect(site_url("/zibbra/login/"));
			exit;

		} // end if

		// Try to load the orders

		if($orders===true) {

			$orders = $customer->getOrders();

		} // end if

		// Try to load the invoices

		if($invoices===true) {

			$invoices = $customer->getInvoices();

		} // end if

		// Assign the data to the query

		$z_query->init();
		$z_query->set("edit", $edit);
		$z_query->set("customer", $customer);
		$z_query->set("orders", $orders);
		$z_query->set("invoices", $invoices);
		$z_query->set("countries", $countries);
		$z_query->set("default_country", $default_country);

		if($edit==="customer") {

			$z_query->set("contact", $customer->getPrimaryContact());
			$z_query->set("company", $customer->getCompany());

		}elseif($edit==="billing") {

			$z_query->set("address", $customer->getBillingAddress());

		}elseif($edit==="shipping") {

			$z_query->set("address", $customer->getShippingAddress());

		} // end if

		// Return template name

		return array(self::MODULE_NAME, $template);

	} // end function

} // end class