<?php

class Zibbra_Plugin_Module_Shipping extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "shipping";

	const QUERY_VAR_ACTION = "action";
	const QUERY_VAR_ADAPTER = "adapter";
	const QUERY_VAR_ORDERID = "orderid";

	const ACTION_SELECT = "select";
	const ACTION_RETURN = "return";
	const ACTION_CANCEL = "cancel";
	const ACTION_ERROR = "error";

	private $title;

	public function getPageTitle() {

		if(empty($this->title)) {

			$this->title = __("Shipping Configuration", Zibbra_Plugin::LC_DOMAIN);

		} // end if

		return $this->title;

	} // end function

	public function getModuleName() {

		return self::MODULE_NAME;

	} // end function

	public function getQueryVars() {

		return [
			self::QUERY_VAR_ACTION,
			self::QUERY_VAR_ADAPTER,
			self::QUERY_VAR_ORDERID
		];

	} // end function

	public function getRewriteRules() {

		return [
			'zibbra/shipping/select/([a-z_]{1,})/([0-9]{1,})/?$' => 'index.php?zibbra='.self::MODULE_NAME . '&' . self::QUERY_VAR_ADAPTER . '=$matches[1]&' . self::QUERY_VAR_ORDERID . '=$matches[2]&'.self::QUERY_VAR_ACTION . '=' . self::ACTION_SELECT,
			'zibbra/shipping/return/([a-z_]{1,})/([0-9]{1,})/?$' => 'index.php?zibbra='.self::MODULE_NAME . '&' . self::QUERY_VAR_ADAPTER . '=$matches[1]&' . self::QUERY_VAR_ORDERID . '=$matches[2]&'.self::QUERY_VAR_ACTION . '=' . self::ACTION_RETURN,
			'zibbra/shipping/cancel/([a-z_]{1,})/([0-9]{1,})/?$' => 'index.php?zibbra='.self::MODULE_NAME . '&' . self::QUERY_VAR_ADAPTER . '=$matches[1]&' . self::QUERY_VAR_ORDERID . '=$matches[2]&'.self::QUERY_VAR_ACTION . '=' . self::ACTION_CANCEL,
			'zibbra/shipping/error/([a-z_]{1,})/([0-9]{1,})/?$' => 'index.php?zibbra='.self::MODULE_NAME . '&' . self::QUERY_VAR_ADAPTER . '=$matches[1]&' . self::QUERY_VAR_ORDERID . '=$matches[2]&'.self::QUERY_VAR_ACTION . '=' . self::ACTION_ERROR
		];

	} // end function

	public function doAjax() {

		// TODO: Implement doAjax() method.

	} // end function

	public function doPost() {

		// TODO: Implement doPost() method.

	} // end function

	public function doOutput( WP_Query $wp_query, Zibbra_Plugin_Query $z_query ) {

		$action = $wp_query->get(self::QUERY_VAR_ACTION);
		$adapterid = $wp_query->get(self::QUERY_VAR_ADAPTER);
		$orderid = $wp_query->get(self::QUERY_VAR_ORDERID);

		if(!empty($action) && !empty($adapterid) && !empty($orderid)) {

			// Load objects

			list($order, $customer, $method, $adapter) = $this->loadObjects($orderid);

			// Check shipping adapter

			if(!$adapter instanceof ZShippingAdapter) {

				$adapter = null;

			}elseif($adapter->getName() !== $adapterid) {

				throw new Exception("Invalid shipping adapter '" . $adapterid . "'");

			} // end if

			// Instantiate the adapter module

			$moduleName = "Zibbra_Plugin_Module_Shipping_" . ucfirst(strtolower($adapterid));

			if(!class_exists($moduleName)) {

				throw new Exception("Invalid shipping adapter '" . $adapterid . "' (" . $moduleName . ")");

			} // end if

			/** @var  Zibbra_Plugin_Module_Shipping_Abstract $oAdapter */
			$oAdapter = new $moduleName($this, $method, $adapter, $order, $customer);

			// Launch the action

			switch($action) {

				case self::ACTION_SELECT: return $oAdapter->onSelect();break;
				case self::ACTION_RETURN: return $oAdapter->onReturn();break;
				case self::ACTION_CANCEL: return $oAdapter->onCancel();break;
				case self::ACTION_ERROR: return $oAdapter->onError();break;

			} // end switch

		} // end if

		return false;

	} // end function

	/**
	 * @param $orderid
	 *
	 * @return [ZOrder, ZCustomer, ZShippingMethod, ZShippingAdapter]
	 * @throws Exception
	 */
	private function loadObjects($orderid) {

		// Load the order

		$order = ZOrder::load($orderid);

		if(!$order instanceof ZOrder) {

			throw new Exception("Unable to load order with id '" . $orderid . "'");

		} // end if

		// Load the customer

		$customer = $order->getCustomer(true);

		if(!$customer instanceof ZCustomer) {

			throw new Exception("Unable to load customer for order with id '" . $orderid . "'");

		} // end if

		// Load the cart and its adapter

		$cart = ZCart::getInstance();
		$method = $cart->getShippingMethod();

		if(!$method instanceof ZShippingMethod) {

			throw new Exception("Unable to load shipping method from cart (session timeout?)");

		} // end if

		$adapter = $method->getShippingAdapter();

		return [
			$order,
			$customer,
			$method,
			$adapter
		];

	} // end function

} // end class