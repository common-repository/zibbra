<?php

class Zibbra_Plugin_Module_Payment_Transcription extends Zibbra_Plugin_Module_Payment_Abstract implements Zibbra_Plugin_Module_Payment_Interface {

	/**
	 * Name of the adapter
	 *
	 * @var string
	 */
	const ADAPTER_NAME = "transcription";

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

		// Simulate a pending order (This will trigger a 0-payment)

		$this->setStatus(parent::STATUS_PENDING);

		// Redirect to the success URL

		$url = $this->getSuccessUrl();
		wp_redirect($url);
		exit;

	} // end function

	public function onReturn() {

		return false;

	} // end function

	public function onNotify() {

		return false;

	} // end function

} // end class