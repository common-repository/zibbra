<?php

class Zibbra_Plugin_Module_Payment extends Zibbra_Plugin_Module_Abstract implements Zibbra_Plugin_Module_Interface {

	const MODULE_NAME = "payment";

	const QUERY_VAR_ACTION = "action";
	const QUERY_VAR_ADAPTER = "adapter";
	const QUERY_VAR_ORDERID = "orderid";

	const ACTION_DISPATCH = "dispatch";
	const ACTION_RETURN = "return";
	const ACTION_CANCEL = "cancel";
	const ACTION_ERROR = "error";
	const ACTION_NOTIFY = "notify";
	const ACTION_STATUS = "status";

	public function getPageTitle() {

		return __("Payment Verification", Zibbra_Plugin::LC_DOMAIN);

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
			'zibbra/payment/status/([a-z_]{1,})/([0-9]{1,})/?$' => 'index.php?zibbra=' . self::MODULE_NAME . '&' . self::QUERY_VAR_ADAPTER . '=$matches[1]&' . self::QUERY_VAR_ORDERID . '=$matches[2]&' . self::QUERY_VAR_ACTION . '=' . self::ACTION_STATUS,
			'zibbra/payment/dispatch/([a-z_]{1,})/([0-9]{1,})/?$' => 'index.php?zibbra=' . self::MODULE_NAME . '&' . self::QUERY_VAR_ADAPTER . '=$matches[1]&' . self::QUERY_VAR_ORDERID . '=$matches[2]&' . self::QUERY_VAR_ACTION . '=' . self::ACTION_DISPATCH,
			'zibbra/payment/notify/([a-z_]{1,})/([0-9]{1,})/?$' => 'index.php?zibbra=' . self::MODULE_NAME . '&' . self::QUERY_VAR_ADAPTER . '=$matches[1]&' . self::QUERY_VAR_ORDERID . '=$matches[2]&' . self::QUERY_VAR_ACTION . '=' . self::ACTION_NOTIFY,
			'zibbra/payment/return/([a-z_]{1,})/([0-9]{1,})/?$' => 'index.php?zibbra=' . self::MODULE_NAME . '&' . self::QUERY_VAR_ADAPTER . '=$matches[1]&' . self::QUERY_VAR_ORDERID . '=$matches[2]&' . self::QUERY_VAR_ACTION . '=' . self::ACTION_RETURN,
			'zibbra/payment/cancel/([a-z_]{1,})/([0-9]{1,})/?$' => 'index.php?zibbra=' . self::MODULE_NAME . '&' . self::QUERY_VAR_ADAPTER . '=$matches[1]&' . self::QUERY_VAR_ORDERID . '=$matches[2]&' . self::QUERY_VAR_ACTION . '=' . self::ACTION_CANCEL,
			'zibbra/payment/error/([a-z_]{1,})/([0-9]{1,})/?$' => 'index.php?zibbra=' . self::MODULE_NAME . '&' . self::QUERY_VAR_ADAPTER . '=$matches[1]&' . self::QUERY_VAR_ORDERID . '=$matches[2]&' . self::QUERY_VAR_ACTION . '=' . self::ACTION_ERROR,
			'zibbra/payment/return/([a-z_]{1,})/?$' => 'index.php?zibbra=' . self::MODULE_NAME . '&' . self::QUERY_VAR_ADAPTER . '=$matches[1]&' . self::QUERY_VAR_ACTION . '=' . self::ACTION_RETURN,
			'zibbra/payment/cancel/([a-z_]{1,})/?$' => 'index.php?zibbra=' . self::MODULE_NAME . '&' . self::QUERY_VAR_ADAPTER . '=$matches[1]&' . self::QUERY_VAR_ACTION . '=' . self::ACTION_CANCEL,
			'zibbra/payment/error/([a-z_]{1,})/?$' => 'index.php?zibbra=' . self::MODULE_NAME . '&' . self::QUERY_VAR_ADAPTER . '=$matches[1]&' . self::QUERY_VAR_ACTION . '=' . self::ACTION_ERROR
		];

	} // end function

	public function doAjax() {

		return false;

	} // end function

	public function doPost() {

		global $wp_query;

		if (isset( $_POST[Zibbra_Plugin::FORM_ACTION] ) && ($oAdapter = $this->loadAdapter($wp_query)) !== false) {

			return $oAdapter->doPost($oAdapter);

		} // end if

		return false;

	} // end function

	public function doOutput( WP_Query $wp_query, Zibbra_Plugin_Query $z_query ) {

		$action = $wp_query->get(self::QUERY_VAR_ACTION);

		if(!empty($action) && ($oAdapter = $this->loadAdapter($wp_query)) !== false) {

			// Launch the action

			switch($action) {

				case self::ACTION_DISPATCH: return $oAdapter->onDispatch();break;
				case self::ACTION_RETURN: return $oAdapter->onReturn();break;
				case self::ACTION_CANCEL: return $oAdapter->onCancel();break;
				case self::ACTION_ERROR: return $oAdapter->onError();break;
				case self::ACTION_NOTIFY: return $oAdapter->onNotify();break;
				case self::ACTION_STATUS: return $oAdapter->onStatus();break;

			} // end switch

		} // end if

		return false;

	} // end function

	/**
	 * @param WP_Query $wp_query
	 *
	 * @return bool|Zibbra_Plugin_Module_Payment_Abstract
	 * @throws Exception
	 */
	private function loadAdapter(WP_Query $wp_query) {

		$adapterid = $wp_query->get(self::QUERY_VAR_ADAPTER);
		$orderid = $wp_query->get(self::QUERY_VAR_ORDERID);

		if(!empty($adapterid) && !empty($orderid)) {

			// Load objects

			list($order, $customer, $adapter) = $this->loadObjects($orderid, $adapterid);

			// Instantiate the adapter module

			$moduleName = "Zibbra_Plugin_Module_Payment_" . ucfirst(strtolower($adapterid));

			if(!class_exists($moduleName)) {

				throw new Exception("Invalid payment adapter '" . $adapterid . "'");

			} // end if

			/** @var Zibbra_Plugin_Module_Payment_Abstract $oAdapter */
			$oAdapter = new $moduleName($this, $adapter, $order, $customer);

			return $oAdapter;

		} // end if

		return false;

	} // end function

	/**
	 * @param int $orderid
	 * @param string $adapterid
	 * @return [ZOrder,ZCustomer,ZPaymentAdapter]
	 *
	 * @throws Exception
	 */
	private function loadObjects($orderid, $adapterid) {

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

		// Load the payment adapter

		$adapter = ZPaymentAdapter::get($adapterid);

		if(!$adapter instanceof ZPaymentAdapter) {

			throw new Exception("Unable to load payment adapter '" . $adapterid . "' for order with id '" . $orderid . "'");

		} // end if

		return [
			$order,
			$customer,
			$adapter
		];

	} // end function

} // end class