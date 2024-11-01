<?php

class Zibbra_Plugin_Module_Payment_Ogone extends Zibbra_Plugin_Module_Payment_Abstract implements Zibbra_Plugin_Module_Payment_Interface {

	/**
	 * Name of the adapter
	 *
	 * @var string
	 */
	const ADAPTER_NAME = "ogone";

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

		// TODO: Implement onDispatch() method.

	} // end function

	public function onReturn() {

		// TODO: Implement onReturn() method.

	} // end function

	public function onCancel() {

		// TODO: Implement onCancel() method.

	} // end function

	public function onError() {

		// TODO: Implement onError() method.

	} // end function

	public function onNotify() {

		// TODO: Implement onNotify() method.

	} // end function

} // end class