<?php

class Zibbra_Plugin_Module_Shipping_Bpost extends Zibbra_Plugin_Module_Shipping_Abstract implements Zibbra_Plugin_Module_Shipping_Interface {

	/**
	 * Name of the adapter
	 *
	 * @var string
	 */
	const ADAPTER_NAME = "bpost";

	private static $methods = [
		'atHome'      => ['name'=>'Regular','index'=>4],
		'atShop'      => ['name'=>'Pugo','index'=>3],
		'at24-7'      => ['name'=>'Parcels depot','index'=>2],
		'intExpress'  => ['name'=>'bpack EXPRESS','index'=>0],
		'intBusiness' => ['name'=>'bpack BUSINESS','index'=>1]
	];

	/**
	 * @return string
	 */
	public function getAdapterName() {

		return self::ADAPTER_NAME;

	} // end function

	public function onSelect() {

		/** @var Zibbra_Plugin_Query $z_query */
		global $z_query;

		// Get the Zibbra adapter

		$adapter = ZLibrary::getInstance()->getAdapter();

		try {

			$contact = $this->customer->getPrimaryContact();
			$company = $this->customer->getCompany();
			$address = $this->customer->getShippingAddress();
			$accountid = $this->adapter->getSetting("accountid");
			$methods = $this->adapter->getSetting("methods");
			$passphrase = $this->adapter->getSetting("passphrase");
			$frontend_uri = $this->adapter->getSetting("frontend_uri");
			$orderid = $this->order->getOrderid();
			$number = $this->order->getNumber();
			$amount = floor($this->order->getAmountIncl() * 100);
			$weight = (int) $this->order->getWeight();
			$languagecode = $this->getLanguageCode();

			if(!$contact instanceof ZCustomerContact) {

				throw new Exception("Customer does not have a valid primary contact");

			} // end if

			if(!$address instanceof ZCustomerAddress) {

				throw new ZException("Customer doesn't have a valid shipping address");

			} // end if

			$config = array(
				"accountId" => $accountid,
				"action" => "START",
				"orderReference" => $number,
				"lang" => $languagecode,
				"orderTotalPrice" => $amount,
				"customerFirstName" => $contact->getFirstname(),
				"customerLastName" => $contact->getLastname(),
				"customerCompany" => $company ? $company->getName() : "",
				"customerStreet" => $address->getStreet(),
				"customerStreetNumber" => $address->getStreetnr(),
				"customerBox" => $address->getBox(),
				"customerCity" => $address->getCity(),
				"customerPostalCode" => $address->getZipcode(),
				"customerCountry" => $address->getCountrycode(),
				"customerEmail" => $contact->getEmail(),
				"customerPhoneNumber" => $contact->getPhone(),
				"orderWeight" => $weight,
				"confirmUrl" => $this->getReturnUrl(),
				"cancelUrl" => $this->getCancelUrl(),
				"errorUrl" => $this->getErrorUrl(),
				"orderLine" => array(),
				"deliveryMethodOverrides" => array()
			);

			// Override delivery methods

			foreach($methods as $method=>$visible) {

				if(!$this->method->isInternational() && substr($method,0,3)==="int") {

					continue;

				} // end if

				if(!array_key_exists($method, self::$methods)) {

					throw new Exception("Method '".$method."' is invalid");

				} // end if

				$name = self::$methods[$method]['name'];
				$index = self::$methods[$method]['index'];
				$price = (int) ($this->method->getPriceVatIncl() * 100);

				$config['deliveryMethodOverrides'][$index] = $name . "|" . ($visible === "Y" ? "VISIBLE|" . $price : "INVISIBLE");

			} // end foreach

			ksort($config['deliveryMethodOverrides']);

			// Order lines

			foreach($this->order->getItems() as $item) {

				if(is_numeric($item->getProductid())) {

					$config['orderLine'][] = $item->getDescription() . "|" . (int) $item->getQuantity();

				} // end if

			} // end foreach

			$config['checksum'] = $this->checksum($config, $passphrase);

			// Set template info

			$z_query->init();
			$z_query->set("orderid", $orderid);
			$z_query->set("adapter", self::ADAPTER_NAME);
			$z_query->set("config", $config);
			$z_query->set("frontend_uri", $frontend_uri);

			// Return template name

			return "shipping";

		} catch(Exception $e) {

			$adapter->log(LOG_ERR, "[PAYPAL] ".$e->getMessage());

		} // end try-catch

		wp_redirect($this->getErrorUrl());
		exit;

	} // end function

	public function onReturn() {

		$this->method->setSettings($_REQUEST);
		$this->method->save($this->order->getOrderid());

		$this->setStatus(parent::STATUS_FINISHED);

		wp_redirect(site_url("/zibbra/checkout/continue/"));
		exit;

	} // end function

	private function getLanguageCode() {

		$languagecode = ZLibrary::getInstance()->getAdapter()->getLanguageCode();

		$lang = strtoupper(array_shift(explode("-",$languagecode)));

		if(!in_array($lang,array("EN","NL","FR","DE"))) {

			return "EN";

		} // end if

		return $lang;

	} // end function

	private function checksum($config, $passphrase) {

		$arr = array(
			"accountId" => $config['accountId'],
			"action" => $config['action'],
			"customerCountry" => $config['customerCountry'],
			"deliveryMethodOverrides" => $config['deliveryMethodOverrides'],
			"orderReference" => $config['orderReference'],
			"orderWeight" => $config['orderWeight']
		);

		$query = "";

		foreach($arr as $key=>$value) {

			if(is_array($value)) {

				foreach($value as $subvalue) {

					$query .= "&".$key."=".$subvalue;

				} // end foreach

			}else{

				$query .= "&".$key."=".$value;

			} // end if

		} // end foreach

		$query = utf8_encode(substr($query,1)."&".$passphrase);

		return hash("sha256",$query);

	} // end function

} // end function