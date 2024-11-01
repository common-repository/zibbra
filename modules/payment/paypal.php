<?php

require_once(ZIBBRA_BASE_DIR . "/includes/paypal/PPBootStrap.php");

use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\DoExpressCheckoutPaymentRequestDetailsType;
use PayPal\EBLBaseComponents\PaymentDetailsItemType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentReq;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentRequestType;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsReq;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsRequestType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\Service\PayPalAPIInterfaceServiceService;

class Zibbra_Plugin_Module_Payment_Paypal extends Zibbra_Plugin_Module_Payment_Abstract implements Zibbra_Plugin_Module_Payment_Interface {

	/**
	 * Name of the adapter
	 *
	 * @var string
	 */
	const ADAPTER_NAME = "paypal";

	/**
	 * API version
	 *
	 * @var string
	 */
	const API_VERSION = "104.0";

	/**
	 * payment action
	 *
	 * @var string
	 */
	const PAYMENT_ACTION = "Sale";

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

		// Get the Zibbra adapter

		$adapter = ZLibrary::getInstance()->getAdapter();

		// Get info from order/customer

		$orderid = $this->order->getOrderid();
		$number = $this->order->getNumber();
		$amount = round($this->order->getAmountIncl(), 2);

		try {

			$config = $this->getConfig();

			$paypalService = new PayPalAPIInterfaceServiceService($config);

			$orderTotal = new BasicAmountType();
			$orderTotal->currencyID = 'EUR';
			$orderTotal->value = $amount;

			$paymentDetails = new PaymentDetailsType();
			$paymentDetails->NotifyURL = $this->getNotifyUrl();
			$paymentDetails->OrderTotal = $orderTotal;
			$paymentDetails->PaymentAction = self::PAYMENT_ACTION;

			$setECReqDetails = new SetExpressCheckoutRequestDetailsType();
			$setECReqDetails->OrderDescription = $adapter->translate("Payment for order")." ".$number;
			$setECReqDetails->Custom = $orderid;
			$setECReqDetails->CancelURL = $this->getCancelUrl();
			$setECReqDetails->ReturnURL = $this->getReturnUrl();
			$setECReqDetails->NoShipping = 1;
			$setECReqDetails->AllowNote = 0;
			$setECReqDetails->BrandName = $_SERVER['HTTP_HOST'];
			$setECReqDetails->PaymentDetails[0] = $paymentDetails;

			$setECReqType = new SetExpressCheckoutRequestType();
			$setECReqType->Version = self::API_VERSION;
			$setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;

			$setECReqType = new SetExpressCheckoutRequestType();
			$setECReqType->Version = self::API_VERSION;
			$setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;

			$setECReq = new SetExpressCheckoutReq();
			$setECReq->SetExpressCheckoutRequest = $setECReqType;

			$setECResponse = $paypalService->SetExpressCheckout($setECReq);

			// Log request/response

			$this->logData($setECReq, $setECResponse);

			// Validate response

			if(!isset($setECResponse->Ack) || $setECResponse->Ack!=="Success" || !isset($setECResponse->Token)) {

				throw new Exception("Unable to retrieve token from paypal: " . $setECResponse->Ack . " (" . json_encode($setECResponse->Errors) . ")");

			} // end if

			// Redirect to Paypal secure payment page

			$uri = $this->getCheckoutUri($setECResponse->Token);

			wp_redirect($uri);
			exit;

		}catch(Exception $e) {

			$adapter->log(LOG_ERR, "[PAYPAL] ".$e->getMessage());

		} // end try-catch

		wp_redirect($this->getErrorUrl());
		exit;

	} // end function

	public function onReturn() {

		/** @var Zibbra_Plugin_Query $z_query */
		global $z_query;

		// Get the Zibbra adapter

		$adapter = ZLibrary::getInstance()->getAdapter();

		try {

			return $this->showConfirmPayment($adapter, $z_query);

		}catch(Exception $e) {

			$adapter->log(LOG_ERR, "[PAYPAL] ".$e->getMessage());

		} // end try-catch

		wp_redirect($this->getErrorUrl());
		exit;

	} // end function

	public function onNotify() {

		// Log request/response

		$this->logData("paypal::onNotify()", $_REQUEST);

		return false;

	} // end function

	public function doPost() {

		// Check if we need to process the confirm page

		if (wp_verify_nonce( $_POST[Zibbra_Plugin::FORM_ACTION] , "do_confirm" )) {

			if($this->doConfirmPayment() === true) {

				// Register a payment

				$adapter = ZLibrary::getInstance()->getAdapter();
				$orderid = $this->order->getOrderid();
				$amount = round($this->order->getAmountIncl(), 2);

				$settings = new stdClass();
				$settings->token = $adapter->getSessionValue("paypal." . $orderid . ".token");
				$settings->payerid = $adapter->getSessionValue("paypal." . $orderid . ".payerid");

				if(!ZOrder::addPayment($orderid, $amount, ZPayment::METHOD_ONLINE, self::ADAPTER_NAME, ZPayment::STATUS_CONFIRMED, $settings)) {

					throw new Exception("Unable to register the payment");

				} // end if

				wp_redirect($this->getSuccessUrl());
				exit;

			} // end if

		} // end if

		wp_redirect($this->getErrorUrl());
		exit;

	} // end function

	/**
	 * @param ZLibrary_Adapter_Interface $adapter
	 * @param Zibbra_Plugin_Query $z_query
	 *
	 * @return string
	 * @throws Exception
	 */
	private function showConfirmPayment(ZLibrary_Adapter_Interface $adapter, Zibbra_Plugin_Query $z_query) {

		// Validate request

		if(!isset($_REQUEST['token']) || !isset($_REQUEST['PayerID'])) {

			throw new Exception("We did not receive the token and PayerID from paypal: " . print_r($_REQUEST, true));

		} // end if

		// Store the token and PayerID

		$token = $_REQUEST['token'];
		$payerid = $_REQUEST['PayerID'];

		$adapter->setSessionValue("paypal." . $this->order->getOrderid() . ".token", $token);
		$adapter->setSessionValue("paypal." . $this->order->getOrderid() . ".payerid", $payerid);

		// Assign order infor to the template

		$z_query->init();
		$z_query->set("customer", $this->customer);
		$z_query->set("order", $this->order);
		$z_query->set("confirm_url", $this->getReturnUrl());
		$z_query->set("cancel_url", $this->getCancelUrl());

		// Set the title

		$this->title = __("Confirm payment", Zibbra_Plugin::LC_DOMAIN);

		// Return the confirmation template

		return Zibbra_Plugin_Module_Payment::MODULE_NAME;

	} // end function

	/**
	 * @return bool
	 * @throws Exception
	 */
	private function doConfirmPayment() {

		$adapter = ZLibrary::getInstance()->getAdapter();

		try {

			$config = $this->getConfig();
			$token = $adapter->getSessionValue("paypal." . $this->order->getOrderid() . ".token");
			$payerid = $adapter->getSessionValue("paypal." . $this->order->getOrderid() . ".payerid");

			$paypalService = new PayPalAPIInterfaceServiceService($config);

			$getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($token);
			$getExpressCheckoutDetailsRequest->Version = self::API_VERSION;
			$getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();
			$getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;

			$getECResponse = $paypalService->GetExpressCheckoutDetails($getExpressCheckoutReq);

			// Log request/response

			$this->logData($getExpressCheckoutReq, $getECResponse);

			// Validate response

			if(!isset($getECResponse->Ack) || $getECResponse->Ack!=="Success") {

				throw new Exception("Unable to retrieve express checkout details from paypal: " . $getECResponse->Ack . " (" . json_encode($getECResponse->Errors) . ")");

			} // end if

			$amount = round($this->order->getAmountIncl(), 2);

			$orderTotal = new BasicAmountType();
			$orderTotal->currencyID = 'EUR';
			$orderTotal->value = $amount;

			$paymentDetails = new PaymentDetailsType();
			$paymentDetails->OrderTotal = $orderTotal;
			$paymentDetails->PaymentAction = self::PAYMENT_ACTION;
			$paymentDetails->NotifyURL = $this->getNotifyUrl();

			$DoECRequestDetails = new DoExpressCheckoutPaymentRequestDetailsType();
			$DoECRequestDetails->PayerID = $payerid;
			$DoECRequestDetails->Token = $token;
			$DoECRequestDetails->PaymentDetails[0] = $paymentDetails;

			$DoECRequest = new DoExpressCheckoutPaymentRequestType();
			$DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;
			$DoECRequest->Version = self::API_VERSION;

			$DoECReq = new DoExpressCheckoutPaymentReq();
			$DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;

			$DoECResponse = $paypalService->DoExpressCheckoutPayment($DoECReq);

			// Log request/response

			$this->logData($DoECReq, $DoECResponse);

			// Validate response

			if(!isset($DoECResponse->Ack) || $DoECResponse->Ack!=="Success") {

				$this->setStatus(parent::STATUS_ERROR);

				throw new Exception("Unable to confirm the payment from paypal: " . $DoECResponse->Ack . " (" . json_encode($DoECResponse->Errors) . ")");

			} // end if

			$this->setStatus(parent::STATUS_PAID);

			return true;

		} catch(Exception $e) {

			$adapter->log(LOG_ERR, $e->getMessage());

		} // end try-catch

		return false;

	} // end function

	private function getConfig() {

		// Get payment adapter settings

		if(($user = $this->adapter->getSetting("user")) === false) {

			throw new Exception("Paypal user is not defined!");

		} // end if

		if(($pwd = $this->adapter->getSetting("pwd")) === false) {

			throw new Exception("Paypal pwd is not defined!");

		} // end if

		if(($signature = $this->adapter->getSetting("signature")) === false) {

			throw new Exception("Paypal signature is not defined!");

		} // end if

		// Prepare PayPal configuration

		$config = array (
			"mode" => ($this->adapter->inSandboxMode() ? "sandbox" : "live") ,
			"acct1.UserName" => $user,
			"acct1.Password" => $pwd,
			"acct1.Signature" => $signature
		);

		return $config;

	} // end function

	/**
	 * Returns the Paypal Checkout URL
	 *
	 * @param string $token
	 *
	 * @return string
	 * @noobfuscate
	 */
	private function getCheckoutUri($token) {

		$uri = "https://www.".($this->adapter->inSandboxMode() ? "sandbox." : "")."paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=" . urlencode($token);

		return $uri;

	} // end function

} // end class