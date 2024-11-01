<?php

class Zibbra_Plugin_Module_Checkout extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "checkout";

	const QUERY_VAR_CANCEL = "cancel";
	const QUERY_VAR_CONTINUE = "continue";
	const QUERY_VAR_FINISH = "finish";

	/**
	 * @var ZCart
	 */
	private $cart;

	/**
	 * @var string
	 */
	private $comments;

	/**
	 * @var ZOrder
	 */
	private $order;

	/**
	 * @var ZCustomer
	 */
	private $customer;

	/**
	 * @var ZShippingMethod[]
	 */
	private $shipping_methods;

	/**
	 * @var ZShippingMethod
	 */
	private $current_shipping_method;

	/**
	 * @var ZPaymentAdapter[]
	 */
	private $payment_adapters;

	/**
	 * @var ZPaymentAdapter
	 */
	private $current_payment_adapter;

	public function getPageTitle() {

		return __("Checkout", Zibbra_Plugin::LC_DOMAIN);

	} // end function

	public function getModuleName() {

		return self::MODULE_NAME;

	} // end function

	public function getQueryVars() {

		return [
			self::QUERY_VAR_CANCEL,
			self::QUERY_VAR_CONTINUE,
			self::QUERY_VAR_FINISH
		];

	} // end function

	public function getRewriteRules() {

		return [
			'zibbra/checkout/?$' => 'index.php?zibbra=' . self::MODULE_NAME,
			'zibbra/checkout/cancel/?$' => 'index.php?zibbra=' . self::MODULE_NAME . '&' . self::QUERY_VAR_CANCEL . '=1',
			'zibbra/checkout/continue/?$' => 'index.php?zibbra=' . self::MODULE_NAME . '&' . self::QUERY_VAR_CONTINUE . '=1',
			'zibbra/checkout/finish/?$' => 'index.php?zibbra=' . self::MODULE_NAME . '&' . self::QUERY_VAR_FINISH . '=1'
		];

	} // end function

	public function doAjax() {

		add_action("wp_ajax_zibbra_checkout_update", array($this, "doUpdate"));
		add_action("wp_ajax_nopriv_zibbra_checkout_update", array($this, "doUpdate"));

	} // end function

	public function doPost() {

		if(wp_verify_nonce($_POST[Zibbra_Plugin::FORM_ACTION], "do_apply_voucher")) {

			$this->doApplyVoucher($_POST['voucher']);

		} // end if

		if(wp_verify_nonce($_POST[Zibbra_Plugin::FORM_ACTION], "do_checkout")) {

			$this->doCheckout();

		} // end if

		return false;

	} // end function

	public function doOutput( WP_Query $wp_query, Zibbra_Plugin_Query $z_query ) {

		// Load checkout information

		if(!$this->loadCheckout()) {

			return false;

		} // end if

		// Check if we need to continue checkout process

		if($wp_query->get(self::QUERY_VAR_CONTINUE) === "1") {

			// Dispatch shipping and payment adapter

			$this->dispatchShipping();
			$this->dispatchPayment();
			return true;

		} // end if

		// Check if we need to cancel the order

		if($wp_query->get(self::QUERY_VAR_CANCEL) === "1") {

			$this->doCancel();

		} // end if

		// Check if we need to finish the order

		if($wp_query->get(self::QUERY_VAR_FINISH) === "1") {

			$this->doFinish();

		} // end if

		// Load all info and assign to the templates

		$z_query->init();
		$z_query->set("cart", $this->cart);
		$z_query->set("comments", $this->comments);
		$z_query->set("order", $this->order);
		$z_query->set("customer", $this->customer);
		$z_query->set("shipping_methods", $this->shipping_methods);
		$z_query->set("current_shipping_method", $this->current_shipping_method);
		$z_query->set("payment_adapters", $this->payment_adapters);
		$z_query->set("current_payment_adapter", $this->current_payment_adapter);

		// Load stylesheet and javascript

		wp_enqueue_style( "wp-plugin-zibbra-checkout" , plugins_url("css/checkout.css" , ZIBBRA_BASE_DIR . "/css") );
		wp_enqueue_script( "wp-plugin-zibbra-checkout" , plugins_url("jscripts/checkout.js" , ZIBBRA_BASE_DIR . "/jscripts") );

		// Return template name

		return self::MODULE_NAME;

	} // end function

	public function doCancel() {

		if($this->order instanceof ZOrder) {

			// Cancel the order

			ZOrder::cancel($this->order->getOrderid());

			// Clear session stuff

			$this->adapter->clearSessionValue("orderid");
			$this->adapter->clearSessionValue("comments");

			// Redirect to shopping cart

			wp_redirect(site_url("/zibbra/cart/"));
			exit;

		} // end if

	} // end function

	public function doFinish() {

		// Load checkout information

		if(!$this->loadCheckout()) {

			return false;

		} // end if

		// Get return URL from settings

		$url = get_option("zibbra_checkout_redirect", "/zibbra/account/");
		$url = site_url(empty($url) ? "/zibbra/account/" : $url);
		$url = Zibbra_Plugin_Fb::trackOrderComplete($url);

		// Send confirmation

		//ZOrder::sendConfirmation($this->order->getOrderid(), $this->current_payment_adapter->getId());

		// Reset the cart

		ZCart::reset();

		// Notify the user

		Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_OK, __("Thank you for your order!", Zibbra_Plugin::LC_DOMAIN));

		// Clear session stuff

		$this->adapter->clearSessionValue("orderid");
		$this->adapter->clearSessionValue("comments");
		$this->adapter->clearSessionValue("shipping.methodid");
		$this->adapter->clearSessionValue("shipping.complete");
		$this->adapter->clearSessionValue("shipping.orderid");
		$this->adapter->clearSessionValue("shipping.settings");
		$this->adapter->clearSessionValue("payment.adapterid");
		$this->adapter->clearSessionValue("payment.complete");

		wp_redirect($url);
		exit;

	} // end function

	public function doUpdate() {

		if(isset($_POST['shipping']) || isset($_POST['payment']) || isset($_POST['comments'])) {

			$cart = ZCart::getInstance();

			if(isset($_POST['comments'])) {

				$this->adapter->setSessionValue("comments", $_POST['comments']);

			} // end if

			if(isset($_POST['shipping'])) {

				$cart->setShippingMethod($_POST['shipping']);

			} // end if

			if(isset($_POST['payment'])) {

				$cart->setPaymentAdapter($_POST['payment']);

			} // end if

			$current_shipping_method = $cart->getShippingMethod();
			$current_payment_adapter = $cart->getPaymentAdapter();

			$response = new stdClass();
			$response->total_excl = (float) $cart->getTotalExcl();
			$response->total_vat = (float) $cart->getTotalVat();
			$response->total_incl = (float) $cart->getTotalAmount();
			$response->shipping_cost = (float) $current_shipping_method->getPriceVatIncl();
			$response->payment_cost = (float) $current_payment_adapter->getPrice();

			foreach($response as $key=>&$value) {

				if($value > 0) {

					$value = $cart->getValutaSymbol()." ".number_format($value,2,",","");

				}else{

					$value = false;

				} // end if

			} // end foreach

			header("Content-Type: application/json");
			echo json_encode($response);
			exit;

		} // end if

		return false;

	} // end function

	private function doApplyVoucher($voucher) {

		$cart = ZCart::getInstance();

		if($cart->hasDiscount()) {

			$response = $cart->removeVoucher($voucher);

			if(!$response) {

				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Unable to remove the voucher code", Zibbra_Plugin::LC_DOMAIN));

			}else{

				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_OK, __("Voucher code has been removed", Zibbra_Plugin::LC_DOMAIN));

			} // end if

		}else{

			$response = $cart->applyVoucher($voucher);

			if($response instanceof ZApiError) {

				switch($response->getCode()) {

					case "AXCA_VOUCHER_CUSTOMER_REQUIRED": {

						Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("This is a personally assigned voucher. Please login first.", Zibbra_Plugin::LC_DOMAIN));

					};break;

					case "AXCA_VOUCHER_CUSTOMER_INVALID": {

						Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("This voucher does not belong to your account.", Zibbra_Plugin::LC_DOMAIN));

					};break;

					case "AXCA_VOUCHER_INACTIVE": {

						Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("This voucher is not activated.", Zibbra_Plugin::LC_DOMAIN));

					};break;

					case "AXCA_VOUCHER_NOT_YET_ACTIVE": {

						Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("This voucher is not yet active.", Zibbra_Plugin::LC_DOMAIN));

					};break;

					case "AXCA_VOUCHER_LIMIT_QUANTITY": {

						Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("This voucher has reached its limit of uses.", Zibbra_Plugin::LC_DOMAIN));

					};break;

					case "AXCA_VOUCHER_LIMIT_EXCLUSIVE": {

						Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("This voucher has already been used by your account.", Zibbra_Plugin::LC_DOMAIN));

					};break;

					case "AXCA_VOUCHER_EXPIRED": {

						Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("This voucher has expired.", Zibbra_Plugin::LC_DOMAIN));

					};break;

					case "AXCA_VOUCHER_INSUFFICIENT_AMOUNT": {

						Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Insufficient value for this voucher.", Zibbra_Plugin::LC_DOMAIN));

					};break;

					default: {

						Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Voucher code is invalid, please try again.", Zibbra_Plugin::LC_DOMAIN));

					};break;

				} // end switch

			}elseif(!$response) {

				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("Voucher code is invalid, please try again.", Zibbra_Plugin::LC_DOMAIN));

			}else{

				Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_OK, __("Voucher code has been applied", Zibbra_Plugin::LC_DOMAIN));

			} // end if

		} // end if

		wp_redirect(site_url("/zibbra/checkout/"));
		exit;

	} // end function

	private function doCheckout() {

		// Load checkout information

		if(!$this->loadCheckout()) {

			return false;

		} // end if

		// Create the order

		$this->order = $this->cart->cartToOrder($this->comments);

		if(!$this->order instanceof ZOrder) {

			return false;

		} // end if

		$this->adapter->setSessionValue("orderid", $this->order->getOrderid());

		// Dispatch shipping and payment adapter

		$this->dispatchShipping();
		$this->dispatchPayment();

		return true;

	} // end function

	/**
	 * Load all checkout information
	 */
	private function loadCheckout() {

		if(!$this->loadCart()) {

			return false;

		} // end if

		$this->loadComments();
		$this->loadOrder();
		$this->loadCustomer();

		if(!$this->loadShippingMethods()) {

			return false;

		} // end if

		if(!$this->loadPaymentAdapters()) {

			return false;

		} // end if

		return true;
		
	} // end function

	/**
	 * Load the shopping cart
	 *
	 * @return bool
	 */
	private function loadCart() {

		// Load the cart

		if(($this->cart = ZCart::getInstance())===false) {

			return false;

		} // end if

		// Check of the cart is not empty

		if($this->cart->isEmpty()) {

			// Notify the user

			Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_WARNING, __("Your cart is empty", Zibbra_Plugin::LC_DOMAIN));

			// Redirect to the checkout page

			wp_redirect(site_url("/zibbra/cart/"));
			exit;

		} // end if

		return true;

	} // end function

	/**
	 * Load the order from SESSION if previously created
	 */
	private function loadOrder() {

		$order = null;
		$orderid = $this->adapter->getSessionValue("orderid", null);

		if(is_numeric($orderid)) {

			$order = ZOrder::load($orderid);

		} // end if

		if(!$order instanceof ZOrder) {

			$order = null;

		} // end if

		$this->order = $order;

	} // end function

	/**
	 * Load the logged-in customer
	 */
	private function loadCustomer() {

		$this->customer = null;

		if(is_user_logged_in()) {

			$user = wp_get_current_user();

			if(in_array(Zibbra_Plugin::ROLE, $user->roles) || in_array(Zibbra_Plugin::ROLE, array_keys($user->caps))) {

				$this->customer = ZCustomer::load();

			} // end if

		} // end if

	} // end function

	/**
	 * Load the comments from SESSION if previously defined or from POST
	 */
	private function loadComments() {

		$comments = $this->adapter->getSessionValue("comments", null);

		if(isset($_POST['comments'])) {

			$comments = $_POST['comments'];

		} // end if

		if(empty($comments)) {

			$comments = null;

		} // end if

		$this->comments = $comments;
		$this->adapter->setSessionValue("comments", $comments);

	} // end function

	/**
	 * Load the available shipping methods and select the first on in the list if not yet selected
	 *
	 * @return bool
	 */
	private function loadShippingMethods() {

		if(!$this->cart instanceof ZCart) {

			return false;

		} // end if

		// Load the shipping methods

		$this->shipping_methods = ZShippingMethod::load();

		// Try to load the currently selected shipping method

		$current_shipping_method = $this->cart->getShippingMethod();

		// If no shipping method has been selected before, select the first one

		if($current_shipping_method === false && is_array($this->shipping_methods) && count($this->shipping_methods)>0) {

			$this->cart->setShippingMethod($this->shipping_methods[0]->getShippingmethodid());

			$current_shipping_method = $this->cart->getShippingMethod();

		} // end if

		if($current_shipping_method instanceof ZShippingMethod) {

			$this->current_shipping_method = $current_shipping_method;

		} // end if

		return true;

	} // end function

	/**
	 * Load the available payment adapters and select the first on in the list if not yet selected
	 *
	 * @return bool
	 */
	private function loadPaymentAdapters() {

		if(!$this->cart instanceof ZCart) {

			return false;

		} // end if

		// Try to load the payment adapters

		if(($this->payment_adapters = ZPaymentAdapter::load())===false) {

			return false;

		} // end if

		// Try to load the currently selected payment adapter

		$current_payment_adapter = $this->cart->getPaymentAdapter();

		// If no payment adapter has been selected before, select the first one

		if(!$current_payment_adapter && count($this->payment_adapters)>0) {

			$this->cart->setPaymentAdapter($this->payment_adapters[0]->getId());

			$current_payment_adapter = $this->cart->getPaymentAdapter();

		} // end if

		if($current_payment_adapter instanceof ZPaymentAdapter) {

			$this->current_payment_adapter = $current_payment_adapter;

		} // end if

		return true;

	} // end function

	private function dispatchShipping() {

		if(!$this->current_shipping_method instanceof ZShippingMethod) {

			return false;

		} // end if

		$methodid = $this->current_shipping_method->getShippingmethodid();
		$method_changed = false;

		if($this->adapter->getSessionValue("shipping.methodid", null) != $methodid) {

			$this->adapter->setSessionValue("shipping.methodid", $methodid);
			$this->adapter->setSessionValue("shipping.complete", false);

			$method_changed = true;

		} // end if

		if($this->adapter->getSessionValue("shipping.complete", false) !== true) {

			$adapter = $this->current_shipping_method->getShippingAdapter();
			$adapter_name = "generic";

			if($adapter instanceof ZShippingAdapter) {

				$adapter_name = $adapter->getName();

			} // end if

			wp_redirect(site_url("/zibbra/shipping/select/" . $adapter_name . "/" . $this->order->getOrderid() . "/"));
			exit;

		} // end if

		if($this->adapter->getSessionValue("shipping.complete", false) === true && !$method_changed) {

			$settings = $this->adapter->getSessionValue("shipping.settings", false);
			$orderid = $this->adapter->getSessionValue("shipping.orderid", false);

			if($settings !== false && $orderid !== false && $orderid != $this->order->getOrderid()) {

				// Method is still the same, but a different orderid, so re-submit the shipping settings to the backend

				$this->current_shipping_method->setSettings($settings);
				$this->current_shipping_method->save($this->order->getOrderid());

			} // end if

		} // end if

		return false;

	} // end function

	private function dispatchPayment() {

		if(!$this->current_payment_adapter instanceof ZPaymentAdapter) {

			return false;

		} // end if

		$adapterid = $this->current_payment_adapter->getId();

		if($this->adapter->getSessionValue("payment.adapterid", null) != $adapterid) {

			$this->adapter->setSessionValue("payment.adapterid", $adapterid);
			$this->adapter->setSessionValue("payment.complete", false);

		} // end if

		if($this->adapter->getSessionValue("payment.complete", false) !== true) {

			wp_redirect(site_url("/zibbra/payment/dispatch/" . $adapterid . "/" . $this->order->getOrderid() . "/"));
			exit;

		} // end if

		return false;

	} // end function

	/*

	//todo Consolidate the finishing of the order/reset cart/unset session variables. Same code in this function, and in Zibbra_Plugin_Module_Payment::do_confirm

	private function do_checkout() {

		$oPaymentAdapter = false;
		$oShippingMethod = false;
		$oShippingAdapter = false;

		// Get the adapter

		$adapter = ZLibrary::getInstance()->getAdapter();

		// Check if the order has been created before

		$order = $adapter->getSessionValue("order", false);

		if($order !== false) { // Create order object

			$oOrder = ZOrder::parseItem($order);

		}else{ // Prepare to create a new order

			// Get the comments

			$comments = $_POST['comments'];

			// Get the cart and amount

			$oCart = ZCart::getInstance();
			$amount = $oCart->getTotalAmount();

			// Get the shipping adapter

			$oShippingMethod = $oCart->getShippingMethod();

			if($oShippingMethod instanceof ZShippingMethod) {

				$oShippingAdapter = $oShippingMethod->getShippingAdapter();

			} // end if

			// Get the payment adapter

			if(($payment = $adapter->getSessionValue("payment.adapter",false)) !== false) {

				$oPaymentAdapter = ZPaymentAdapter::get($payment);

			} // end if

			// Create the order

			$oOrder = $oCart->cartToOrder($comments);

			// Store the orderid, number and amount

			if($oOrder instanceof ZOrder) {

				$adapter->setSessionValue("order.id", $oOrder->getOrderid());
				$adapter->setSessionValue("order.number", $oOrder->getNumber());
				$adapter->setSessionValue("order.amount", $amount);

			} // end if

		} // end if

		if($oOrder instanceof ZOrder) {

			// Get the orderid, number and amount

			$orderid = $oOrder->getOrderid();
			$number = $oOrder->getNumber();
			$amount = $oOrder->getAmountIncl();

			// Shipping costs

			if(($shipping_cost = $adapter->getSessionValue("shipping.price",false)) !== false) {

				$amount += ($shipping_cost * 1.21);

			}elseif($oShippingMethod instanceof ZShippingMethod) {

				$amount += $oShippingMethod->getPriceVatIncl();

			} // end if

			// Load the customer

			$oCustomer = ZCustomer::load();

			// Dispatch Shipping

			if($oCustomer && $oShippingAdapter && !$adapter->getSessionValue("shipping.complete",false)) {

				$oShippingAdapter->setSelectUrl(site_url("/zibbra/shipping/select"));
				$oShippingAdapter->setReturnUrl(site_url("/zibbra/shipping/return"));
				$oShippingAdapter->setCancelUrl(site_url("/zibbra/shipping/cancel"));
				$oShippingAdapter->setErrorUrl(site_url("/zibbra/shipping/error"));
				$oShippingAdapter->dispatch($oCustomer, $oOrder);

			} // end if

			// Get the payment adapter

			if($oPaymentAdapter == false && ($payment = $adapter->getSessionValue("payment.adapter",false)) !== false) {

				$oPaymentAdapter = ZPaymentAdapter::get($payment);

			} // end if

			// Dispatch Payment

			if($oPaymentAdapter !== false && !$adapter->getSessionValue("payment.complete",false) && $amount > 0) {

				$dispatch_url = site_url("/zibbra/payment/dispatch/" . $oPaymentAdapter->getId() . "/" . $orderid . "/");
				wp_redirect($dispatch_url);
				exit;

			} // end if

		} // end if

		// Reset checkout progress

		$adapter->clearSessionValue("shipping");
		$adapter->clearSessionValue("shipping.settings");
		$adapter->clearSessionValue("shipping.price");
		$adapter->clearSessionValue("shipping.type");
		$adapter->clearSessionValue("shipping.complete");
		$adapter->clearSessionValue("shipping.".$adapter->getSessionValue("shipping.type"));

		$adapter->clearSessionValue("payment");
		$adapter->clearSessionValue("payment.complete");

		$adapter->clearSessionValue("order");
		$adapter->clearSessionValue("order.id");
		$adapter->clearSessionValue("order.number");
		$adapter->clearSessionValue("order.amount");

		$adapter->clearSessionValue("comments");

		// Redirect

		$uri = site_url(get_option("zibbra_checkout_redirect", "/zibbra/account/"));

		if(!$oOrder instanceof ZOrder) {

			// Notify the user

			Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_ERROR, __("We were unable to complete your order, please try again.", Zibbra_Plugin::LC_DOMAIN));

			// Redirect to the cart

			$uri = site_url("/zibbra/cart/");

		}else{

			// Notify the user

			Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_OK, __("Thank you for your order!", Zibbra_Plugin::LC_DOMAIN));

			// Facebook Pixel tracking

			$uri = Zibbra_Plugin_Fb::trackOrderComplete($uri, $oOrder);

			// Reset the cart

			ZCart::reset();

		} // end if

		wp_redirect($uri);
		exit;

	} // end function

	*/

} // end class