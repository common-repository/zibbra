<?php

class Zibbra_Plugin_Module_Payment_Payzen extends Zibbra_Plugin_Module_Payment_Abstract implements Zibbra_Plugin_Module_Payment_Interface {

	/**
	 * Name of the adapter
	 *
	 * @var string
	 */
	const ADAPTER_NAME = "payzen";

	/**
	 * URI to the Payzen payment page
	 *
	 * @var string
	 */
	const PAYZEN_URI = "https://secure.payzen.eu/vads-payment/";

	/**
	 * @return string
	 */
	public function getAdapterName() {

		return self::ADAPTER_NAME;

	} // end function

	/**
	 * onDispatch
	 */
	public function onDispatch() {

		// Get the params and signature

		$params = $this->getParams();
		$signature = $this->paramsToSha($params);

		// Log

		$this->logData(self::PAYZEN_URI, $params);

		// Draw the form and submit with javascript

		echo "<form id=\"zibbra-checkout-confirm-edenred\" action=\"".self::PAYZEN_URI."\" method=\"post\">\n";

		foreach($params as $key=>$value) {

			if($value!=="") {

				echo "<input type=\"hidden\" name=\"".$key."\" value=\"".$value."\" />\n";

			} // end if

		} // end foreach

		echo "<input type=\"hidden\" name=\"signature\" value=\"" . $signature . "\" />\n";
		echo "<input type=\"hidden\" name=\"pay\" value=\"Pay\" />\n";
		echo "</form>";
		echo "<script> document.getElementById('zibbra-checkout-confirm-edenred').submit(); </script>";
		exit;

	} // end function

	public function onNotify() {

		// Get the Zibbra adapter

		$adapter = ZLibrary::getInstance()->getAdapter();

		try {

			// Log request/response

			$this->logData($_REQUEST, "Payzen::onNotify()");

			// Get the Zibbra adapter

			$adapter = ZLibrary::getInstance()->getAdapter();

			// Check that the order id matches

			if(!isset($_REQUEST['vads_order_id']) || $_REQUEST['vads_order_id'] != $this->order->getOrderid()) {

				throw new Exception("Invalid order ID returned from Payzen!");

			} // end if

			$orderid = $_REQUEST['vads_order_id'];

			// Signature check

//			if(!isset($_REQUEST['signature'])) {
//
//				throw new Exception("No signature returned from Payzen!");
//
//			} // end if
//
//			$signature = $_REQUEST['signature'];
//			unset($_REQUEST['signature']);
//
//			if($signature !== $this->paramsToSha($_REQUEST)) {
//
//				throw new Exception("Invalid signature returned from Payzen!");
//
//			} // end if

			// Check the status

			if(!isset($_REQUEST['vads_trans_status'])) {

				throw new Exception("No status returned from Payzen!");

			} // end if

			$status = $_REQUEST['vads_trans_status'];
			$payment_id = $_REQUEST['vads_trans_id'];

			$adapter->log(LOG_DEBUG, "Payment status is '" . $status . "' for id '" . $payment_id . "'");

			switch($status) {

				// The payment has been abandoned by the buyer. The transaction has not been created and cannot be viewed in the Back Office.

				case "ABANDONED": {

					// Store the status

					$this->setStatus(parent::STATUS_CANCELLED);

				};break;

				// The transaction has been accepted and will be automatically captured by the bank on the due date.

				case "CAPTURED":
				case "AUTHORISED": {

					// Register a payment

					$amount = round($this->order->getAmountIncl(), 2);

					if(!ZOrder::addPayment($orderid, $amount, ZPayment::METHOD_ONLINE, self::ADAPTER_NAME, ZPayment::STATUS_CONFIRMED, $payment_id)) {

						throw new Exception("Unable to register the payment");

					} // end if

					// Store the status

					$this->setStatus(parent::STATUS_PAID);

				};break;

				// The transaction has not been created and cannot be viewed in the Back Office.

				case "NOT_CREATED": {

					// Store the status

					$this->setStatus(parent::STATUS_ERROR);

				};break;

				// The transaction can be validated as long as the capture date has not passed. If the capture date has passed, the payment status changes to EXPIRED. The Expired status is final..

				case "AUTHORISED_TO_VALIDATE": {

				};break;

				// This status is specific to all the payment methods that require a form integration with redirection, in particular SOFORT BANKING and 3xCB COFINOGA.
				// This status is returned when:
				//  *) no response is returned from the acquirer
				//  *) the acquirer response time is greater than the session payment time on the payment gateway.
				// This status is temporary. The final status will be returned once the synchronization has been made.

				case "INITIAL": {

				};break;

			} // end switch

			// Notify Payzen request has been handled with 200 OK

			header("HTTP/1.0 200 OK");
			echo "OK";
			exit;

		}catch(Exception $e) {

			$adapter->log(LOG_ERR, "[PAYZEN] ".$e->getMessage());

		} // end try-catch

		header("HTTP/1.0 500 Internal Server Error");
		echo "ERROR";
		exit;

	} // end function

	/**
	 * Create SHA token for the given params
	 *
	 * @param array $params
	 * @return string
	 * @noobfuscate
	 */
	private function paramsToSha($params) {

		$sha = "";

		ksort($params);

		foreach($params as $key=>$value) {

			if($value!=="") {

				$sha .= utf8_encode($value) . "+";

			} // end if

		} // end foreach

		$sha .= ($this->adapter->inSandboxMode() ? $this->adapter->getSetting("test_certificate") : $this->adapter->getSetting("production_certificate"));

		return hash("sha1", $sha);

	} // end function

	/**
	 * Returns the params
	 *
	 * @return array
	 * @noobfuscate
	 */
	private function getParams() {

		$customerid = $this->customer->getCustomerid();
		$contact = $this->customer->getPrimaryContact();
		$orderid = $this->order->getOrderid();
		$number = $this->order->getNumber();
		$amount = round($this->order->getAmountIncl(), 2) * 100;

		$trans_id = str_pad($this->order->getOrderid(), 6, "0", STR_PAD_LEFT);

		return array(
			"vads_site_id" => $this->adapter->getSetting("shop_id"),
			"vads_ctx_mode" => ($this->adapter->inSandboxMode() ? "TEST" : "PRODUCTION"),
			"vads_trans_id" => $trans_id,
			"vads_trans_date" => gmdate("YmdHis"),
			"vads_order_id" => $orderid,
			"vads_order_info" => $number,
			"vads_amount" => $amount,
			"vads_currency" => "978",
			"vads_action_mode" => "INTERACTIVE",
			"vads_page_action" => "PAYMENT",
			"vads_version" => "V2",
			"vads_payment_config" => "SINGLE",
			"vads_cust_id" => $customerid,
			"vads_cust_first_name" => utf8_encode($contact->getFirstname()),
			"vads_cust_last_name" => utf8_encode($contact->getLastname()),
			"vads_cust_email" => utf8_encode($contact->getEmail()),
			"vads_url_success" => $this->getReturnUrl(),
			"vads_url_cancel" => $this->getCancelUrl(),
			"vads_url_check" => $this->getNotifyUrl(),
			"vads_url_check_src" => "PAY",
			"vads_url_error" => $this->getErrorUrl(),
			"vads_url_refused" => $this->getErrorUrl(),
			"vads_url_return" => $this->getReturnUrl()
		);

	} // end function

} // end class