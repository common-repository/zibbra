<?php

require_once(ZIBBRA_BASE_DIR . "/includes/mollie/Autoloader.php");

class Zibbra_Plugin_Module_Payment_Mollie extends Zibbra_Plugin_Module_Payment_Abstract implements Zibbra_Plugin_Module_Payment_Interface {

	/**
	 * Name of the adapter
	 *
	 * @var string
	 */
	const ADAPTER_NAME = "mollie";

	/**
	 * Mollie API URI
	 *
	 * @var string
	 */
	const MOLLIE_API = "https://api.mollie.nl/v1/";

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

		// Get payment adapter settings

		if(($api_key = $this->adapter->getSetting("api_key")) === false) {

			throw new Exception("Mollie api_key is not defined!");

		} // end if

		// Get the Zibbra adapter

		$adapter = ZLibrary::getInstance()->getAdapter();

		// Get info from order/customer

		$orderid = $this->order->getOrderid();
		$number = $this->order->getNumber();
		$amount = round($this->order->getAmountIncl(), 2);

		// Instantiate mollie client

		$mollie = new Mollie_API_Client();
		$mollie->setApiKey($api_key);

		// Create the payment

		$request = array(
			"amount"       => $amount,
			"description"  => $adapter->translate("Payment for order")." ".$number,
			"redirectUrl"  => $this->getReturnUrl(),
			"webhookUrl"   => $this->getNotifyUrl(),
			"metadata"     => array(
				"order_id" => $orderid
			)
		);

		$response = $mollie->payments->create($request);

		// Log

		$this->logData($request, $response);

		// Store the status

		$this->setStatus(parent::STATUS_OPEN);

		// Send the customer off to complete the payment

		header("Location: " . $response->getPaymentUrl());
		exit;

	} // end function

	public function onNotify() {

		// Get the Zibbra adapter

		$adapter = ZLibrary::getInstance()->getAdapter();

		try {

			// Check if we received the payment id from Mollie

			if(!isset($_REQUEST['id'])) {

				throw new Exception("Mollie did not return the payment ID!");

			} // end if

			$payment_id = $_REQUEST['id'];

			// Get payment adapter settings

			if(($api_key = $this->adapter->getSetting("api_key")) === false) {

				throw new Exception("Mollie api_key is not defined!");

			} // end if

			// Instantiate mollie client

			$mollie = new Mollie_API_Client();
			$mollie->setApiKey($api_key);

			// Make a request and fetch the payment status

			$payment = $mollie->payments->get($payment_id);

			// Log request/response

			$this->logData($_REQUEST, $payment);

			// Get the orderid from the metadata

			$orderid = (int) $payment->metadata->order_id;

			// Check that the order is the same

			if($orderid !== $this->order->getOrderid()) {

				throw new Exception("Payment received from Mollie is for a different order (uri = " . $this->order->getOrderid() . ", mollie = ". $orderid .")");

			} // end if

			// Take action depending on the status

			if($payment->isCancelled()) {

				$adapter->log(LOG_DEBUG, "Payment status is 'cancelled' for id '" . $payment_id . "'");

				// Store the status

				$this->setStatus(parent::STATUS_CANCELLED);

			}elseif($payment->isPending()) {

				$adapter->log(LOG_DEBUG, "Payment status is 'pending' for id '" . $payment_id . "'");

				// Store the status (This will trigger a 0-payment)

				$this->setStatus(parent::STATUS_PENDING);

			}elseif($payment->isPaid()) {

				$adapter->log(LOG_DEBUG, "Payment status is 'paid' for id '" . $payment_id . "'");

				// Register a payment

				$amount = round($this->order->getAmountIncl(), 2);

				if(!ZOrder::addPayment($orderid, $amount, ZPayment::METHOD_ONLINE, self::ADAPTER_NAME, ZPayment::STATUS_CONFIRMED, $payment_id)) {

					throw new Exception("Unable to register the payment");

				} // end if

				// Store the status

				$this->setStatus(parent::STATUS_PAID);

			}elseif($payment->isRefunded() || $payment->isExpired()) {

				$adapter->log(LOG_DEBUG, "Payment status is '" . $payment->status . "' for id '" . $payment_id . "'");

				// Store the status

				$this->setStatus(parent::STATUS_ERROR);

			}else{

				// Store the status

				$this->setStatus(parent::STATUS_ERROR);

				throw new Exception("Payment status is '" . $payment->status . "' for id '" . $payment_id . "' and is not accepted");

			} // end if

			// Notify Mollie request has been handled with 200 OK

			header("HTTP/1.0 200 OK");
			echo "OK";
			exit;

		}catch(Exception $e) {

			$adapter->log(LOG_ERR, "[MOLLIE] ".$e->getMessage());

		} // end try-catch

		header("HTTP/1.0 500 Internal Server Error");
		echo "ERROR";
		exit;

	} // end function

} // end class