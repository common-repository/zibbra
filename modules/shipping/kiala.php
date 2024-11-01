<?php

class Zibbra_Plugin_Module_Shipping_Kiala extends Zibbra_Plugin_Module_Shipping_Abstract implements Zibbra_Plugin_Module_Shipping_Interface {

	/**
	 * Name of the adapter
	 *
	 * @var string
	 */
	const ADAPTER_NAME = "kiala";

	/**
	 * @return string
	 */
	public function getAdapterName() {

		return self::ADAPTER_NAME;

	} // end function

	/**
	 * onSelect
	 *
	 * @return boolean
	 */
	public function onSelect() {

		/** @var Zibbra_Plugin_Query $z_query */
		global $z_query;

		// Get Zibbra library adapter

		$adapter = ZLibrary::getInstance()->getAdapter();

		// Get the shipping address

		$address = $this->customer->getShippingAddress();

		// Get countrycode and languagecode

		$countrycode = strtolower($address->getCountrycode());
		$languagecode = strtolower(substr($adapter->getLanguageCode(),0,2));

		// Get dspID

		switch($countrycode) {

			case "be":
			case "lu": $dspid = 32600160;break;
			case "nl": $dspid = 31600160;break;
			case "fr": $dspid = 33600500;break;
			case "es": $dspid = 34600160;break;
			default: $dspid = "DEMO_DSP";break;

		}; // end switch

		// Prepare the URI from Kiala

		$params = array(
			"dspid" => $dspid,
			"preparationdelay" => 2,
			"language" => $languagecode,
			"country"=>$countrycode,
			"street"=>strtolower($address->getStreet()),
			"zip"=>strtolower($address->getZipcode()),
			"city"=>strtolower($address->getCity()),
			"bckUrl"=>$this->getReturnUrl()."?",
			"target"=>"_parent"
		);
		$kiala_url = "//locateandselect.kiala.com/search?".http_build_query($params);

		$z_query->init();
		$z_query->set("orderid", $this->order->getOrderid());
		$z_query->set("adapter", self::ADAPTER_NAME);
		$z_query->set("kiala_url", $kiala_url);

		// Return template name

		return "shipping";

	} // end function

	public function onReturn() {

		$this->method->setSettings($_REQUEST);
		$this->method->save($this->order->getOrderid());

		$this->setStatus(parent::STATUS_FINISHED);

		wp_redirect(site_url("/zibbra/checkout/continue/"));
		exit;

	} // end function

} // end function