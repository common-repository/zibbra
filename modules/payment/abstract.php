<?php

abstract class Zibbra_Plugin_Module_Payment_Abstract implements Zibbra_Plugin_Module_Payment_Interface {

	const STATUS_OPEN = "open";
	const STATUS_PENDING = "pending";
	const STATUS_CANCELLED = "cancelled";
	const STATUS_PAID = "paid";
	const STATUS_ERROR = "error";

	/**
	 * @var Zibbra_Plugin_Module_Payment
	 */
	protected $module;

	/**
	 * @var ZPaymentAdapter
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
	 * Zibbra_Plugin_Module_Payment_Abstract constructor.
	 *
	 * @param Zibbra_Plugin_Module_Payment $module
	 * @param ZPaymentAdapter $adapter
	 * @param ZOrder $order
	 * @param ZCustomer $customer
	 */
	public function __construct(Zibbra_Plugin_Module_Payment $module, ZPaymentAdapter $adapter, ZOrder $order, ZCustomer $customer) {

		$this->module = $module;
		$this->adapter = $adapter;
		$this->order = $order;
		$this->customer = $customer;
		$this->customer = $customer;

	} // end function

	public function doPost() {

		return false;

	} // end function

	public function onReturn() {

		/** @var Zibbra_Plugin_Query $z_query */
		global $z_query;

		// Check the status

		if($this->isStatusCancelled()) {

			return $this->onCancel();

		} // end if

		if($this->isStatusError()) {

			return $this->onError();

		} // end if

		$z_query->init();
		$z_query->set("orderid", $this->order->getOrderid());
		$z_query->set("adapter", $this->getAdapterName());
		$z_query->set("host", site_url());

		// Set the title

		$this->title = __("Validating payment", Zibbra_Plugin::LC_DOMAIN);

		// Load stylesheet and javascript

		wp_enqueue_style("wp-plugin-zibbra-payment", plugins_url("css/payment.css",ZIBBRA_BASE_DIR."/css"));
		wp_enqueue_script("wp-plugin-zibbra-payment", plugins_url("jscripts/payment.js",ZIBBRA_BASE_DIR."/jscripts"), $deps = array(), "1.6.0");

		// Return template name

		return Zibbra_Plugin_Module_Payment::MODULE_NAME . "-verify";

	} // end function

	public function onCancel($message = null) {

		if($this->order instanceof ZOrder) {

			// Cancel the order

			ZOrder::cancel($this->order->getOrderid());

			// Clear session stuff

			ZLibrary::getInstance()->getAdapter()->clearSessionValue("orderid");

			// Notify the user

			Zibbra_Plugin_Notify::register(Zibbra_Plugin_Notify::STATUS_OK, (is_null($message) ? __("Your payment has been cancelled", Zibbra_Plugin::LC_DOMAIN) : $message));

			// Redirect to checkout again

			wp_redirect(site_url("/zibbra/checkout/"));
			exit;

		} // end if

		return false;

	} // end function

	public function onError() {

		return $this->onCancel(__("There was a problem processing your payment, please try again", Zibbra_Plugin::LC_DOMAIN));

	} // end function

	public function onStatus() {

		// Default response

		$response = new stdClass();
		$response->status = "open";
		$response->url = $this->getErrorUrl();

		// Check stored status

		if($this->isStatusCancelled()) {

			$response->status = "cancelled";
			$response->url = $this->getCancelUrl();

		} // end if

		if($this->isStatusError()) {

			$response->status = "error";
			$response->url = $this->getErrorUrl();

		} // end if

		if($this->isStatusPending()) {

			$response->status = "pending";
			$response->url = $this->getSuccessUrl();

		} // end if

		if($this->isStatusPaid()) {

			$response->status = "paid";
			$response->url = $this->getSuccessUrl();

		} // end if

		header("Content-Type: application/json");
		echo json_encode($response);
		exit;

	} // end function

	protected function getReturnUrl() {

		$return_url = $this->adapter->getReturnUrl($this->order->getOrderid());

		if(empty($return_url)) {

			$return_url = site_url("/zibbra/payment/return/" . $this->adapter->getId() . "/" . $this->order->getOrderid() . "/");

		} // end if

		return $return_url;

	} // end function

	protected function getCancelUrl() {

		$cancel_url = $this->adapter->getCancelUrl($this->order->getOrderid());

		if(empty($cancel_url)) {

			$cancel_url = site_url("/zibbra/payment/cancel/" . $this->adapter->getId() . "/" . $this->order->getOrderid() . "/");

		} // end if

		return $cancel_url;

	} // end function

	protected function getErrorUrl() {

		$error_url = $this->adapter->getErrorUrl($this->order->getOrderid());

		if(empty($error_url)) {

			$error_url = site_url("/zibbra/payment/error/" . $this->adapter->getId() . "/" . $this->order->getOrderid() . "/");

		} // end if

		return $error_url;

	} // end function

	protected function getNotifyUrl() {

		$notify_url = $this->adapter->getNotifyUrl($this->order->getOrderid());

		if(empty($notify_url)) {

			$notify_url = site_url("/zibbra/payment/notify/" . $this->adapter->getId() . "/" . $this->order->getOrderid() . "/");

		} // end if

		return $notify_url;

	} // end function

	protected function getSuccessUrl() {

		return site_url("/zibbra/checkout/finish/");

	} // end function

	protected function setStatus($status) {

		// Validate the status

		if(!in_array($status, [self::STATUS_OPEN, self::STATUS_PENDING, self::STATUS_CANCELLED, self::STATUS_ERROR, self::STATUS_PAID])) {

			throw new Exception("Invalid status");

		} // end if

		// Get the Zibbra adapter

		$adapter = ZLibrary::getInstance()->getAdapter();

		// Store the status

		$adapter->setPersistentValue("payment.". $this->order->getOrderid() . "." . $this->getAdapterName() . ".status", $status);

		// Submit a 0-payment to the API when the status is pending so it triggers auto-confirm

		if($status === self::STATUS_PENDING) {

			if(!ZOrder::addPayment($this->order->getOrderid(), 0, ZPayment::METHOD_ONLINE, $this->getAdapterName(), ZPayment::STATUS_CONFIRMED)) {

				throw new Exception("Unable to register the payment");

			} // end if

		} // end if

	} // end function

	protected function getStatus() {

		// Get the Zibbra adapter

		$adapter = ZLibrary::getInstance()->getAdapter();

		// Get the status

		$status = $adapter->getPersistentValue("payment.". $this->order->getOrderid() . "." . $this->getAdapterName() . ".status");

		// Validate

		if(!in_array($status, [self::STATUS_OPEN, self::STATUS_PENDING, self::STATUS_CANCELLED, self::STATUS_ERROR, self::STATUS_PAID])) {

			$status = self::STATUS_OPEN;

		} // end if

		return $status;

	} // end function

	protected function isStatusOpen() {

		return $this->getStatus() === self::STATUS_OPEN;

	} // end function

	protected function isStatusPending() {

		return $this->getStatus() === self::STATUS_PENDING;

	} // end function

	protected function isStatusPaid() {

		return $this->getStatus() === self::STATUS_PAID;

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