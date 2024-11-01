<?php

interface Zibbra_Plugin_Module_Shipping_Interface {

	/**
	 * @return string
	 */
	public function getAdapterName();

	public function onSelect();

	public function onReturn();

	public function onCancel();

	public function onError();

} // end class