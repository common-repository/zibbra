<?php

interface Zibbra_Plugin_Module_Payment_Interface {

	/**
	 * @return string
	 */
	public function getAdapterName();

	public function doPost();

	public function onDispatch();

	public function onReturn();

	public function onCancel();

	public function onError();

	public function onNotify();

} // end class