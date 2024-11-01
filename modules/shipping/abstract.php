<?php

abstract class Zibbra_Plugin_Module_Shipping_Abstract implements Zibbra_Plugin_Module_Shipping_Interface {

	const STATUS_PENDING = "pending";
	const STATUS_CANCELLED = "cancelled";
	const STATUS_FINISHED = "finished";
	const STATUS_ERROR = "error";

	/**
	 * @var Zibbra_Plugin_Module_Shipping
	 */
	protected $module;

	/**
	 * @var ZShippingMethod
	 */
	protected $method;

	/**
	 * @var ZShippingAdapter
	 */
	protected $adapter;

	/**
	 * @var ZOrder
	 */
	protected $order;

	/**
	 * @var ZCustomer
	 */
	protected $customer;

	/**
	 * Zibbra_Plugin_Module_Shipping_Abstract constructor.
	 *
	 * @param Zibbra_Plugin_Module_Shipping $module
	 * @param ZShippingMethod $method
	 * @param ZShippingAdapter $adapter
	 * @param ZOrder $order
	 * @param ZCustomer $customer
	 */
	public function __construct(Zibbra_Plugin_Module_Shipping $module, ZShippingMethod $method, ZShippingAdapter $adapter = null, ZOrder $order, ZCustomer $customer) {

		$this->module = $module;
		$this->method = $method;
		$this->adapter = $adapter;
		$this->order = $order;
		$this->customer = $customer;

	} // end function

	public function onCancel() {

		if($this->order instanceof ZOrder) {

			$this->setStatus(self::STATUS_CANCELLED);

			// Notify the user

			Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_OK, __("Your shipping configuration has been cancelled", Zibbra_Plugin::LC_DOMAIN));

			// Redirect to checkout again

			wp_redirect(site_url("/zibbra/checkout/"));
			exit;

		} // end if

		return false;

	} // end function

	public function onError() {

		if($this->order instanceof ZOrder) {

			$this->setStatus(self::STATUS_ERROR);

			// Notify the user

			Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_OK, __("There was a problem processing your shipping configuration, please try again", Zibbra_Plugin::LC_DOMAIN));

			// Redirect to checkout again

			wp_redirect(site_url("/zibbra/checkout/"));
			exit;

		} // end if

		return false;

	} // end function

	public function onReturn() {

		return false;

	} // end function

	protected function getSelectUrl() {

		return site_url("/zibbra/shipping/select/" . $this->adapter->getName() . "/" . $this->order->getOrderid() . "/");

	} // end function

	protected function getReturnUrl() {

		$return_url = $this->adapter->getReturnUrl($this->order->getOrderid());

		if(empty($return_url)) {

			$return_url = site_url("/zibbra/shipping/return/" . $this->adapter->getName() . "/" . $this->order->getOrderid() . "/");

		} // end if

		return $return_url;

	} // end function

	protected function getCancelUrl() {

		$cancel_url = $this->adapter->getCancelUrl($this->order->getOrderid());

		if(empty($cancel_url)) {

			$cancel_url = site_url("/zibbra/shipping/cancel/" . $this->adapter->getName() . "/" . $this->order->getOrderid() . "/");

		} // end if

		return $cancel_url;

	} // end function

	protected function getErrorUrl() {

		$error_url = $this->adapter->getErrorUrl($this->order->getOrderid());

		if(empty($error_url)) {

			$error_url = site_url("/zibbra/shipping/error/" . $this->adapter->getName() . "/" . $this->order->getOrderid() . "/");

		} // end if

		return $error_url;

	} // end function

	protected function setStatus($status) {

		// Validate the status

		if(!in_array($status, [self::STATUS_PENDING, self::STATUS_CANCELLED, self::STATUS_ERROR, self::STATUS_FINISHED])) {

			throw new Exception("Invalid status");

		} // end if

		// Get the Zibbra adapter

		$adapter = ZLibrary::getInstance()->getAdapter();

		// Store the status

		$adapter->setPersistentValue("shipping.". $this->order->getOrderid() . "." . $this->getAdapterName() . ".status", $status);

		// Store session when finished

		if($status === self::STATUS_FINISHED) {

			$adapter->setSessionValue("shipping.orderid", $this->order->getOrderid());
			$adapter->setSessionValue("shipping.methodid", $this->method->getShippingmethodid());
			$adapter->setSessionValue("shipping.settings", $this->method->getSettings());
			$adapter->setSessionValue("shipping.complete", true);

		} // end if

	} // end function

	protected function getStatus() {

		// Get the Zibbra adapter

		$adapter = ZLibrary::getInstance()->getAdapter();

		// Get the status

		$status = $adapter->getPersistentValue("shipping.". $this->order->getOrderid() . "." . $this->getAdapterName() . ".status");

		// Validate

		if(!in_array($status, [self::STATUS_PENDING, self::STATUS_CANCELLED, self::STATUS_ERROR, self::STATUS_FINISHED])) {

			$status = self::STATUS_PENDING;

		} // end if

		return $status;

	} // end function

	protected function isStatusPending() {

		return $this->getStatus() === self::STATUS_PENDING;

	} // end function

	protected function isStatusFinished() {

		return $this->getStatus() === self::STATUS_FINISHED;

	} // end function

	protected function isStatusCancelled() {

		return $this->getStatus() === self::STATUS_CANCELLED;

	} // end function

	protected function isStatusError() {

		return $this->getStatus() === self::STATUS_ERROR;

	} // end function

	/**
	 * Log data
	 *
	 * @param mixed $request
	 * @param mixed $response
	 */
	protected function logData($request, $response) {

		$adapter = ZLibrary::getInstance()->getAdapter();
		$logdir = $adapter->getLogDir();
		$logfile = $logdir . "/" . $this->getAdapterName() . "-" . date("Ymd") . ".log";
		$data = "";

		if(is_file($logfile)) {

			$data = file_get_contents($logfile);

		} // end if

		$trace = debug_backtrace();
		$caller = $trace[1];

		$txt = "Date: " . date("d/m/Y H:i:s") . "\n";
		$txt .= "Caller: " . $caller['class'] . "::" . $caller['function'] . "\n";
		$txt .= "Request: " . var_export($request, true) . "\n";
		$txt .= "Response: " . var_export($response, true) . "\n";
		$txt .= "\n";

		$data = $txt . $data;

		file_put_contents($logfile, $data);

	} // end function

} // end class